<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TalentStatusResource\Pages;
use App\Models\TalentStatus;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use \Filament\Actions\EditAction;
use \Filament\Actions\DeleteAction;
use \Filament\Actions\DeleteBulkAction;
use \Filament\Actions\BulkActionGroup;

class TalentStatusResource extends Resource
{
    protected static ?string $model = TalentStatus::class;

    protected static ?string $cluster = \App\Filament\Clusters\Settings\SettingsCluster::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static string|\UnitEnum|null $navigationGroup = 'إعدادات النظام';

    public static function getModelLabel(): string
    {
        return 'حالة';
    }

    public static function getPluralModelLabel(): string
    {
        return 'حالات المرشحين';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('اسم الحالة')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('مثال: قيد المراجعة'),
                ColorPicker::make('color')
                    ->label('اللون')
                    ->required(),
                Toggle::make('is_default')
                    ->label('الحالة الافتراضية للمرشحين الجدد')
                    ->helperText('حالة واحدة بس تكون افتراضية'),
                TextInput::make('order_column')
                    ->label('الترتيب في القائمة')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->badge()
                    ->color(fn ($record) => $record->color ?? 'primary')
                    ->label('الحالة')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_default')
                    ->boolean()
                    ->label('افتراضية')
                    ->sortable(),
                TextColumn::make('order_column')
                    ->label('الترتيب')
                    ->sortable(),
            ])
            ->defaultSort('order_column', 'asc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('مفيش حالات لسه')
            ->emptyStateDescription('ابدأ بإضافة حالة جديدة');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTalentStatuses::route('/'),
        ];
    }
}

