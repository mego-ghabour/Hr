<?php

namespace App\Filament\Admin\Resources\Excuses;

use App\Filament\Admin\Resources\Excuses\Pages\CreateExcuse;
use App\Filament\Admin\Resources\Excuses\Pages\EditExcuse;
use App\Filament\Admin\Resources\Excuses\Pages\ListExcuses;
use App\Filament\Admin\Resources\Excuses\Schemas\ExcuseForm;
use App\Filament\Admin\Resources\Excuses\Tables\ExcusesTable;
use App\Models\Excuse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExcuseResource extends Resource
{
    protected static ?string $model = Excuse::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    public static function getNavigationGroup(): ?string
    {
        return 'شؤون العاملين والموظفين';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
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
        return 'إذن استئذان';
    }

    public static function getPluralModelLabel(): string
    {
        return 'أذونات الاستئذان';
    }

    public static function form(Schema $schema): Schema
    {
        return ExcuseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExcusesTable::configure($table);
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
            'index' => ListExcuses::route('/'),
            'create' => CreateExcuse::route('/create'),
            'edit' => EditExcuse::route('/{record}/edit'),
        ];
    }
}
