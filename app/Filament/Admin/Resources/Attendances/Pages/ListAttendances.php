<?php

namespace App\Filament\Admin\Resources\Attendances\Pages;

use App\Filament\Admin\Resources\Attendances\AttendanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export_csv')
                ->label('تصدير كشف الحضور (Excel / CSV)')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    return response()->streamDownload(function () {
                        $handle = fopen('php://output', 'w');
                        fputs($handle, "\xEF\xBB\xBF");
                        fputcsv($handle, [
                            'اسم الموظف',
                            'القسم',
                            'التاريخ',
                            'وقت الحضور',
                            'وقت الانصراف',
                            'ساعات العمل',
                            'الحالة',
                            'ملاحظات'
                        ]);
                        
                        \App\Models\Attendance::with('employee.department')->latest('date')->chunk(200, function ($records) use ($handle) {
                            foreach ($records as $r) {
                                fputcsv($handle, [
                                    $r->employee ? ($r->employee->first_name . ' ' . $r->employee->last_name) : '-',
                                    $r->employee?->department?->name ?? '-',
                                    $r->date,
                                    $r->check_in_time ?? '-',
                                    $r->check_out_time ?? '-',
                                    $r->total_hours ?? '-',
                                    match ($r->status) {
                                        'present' => 'حاضر',
                                        'absent' => 'غائب',
                                        'late' => 'متأخر',
                                        'half_day' => 'نصف يوم',
                                        default => $r->status,
                                    },
                                    $r->notes ?? '-',
                                ]);
                            }
                        });
                        fclose($handle);
                    }, 'attendance-report-' . date('Y-m-d') . '.csv');
                }),
            CreateAction::make(),
        ];
    }
}
