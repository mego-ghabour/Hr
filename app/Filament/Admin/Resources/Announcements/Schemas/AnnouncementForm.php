<?php

namespace App\Filament\Admin\Resources\Announcements\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('تفاصيل الإعلان')
                    ->columns(2)
                    ->components([
                        TextInput::make('title')
                            ->label('عنوان الإعلان')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\Select::make('type')
                            ->label('نوع الإعلان')
                            ->required()
                            ->options([
                                'info' => 'معلومة (أزرق)',
                                'success' => 'خبر سعيد (أخضر)',
                                'warning' => 'تنبيه (أصفر)',
                                'danger' => 'هام جداً (أحمر)',
                            ])
                            ->default('info'),
                        Textarea::make('content')
                            ->label('محتوى الإعلان')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('مفعل (يظهر للموظفين)')
                            ->default(true),
                    ])
            ]);
    }
}
