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
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\HtmlString;
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
            ->modalWidth('5xl')
            ->modalHeading(function (?Talent $record, array $arguments = []): string {
                $talent = self::resolveTargetTalent($record, $arguments);
                if (! $talent) {
                    return 'تسجيل نتيجة مقابلة وتوضيح الكيس';
                }

                return 'تقييم وحسم كيس المرشح: ' . $talent->full_name;
            })
            ->modalDescription('وثّق تفاصيل المقابلة، سبب عدم القبول أو عدم الملاءمة، والملخص الكامل للحالة للرجوع إليه مستقبلاً.')
            ->modalSubmitActionLabel('حفظ المقابلة وقرار الكيس')
            ->modalIcon('heroicon-o-clipboard-document-check')
            ->modalIconColor('warning')
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

                Tabs::make('interview_tabs')
                    ->contained(false)
                    ->schema([
                        // ─── Tab 1: Interview Context ───
                        Tabs\Tab::make('سياق المقابلة')
                            ->icon('heroicon-m-calendar-days')
                            ->schema([
                                Grid::make(3)->schema([
                                    Select::make('interview_stage')
                                        ->label('مرحلة المقابلة')
                                        ->options(InterviewStage::class)
                                        ->required()
                                        ->native(false)
                                        ->prefixIcon('heroicon-m-funnel')
                                        ->columnSpan(1),

                                    Select::make('rating')
                                        ->label('التقييم العام')
                                        ->options([
                                            5 => '⭐⭐⭐⭐⭐  ممتاز',
                                            4 => '⭐⭐⭐⭐  جيد جداً',
                                            3 => '⭐⭐⭐  جيد',
                                            2 => '⭐⭐  مقبول',
                                            1 => '⭐  ضعيف',
                                        ])
                                        ->native(false)
                                        ->prefixIcon('heroicon-m-star')
                                        ->columnSpan(1),
                                ]),

                                ToggleButtons::make('attendance_status')
                                    ->label('حالة الحضور')
                                    ->options(InterviewAttendance::class)
                                    ->icons(InterviewAttendance::class)
                                    ->colors(InterviewAttendance::class)
                                    ->required()
                                    ->live()
                                    ->inline()
                                    ->afterStateUpdated(function (mixed $state, callable $set): void {
                                            $rawValue = $state instanceof \BackedEnum ? $state->value : $state;
                                            if ($rawValue === InterviewAttendance::NO_SHOW->value) {
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
                                            5 => '⭐⭐⭐⭐⭐  ممتاز',
                                            4 => '⭐⭐⭐⭐  جيد جداً',
                                            3 => '⭐⭐⭐  جيد',
                                            2 => '⭐⭐  مقبول',
                                            1 => '⭐  ضعيف',
                                        ])
                                        ->native(false)
                                        ->prefixIcon('heroicon-m-star'),
                                ]),

                                Fieldset::make('الملاحظات الفنية')
                                    ->columns(2)
                                    ->schema([
                                        Textarea::make('strengths')
                                            ->label('نقاط القوة')
                                            ->rows(3)
                                            ->placeholder('أبرز المميزات التي ظهرت في المقابلة...')
                                            ->helperText('مهارات، خبرات، شخصية مميزة..'),

                                        Textarea::make('weaknesses')
                                            ->label('نقاط الضعف / الفجوات')
                                            ->rows(3)
                                            ->placeholder('الفجوات الفنية أو الإدارية أو التحفظات...')
                                            ->helperText('نقاط تحتاج تطوير أو تحفظات..'),
                                    ]),
                            ]),

                        // ─── Tab 2: Decision & Outcome ───
                        Tabs\Tab::make('القرار والنتيجة')
                            ->icon('heroicon-m-scale')
                            ->schema([
                                // ⚠️ Dynamic warning banner
                                Placeholder::make('decision_warning')
                                    ->hiddenLabel()
                                    ->content(function (Get $get): ?HtmlString {
                                        $outcome = $get('decision_outcome');
                                        if (! $outcome) {
                                            return null;
                                        }

                                        $enum = TalentDecisionOutcome::tryFrom($outcome);
                                        if (! $enum || $enum === TalentDecisionOutcome::RECOMMENDED) {
                                            return null;
                                        }

                                        $config = match ($enum) {
                                            TalentDecisionOutcome::REJECTED => [
                                                'bg' => 'bg-danger-50 dark:bg-danger-950',
                                                'border' => 'border-danger-300 dark:border-danger-700',
                                                'text' => 'text-danger-800 dark:text-danger-200',
                                                'icon' => '🚫',
                                                'msg' => 'أنت على وشك <strong>رفض</strong> هذا المرشح نهائياً. يرجى توضيح السبب والملخص بدقة.',
                                            ],
                                            TalentDecisionOutcome::NOT_SUITABLE => [
                                                'bg' => 'bg-warning-50 dark:bg-warning-950',
                                                'border' => 'border-warning-300 dark:border-warning-700',
                                                'text' => 'text-warning-800 dark:text-warning-200',
                                                'icon' => '⚠️',
                                                'msg' => 'المرشح <strong>غير مناسب حالياً</strong>. وثّق الأسباب لتفيد الفريق مستقبلاً.',
                                            ],
                                            TalentDecisionOutcome::NO_SHOW => [
                                                'bg' => 'bg-danger-50 dark:bg-danger-950',
                                                'border' => 'border-danger-300 dark:border-danger-700',
                                                'text' => 'text-danger-800 dark:text-danger-200',
                                                'icon' => '👻',
                                                'msg' => 'المرشح <strong>لم يحضر</strong> المقابلة. سيتم تسجيل ذلك في ملفه.',
                                            ],
                                            TalentDecisionOutcome::WITHDRAWN => [
                                                'bg' => 'bg-gray-50 dark:bg-gray-800',
                                                'border' => 'border-gray-300 dark:border-gray-600',
                                                'text' => 'text-gray-700 dark:text-gray-300',
                                                'icon' => '🚶',
                                                'msg' => 'المرشح <strong>انسحب</strong> من العملية. وثّق السبب إن أمكن.',
                                            ],
                                            default => [
                                                'bg' => 'bg-info-50 dark:bg-info-950',
                                                'border' => 'border-info-300 dark:border-info-700',
                                                'text' => 'text-info-800 dark:text-info-200',
                                                'icon' => 'ℹ️',
                                                'msg' => 'مرشح احتياطي — يمكن ترشيحه لوظيفة أخرى.',
                                            ],
                                        };

                                        return new HtmlString(
                                            "<div class=\"rounded-xl border p-4 {$config['bg']} {$config['border']}\">"
                                            . "<p class=\"text-sm font-medium {$config['text']}\">"
                                            . "{$config['icon']} {$config['msg']}"
                                            . '</p></div>'
                                        );
                                    })
                                    ->visible(fn (Get $get): bool => filled($get('decision_outcome'))
                                        && $get('decision_outcome') !== TalentDecisionOutcome::RECOMMENDED->value),

                                // 🎛️ ToggleButtons for decision outcome
                                ToggleButtons::make('decision_outcome')
                                    ->label('النتيجة / القرار')
                                    ->options(TalentDecisionOutcome::class)
                                    ->icons(TalentDecisionOutcome::class)
                                    ->colors(TalentDecisionOutcome::class)
                                    ->descriptions(TalentDecisionOutcome::class)
                                    ->required()
                                    ->live()
                                    ->inline()
                                    ->columnSpanFull()
                                    ->afterStateUpdated(function (mixed $state, callable $set): void {
                                        $rawValue = $state instanceof TalentDecisionOutcome ? $state->value : $state;
                                        $outcome = $rawValue ? TalentDecisionOutcome::tryFrom($rawValue) : null;
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

                                Grid::make(2)->schema([
                                    Select::make('decision_reason')
                                        ->label('السبب الرئيسي')
                                        ->options(TalentDecisionReason::class)
                                        ->searchable()
                                        ->native(false)
                                        ->prefixIcon('heroicon-m-chat-bubble-bottom-center-text')
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
                                        ->prefixIcon('heroicon-m-arrow-path')
                                        ->helperText('اختر المرحلة الجديدة للمرشح في الجدول والكانبان.'),

                                    Select::make('reapply_eligibility')
                                        ->label('إمكانية إعادة التقديم مستقبلاً')
                                        ->options(ReapplyEligibility::class)
                                        ->native(false)
                                        ->prefixIcon('heroicon-m-arrow-uturn-left')
                                        ->visible(fn (Get $get): bool => $get('decision_outcome') !== TalentDecisionOutcome::RECOMMENDED->value),
                                ]),

                                Textarea::make('decision_summary')
                                    ->label('توضيح الكيس بالكامل (ملخص الحالة)')
                                    ->rows(4)
                                    ->columnSpanFull()
                                    ->placeholder('اشرح الكيس كاملة بوضوح: ما دار في المقابلة، سبب عدم القبول أو عدم الملاءمة، وأي سياق يفيد الفريق مستقبلاً...')
                                    ->helperText('هذا الملخص سيظهر في ملف المرشح ويساعد الفريق في القرارات المستقبلية.')
                                    ->required(fn (Get $get): bool => in_array($get('decision_outcome'), [
                                        TalentDecisionOutcome::NOT_SUITABLE->value,
                                        TalentDecisionOutcome::REJECTED->value,
                                        TalentDecisionOutcome::NO_SHOW->value,
                                        TalentDecisionOutcome::WITHDRAWN->value,
                                    ], true)),
                            ]),

                        // ─── Tab 3: Details & Alternatives ───
                        Tabs\Tab::make('تفاصيل إضافية')
                            ->icon('heroicon-m-document-text')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('interview_salary_expectation')
                                        ->label('الراتب المطلوب في المقابلة')
                                        ->numeric()
                                        ->prefix(fn () => Setting::currencySymbol())
                                        ->nullable()
                                        ->prefixIcon('heroicon-m-banknotes'),

                                    TextInput::make('notice_period')
                                        ->label('فترة الإشعار / الإتاحة الفعلية')
                                        ->placeholder('مثال: شهر واحد / متاح فوراً')
                                        ->maxLength(120)
                                        ->prefixIcon('heroicon-m-calendar'),

                                    Select::make('recommended_job_posting_id')
                                        ->label('ترشيح لوظيفة أخرى بديلة (اختياري)')
                                        ->options(fn () => JobPosting::where('is_active', true)->orderBy('title')->pluck('title', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->native(false)
                                        ->prefixIcon('heroicon-m-briefcase')
                                        ->visible(fn (Get $get): bool => $get('decision_outcome') !== TalentDecisionOutcome::RECOMMENDED->value)
                                        ->columnSpanFull(),
                                ]),
                            ]),

                        // ─── Tab 4: Email Notification ───
                        Tabs\Tab::make('إشعار المرشح بالبريد')
                            ->icon('heroicon-m-envelope')
                            ->badge(fn (Get $get): ?string => (bool) $get('send_rejection_email') ? '!' : null)
                            ->badgeColor('danger')
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
                                    ->live()
                                    ->onIcon('heroicon-m-paper-airplane')
                                    ->offIcon('heroicon-m-x-mark')
                                    ->onColor('danger'),
                                TextInput::make('rejection_email_subject')
                                    ->label('عنوان الإيميل')
                                    ->prefixIcon('heroicon-m-tag')
                                    ->visible(fn (Get $get): bool => (bool) $get('send_rejection_email'))
                                    ->required(fn (Get $get): bool => (bool) $get('send_rejection_email')),
                                Textarea::make('rejection_email_body')
                                    ->label('نص الرسالة')
                                    ->rows(5)
                                    ->visible(fn (Get $get): bool => (bool) $get('send_rejection_email'))
                                    ->required(fn (Get $get): bool => (bool) $get('send_rejection_email')),
                            ]),
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
