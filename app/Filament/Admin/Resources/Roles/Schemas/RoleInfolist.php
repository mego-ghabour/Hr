<?php

namespace App\Filament\Admin\Resources\Roles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات الدور والصلاحيات')
                    ->description('يستعرض التفاصيل الكاملة لهذا الدور والصلاحيات الموكلة له')
                    ->icon('heroicon-m-shield-check')
                    ->schema([
                        TextEntry::make('name')
                            ->label('اسم الدور')
                            ->weight('bold')
                            ->color('primary')
                            ->size('lg'),
                        TextEntry::make('permissions.name')
                            ->label('الصلاحيات الممنوحة')
                            ->formatStateUsing(fn (string $state): string => RoleForm::getPermissionLabel($state))
                            ->badge()
                            ->color('success'),
                    ])->columns(1)
            ]);
    }
}
