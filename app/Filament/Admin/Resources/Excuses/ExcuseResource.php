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

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    public static function getNavigationGroup(): ?string
    {
        return 'شؤون العاملين';
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

    public static function getPages(): array
    {
        return [
            'index' => ListExcuses::route('/'),
            'create' => CreateExcuse::route('/create'),
            'edit' => EditExcuse::route('/{record}/edit'),
        ];
    }
}
