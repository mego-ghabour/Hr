<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DuplicateResource\Pages;
use App\Models\Duplicate;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use \Filament\Actions\ViewAction;
use \Filament\Actions\DeleteBulkAction;
use \Filament\Actions\BulkActionGroup;

class DuplicateResource extends Resource
{
    protected static ?string $model = Duplicate::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-duplicate';

    protected static string|\UnitEnum|null $navigationGroup = 'إدارة المرشحين';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return 'ملف مكرر';
    }

    public static function getPluralModelLabel(): string
    {
        return 'الملفات المكررة';
    }



    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('originalTalent.full_name')
                    ->label('المرشح الأصلي')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('duplicateTalent.full_name')
                    ->label('الملف المكرر')
                    ->searchable(),
                TextColumn::make('confidence')
                    ->label('نسبة التشابه')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->badge()
                    ->color(fn ($state): string => $state >= 90 ? 'danger' : ($state >= 70 ? 'warning' : 'info')),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('تاريخ الاكتشاف')
                    ->date('d/m/Y'),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('مفيش ملفات مكررة')
            ->emptyStateDescription('النظام بيكتشف الملفات المكررة تلقائياً')
            ->emptyStateIcon('heroicon-o-document-duplicate');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDuplicates::route('/'),
            'view' => Pages\ViewDuplicate::route('/{record}'),
        ];
    }
}

