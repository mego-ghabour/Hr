<?php

namespace App\Filament\Admin\Resources\Employees\Tables;

use App\Services\PermissionModuleService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        // By default we use row-based list layout.
        return $table
            ->columns(self::getListColumns())
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('department_id')
                    ->label('القسم')
                    ->relationship('department', 'name')
                    ->preload(),
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'active' => 'نشط (على رأس العمل)',
                        'on_leave' => 'في إجازة',
                        'terminated' => 'مستقيل / مفصول',
                    ]),
                \Filament\Tables\Filters\Filter::make('contract_expiring_soon')
                    ->label('عقود تنتهي قريباً (خلال 30 يوماً)')
                    ->query(fn (Builder $query) => $query->whereNotNull('contract_end_date')->whereBetween('contract_end_date', [now(), now()->addDays(30)])),
                \Filament\Tables\Filters\Filter::make('contract_expired')
                    ->label('عقود منتهية الصلاحية')
                    ->query(fn (Builder $query) => $query->whereNotNull('contract_end_date')->where('contract_end_date', '<', now())),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('managePermissions')
                    ->label('صلاحيات الوصول')
                    ->icon('heroicon-o-shield-check')
                    ->color('primary')
                    ->modalHeading('')
                    ->modalWidth('2xl')
                    ->modalSubmitActionLabel('حفظ الصلاحيات')
                    ->modalCancelActionLabel('إلغاء')
                    ->fillForm(function ($record) {
                        $user = $record->user;
                        if (! $user) {
                            return ['active_modules' => []];
                        }
                        return [
                            'active_modules' => PermissionModuleService::getActiveModulesForRecord($user),
                        ];
                    })
                    ->form([
                        ViewField::make('active_modules')
                            ->view('filament.admin.components.permission-matrix-modal')
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data, $record) {
                        $user = $record->user;
                        if (! $user) {
                            $user = \App\Models\User::firstOrCreate(
                                ['email' => $record->work_email ?: ($record->personal_email ?: "emp_{$record->id}@company.local")],
                                [
                                    'name' => $record->full_name,
                                    'password' => bcrypt('password123'),
                                ]
                            );
                            $record->update(['user_id' => $user->id]);
                        }

                        $modules = $data['active_modules'] ?? [];
                        if (is_string($modules)) {
                            $modules = json_decode($modules, true) ?: [];
                        }
                        PermissionModuleService::syncModulesToRecord($user, $modules);
                        Notification::make()
                            ->title('تم حفظ الصلاحيات بنجاح')
                            ->body('تم تحديث صلاحيات الوصول للموظف ' . $record->full_name)
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    protected static function getListColumns(): array
    {
        return [
            ImageColumn::make('avatar_url')
                ->label('الصورة')
                ->circular()
                ->defaultImageUrl(url('https://ui-avatars.com/api/?name=Employee&color=7F9CF5&background=EBF4FF')),
            TextColumn::make('first_name')
                ->label('الاسم الأول')
                ->searchable()
                ->sortable(),
            TextColumn::make('last_name')
                ->label('الاسم الأخير')
                ->searchable()
                ->sortable(),
            TextColumn::make('department.name')
                ->label('القسم')
                ->sortable(),
            TextColumn::make('job_title')
                ->label('المسمى الوظيفي')
                ->searchable(),
            TextColumn::make('salary')
                ->label('الراتب')
                ->formatStateUsing(fn ($state) => $state !== null && $state !== '' ? (number_format((float) $state, 2) . ' ' . \App\Models\Setting::currencySymbol()) : '-')
                ->sortable()
                ->toggleable(),
            TextColumn::make('phone')
                ->label('رقم الهاتف')
                ->searchable(),
            IconColumn::make('is_remote')
                ->label('عن بُعد')
                ->boolean()
                ->toggleable(),
            TextColumn::make('status')
                ->label('الحالة')
                ->badge()
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'active' => 'نشط',
                    'on_leave' => 'في إجازة',
                    'terminated' => 'مستقيل / مفصول',
                    default => $state,
                })
                ->colors([
                    'success' => 'active',
                    'warning' => 'on_leave',
                    'danger' => 'terminated',
                ]),
        ];
    }
}
