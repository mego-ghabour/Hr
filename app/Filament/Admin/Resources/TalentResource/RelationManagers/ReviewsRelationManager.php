<?php

namespace App\Filament\Admin\Resources\TalentResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';
    protected static ?string $title = 'المراجعات';

    public static function getModelLabel(): string { return 'مراجعة'; }
    public static function getPluralModelLabel(): string { return 'المراجعات'; }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('rating')
                ->label('التقييم')
                ->options([
                    1 => '1 - ضعيف',
                    2 => '2 - مقبول',
                    3 => '3 - جيد',
                    4 => '4 - جيد جداً',
                    5 => '5 - ممتاز',
                ])
                ->required(),
            Textarea::make('notes')
                ->label('الملاحظات')
                ->maxLength(65535)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('reviewer.name')->label('المراجع'),
                Tables\Columns\TextColumn::make('rating')
                    ->label('التقييم')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1', '2' => 'danger',
                        '3' => 'warning',
                        '4', '5' => 'success',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('reviewed_at')->label('تاريخ المراجعة')->dateTime(),
                Tables\Columns\TextColumn::make('notes')->label('الملاحظات')->limit(50),
            ])
            ->filters([])
            ->headerActions([\Filament\Actions\CreateAction::make()])
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
}

