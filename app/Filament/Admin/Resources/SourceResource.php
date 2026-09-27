<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SourceResource\Pages;
use App\Models\Source;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use \Filament\Actions\EditAction;
use \Filament\Actions\DeleteAction;
use \Filament\Actions\DeleteBulkAction;
use \Filament\Actions\BulkActionGroup;

class SourceResource extends Resource
{
    protected static ?string $model = Source::class;

    protected static ?string $cluster = \App\Filament\Clusters\Settings\SettingsCluster::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static string|\UnitEnum|null $navigationGroup = 'إعدادات النظام';

    public static function getModelLabel(): string
    {
        return 'مصدر';
    }

    public static function getPluralModelLabel(): string
    {
        return 'مصادر الترشيح';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('مصدر الترشيح')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('مثال: لينكد إن، ترشيح داخلي')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('المصدر')
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
            ->emptyStateHeading('مفيش مصادر لسه')
            ->emptyStateDescription('ابدأ بإضافة مصدر جديد');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSources::route('/'),
        ];
    }
}

