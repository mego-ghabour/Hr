<?php

namespace App\Filament\Admin\Resources\Payrolls\Pages;

use App\Filament\Admin\Resources\Payrolls\PayrollResource;
use App\Models\Employee;
use App\Models\Payroll;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPayrolls extends ListRecords
{
    protected static string $resource = PayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // زر توليد مسير الشهر تلقائياً لكافة الموظفين
            Action::make('generate_monthly_payroll')
                ->label('توليد مسير الشهر تلقائياً')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->modalHeading('توليد مسير الرواتب الشهري للموظفين النشطين')
                ->modalDescription('سيتم جلب كافة الموظفين النشطين بالشركة وتوليد سجلات مسير الرواتب تلقائياً بناءً على رواتبهم الأساسية المسجلة.')
                ->form([
                    Select::make('month')
                        ->label('عن شهر')
                        ->options([
                            1 => 'يناير',
                            2 => 'فبراير',
                            3 => 'مارس',
                            4 => 'أبريل',
                            5 => 'مايو',
                            6 => 'يونيو',
                            7 => 'يوليو',
                            8 => 'أغسطس',
                            9 => 'سبتمبر',
                            10 => 'أكتوبر',
                            11 => 'نوفمبر',
                            12 => 'ديسمبر',
                        ])
                        ->default(now()->month)
                        ->required(),

                    TextInput::make('year')
                        ->label('السنة')
                        ->numeric()
                        ->default(now()->year)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $month = (int) $data['month'];
                    $year = (int) $data['year'];

                    $activeEmployees = Employee::where('status', 'active')->whereNotNull('salary')->get();
                    $createdCount = 0;
                    $skippedCount = 0;

                    foreach ($activeEmployees as $emp) {
                        $exists = Payroll::where('employee_id', $emp->id)
                            ->where('month', $month)
                            ->where('year', $year)
                            ->exists();

                        if ($exists) {
                            $skippedCount++;
                            continue;
                        }

                        Payroll::create([
                            'employee_id' => $emp->id,
                            'month' => $month,
                            'year' => $year,
                            'basic_salary' => $emp->salary,
                            'allowances' => 0,
                            'deductions' => 0,
                            'net_salary' => $emp->salary,
                            'status' => 'draft',
                            'payment_method' => $emp->iban ? 'bank_transfer' : 'cash',
                        ]);
                        $createdCount++;
                    }

                    Notification::make()
                        ->title("اكتمل التوليد: تم إنشاء {$createdCount} مسير جديد (وتخطي {$skippedCount} مسير موجود مسبقاً)")
                        ->success()
                        ->send();
                }),

            // زر تصدير كشف الرواتب Excel / CSV
            Action::make('export_csv')
                ->label('تصدير كشف الرواتب (Excel / CSV)')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    return response()->streamDownload(function () {
                        $handle = fopen('php://output', 'w');
                        fputs($handle, "\xEF\xBB\xBF");
                        fputcsv($handle, [
                            'اسم الموظف',
                            'القسم',
                            'المسمى الوظيفي',
                            'الفترة / الشهر',
                            'الراتب الأساسي',
                            'البدلات والحوافز',
                            'الخصومات والغيابات',
                            'صافي الراتب المستحق',
                            'الحالة',
                            'طريقة الصرف',
                            'الحساب البنكي / IBAN',
                            'تاريخ الصرف'
                        ]);

                        Payroll::with('employee.department')->latest()->chunk(200, function ($payrolls) use ($handle) {
                            foreach ($payrolls as $r) {
                                fputcsv($handle, [
                                    $r->employee ? "{$r->employee->first_name} {$r->employee->last_name}" : '-',
                                    $r->employee?->department?->name ?? '-',
                                    $r->employee?->job_title ?? '-',
                                    $r->period,
                                    $r->basic_salary,
                                    $r->allowances,
                                    $r->deductions,
                                    $r->net_salary,
                                    match ($r->status) {
                                        'draft' => 'مسودة',
                                        'approved' => 'معتمد',
                                        'paid' => 'تم الصرف',
                                        default => $r->status,
                                    },
                                    match ($r->payment_method) {
                                        'bank_transfer' => 'تحويل بنكي',
                                        'cash' => 'نقداً',
                                        'cheque' => 'شيك',
                                        default => $r->payment_method,
                                    },
                                    $r->employee?->iban ?? '-',
                                    $r->payment_date?->format('Y-m-d') ?? '-',
                                ]);
                            }
                        });
                        fclose($handle);
                    }, 'payroll-report-' . date('Y-m-d') . '.csv');
                }),

            CreateAction::make()->label('إضافة مسير فردي'),
        ];
    }
}
