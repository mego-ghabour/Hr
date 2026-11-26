<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Announcement;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class CompanyAnnouncementsWidget extends Widget
{
    protected static bool $isLazy = false;
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    protected string $view = 'filament.admin.widgets.company-announcements-widget';

    public static function canView(): bool
    {
        try {
            return Schema::hasTable('announcements')
                && Announcement::where('is_active', true)->exists();
        } catch (\Throwable) {
            return false;
        }
    }

    protected function getViewData(): array
    {
        $user = Auth::user();
        $canCreate = $user && ($user->hasRole(['Super Admin', 'admin']) || $user->can('create_announcement'));

        try {
            $announcements = Schema::hasTable('announcements')
                ? Announcement::where('is_active', true)->latest()->take(4)->get()
                : collect();
        } catch (\Throwable) {
            $announcements = collect();
        }

        return [
            'announcements' => $announcements,
            'canCreate' => $canCreate,
        ];
    }
}
