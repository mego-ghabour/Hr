<?php

namespace App\Filament\Actions;

use App\Actions\Talent\RecordInterviewCaseAction;
use App\Enums\InterviewAttendance;
use App\Enums\InterviewStage;
use App\Enums\ReapplyEligibility;
use App\Enums\TalentDecisionOutcome;
use App\Enums\TalentDecisionReason;
use App\Enums\TalentNoteCategory;
use App\Models\JobPosting;
use App\Models\Setting;
use App\Models\Talent;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\TalentStatus;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TalentActions
{
    /**
     * Action to convert/hire a Talent into an Employee
     */
    public static function makeHireAsEmployeeAction(): Action
    {
        return Action::make('hireAsEmployee')
            ->label('تعيين كموظف')
            ->icon('heroicon-m-user-plus')
            ->color('success')
            ->modalHeading(fn (Talent $record) => 'تعيين المرشح: ' . $record->full_name . ' كموظف جديد')
            ->modalDescription('سيتم إنشاء ملف موظف جديد في شؤون العاملين ونقل مستنداته وتجهيز أرصدة إجازاته السنوية تلقائياً.')
            ->modalSubmitActionLabel('تأكيد التعيين')
            ->form(function (Talent $record) {
                $nameParts = explode(' ', trim($record->full_name), 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';

                $defaultJobTitle = $record->jobPosting?->title ?? $record->current_job_title;

                return [
                    Grid::make(2)->schema([
                        TextInput::make('first_name')
                            ->label('الاسم الأول')
                            ->default($firstName)
                            ->required(),
                        TextInput::make('last_name')
                            ->label('الاسم الأخير')
                            ->default($lastName)
                            ->required(),
                        TextInput::make('phone')
                            ->label('رقم الهاتف')
                            ->default($record->phone)
                            ->tel(),
                        TextInput::make('national_id')
                            ->label('الرقم القومي / الهوية')
                            ->numeric()
                            ->maxLength(14)
                            ->nullable(),
                        Select::make('department_id')
                            ->label('القسم')
                            ->relationship('department', 'name')
                            ->default($record->department_id)
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('job_title')
                            ->label('المسمى الوظيفي')
                            ->default($defaultJobTitle)
                            ->required(),
                        TextInput::make('salary')
                            ->label('الراتب المتفق عليه شهرياً')
                            ->numeric()
                            ->prefix(fn () => \App\Models\Setting::currencySymbol())
                            ->default($record->expected_salary)
                            ->required(),
                        DatePicker::make('hire_date')
                            ->label('تاريخ بدء العمل')
                            ->default(now()->toDateString())
                            ->required(),
                        Select::make('manager_id')
                            ->label('المدير المباشر')
                            ->options(function () {
                                return Employee::where('status', 'active')
                                    ->get()
                                    ->mapWithKeys(fn ($emp) => [$emp->id => $emp->first_name . ' ' . $emp->last_name . ' (' . ($emp->job_title ?? 'موظف') . ')']);
                            })
                            ->searchable()
                            ->nullable(),
                    ]),

                    Section::make('حساب الدخول للنظام (اختياري)')
                        ->collapsible()
                        ->collapsed(true)
                        ->schema([
                            Toggle::make('create_user_account')
                                ->label('إنشاء حساب مستخدم جديد لدخول النظام')
                                ->reactive(),
                            TextInput::make('account_email')
                                ->label('البريد الإلكتروني للدخول')
                                ->default($record->email)
                                ->email()
                                ->visible(fn (Get $get) => $get('create_user_account')),
                            TextInput::make('account_password')
                                ->label('كلمة المرور الافتراضية')
                                ->password()
                                ->default('12345678')
                                ->helperText('الافتراضي: 12345678')
                                ->visible(fn (Get $get) => $get('create_user_account')),
                        ]),
                ];
            })
            ->action(function (array $data, Talent $record) {
                // 1. Create User account if requested
                $userId = null;
                if (!empty($data['create_user_account']) && !empty($data['account_email'])) {
                    $existingUser = User::where('email', $data['account_email'])->first();
                    if ($existingUser) {
                        $userId = $existingUser->id;
                    } else {
                        $user = User::create([
                            'name' => $data['first_name'] . ' ' . $data['last_name'],
                            'email' => $data['account_email'],
                            'password' => Hash::make($data['account_password'] ?? '12345678'),
                        ]);
                        $userId = $user->id;
                    }
                }

                // 2. Create Employee
                $employee = Employee::create([
                    'user_id' => $userId,
                    'department_id' => $data['department_id'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone' => $data['phone'] ?? null,
                    'national_id' => $data['national_id'] ?? null,
                    'job_title' => $data['job_title'],
                    'salary' => $data['salary'],
                    'hire_date' => $data['hire_date'],
                    'manager_id' => $data['manager_id'] ?? null,
                    'status' => 'active',
                ]);

                // 3. Copy documents from Talent to Employee
                foreach ($record->documents as $doc) {
                    EmployeeDocument::create([
                        'employee_id' => $employee->id,
                        'name' => $doc->file_name ?? 'السيرة الذاتية للمرشح',
                        'file_path' => $doc->file_path,
                    ]);
                }

                // 4. Initialize Leave Balances
                $leaveTypes = LeaveType::all();
                $year = now()->year;
                foreach ($leaveTypes as $lt) {
                    LeaveBalance::firstOrCreate([
                        'employee_id' => $employee->id,
                        'leave_type_id' => $lt->id,
                        'year' => $year,
                    ], [
                        'total_days' => $lt->default_days_per_year ?? 21,
                        'used_days' => 0,
                    ]);
                }

                // 5. Update Talent status to Hired
                $hiredStatus = TalentStatus::where('name', 'LIKE', '%توظيف%')
                    ->orWhere('name', 'LIKE', '%hired%')
                    ->orWhere('name', 'LIKE', '%مقبول%')
                    ->first();
                if ($hiredStatus) {
                    $record->update(['status_id' => $hiredStatus->id]);
                }

                // 6. Log note on talent record
                $currencySymbol = Setting::currencySymbol();
                $record->notes()->create([
                    'user_id' => Auth::id(),
                    'category' => TalentNoteCategory::GENERAL->value,
                    'body' => "تم تعيين المرشح رسمياً كموظف جديد برقم: EMP-{$employee->id} براتب: " . number_format($employee->salary, 2) . " {$currencySymbol} وتجهيز رصيد إجازاته.",
                ]);

                Notification::make()
                    ->title('تم التعيين بنجاح!')
                    ->body("تم إنشاء ملف وظيفي للموظف {$employee->first_name} {$employee->last_name} وتجهيز مستنداته وأرصدة إجازاته.")
                    ->success()
                    ->actions([
                        \Filament\Actions\Action::make('view_employee')
                            ->label('عرض ملف الموظف')
                            ->button()
                            ->url(\App\Filament\Admin\Resources\Employees\EmployeeResource::getUrl('edit', ['record' => $employee->id])),
                    ])
                    ->send();
            });
    }

    /**
     * Resolve the target Talent from either a record-bound action or Kanban arguments.
     */
    private static function resolveTargetTalent(?Talent $record, array $arguments = []): ?Talent
    {
        if ($record instanceof Talent) {
            return $record;
        }

        $talentId = $arguments['talentId'] ?? null;

        return $talentId ? Talent::find((int) $talentId) : null;
    }

    private static function formatRejectionEmailDefaults(Talent $talent): array
    {
        $brand = (string) Setting::get('brand_name', 'فريق التوظيف');
        $subjectTpl = (string) Setting::get('email_template_reject_subject', 'تحديث بخصوص التقديم - {brand}');
        $bodyTpl = (string) Setting::get(
            'email_template_reject_body',
            "عزيزي/عزيزتي {name}،\n\nنشكرك على اهتمامك بالانضمام إلينا. نود إعلامك بأنه لم يقع الاختيار عليك في هذه المرحلة.\n\nنتمنى لك كل التوفيق.\n{brand}"
        );

        $replace = [
            '{name}' => $talent->full_name,
            '{brand}' => $brand,
        ];

        return [
            'subject' => strtr($subjectTpl, $replace),
            'body' => strtr($bodyTpl, $replace),
        ];
    }

    /**
     * Unified action for recording interview feedback, rejection/not-suitable decisions, and full case summary.
     * Reused across Default View (Table & ViewTalent) and Kanban Board.
     */
    public static function makeRecordInterviewCaseAction(string $name = 'recordInterviewCase', ?callable $afterSaveCallback = null): Action
    {
        return Action::make($name)
            ->label('تسجيل مقابلة / قرار')
            ->icon('heroicon-m-clipboard-document-check')
            ->color('warning')
            ->modalWidth('3xl')
            ->modalHeading(function (?Talent $record, array $arguments = []): string {
                $talent = self::resolveTargetTalent($record, $arguments);
                if (! $talent) {
                    return 'تسجيل نتيجة مقابلة وتوضيح الكيس';
                }

                return 'تقييم وحسم كيس المرشح: ' . $talent->full_name;
            })
            ->modalDescription('وثّق تفاصيل المقابلة، سبب عدم القبول أو عدم الملاءمة، والملخص الكامل للحالة للرجوع إليه مستقبلاً.')
            ->modalSubmitActionLabel('حفظ المقابلة وقرار الكيس')
            ->authorize(function (?Talent $record, array $arguments = []): bool {
                $user = Auth::user();
                if (! $user) {
                    return false;
                }

                $talent = self::resolveTargetTalent($record, $arguments);

                return ($talent && $user->can('update', $talent))
                    || $user->can('update_talent')
                    || $user->can('manage_reviews')
                    || $user->can('manage_notes');
            })
            ->fillForm(function (?Talent $record, array $arguments = []): array {
                $talent = self::resolveTargetTalent($record, $arguments);
                $targetStatusId = $arguments['targetStatusId'] ?? null;
                $targetStatus = $targetStatusId ? TalentStatus::find((int) $targetStatusId) : null;

                $suggestedOutcome = $targetStatus?->suggestDecisionOutcome()?->value
                    ?? $talent?->decision_outcome?->value;

                $defaultStatusId = $targetStatus?->id ?? $talent?->status_id;

                $emailDefaults = $talent
                    ? self::formatRejectionEmailDefaults($talent)
                    : ['subject' => '', 'body' => ''];

                return [
                    'interview_stage' => InterviewStage::HR_SCREENING->value,
                    'attendance_status' => $suggestedOutcome === TalentDecisionOutcome::NO_SHOW->value
                        ? InterviewAttendance::NO_SHOW->value
                        : InterviewAttendance::ATTENDED->value,
                    'decision_outcome' => $suggestedOutcome,
                    'decision_reason' => $talent?->decision_reason?->value,
                    'status_id' => $defaultStatusId,
                    'reapply_eligibility' => $talent?->reapply_eligibility?->value,
                    'recommended_job_posting_id' => $talent?->recommended_job_posting_id,
                    'interview_salary_expectation' => $talent?->interview_salary_expectation ?? $talent?->expected_salary,
                    'decision_summary' => $talent?->decision_summary,
                    'has_candidate_email' => filled($talent?->email),
                    'send_rejection_email' => false,
                    'rejection_email_subject' => $emailDefaults['subject'],
                    'rejection_email_body' => $emailDefaults['body'],
                ];
            })
            ->form([
                Hidden::make('has_candidate_email'),
                Section::make('سياق المقابلة والحضور')
                    ->columns(3)
                    ->schema([
                        Select::make('interview_stage')
                            ->label('مرحلة المقابلة')
                            ->options(InterviewStage::class)
                            ->required()
                            ->native(false),

                        Select::make('attendance_status')
                            ->label('حالة الحضور')
                            ->options(InterviewAttendance::class)
                            ->required()
                            ->live()
                            ->native(false)
                            ->afterStateUpdated(function (?string $state, callable $set): void {
                                if ($state === InterviewAttendance::NO_SHOW->value) {
                                    $set('decision_outcome', TalentDecisionOutcome::NO_SHOW->value);
                                    $set('decision_reason', TalentDecisionReason::NO_SHOW->value);
                                    $status = TalentStatus::findStatusForOutcome(TalentDecisionOutcome::NO_SHOW);
                                    if ($status) {
                                        $set('status_id', $status->id);
                                    }
                                }
                            }),

                        Select::make('rating')
                            ->label('التقييم العام')
                            ->options([
                                5 => '5 - ممتاز',
                                4 => '4 - جيد جداً',
                                3 => '3 - جيد',
                                2 => '2 - مقبول',
                                1 => '1 - ضعيف',
                            ])
                            ->native(false),
                    ]),

                Section::make('القرار وتوضيح الكيس بالكامل')
                    ->columns(2)
                    ->schema([
                        Select::make('decision_outcome')
                            ->label('النتيجة / القرار')
                            ->options(TalentDecisionOutcome::class)
                            ->required()
                            ->live()
                            ->native(false)
                            ->afterStateUpdated(function (?string $state, callable $set): void {
                                $outcome = $state ? TalentDecisionOutcome::tryFrom($state) : null;
                                if (! $outcome) {
                                    return;
                                }

                                if ($outcome === TalentDecisionOutcome::NO_SHOW) {
                                    $set('decision_reason', TalentDecisionReason::NO_SHOW->value);
                                } elseif ($outcome === TalentDecisionOutcome::WITHDRAWN) {
                                    $set('decision_reason', TalentDecisionReason::CANDIDATE_WITHDREW->value);
                                } elseif ($outcome === TalentDecisionOutcome::RECOMMENDED) {
                                    $set('decision_reason', null);
                                }

                                $suggestedStatus = TalentStatus::findStatusForOutcome($outcome);
                                if ($suggestedStatus) {
                                    $set('status_id', $suggestedStatus->id);
                                }
                            }),

                        Select::make('decision_reason')
                            ->label('السبب الرئيسي')
                            ->options(TalentDecisionReason::class)
                            ->searchable()
                            ->native(false)
                            ->visible(fn (Get $get): bool => $get('decision_outcome') !== TalentDecisionOutcome::RECOMMENDED->value)
                            ->required(fn (Get $get): bool => in_array($get('decision_outcome'), [
                                TalentDecisionOutcome::NOT_SUITABLE->value,
                                TalentDecisionOutcome::REJECTED->value,
                                TalentDecisionOutcome::NO_SHOW->value,
                                TalentDecisionOutcome::WITHDRAWN->value,
                            ], true)),

                        Select::make('status_id')
                            ->label('تحديث حالة المرشح إلى')
                            ->options(fn () => TalentStatus::orderBy('order_column')->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->helperText('اختر المرحلة الجديدة للمرشح في الجدول والكانبان.'),

                        Select::make('reapply_eligibility')
                            ->label('إمكانية إعادة التقديم مستقبلاً')
                            ->options(ReapplyEligibility::class)
                            ->native(false)
                            ->visible(fn (Get $get): bool => $get('decision_outcome') !== TalentDecisionOutcome::RECOMMENDED->value),

                        Select::make('recommended_job_posting_id')
                            ->label('ترشيح لوظيفة أخرى بديلة (اختياري)')
                            ->options(fn () => JobPosting::where('is_active', true)->orderBy('title')->pluck('title', 'id'))
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->visible(fn (Get $get): bool => $get('decision_outcome') !== TalentDecisionOutcome::RECOMMENDED->value),

                        TextInput::make('interview_salary_expectation')
                            ->label('الراتب المطلوب في المقابلة')
                            ->numeric()
                            ->prefix(fn () => Setting::currencySymbol())
                            ->nullable(),

                        TextInput::make('notice_period')
                            ->label('فترة الإشعار / الإتاحة الفعلية')
                            ->placeholder('مثال: شهر واحد / متاح فوراً')
                            ->maxLength(120),

                        Textarea::make('strengths')
                            ->label('نقاط القوة')
                            ->rows(2)
                            ->placeholder('أبرز المميزات التي ظهرت في المقابلة...'),

                        Textarea::make('weaknesses')
                            ->label('نقاط الضعف / الفجوات')
                            ->rows(2)
                            ->placeholder('الفجوات الفنية أو الإدارية أو التحفظات...'),

                        Textarea::make('decision_summary')
                            ->label('توضيح الكيس بالكامل (ملخص الحالة)')
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('اشرح الكيس كاملة بوضوح: ما دار في المقابلة، سبب عدم القبول أو عدم الملاءمة، وأي سياق يفيد الفريق مستقبلاً...')
                            ->required(fn (Get $get): bool => in_array($get('decision_outcome'), [
                                TalentDecisionOutcome::NOT_SUITABLE->value,
                                TalentDecisionOutcome::REJECTED->value,
                                TalentDecisionOutcome::NO_SHOW->value,
                                TalentDecisionOutcome::WITHDRAWN->value,
                            ], true)),
                    ]),

                Section::make('إشعار المرشح بالبريد الإلكتروني (اختياري)')
                    ->collapsible()
                    ->collapsed(true)
                    ->visible(function (Get $get): bool {
                        if (! (bool) $get('has_candidate_email')) {
                            return false;
                        }

                        return in_array($get('decision_outcome'), [
                            TalentDecisionOutcome::NOT_SUITABLE->value,
                            TalentDecisionOutcome::REJECTED->value,
                            TalentDecisionOutcome::NO_SHOW->value,
                        ], true);
                    })
                    ->schema([
                        Toggle::make('send_rejection_email')
                            ->label('إرسال إيميل اعتذار لطيف للمرشح الآن')
                            ->default(false)
                            ->live(),
                        TextInput::make('rejection_email_subject')
                            ->label('عنوان الإيميل')
                            ->visible(fn (Get $get): bool => (bool) $get('send_rejection_email'))
                            ->required(fn (Get $get): bool => (bool) $get('send_rejection_email')),
                        Textarea::make('rejection_email_body')
                            ->label('نص الرسالة')
                            ->rows(4)
                            ->visible(fn (Get $get): bool => (bool) $get('send_rejection_email'))
                            ->required(fn (Get $get): bool => (bool) $get('send_rejection_email')),
                    ]),
            ])
            ->action(function (array $data, ?Talent $record, array $arguments = []) use ($afterSaveCallback): void {
                $talent = self::resolveTargetTalent($record, $arguments);
                if (! $talent) {
                    Notification::make()
                        ->title('تعذر العثور على المرشح')
                        ->danger()
                        ->send();

                    return;
                }

                app(RecordInterviewCaseAction::class)->execute($talent, $data, Auth::user());

                if ($afterSaveCallback) {
                    $afterSaveCallback($talent->fresh());
                }

                Notification::make()
                    ->title('تم توثيق المقابلة وحفظ الكيس بنجاح')
                    ->body("تم تسجيل القرار وتحديث ملف المرشح {$talent->full_name}.")
                    ->success()
                    ->send();
            });
    }
}
