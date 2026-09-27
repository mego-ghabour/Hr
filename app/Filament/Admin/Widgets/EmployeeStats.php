<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected static bool $isLazy = false;

    protected int | string | array $columnStart = '';

    protected function getStats(): array
    {
        $activeEmployees = \App\Models\Employee::where('status', 'active')->count();
        $onLeaveToday = \App\Models\Employee::where('status', 'on_leave')->count();
        $pendingLeaves = \App\Models\Leave::where('status', 'pending')->count();

        return [
            Stat::make('الموظفين النشطين', $activeEmployees)
                ->description('إجمالي الموظفين على رأس العمل')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            
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
