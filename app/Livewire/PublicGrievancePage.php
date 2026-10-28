<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Grievance;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.portal')]
class PublicGrievancePage extends Component
{
    use WithFileUploads;

    // View State: 'submit', 'success', 'track'
    public string $viewMode = 'submit';

    // Wizard State
    public int $currentStep = 1;
    public int $totalSteps = 3;

    // Submission Form State
    public string $type = 'administrative'; // administrative, workplace, behavioral, suggestion, other
    public string $priority = 'normal'; // low, normal, high, urgent
    public string $title = '';
    public string $content = '';
    public ?int $department_id = null;
    public bool $against_manager = false;
    public bool $is_anonymous = true;
    public string $submitter_name = '';
    public string $submitter_contact = '';
    public $attachment = null;

    // Success State
    public ?string $submittedTicketNumber = null;
    public ?Grievance $submittedGrievance = null;

    // Tracking State
    public string $searchTicket = '';
    public ?Grievance $trackedGrievance = null;
    public bool $hasSearched = false;
    public ?string $trackError = null;

    // UI Configuration
    public bool $allowPublic = true;
    public string $brandName = '';
    public ?string $brandLogo = null;
    public string $primaryColor = '#0d9488';

    public function mount(): void
    {
        $this->allowPublic = (bool) Setting::get('allow_public_grievances', true);
        $this->brandName = (string) Setting::get('brand_name', 'نظام إدارة الموارد البشرية');
        $this->brandLogo = Setting::get('logo');
        $this->primaryColor = (string) Setting::get('brand_primary_color', '#0d9488');

        // Check if ticket query is passed for direct tracking: /complaints?ticket=GRV-XXXX
        $queryTicket = request()->query('ticket');
        if ($queryTicket) {
            $this->viewMode = 'track';
            $this->searchTicket = trim($queryTicket);
            $this->lookupTicket();
            return;
        }

        // If public submissions are disabled and visitor is NOT logged in:
        if (! $this->allowPublic && ! Auth::check()) {
            session()->flash('error', 'تم قفل التقديم العام للشكاوى. يتطلب الوصول تسجيل الدخول بحسابك الوظيفي.');
            redirect()->guest(route('portal.login', ['redirect' => '/portal?tab=grievances']));
            return;
        }

        // Auto-fill submitter name if logged in and not anonymous
        if (Auth::check()) {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->first();
            $this->submitter_name = $employee?->full_name ?? $user->name ?? '';
            $this->submitter_contact = $employee?->work_email ?? $user->email ?? $employee?->mobile ?? '';
            $this->department_id = $employee?->department_id;
        }
    }

    public function switchMode(string $mode): void
    {
        if (in_array($mode, ['submit', 'track'])) {
            $this->viewMode = $mode;
            if ($mode === 'submit') {
                $this->currentStep = 1;
            }
            $this->resetValidation();
        }
    }

    public function nextStep(): void
    {
        if ($this->currentStep === 1) {
            $rules = [
                'type' => 'required|string|in:administrative,workplace,behavioral,suggestion,other',
                'is_anonymous' => 'boolean',
            ];

            if (! $this->is_anonymous && ! Auth::check()) {
                $rules['submitter_name'] = 'required|string|min:3|max:100';
                $rules['submitter_contact'] = 'nullable|string|max:100';
            }

            $messages = [
                'type.required' => 'يرجى اختيار تصنيف الشكوى أو المقترح.',
                'submitter_name.required' => 'يرجى إدخال اسمك أو قم باختيار "تقديم سري 100%".',
                'submitter_name.min' => 'الاسم يجب ألا يقل عن 3 أحرف.',
            ];

            $this->validate($rules, $messages);
            $this->currentStep = 2;
        } elseif ($this->currentStep === 2) {
            $rules = [
                'title' => 'required|string|min:4|max:200',
                'content' => 'required|string|min:15|max:5000',
                'priority' => 'required|string|in:low,normal,high,urgent',
                'department_id' => 'nullable|exists:departments,id',
                'against_manager' => 'boolean',
            ];

            $messages = [
                'title.required' => 'يرجى كتابة عنوان للموضوع.',
                'title.min' => 'العنوان يجب ألا يقل عن 4 أحرف.',
                'content.required' => 'يرجى كتابة التفاصيل والوقائع بوضوح.',
                'content.min' => 'يرجى كتابة تفاصيل وافية (15 حرفاً على الأقل) لنتمكن من المتابعة الدقيقة.',
                'priority.required' => 'يرجى تحديد درجة الأهمية.',
            ];

            $this->validate($rules, $messages);
            $this->currentStep = 3;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep(int $step): void
    {
        if ($step < $this->currentStep) {
            $this->currentStep = $step;
        } elseif ($step === 2 && $this->currentStep === 1) {
            $this->nextStep();
        } elseif ($step === 3 && $this->currentStep === 2) {
            $this->nextStep();
        }
    }

    public function removeAttachment(): void
    {
        $this->attachment = null;
    }

    public function submit(): void
    {
        // Re-check permission if user is not authenticated
        if (! $this->allowPublic && ! Auth::check()) {
            session()->flash('error', 'التقديم العام مغلق حالياً. يرجى تسجيل الدخول أولاً.');
            redirect()->route('portal.login');
            return;
        }

        $rules = [
            'type' => 'required|string|in:administrative,workplace,behavioral,suggestion,other',
            'priority' => 'required|string|in:low,normal,high,urgent',
            'title' => 'required|string|min:4|max:200',
            'content' => 'required|string|min:15|max:5000',
            'department_id' => 'nullable|exists:departments,id',
            'against_manager' => 'boolean',
            'is_anonymous' => 'boolean',
            'attachment' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx',
        ];

        if (! $this->is_anonymous && ! Auth::check()) {
            $rules['submitter_name'] = 'required|string|min:3|max:100';
            $rules['submitter_contact'] = 'nullable|string|max:100';
        }

        $messages = [
            'type.required' => 'يرجى اختيار تصنيف الشكوى أو المقترح.',
            'title.required' => 'يرجى كتابة عنوان مختصر للموضوع.',
            'title.min' => 'العنوان يجب ألا يقل عن 4 أحرف.',
            'content.required' => 'يرجى كتابة التفاصيل والوقائع بوضوح.',
            'content.min' => 'يرجى كتابة تفاصيل وافية (15 حرفاً على الأقل) لنتمكن من المتابعة الدقيقة.',
            'submitter_name.required' => 'يرجى إدخال اسمك أو قم باختيار "إرسال سري (بدون اسم)".',
            'attachment.max' => 'الحد الأقصى للمرفق هو 5 ميجابايت.',
            'attachment.mimes' => 'صيغ الملفات المسموح بها: PDF, JPG, PNG, DOC, DOCX.',
        ];

        try {
            $this->validate($rules, $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorKeys = array_keys($e->errors());
            if (in_array('type', $errorKeys) || in_array('submitter_name', $errorKeys)) {
                $this->currentStep = 1;
            } elseif (in_array('title', $errorKeys) || in_array('content', $errorKeys) || in_array('priority', $errorKeys)) {
                $this->currentStep = 2;
            }
            throw $e;
        }

        $attachmentPath = null;
        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('grievances', 'public');
        }

        $user = Auth::user();
        $employee = $user ? Employee::where('user_id', $user->id)->first() : null;

        $grievance = Grievance::create([
            'user_id' => $user?->id,
            'employee_id' => $employee?->id,
            'submitter_name' => $this->is_anonymous ? null : ($user ? ($employee?->full_name ?? $user->name) : $this->submitter_name),
            'submitter_contact' => $this->is_anonymous ? null : ($user ? ($employee?->work_email ?? $user->email) : $this->submitter_contact),
            'is_anonymous' => $this->is_anonymous,
            'type' => $this->type,
            'priority' => $this->priority,
            'title' => trim($this->title),
            'content' => trim($this->content),
            'department_id' => $this->department_id,
            'against_manager' => $this->against_manager,
            'attachment_path' => $attachmentPath,
            'status' => 'new',
        ]);

        $this->submittedTicketNumber = $grievance->ticket_number;
        $this->submittedGrievance = $grievance;
        $this->viewMode = 'success';
        $this->currentStep = 1;
    }

    public function lookupTicket(): void
    {
        $cleanTicket = strtoupper(trim($this->searchTicket));
        $this->hasSearched = true;
        $this->trackError = null;
        $this->trackedGrievance = null;

        if (empty($cleanTicket)) {
            $this->trackError = 'يرجى إدخال رقم التذكرة للبحث (مثال: GRV-2610-0001).';
            return;
        }

        if (! Schema::hasTable('grievances')) {
            $this->trackError = 'الخدمة غير مفعلة حالياً في قاعدة البيانات.';
            return;
        }

        $found = Grievance::where('ticket_number', $cleanTicket)->first();

        if (! $found) {
            $this->trackError = "لم يتم العثور على تذكرة بالرقم ({$cleanTicket}). يرجى التحقق من الرقم والمحاولة مرة أخرى.";
            return;
        }

        $this->trackedGrievance = $found;
    }

    public function trackSubmitted(): void
    {
        if ($this->submittedTicketNumber) {
            $this->searchTicket = $this->submittedTicketNumber;
            $this->viewMode = 'track';
            $this->lookupTicket();
        }
    }

    public function resetForm(): void
    {
        $this->type = 'administrative';
        $this->priority = 'normal';
        $this->title = '';
        $this->content = '';
        $this->department_id = null;
        $this->against_manager = false;
        $this->is_anonymous = true;
        $this->submitter_name = '';
        $this->submitter_contact = '';
        $this->attachment = null;
        $this->submittedTicketNumber = null;
        $this->submittedGrievance = null;
        $this->viewMode = 'submit';
        $this->currentStep = 1;
        $this->resetValidation();
    }

    public function render()
    {
        $departments = collect();
        if (Schema::hasTable('departments')) {
            $departments = Department::orderBy('name')->get();
        }

        return view('livewire.public-grievance-page', [
            'departments' => $departments,
            'isAuth' => Auth::check(),
            'authUser' => Auth::user(),
            'brandName' => $this->brandName,
            'brandLogo' => $this->brandLogo,
            'primaryColor' => $this->primaryColor,
        ])->title('صندوق الشكاوى والمقترحات الرقمي | ' . $this->brandName);
    }
}
