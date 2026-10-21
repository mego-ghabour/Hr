<?php

namespace App\Filament\Clusters\Settings;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class SettingsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'مركز الإعدادات الموحد';
    protected static ?int $navigationSort = 1;
    protected static ?string $clusterBreadcrumb = 'الإعدادات';
    protected static bool $shouldRegisterSubNavigation = false;

    public static function getNavigationGroup(): ?string
    {
        return 'إدارة النظام والصلاحيات';
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole(['Super Admin', 'admin', 'المدير العام', 'مسؤول النظام']);
    }

    public function mount(): void
    {
        $this->redirect(\App\Filament\Admin\Pages\Settings::getUrl());
    }
}
