<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LocationResource\Pages;
use App\Models\Location;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use \Filament\Actions\EditAction;
use \Filament\Actions\DeleteAction;
use \Filament\Actions\DeleteBulkAction;
use \Filament\Actions\BulkActionGroup;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $cluster = \App\Filament\Clusters\Settings\SettingsCluster::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static string|\UnitEnum|null $navigationGroup = 'الإعدادات العامة';

    public static function getModelLabel(): string
    {
        return 'موقع';
    }

    public static function getPluralModelLabel(): string
    {
        return 'المواقع';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('اسم الموقع')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('مثال: الرياض، جدة، ريموت')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم الموقع')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('talents_count')
                    ->counts('talents')
                    ->label('عدد المرشحين')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->date('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('مفيش مواقع لسه')
            ->emptyStateDescription('ابدأ بإضافة موقع جديد');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLocations::route('/'),
        ];
    }
}

