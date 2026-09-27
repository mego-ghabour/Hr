<?php

namespace App\Filament\Admin\Pages;

use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
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
            ]);
    }

    public function getColumns(): int | array
    {
        return [
            'sm'  => 1,
            'md'  => 2,
            'lg'  => 3,
            'xl'  => 3,
            '2xl' => 3,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('new_candidate')
                ->label('إضافة مرشح جديد')
                ->icon('heroicon-o-user-plus')
                ->color('primary')
                ->url(route('filament.admin.resources.talent.create')),
        ];
    }
}

