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
        return 'شؤون العاملين';
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

    public static function getPages(): array
    {
        return [
            'index' => ListAttendances::route('/'),
            'create' => CreateAttendance::route('/create'),
            'edit' => EditAttendance::route('/{record}/edit'),
        ];
    }
}
