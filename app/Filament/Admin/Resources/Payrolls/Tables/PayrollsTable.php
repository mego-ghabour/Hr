<?php

namespace App\Filament\Admin\Resources\Payrolls\Tables;

use App\Models\Payroll;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label('اسم الموظف')
                    ->state(fn ($record) => $record->employee ? "{$record->employee->first_name} {$record->employee->last_name}" : 'غير معروف')
                    ->description(fn ($record) => $record->employee?->job_title ?? '')
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereHas('employee', function (Builder $q) use ($search) {
                            $q->where('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(['employee.first_name'])
                    ->icon('heroicon-m-user'),

                TextColumn::make('period')
                    ->label('الفترة / الشهر')
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-m-calendar'),

                TextColumn::make('basic_salary')
                    ->label('الأساسي')
                    ->formatStateUsing(fn ($state) => $state !== null ? number_format((float) $state, 2) . ' ' . \App\Models\Setting::currencySymbol() : '-')
                    ->sortable(),

                TextColumn::make('allowances')
                    ->label('البدلات (+)')
                    ->formatStateUsing(fn ($state) => $state !== null ? '+' . number_format((float) $state, 2) . ' ' . \App\Models\Setting::currencySymbol() : '-')
                    ->color('success')
                    ->sortable(),

                TextColumn::make('deductions')
                    ->label('الخصومات (-)')
                    ->formatStateUsing(fn ($state) => $state !== null ? '-' . number_format((float) $state, 2) . ' ' . \App\Models\Setting::currencySymbol() : '-')
                    ->color('danger')
                    ->sortable(),

                TextColumn::make('net_salary')
                    ->label('صافي الراتب')
                    ->formatStateUsing(fn ($state) => $state !== null ? number_format((float) $state, 2) . ' ' . \App\Models\Setting::currencySymbol() : '-')
                    ->weight('bold')
                    ->color('primary')
                    ->sortable(),

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
                    ->date('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('employee_id')
                    ->label('الموظف')
                    ->relationship('employee', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn ($r) => $r ? "{$r->first_name} {$r->last_name}" : '')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('month')
                    ->label('الشهر')
                    ->options([
                        1 => 'يناير',
                        2 => 'فبراير',
                        3 => 'مارس',
                        4 => 'أبريل',
                        5 => 'مايو',
                        6 => 'يونيو',
                        7 => 'يوليو',
                        8 => 'أغسطس',
                        9 => 'سبتمبر',
                        10 => 'أكتوبر',
                        11 => 'نوفمبر',
                        12 => 'ديسمبر',
                    ]),

                SelectFilter::make('year')
                    ->label('السنة')
                    ->options(fn () => array_combine(range(now()->year - 2, now()->year + 1), range(now()->year - 2, now()->year + 1))),

                SelectFilter::make('status')
                    ->label('حالة الصرف')
                    ->options([
                        'draft' => 'مسودة',
                        'approved' => 'معتمد',
                        'paid' => 'تم الصرف',
                    ]),
            ])
            ->recordActions([
                // قسيمة الراتب Payslip Modal
                \Filament\Actions\Action::make('payslip')
                    ->label('قسيمة الراتب')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->modalHeading(fn ($record) => 'قسيمة راتب: ' . ($record->employee?->full_name ?? '') . ' (' . $record->period . ')')
                    ->modalContent(fn ($record) => new HtmlString('
                        <div style="direction: rtl; font-family: Cairo, sans-serif; padding: 1rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; background: #fafafa;">
                            <div style="display: flex; justify-content: space-between; border-bottom: 2px solid #0d9488; padding-bottom: 0.75rem; margin-bottom: 1rem;">
                                <div>
                                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #111827; margin: 0;">' . e($record->employee?->full_name ?? '-') . '</h3>
                                    <p style="color: #6b7280; font-size: 0.875rem; margin: 0.25rem 0 0 0;">' . e($record->employee?->job_title ?? '-') . ' | ' . e($record->employee?->department?->name ?? '-') . '</p>
                                </div>
                                <div style="text-align: left;">
                                    <span style="font-size: 0.875rem; font-weight: 700; color: #0d9488; background: #ccfbf1; padding: 0.25rem 0.75rem; border-radius: 9999px;">' . e($record->period) . '</span>
                                </div>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                                <div style="background: white; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #f3f4f6;">
                                    <div style="font-size: 0.75rem; color: #6b7280;">الرقم القومي / الهوية</div>
                                    <div style="font-weight: 700;">' . e($record->employee?->national_id ?? '-') . '</div>
                                </div>
                                <div style="background: white; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #f3f4f6;">
                                    <div style="font-size: 0.75rem; color: #6b7280;">الحساب البنكي / IBAN</div>
                                    <div style="font-weight: 700; font-family: monospace;" dir="ltr">' . e($record->employee?->iban ?? ($record->employee?->bank_name ? $record->employee->bank_name : '-')) . '</div>
                                </div>
                            </div>

                            <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem; margin-bottom: 1rem;">
                                <tbody>
                                    <tr style="border-bottom: 1px solid #e5e7eb;">
                                        <td style="padding: 0.5rem; color: #4b5563;">الراتب الأساسي</td>
                                        <td style="padding: 0.5rem; text-align: left; font-weight: 700;">' . number_format($record->basic_salary, 2) . ' ' . e(\App\Models\Setting::currencySymbol()) . '</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #e5e7eb; color: #16a34a;">
                                        <td style="padding: 0.5rem;">+ البدلات والحوافز</td>
                                        <td style="padding: 0.5rem; text-align: left; font-weight: 700;">+' . number_format($record->allowances, 2) . ' ' . e(\App\Models\Setting::currencySymbol()) . '</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #e5e7eb; color: #dc2626;">
                                        <td style="padding: 0.5rem;">- الخصومات والغيابات</td>
                                        <td style="padding: 0.5rem; text-align: left; font-weight: 700;">-' . number_format($record->deductions, 2) . ' ' . e(\App\Models\Setting::currencySymbol()) . '</td>
                                    </tr>
                                    <tr style="background: #f0fdf4; font-size: 1.1rem; font-weight: 800; color: #15803d;">
                                        <td style="padding: 0.75rem;">صافي الراتب المستحق للتحويل</td>
                                        <td style="padding: 0.75rem; text-align: left;">' . number_format($record->net_salary, 2) . ' ' . e(\App\Models\Setting::currencySymbol()) . '</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div style="font-size: 0.75rem; color: #9ca3af; text-align: center;">
                                صدرت هذه القسيمة إلكترونياً من نظام الموارد البشرية | الحالة: ' . match($record->status) { 'paid' => 'تم الصرف بنجاح', 'approved' => 'معتمد بانتظار التحويل', default => 'مسودة' } . '
                            </div>
                        </div>
                    '))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('إغلاق'),

                \Filament\Actions\Action::make('approve')
                    ->label('اعتماد')
                    ->icon('heroicon-o-check-circle')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'draft')
                    ->action(function ($record) {
                        $record->update(['status' => 'approved']);
                        Notification::make()->title('تم اعتماد مسير الراتب')->success()->send();
                    }),

                \Filament\Actions\Action::make('mark_as_paid')
                    ->label('صرف')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== 'paid')
                    ->requiresConfirmation()
                    ->modalHeading('تأكيد صرف الراتب')
                    ->modalDescription('سيتم تحويل حالة المسير إلى (مدفوع) وتسجيل تاريخ الصرف باليوم.')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'paid',
                            'payment_date' => now(),
                        ]);
                        Notification::make()->title('تم تأكيد صرف الراتب بنجاح')->success()->send();
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_approve')
                        ->label('اعتماد المسيرات المحددة')
                        ->icon('heroicon-o-check')
                        ->color('info')
                        ->action(function (Collection $records) {
                            $records->each->update(['status' => 'approved']);
                            Notification::make()->title('تم اعتماد المسيرات المحددة بنجاح')->success()->send();
                        }),

                    BulkAction::make('bulk_mark_as_paid')
                        ->label('تأكيد صرف المسيرات المحددة')
                        ->icon('heroicon-o-banknotes')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each->update([
                                'status' => 'paid',
                                'payment_date' => now(),
                            ]);
                            Notification::make()->title('تم تأكيد صرف المسيرات المحددة بنجاح')->success()->send();
                        }),

                    BulkAction::make('export_selected')
                        ->label('تصدير المحددين كـ CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->action(function (Collection $records) {
                            return response()->streamDownload(function () use ($records) {
                                $handle = fopen('php://output', 'w');
                                fputs($handle, "\xEF\xBB\xBF");
                                fputcsv($handle, [
                                    'اسم الموظف',
                                    'القسم',
                                    'المسمى الوظيفي',
                                    'الفترة',
                                    'الأساسي',
                                    'البدلات',
                                    'الخصومات',
                                    'صافي الراتب',
                                    'الحالة',
                                    'طريقة الصرف',
                                    'تاريخ الصرف'
                                ]);
                                foreach ($records as $r) {
                                    fputcsv($handle, [
                                        $r->employee ? "{$r->employee->first_name} {$r->employee->last_name}" : '-',
                                        $r->employee?->department?->name ?? '-',
                                        $r->employee?->job_title ?? '-',
                                        $r->period,
                                        $r->basic_salary,
                                        $r->allowances,
                                        $r->deductions,
                                        $r->net_salary,
                                        match ($r->status) {
                                            'draft' => 'مسودة',
                                            'approved' => 'معتمد',
                                            'paid' => 'تم الصرف',
                                            default => $r->status,
                                        },
                                        match ($r->payment_method) {
                                            'bank_transfer' => 'تحويل بنكي',
                                            'cash' => 'نقداً',
                                            'cheque' => 'شيك',
                                            default => $r->payment_method,
                                        },
                                        $r->payment_date?->format('Y-m-d') ?? '-',
                                    ]);
                                }
                                fclose($handle);
                            }, 'payrolls-selected-' . date('Y-m-d') . '.csv');
                        }),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
