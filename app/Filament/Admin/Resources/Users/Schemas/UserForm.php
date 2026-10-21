<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('معلومات المستخدم')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('الاسم')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('password')
                            ->label('كلمة المرور')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255),
                        \Filament\Forms\Components\Select::make('roles')
                            ->label('الأدوار والصلاحيات (Roles)')
                            ->relationship('roles', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => \App\Filament\Admin\Resources\Roles\Schemas\RoleForm::getRoleLabel($record->name))
                            ->multiple()
                            ->preload()
                            ->searchable(),
                        \Filament\Forms\Components\Select::make('employee_id')
                            ->label('الموظف المرتبط بهذا الحساب')
                            ->options(fn () => \App\Models\Employee::all()->pluck('full_name', 'id'))
                            ->searchable()
                            ->preload()
                            ->placeholder('اختر موظف لربطه بهذا الحساب (اختياري)')
                            ->helperText('ربط الحساب بملف موظف يتيح له تسجيل الدخول على بوابة الخدمة الذاتية (ESS).')
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }
}
