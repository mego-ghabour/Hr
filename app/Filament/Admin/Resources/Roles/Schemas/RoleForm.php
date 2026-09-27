<?php

namespace App\Filament\Admin\Resources\Roles\Schemas;

use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('معلومات الدور')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('اسم الدور (Role Name)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        \Filament\Forms\Components\CheckboxList::make('permissions')
                            ->label('الصلاحيات الممنوحة')
                            ->relationship('permissions', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => match ($record->name) {
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
                                default => $record->name,
                            })
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(2)
                            ->gridDirection('row')
                    ])
            ]);
    }
}
