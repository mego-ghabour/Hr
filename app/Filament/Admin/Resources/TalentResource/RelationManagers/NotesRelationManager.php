<?php

namespace App\Filament\Admin\Resources\TalentResource\RelationManagers;

use App\Enums\TalentNoteCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';
    protected static ?string $title = 'الملاحظات وسجل القرارات';

    public static function getModelLabel(): string { return 'ملاحظة'; }
    public static function getPluralModelLabel(): string { return 'الملاحظات'; }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('category')
                ->label('التصنيف')
                ->options(TalentNoteCategory::class)
                ->default(TalentNoteCategory::GENERAL->value)
                ->required()
                ->native(false)
                ->columnSpanFull(),
            Textarea::make('body')
                ->label('النص')
                ->required()
                ->rows(4)
                ->maxLength(65535)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('category')
                    ->label('التصنيف')
                    ->badge()
                    ->placeholder('ملاحظة عامة'),
                Tables\Columns\TextColumn::make('body')
                    ->label('الملاحظة')
                    ->limit(120)
                    ->wrap(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('بواسطة'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('d/m/Y h:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('category')
                    ->label('التصنيف')
                    ->options(TalentNoteCategory::class),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label('إضافة ملاحظة')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = Auth::id();
                        return $data;
                    }),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
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


