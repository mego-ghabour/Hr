<?php

namespace App\Filament\Admin\Resources\Attendances;

use App\Filament\Admin\Resources\Attendances\Pages\CreateAttendance;
use App\Filament\Admin\Resources\Attendances\Pages\EditAttendance;
use App\Filament\Admin\Resources\Attendances\Pages\ListAttendances;
use App\Filament\Admin\Resources\Attendances\Schemas\AttendanceForm;
use App\Filament\Admin\Resources\Attendances\Tables\AttendancesTable;
use App\Models\Attendance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    public static function getNavigationGroup(): ?string
    {
        return 'شؤون العاملين والموظفين';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getModelLabel(): string
    {
        return 'حضور وانصراف';
    }

    public static function getPluralModelLabel(): string
    {
        return 'الحضور والانصراف';
    }

    public static function form(Schema $schema): Schema
    {
        return AttendanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendancesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery()->with('employee');
        
        $user = auth()->user();
        if (!$user) {
            return $query;
        }

        // If the user is a super admin, admin, manager, or HR manager
        if ($user->hasRole(['Super Admin', 'admin', 'manager', 'المدير العام', 'مسؤول النظام', 'مدير الموارد البشرية', 'مدير قسم'])) {
            return $query;
        }

        // If the user has an employee record, show only their attendance
        if ($user->employee) {
            return $query->where('employee_id', $user->employee->id);
        }

        // Otherwise, return nothing (for safety)
        return $query->whereNull('id');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendances::route('/'),
            'create' => CreateAttendance::route('/create'),
            'edit' => EditAttendance::route('/{record}/edit'),
        ];
    }
}
