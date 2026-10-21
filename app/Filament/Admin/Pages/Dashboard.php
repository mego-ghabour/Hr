<?php

namespace App\Filament\Admin\Pages;

use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;

class Dashboard extends BaseDashboard
{
    use HasFiltersAction;

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'نظرة عامة';
    }

    public function getSubheading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        return 'نظام الموارد البشرية - لوحة المدير العام';
    }



    public function getColumns(): int | array
    {
        return [
            'sm'  => 1,
            'md'  => 2,
            'lg'  => 2,
            'xl'  => 2,
            '2xl' => 2,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Pages\Dashboard\Actions\FilterAction::make()
                ->name('تصفية البيانات')
                ->icon('heroicon-o-funnel')
                ->form([
                    DatePicker::make('startDate')
                        ->label('من تاريخ')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->closeOnDateSelection(),
                    DatePicker::make('endDate')
                        ->label('إلى تاريخ')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->closeOnDateSelection(),
                    \Filament\Forms\Components\Select::make('department_id')
                        ->label('تصفية بالقسم')
                        ->options(\App\Models\Department::pluck('name', 'id'))
                        ->searchable()
                        ->preload(),
                ]),
        ];
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Admin\Widgets\CompanyAnnouncementsWidget::class,
            \App\Filament\Admin\Widgets\EmployeeClockWidget::class,
            \App\Filament\Admin\Widgets\HrOverviewWidget::class,
            \App\Filament\Admin\Widgets\ContractExpiryWidget::class,
            \App\Filament\Admin\Widgets\DepartmentTalentsChartWidget::class,
            \App\Filament\Admin\Widgets\SourcePerformanceChartWidget::class,
        ];
    }
}

