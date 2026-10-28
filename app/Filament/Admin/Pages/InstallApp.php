<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class InstallApp extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected string $view = 'filament.admin.pages.install-app';

    protected static string|\UnitEnum|null $navigationGroup = 'الرئيسية';

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return 'تثبيت التطبيق';
    }

    public function getTitle(): string|Htmlable
    {
        return 'تثبيت التطبيق وتفعيل البصمة الذكية';
    }

    public function getViewData(): array
    {
        $brandName = Setting::get('brand_name', 'Bwsla HR');
        $brandLogo = Setting::get('logo');
        $primaryColor = Setting::get('brand_primary_color', '#0d9488');

        return [
            'brandName' => $brandName,
            'brandLogo' => $brandLogo,
            'primaryColor' => $primaryColor,
            'user' => auth()->user(),
            'employee' => auth()->user()?->employee,
        ];
    }
}
