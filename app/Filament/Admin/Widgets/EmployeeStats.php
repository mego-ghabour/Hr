<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeStats extends StatsOverviewWidget
{
    use \Filament\Widgets\Concerns\InteractsWithPageFilters;
    protected static ?int $sort = 1;
    protected static bool $isLazy = false;

    protected int | string | array $columnStart = '';

    protected function getStats(): array
    {
        $activeEmployees = \App\Models\Employee::where('status', 'active')->count();
        $totalMonthlyPayroll = (float) \App\Models\Employee::where('status', 'active')->sum('salary');
        $currencySymbol = \App\Models\Setting::currencySymbol();
        $onLeaveToday = \App\Models\Employee::where('status', 'on_leave')->count();
        $pendingLeaves = \App\Models\Leave::where('status', 'pending')->count();

        return [
            Stat::make('الموظفين النشطين', $activeEmployees)
                ->description('إجمالي الموظفين على رأس العمل')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('إجمالي الرواتب الشهرية', number_format($totalMonthlyPayroll, 2) . ' ' . $currencySymbol)
                ->description('الكتلة الشهرية للموظفين النشطين')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),
            
            Stat::make('في إجازة', $onLeaveToday)
                ->description('الموظفين المجازين حالياً')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'),
                
            Stat::make('طلبات الإجازة المعلقة', $pendingLeaves)
                ->description('طلبات بانتظار الموافقة')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
        ];
    }
}
