<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DepartmentResource\Pages;
use App\Models\Department;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use \Filament\Actions\EditAction;
use \Filament\Actions\DeleteAction;
use \Filament\Actions\DeleteBulkAction;
use \Filament\Actions\BulkActionGroup;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $cluster = \App\Filament\Clusters\Settings\SettingsCluster::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static string|\UnitEnum|null $navigationGroup = 'الإعدادات العامة';

    public static function getModelLabel(): string
    {
        return 'قسم';
    }

    public static function getPluralModelLabel(): string
    {
        return 'الأقسام';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('اسم القسم')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('مثال: هندسة البرمجيات')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم القسم')
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
            ->emptyStateHeading('مفيش أقسام لسه')
            ->emptyStateDescription('ابدأ بإضافة قسم جديد');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDepartments::route('/'),
        ];
    }
}

