<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Location;
use App\Models\Source;
use App\Models\Talent;
use App\Models\TalentDocument;
use App\Models\TalentStatus;
use App\Models\AvailabilityOption;
use App\Enums\DocumentType;
use App\Enums\SeniorityLevel;
use App\Enums\WorkPreference;
use App\Enums\Availability;
use Illuminate\Http\Request;
use App\Http\Requests\SubmitJobApplicationRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Enum;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\JobPosting::with(['department', 'location'])
            ->where('is_active', true);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        $jobs = $query->latest()->get();
        $departments = \App\Models\Department::orderBy('name')->get();
        $locations = \App\Models\Location::orderBy('name')->get();

        return view('jobs.index', compact('jobs', 'departments', 'locations'));
    }

    public function showJobForm(\App\Models\JobPosting $job)
    {
        abort_if(!$job->is_active, 404, 'هذه الوظيفة لم تعد متاحة.');
        
        $departments = Department::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('jobs.apply', compact('job', 'departments', 'locations'));
    }

    public function submitJobForm(SubmitJobApplicationRequest $request, \App\Models\JobPosting $job)
    {
        abort_if(!$job->is_active, 404, 'هذه الوظيفة لم تعد متاحة.');

        $validated = $request->validated();
        $formSchema = $job->form_schema ?? [];

        // Extract custom answers
        $customAnswers = [];
        foreach ($formSchema as $block) {
            $data = $block['data'];
            $fieldName = 'custom_' . $data['name'];
            
            if ($request->has($fieldName)) {
                if ($block['type'] === 'file' && $request->hasFile($fieldName)) {
                    $file = $request->file($fieldName);
                    $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                    $path = $file->storeAs('talent-custom-documents', $fileName, 'public');
                    $customAnswers[$data['name']] = [
                        'type' => 'file',
                        'path' => $path,
                        'name' => $file->getClientOriginalName(),
                        'question' => $data['label']
                    ];
                } elseif ($block['type'] === 'checkbox') {
                    $customAnswers[$data['name']] = [
                        'type' => 'checkbox',
                        'value' => $request->boolean($fieldName) ? 'نعم' : 'لا',
                        'question' => $data['label']
                    ];
                } else {
                    $val = $request->input($fieldName);
                    if (is_array($val)) {
                        $val = implode('، ', $val);
                    }
                    $customAnswers[$data['name']] = [
                        'type' => 'text',
                        'value' => $val,
                        'question' => $data['label']
                    ];
                }
            }
        }

        // تحويل وتحديد الإتاحة (Availability)
        $rawAvailability = strtolower(trim((string) ($validated['availability'] ?? '')));
        $availabilityMap = [
            'immediately'       => ['enum' => Availability::IMMEDIATELY,   'en' => 'immediately'],
            'فوراً'             => ['enum' => Availability::IMMEDIATELY,   'en' => 'immediately'],
            'two_weeks'         => ['enum' => Availability::TWO_WEEKS,     'en' => 'two_weeks'],
            'within 2 weeks'    => ['enum' => Availability::TWO_WEEKS,     'en' => 'two_weeks'],
            'خلال أسبوعين'      => ['enum' => Availability::TWO_WEEKS,     'en' => 'two_weeks'],
            'one_month'         => ['enum' => Availability::ONE_MONTH,     'en' => 'one_month'],
            'within 1 month'    => ['enum' => Availability::ONE_MONTH,     'en' => 'one_month'],
            'خلال شهر'          => ['enum' => Availability::ONE_MONTH,     'en' => 'one_month'],
            'two_months'        => ['enum' => Availability::TWO_MONTHS,    'en' => 'two_months'],
            'more than 1 month' => ['enum' => Availability::TWO_MONTHS,    'en' => 'two_months'],
            'أكثر من شهر'       => ['enum' => Availability::TWO_MONTHS,    'en' => 'two_months'],
            'بعد شهرين'         => ['enum' => Availability::TWO_MONTHS,    'en' => 'two_months'],
            'not_available'     => ['enum' => Availability::NOT_AVAILABLE, 'en' => 'not_available'],
        ];

        $availabilityEnum = null;
        $availabilityOptionId = null;
        if (isset($availabilityMap[$rawAvailability])) {
            $availabilityEnum = $availabilityMap[$rawAvailability]['enum'];
            $availabilityOptionId = AvailabilityOption::where('name_en', $availabilityMap[$rawAvailability]['en'])->value('id');
        } elseif ($rawAvailability !== '') {
            $availabilityEnum = Availability::tryFrom($rawAvailability);
            $availabilityOptionId = AvailabilityOption::where('name_en', $rawAvailability)->value('id');
        }

        // تحويل المستوى المهني (Seniority Level)
        $rawSeniority = strtolower(trim((string) ($validated['seniority_level'] ?? '')));
        $seniorityMap = [
            'intern'    => SeniorityLevel::INTERN,
            'trainee'   => SeniorityLevel::INTERN,
            'متدرب'     => SeniorityLevel::INTERN,
            'junior'    => SeniorityLevel::JUNIOR,
            'مبتدئ'     => SeniorityLevel::JUNIOR,
            'mid'       => SeniorityLevel::MID,
            'mid-level' => SeniorityLevel::MID,
            'متوسط'     => SeniorityLevel::MID,
            'senior'    => SeniorityLevel::SENIOR,
            'متقدم'     => SeniorityLevel::SENIOR,
            'سينيور'    => SeniorityLevel::SENIOR,
            'lead'      => SeniorityLevel::LEAD,
            'قائد فريق' => SeniorityLevel::LEAD,
            'manager'   => SeniorityLevel::MANAGER,
            'مدير'      => SeniorityLevel::MANAGER,
            'director'  => SeniorityLevel::DIRECTOR,
            'دايركتور'  => SeniorityLevel::DIRECTOR,
            'vp'        => SeniorityLevel::VP,
            'c_level'   => SeniorityLevel::C_LEVEL,
        ];
        $seniorityLevel = $seniorityMap[$rawSeniority] ?? SeniorityLevel::tryFrom($rawSeniority);

        // تحويل نظام العمل المفضل (Work Preference)
        $rawWorkPref = strtolower(trim((string) ($validated['work_preference'] ?? '')));
        $workPrefMap = [
            'onsite'    => WorkPreference::ONSITE,
            'on-site'   => WorkPreference::ONSITE,
            'مقر العمل' => WorkPreference::ONSITE,
            'في المقر'  => WorkPreference::ONSITE,
            'remote'    => WorkPreference::REMOTE,
            'عن بعد'    => WorkPreference::REMOTE,
            'عن بُعد'   => WorkPreference::REMOTE,
            'hybrid'    => WorkPreference::HYBRID,
            'هجين'      => WorkPreference::HYBRID,
            'هايبريد'   => WorkPreference::HYBRID,
            'flexible'  => WorkPreference::FLEXIBLE,
            'مرن'       => WorkPreference::FLEXIBLE,
        ];
        $workPreference = $workPrefMap[$rawWorkPref] ?? WorkPreference::tryFrom($rawWorkPref);

        $defaultStatus = TalentStatus::where('is_default', true)->first() ?? TalentStatus::first();
        $defaultSource = Source::where('name', 'LIKE', '%موقع%')
            ->orWhere('name', 'LIKE', '%مباشر%')
            ->first() ?? Source::first();

        // إنشاء المرشح الجديد وحفظ جميع البيانات بما فيها الراتب
        $talent = Talent::create([
            'full_name'              => $validated['full_name'] ?? 'بدون اسم',
            'email'                  => $validated['email'] ?? null,
            'phone'                  => $validated['phone'] ?? null,
            'linkedin_url'           => $validated['linkedin_url'] ?? null,
            'portfolio_url'          => $validated['portfolio_url'] ?? null,
            'current_job_title'      => $validated['current_job_title'] ?? null,
            'current_company'        => $validated['current_company'] ?? null,
            'years_of_experience'    => $validated['years_of_experience'] ?? null,
            'expected_salary'        => $validated['expected_salary'] ?? null,
            'seniority_level'        => $seniorityLevel,
            'work_preference'        => $workPreference,
            'availability'           => $availabilityEnum,
            'availability_option_id' => $availabilityOptionId,
            'general_notes'          => $validated['general_notes'] ?? null,
            'job_posting_id'         => $job->id,
            'department_id'          => $job->department_id,
            'location_id'            => $job->location_id,
            'source_id'              => $defaultSource?->id,
            'status_id'              => $defaultStatus?->id,
            'date_added'             => now(),
            'custom_answers'         => $customAnswers,
        ]);

        // رفع السيرة الذاتية
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('talent-documents', $fileName, 'public');

            TalentDocument::create([
                'talent_id' => $talent->id,
                'type'      => DocumentType::CV,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'size'      => $file->getSize(),
            ]);
        }

        // إرسال تنبيه لموظفي الـ HR
        $admins = \App\Models\User::all();
        \Filament\Notifications\Notification::make()
            ->title('تقديم جديد لوظيفة: ' . $job->title)
            ->body("**{$talent->full_name}** قدم طلب انضمام للتو.")
            ->icon('heroicon-o-sparkles')
            ->iconColor('primary')
            ->color('primary')
            ->actions([
                \Filament\Actions\Action::make('view')
                    ->label('عرض الملف')
                    ->button()
                    ->url(\App\Filament\Admin\Resources\TalentResource::getUrl('view', ['record' => $talent->id]))
                    ->markAsRead(),
            ])
            ->sendToDatabase($admins);

        // إرسال بريد إلكتروني فوري للمرشح لتأكيد استلام الطلب بنجاح
        if (!empty($talent->email)) {
            try {
                \Illuminate\Support\Facades\Notification::route('mail', $talent->email)
                    ->notify(new \App\Notifications\TalentApplicationReceived($talent));
            } catch (\Throwable $e) {
                Log::error('فشل إرسال بريد التأكيد للمرشح: ' . $e->getMessage());
            }
        }

        $successMessage = $job->success_message ?: 'تم التقديم بنجاح! سيقوم فريق التوظيف بمراجعة ملفك والتواصل معك قريباً.';
        return redirect()->back()->with('success', strip_tags($successMessage));
    }
}
