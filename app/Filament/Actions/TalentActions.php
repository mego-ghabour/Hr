<?php

namespace App\Filament\Actions;

use App\Models\Talent;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\TalentStatus;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
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
                $currencySymbol = \App\Models\Setting::currencySymbol();
                $record->notes()->create([
                    'user_id' => Auth::id(),
                    'content' => "تم تعيين المرشح رسمياً كموظف جديد برقم: EMP-{$employee->id} براتب: " . number_format($employee->salary, 2) . " {$currencySymbol} وتجهيز رصيد إجازاته.",
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
}
