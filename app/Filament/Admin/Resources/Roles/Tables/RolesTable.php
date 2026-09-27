<?php

namespace App\Filament\Admin\Resources\Roles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('اسم الدور')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                \Filament\Tables\Columns\TextColumn::make('permissions.name')
                    ->label('الصلاحيات')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'view_talent' => 'عرض مرشح',
                        'view_any_talent' => 'استعراض المرشحين',
                        'create_talent' => 'إضافة مرشح',
                        'update_talent' => 'تعديل مرشح',
                        'delete_talent' => 'حذف مرشح',
                        'restore_talent' => 'استعادة مرشح',
                        'force_delete_talent' => 'حذف مرشح نهائياً',
                        'view_department' => 'عرض قسم',
                        'view_any_department' => 'استعراض الأقسام',
                        'create_department' => 'إضافة قسم',
                        'update_department' => 'تعديل قسم',
                        'delete_department' => 'حذف قسم',
                        'view_location' => 'عرض موقع',
                        'view_any_location' => 'استعراض المواقع',
                        'create_location' => 'إضافة موقع',
                        'update_location' => 'تعديل موقع',
                        'delete_location' => 'حذف موقع',
                        'view_source' => 'عرض مصدر',
                        'view_any_source' => 'استعراض المصادر',
                        'create_source' => 'إضافة مصدر',
                        'update_source' => 'تعديل مصدر',
                        'delete_source' => 'حذف مصدر',
                        'view_status' => 'عرض حالة',
                        'view_any_status' => 'استعراض الحالات',
                        'create_status' => 'إضافة حالة',
                        'update_status' => 'تعديل حالة',
                        'delete_status' => 'حذف حالة',
                        'view_duplicate' => 'عرض تكرار',
                        'view_any_duplicate' => 'استعراض التكرارات',
                        'merge_duplicate' => 'دمج مكرر',
                        'reject_duplicate' => 'رفض التكرار',
                        'manage_follow_ups' => 'إدارة المتابعات',
                        'manage_reviews' => 'إدارة التقييمات',
                        'manage_documents' => 'إدارة المستندات',
                        'manage_notes' => 'إدارة الملاحظات',
                        'view_roles' => 'عرض الأدوار',
                        'manage_roles' => 'إدارة الأدوار',
                        'view_users' => 'عرض المستخدمين',
                        'manage_users' => 'إدارة المستخدمين',
                        default => $state,
                    })
                    ->badge()
                    ->limitList(3)
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
