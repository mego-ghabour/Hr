<?php

namespace App\Filament\Admin\Resources\Employees\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeaveBalancesRelationManager extends RelationManager
{
    protected static string $relationship = 'leaveBalances';

    protected static ?string $title = 'أرصدة الإجازات';
    
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('leave_type_id')
                    ->label('نوع الإجازة')
                    ->relationship('leaveType', 'name')
                    ->required(),
                \Filament\Forms\Components\TextInput::make('year')
                    ->label('السنة')
                    ->numeric()
                    ->default(now()->year)
                    ->required(),
                \Filament\Forms\Components\TextInput::make('total_days')
                    ->label('إجمالي الرصيد (أيام)')
                    ->numeric()
                    ->required(),
                \Filament\Forms\Components\TextInput::make('used_days')
                    ->label('الرصيد المستخدم (أيام)')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('leaveType.name')
            ->columns([
                TextColumn::make('leaveType.name')->label('نوع الإجازة')->sortable(),
                TextColumn::make('year')->label('السنة')->sortable(),
                TextColumn::make('total_days')->label('الإجمالي'),
                TextColumn::make('used_days')->label('المستخدم'),
                TextColumn::make('remaining')
                    ->label('المتبقي')
                    ->getStateUsing(fn ($record) => $record->total_days - $record->used_days)
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make(),
            ])
            ->recordActions([
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
