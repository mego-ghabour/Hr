<?php

namespace App\Filament\Admin\Resources\AvailabilityOptions;

use App\Filament\Admin\Resources\AvailabilityOptions\Pages;
use App\Models\AvailabilityOption;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class AvailabilityOptionResource extends Resource
{
    protected static ?string $model = AvailabilityOption::class;
    protected static ?string $cluster = \App\Filament\Clusters\Settings\SettingsCluster::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static string|\UnitEnum|null $navigationGroup = 'إعدادات النظام';

    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return 'خيارات الإتاحة';
    }

    public static function getModelLabel(): string
    {
        return 'خيار إتاحة';
    }

    public static function getPluralModelLabel(): string
    {
        return 'خيارات الإتاحة';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('الاسم بالعربي')
                ->required()
                ->maxLength(100)
                ->placeholder('مثال: بعد ثلاثة أشهر'),

            TextInput::make('name_en')
                ->label('الاسم بالإنجليزي (للرجوع إليه)')
                ->maxLength(100)
                ->placeholder('three_months'),

            TextInput::make('sort_order')
                ->label('الترتيب في القائمة')
                ->numeric()
                ->default(0),

            Toggle::make('is_active')
                ->label('نشط (يظهر في القائمة)')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->width('50px'),

                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('name_en')
                    ->label('الاسم الإنجليزي')
                    ->color('gray'),

                TextColumn::make('talents_count')
                    ->label('عدد المرشحين')
                    ->counts('talents')
                    ->badge()
                    ->color('primary'),

                ToggleColumn::make('is_active')
                    ->label('نشط'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAvailabilityOptions::route('/'),
            'create' => Pages\CreateAvailabilityOption::route('/create'),
            'edit'   => Pages\EditAvailabilityOption::route('/{record}/edit'),
        ];
    }
}
