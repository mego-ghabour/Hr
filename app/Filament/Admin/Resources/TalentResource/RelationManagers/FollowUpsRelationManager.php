<?php

namespace App\Filament\Admin\Resources\TalentResource\RelationManagers;

use App\Enums\FollowUpPriority;
use App\Enums\FollowUpStatus;
use App\Enums\FollowUpType;
use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FollowUpsRelationManager extends RelationManager
{
    protected static string $relationship = 'followUps';
    protected static ?string $title = 'المتابعات';

    public static function getModelLabel(): string { return 'متابعة'; }
    public static function getPluralModelLabel(): string { return 'المتابعات'; }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            DateTimePicker::make('follow_up_date')
                ->label('تاريخ المتابعة')
                ->required(),
            Select::make('type')
                ->label('النوع')
                ->options([
                    'call' => 'مكالمة',
                    'email' => 'بريد إلكتروني',
                    'meeting' => 'اجتماع',
                    'interview' => 'مقابلة',
                    'other' => 'أخرى',
                ])
                ->required(),
            Select::make('priority')
                ->label('الأولوية')
                ->options([
                    'low' => 'منخفضة',
                    'medium' => 'متوسطة',
                    'high' => 'عالية',
                    'urgent' => 'عاجلة',
                ])
                ->required(),
            Select::make('status')
                ->label('الحالة')
                ->options([
                    'pending' => 'معلقة',
                    'completed' => 'مكتملة',
                    'cancelled' => 'ملغية',
                    'overdue' => 'متأخرة',
                ])
                ->default('pending')
                ->required(),
            Select::make('assigned_to')
                ->label('المسؤول')
                ->options(User::pluck('name', 'id'))
                ->required(),
            Textarea::make('notes')
                ->label('الملاحظات')
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('follow_up_date')->label('تاريخ المتابعة')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('type')->label('النوع')->badge(),
                Tables\Columns\TextColumn::make('priority')->label('الأولوية')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'success', 'medium' => 'info', 'high' => 'warning', 'urgent' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('status')->label('الحالة')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning', 'completed' => 'success', 'cancelled' => 'gray', 'overdue' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('assignedTo.name')->label('المسؤول'),
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
            ])
            ->defaultSort('follow_up_date', 'desc');
    }
}

