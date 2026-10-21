<?php

namespace App\Filament\Admin\Resources\Payrolls;

use App\Filament\Admin\Resources\Payrolls\Pages\CreatePayroll;
use App\Filament\Admin\Resources\Payrolls\Pages\EditPayroll;
use App\Filament\Admin\Resources\Payrolls\Pages\ListPayrolls;
use App\Filament\Admin\Resources\Payrolls\Schemas\PayrollForm;
use App\Filament\Admin\Resources\Payrolls\Tables\PayrollsTable;
use App\Models\Payroll;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    public static function getNavigationGroup(): ?string
    {
        return 'شؤون العاملين والموظفين';
    }

    public static function getNavigationSort(): ?int
    {
        return 5;
    }

    public static function getModelLabel(): string
    {
        return 'مسير راتب';
    }

    public static function getPluralModelLabel(): string
    {
        return 'مسيرات الرواتب';
    }

    public static function form(Schema $schema): Schema
    {
        return PayrollForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayrollsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with('employee.department');
        $user = auth()->user();
        if (!$user) {
            return $query;
        }

        if ($user->hasRole(['Super Admin', 'admin', 'manager', 'المدير العام', 'مسؤول النظام', 'مدير الموارد البشرية', 'مدير قسم'])) {
            return $query;
        }

        if ($user->employee) {
            return $query->where('employee_id', $user->employee->id);
        }

        return $query->whereNull('id');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayrolls::route('/'),
            'create' => CreatePayroll::route('/create'),
            'edit' => EditPayroll::route('/{record}/edit'),
        ];
    }
}
