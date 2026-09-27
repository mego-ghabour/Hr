<?php
namespace App\Providers\Filament;

use App\Models\Setting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // اقرأ الإعدادات من قاعدة البيانات (مع fallback لو الجدول فاضي)
        try {
            $brandName = Setting::get('brand_name', 'نظام الموارد البشرية (HR)');
            $logoPath  = Setting::get('logo');
        } catch (\Exception) {
            $brandName = 'نظام الموارد البشرية (HR)';
            $logoPath  = null;
        }

        $panel = $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->darkMode()
            ->breadcrumbs(true)
            ->brandName($brandName)
            ->font('Cairo', 'https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap')
            ->favicon(null)
            ->topNavigation()
            ->spa()
            ->databaseNotifications()
            ->databaseNotificationsPolling('3s')
            ->globalSearch()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->colors([
                'primary' => Color::hex('#1e4d6e'),
                'secondary' => Color::Sky,
                'danger' => Color::Rose,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'info' => Color::Blue,
            ])
            ->navigationGroups([
                'إدارة المرشحين',
                'الإعدادات',
                'التعليمات والمساعدة',
            ])
            ->navigationItems([
                NavigationItem::make('استمارة التقديم الخارجي')
                    ->url('/apply', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->group('إدارة المرشحين')
                    ->visible(fn (): bool => auth()->user()->hasPermissionTo('view_any_talent'))
                    ->sort(10),
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([\App\Filament\Admin\Pages\Dashboard::class])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class]);

        if ($logoPath) {
            // Handle cases where FileUpload saves as array or JSON string
            if (is_array($logoPath)) {
                $logoPath = array_values($logoPath)[0] ?? null;
            } elseif (is_string($logoPath) && str_starts_with($logoPath, '[')) {
                $decoded = json_decode($logoPath, true);
                if (is_array($decoded) && count($decoded) > 0) {
                    $logoPath = array_values($decoded)[0];
                }
            }

            if ($logoPath) {
                // Use relative path to avoid APP_URL port issues (like missing :8000)
                $logoUrl = (str_starts_with($logoPath, 'http') || str_starts_with($logoPath, '/'))
                    ? $logoPath
                    : '/storage/' . $logoPath;
                    
                $panel->brandLogo($logoUrl);
                $panel->brandLogoHeight('3rem'); // Ensure logo looks good
            }
        }

        return $panel;
    }
}
