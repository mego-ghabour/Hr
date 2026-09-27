<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Section::make('المعلومات الأساسية')
                        ->description('البيانات الشخصية للمستخدم ومعلومات التواصل')
                        ->icon('heroicon-m-user-circle')
                        ->schema([
                            TextEntry::make('name')
                                ->label('الاسم الكامل')
                                ->weight('bold')
                                ->color('primary'),
                            TextEntry::make('email')
                                ->label('البريد الإلكتروني')
                                ->icon('heroicon-m-envelope')
                                ->copyable()
                                ->copyMessage('تم النسخ'),
                        ])->columnSpan(2),
                        
                    Section::make('الصلاحيات وحالة الحساب')
                        ->icon('heroicon-m-shield-check')
                        ->schema([
                            TextEntry::make('roles.name')
                                ->label('الأدوار الممنوحة')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'Super Admin' => 'danger',
                                    'HR Manager' => 'success',
                                    'Interviewer' => 'warning',
                                    default => 'primary',
                                })
                                ->placeholder('لا يوجد أدوار مسندة'),
                            TextEntry::make('created_at')
                                ->label('تاريخ الإنضمام')
                                ->date('M d, Y')
                                ->icon('heroicon-m-calendar'),
                            TextEntry::make('updated_at')
                                ->label('آخر تحديث')
                                ->since()
                                ->icon('heroicon-m-clock'),
                        ])->columnSpan(1),
                ])
            ]);
    }
}
