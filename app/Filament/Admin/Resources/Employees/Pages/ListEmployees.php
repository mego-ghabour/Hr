<?php

namespace App\Filament\Admin\Resources\Employees\Pages;

use App\Filament\Admin\Resources\Employees\EmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export_csv')
                ->label('تصدير الموظفين (Excel / CSV)')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    return response()->streamDownload(function () {
                        $handle = fopen('php://output', 'w');
                        fputs($handle, "\xEF\xBB\xBF");
                        fputcsv($handle, [
                            'الاسم الكامل',
                            'القسم',
                            'المسمى الوظيفي',
                            'الراتب الأساسي',
                            'رقم الهاتف',
                            'الرقم القومي / الهوية',
                            'تاريخ التعيين',
                            'حالة الموظف',
                            'جهة اتصال الطوارئ',
                            'هاتف الطوارئ'
                        ]);
                        
                        \App\Models\Employee::with('department')->chunk(200, function ($records) use ($handle) {
                            foreach ($records as $r) {
                                fputcsv($handle, [
                                    $r->first_name . ' ' . $r->last_name,
                                    $r->department?->name ?? '-',
                                    $r->job_title ?? '-',
                                    $r->salary ? (number_format((float) $r->salary, 2) . ' ' . \App\Models\Setting::currencySymbol()) : '-',
                                    $r->phone ?? '-',
                                    $r->national_id ?? '-',
                                    $r->hire_date ?? '-',
                                    match ($r->status) {
                                        'active' => 'نشط',
                                        'on_leave' => 'في إجازة',
                                        'terminated' => 'مستقيل / مفصول',
                                        default => $r->status,
                                    },
                                    $r->emergency_contact_name ?? '-',
                                    $r->emergency_contact_phone ?? '-',
                                ]);
                            }
                        });
                        fclose($handle);
                    }, 'employees-report-' . date('Y-m-d') . '.csv');
                }),
            CreateAction::make(),
        ];
    }
}
