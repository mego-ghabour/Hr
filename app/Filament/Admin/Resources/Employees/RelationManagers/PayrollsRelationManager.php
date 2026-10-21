<?php

namespace App\Filament\Admin\Resources\Employees\RelationManagers;

use App\Filament\Admin\Resources\Payrolls\Schemas\PayrollForm;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PayrollsRelationManager extends RelationManager
{
    protected static string $relationship = 'payrolls';

    protected static ?string $title = 'مسيرات الرواتب';

    public function form(Schema $schema): Schema
    {
        return PayrollForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('period')
                    ->label('الفترة / الشهر')
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-m-calendar'),

                TextColumn::make('basic_salary')
                    ->label('الأساسي')
                    ->formatStateUsing(fn ($state) => $state !== null ? number_format((float) $state, 2) . ' ' . \App\Models\Setting::currencySymbol() : '-'),

                TextColumn::make('allowances')
                    ->label('البدلات (+)')
                    ->formatStateUsing(fn ($state) => $state !== null ? '+' . number_format((float) $state, 2) . ' ' . \App\Models\Setting::currencySymbol() : '-')
                    ->color('success'),

                TextColumn::make('deductions')
                    ->label('الخصومات (-)')
                    ->formatStateUsing(fn ($state) => $state !== null ? '-' . number_format((float) $state, 2) . ' ' . \App\Models\Setting::currencySymbol() : '-')
                    ->color('danger'),

                TextColumn::make('net_salary')
                    ->label('صافي الراتب')
                    ->formatStateUsing(fn ($state) => $state !== null ? number_format((float) $state, 2) . ' ' . \App\Models\Setting::currencySymbol() : '-')
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'مسودة',
                        'approved' => 'معتمد',
                        'paid' => 'تم الصرف',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'draft',
                        'info' => 'approved',
                        'success' => 'paid',
                    ]),

                TextColumn::make('payment_date')
                    ->label('تاريخ الصرف')
                    ->date('Y-m-d'),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                CreateAction::make()->label('إضافة مسير راتب'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
