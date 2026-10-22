<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class CompanyAnnouncementsWidget extends Widget
{
    protected static bool $isLazy = false;
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    protected string $view = 'filament.admin.widgets.company-announcements-widget';

    protected function getViewData(): array
    {
        $user = Auth::user();
        $canCreate = $user && ($user->hasRole(['Super Admin', 'admin']) || $user->can('create_announcement'));

        try {
            $announcements = \Illuminate\Support\Facades\Schema::hasTable('announcements')
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
