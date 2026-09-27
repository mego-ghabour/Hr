<?php

namespace App\Filament\Admin\Resources\LeaveTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class LeaveTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم نوع الإجازة')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('default_days_per_year')
                    ->label('الرصيد الافتراضي (سنوياً)')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->suffix(' يوم'),
                
                IconColumn::make('requires_approval')
                    ->label('تتطلب موافقة')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
