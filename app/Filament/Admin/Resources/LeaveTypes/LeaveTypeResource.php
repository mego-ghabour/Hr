<?php

namespace App\Filament\Admin\Resources\LeaveTypes;

use App\Filament\Admin\Resources\LeaveTypes\Pages\CreateLeaveType;
use App\Filament\Admin\Resources\LeaveTypes\Pages\EditLeaveType;
use App\Filament\Admin\Resources\LeaveTypes\Pages\ListLeaveTypes;
use App\Filament\Admin\Resources\LeaveTypes\Schemas\LeaveTypeForm;
use App\Filament\Admin\Resources\LeaveTypes\Tables\LeaveTypesTable;
use App\Models\LeaveType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LeaveTypeResource extends Resource
{
    protected static ?string $model = LeaveType::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    public static function getNavigationGroup(): ?string
    {
        return 'شؤون العاملين';
    }

    public static function getModelLabel(): string
    {
        return 'نوع إجازة';
    }

    public static function getPluralModelLabel(): string
    {
        return 'أنواع الإجازات';
    }

    public static function form(Schema $schema): Schema
    {
        return LeaveTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveTypesTable::configure($table);
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
            'index' => ListLeaveTypes::route('/'),
            'create' => CreateLeaveType::route('/create'),
            'edit' => EditLeaveType::route('/{record}/edit'),
        ];
    }
}
