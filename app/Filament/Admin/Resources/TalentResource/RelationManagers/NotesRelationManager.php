<?php

namespace App\Filament\Admin\Resources\TalentResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';
    protected static ?string $title = 'الملاحظات';

    public static function getModelLabel(): string { return 'ملاحظة'; }
    public static function getPluralModelLabel(): string { return 'الملاحظات'; }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Textarea::make('body')
                ->label('النص')
                ->required()
                ->maxLength(65535)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('body')->label('الملاحظة')->limit(60),
                Tables\Columns\TextColumn::make('user.name')->label('بواسطة'),
                Tables\Columns\TextColumn::make('created_at')->label('التاريخ')->dateTime(),
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

