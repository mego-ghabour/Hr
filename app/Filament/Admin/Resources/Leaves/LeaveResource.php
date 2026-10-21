<?php

namespace App\Filament\Admin\Resources\Leaves;

use App\Filament\Admin\Resources\Leaves\Pages\CreateLeave;
use App\Filament\Admin\Resources\Leaves\Pages\EditLeave;
use App\Filament\Admin\Resources\Leaves\Pages\ListLeaves;
use App\Filament\Admin\Resources\Leaves\Schemas\LeaveForm;
use App\Filament\Admin\Resources\Leaves\Tables\LeavesTable;
use App\Models\Leave;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    public static function getNavigationGroup(): ?string
    {
        return 'شؤون العاملين والموظفين';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getModelLabel(): string
    {
        return 'إجازة';
    }

    public static function getPluralModelLabel(): string
    {
        return 'الإجازات';
    }

    public static function form(Schema $schema): Schema
    {
        return LeaveForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeavesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery()->with(['employee', 'leaveType']);
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
            'index' => ListLeaves::route('/'),
            'create' => CreateLeave::route('/create'),
            'edit' => EditLeave::route('/{record}/edit'),
        ];
    }
}
