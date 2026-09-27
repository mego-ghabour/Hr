<?php

namespace App\Filament\Clusters\Settings;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class SettingsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?string $navigationLabel = 'الإعدادات';
    protected static ?int $navigationSort = 100;
    protected static ?string $clusterBreadcrumb = 'الإعدادات';
}
