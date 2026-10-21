<?php
namespace App\Providers\Filament;

use App\Http\Middleware\RedirectEmployeeToPortal;
use App\Models\Setting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
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
        // اقرأ الإعدادات من قاعدة البيانات (مع fallback آمن لو الجدول فارغ)
        try {
            $brandName = Setting::get('brand_name', 'نظام الموارد البشرية (HR)');
            $logoPath  = Setting::get('logo');
            $fontFamily = Setting::get('font_family', 'Cairo');
            $primaryColorHex = Setting::get('brand_primary_color', '#0d9488');
            $sidebarTheme = Setting::get('sidebar_theme', 'dark_navy');
        } catch (\Throwable) {
            $brandName = 'نظام الموارد البشرية (HR)';
            $logoPath  = null;
            $fontFamily = 'Cairo';
            $primaryColorHex = '#0d9488';
            $sidebarTheme = 'dark_navy';
        }

        if (!is_string($primaryColorHex) || !preg_match('/^#[a-fA-F0-9]{6}$/', $primaryColorHex)) {
            $primaryColorHex = '#0d9488';
        }

        if (!is_string($fontFamily) || empty($fontFamily)) {
            $fontFamily = 'Cairo';
        }

        if (!is_string($sidebarTheme) || empty($sidebarTheme)) {
            $sidebarTheme = 'dark_navy';
        }

        [$r, $g, $b] = sscanf(ltrim($primaryColorHex, '#'), "%02x%02x%02x");
        $primaryRgb = "{$r}, {$g}, {$b}";

        $fontUrls = [
            'Cairo' => 'https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap',
            'Tajawal' => 'https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap',
            'IBM Plex Sans Arabic' => 'https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap',
            'Almarai' => 'https://fonts.googleapis.com/css2?family=Almarai:wght@400;700;800&display=swap',
            'Readex Pro' => 'https://fonts.googleapis.com/css2?family=Readex+Pro:wght@400;500;600;700&display=swap',
            'Alexandria' => 'https://fonts.googleapis.com/css2?family=Alexandria:wght@400;500;600;700;800&display=swap',
            'Noto Sans Arabic' => 'https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;500;600;700;800&display=swap',
        ];
        $fontUrl = $fontUrls[$fontFamily] ?? "https://fonts.googleapis.com/css2?family=" . urlencode($fontFamily) . ":wght@400;500;600;700;800&display=swap";

        $isDarkSidebar = ($sidebarTheme !== 'light_clean');

        $sidebarBg = match ($sidebarTheme) {
            'deep_black' => '#09090b',
            'brand_tinted' => "linear-gradient(180deg, rgba({$primaryRgb}, 0.28) 0%, #0a0e1a 65%, #070b14 100%)",
            'slate_gray' => '#1e293b',
            'light_clean' => '#ffffff',
            default => 'linear-gradient(180deg, #090e1a 0%, #0f172a 45%, #131c33 100%)', // dark_navy
        };

        $sidebarBorder = match ($sidebarTheme) {
            'deep_black' => '1px solid #18181b',
            'brand_tinted' => "1px solid rgba({$primaryRgb}, 0.25)",
            'slate_gray' => '1px solid #334155',
            'light_clean' => '1px solid #f1f5f9',
            default => '1px solid rgba(255, 255, 255, 0.08)',
        };

        $sidebarHeaderBg = match ($sidebarTheme) {
            'deep_black' => '#09090b',
            'brand_tinted' => "rgba({$primaryRgb}, 0.15)",
            'slate_gray' => '#1e293b',
            'light_clean' => '#ffffff',
            default => 'rgba(9, 14, 26, 0.95)',
        };

        $sidebarItemText = $isDarkSidebar ? '#94a3b8' : '#475569';
        $sidebarItemHoverBg = $isDarkSidebar ? 'rgba(255, 255, 255, 0.07)' : '#f8fafc';
        $sidebarItemHoverText = $isDarkSidebar ? '#ffffff' : '#0f172a';
        $sidebarGroupText = $isDarkSidebar ? '#64748b' : '#64748b';
        $sidebarBrandText = $isDarkSidebar ? '#ffffff' : '#0f172a';

        try {
            $primaryColorObj = Color::hex($primaryColorHex);
        } catch (\Throwable) {
            $primaryColorObj = Color::hex('#0d9488');
        }

        $panel = $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->darkMode()
            ->breadcrumbs(true)
            ->brandName($brandName)
            ->font($fontFamily, $fontUrl)
            ->favicon(null)
            ->sidebarFullyCollapsibleOnDesktop()
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): string => \Illuminate\Support\Facades\Blade::render('
                    <style>
                        :root {
                            --primary-color: ' . $primaryColorHex . ';
                            --primary-rgb: ' . $primaryRgb . ';
                            --font-family: "' . $fontFamily . '", sans-serif;
                        }

                        html, body, button, input, select, textarea, .fi-body, .fi-sidebar, .fi-topbar {
                            font-family: "' . $fontFamily . '", sans-serif !important;
                        }

                        /* ==========================================================================
                           ENTERPRISE MODERN SIDEBAR STYLING
                           ========================================================================== */

                        /* 1. Main Sidebar Outer Container */
                        #fi-main-sidebar,
                        .fi-sidebar,
                        .fi-main-sidebar {
                            background: ' . $sidebarBg . ' !important;
                            border-inline-end: ' . $sidebarBorder . ' !important;
                            box-shadow: ' . ($isDarkSidebar ? '-4px 0 30px rgba(0, 0, 0, 0.45)' : '4px 0 20px rgba(0, 0, 0, 0.04)') . ' !important;
                            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
                        }

                        /* 2. Sidebar Header Area (Mobile only - hidden on Desktop to prevent duplicate logo) */
                        @media (min-width: 1024px) {
                            #fi-main-sidebar .fi-sidebar-header-ctn,
                            #fi-main-sidebar .fi-sidebar-header {
                                display: none !important;
                                height: 0 !important;
                                min-height: 0 !important;
                                padding: 0 !important;
                                border: none !important;
                                overflow: hidden !important;
                            }
                        }

                        @media (max-width: 1023.98px) {
                            #fi-main-sidebar .fi-sidebar-header-ctn,
                            #fi-main-sidebar .fi-sidebar-header {
                                background: ' . $sidebarHeaderBg . ' !important;
                                border-bottom: ' . $sidebarBorder . ' !important;
                                padding: 0.85rem 1.15rem !important;
                                min-height: 4.5rem !important;
                                display: flex !important;
                                flex-direction: row-reverse !important;
                                justify-content: space-between !important;
                                align-items: center !important;
                            }
                        }

                        #fi-main-sidebar .fi-sidebar-header .fi-logo {
                            max-height: 2.75rem !important;
                            object-fit: contain !important;
                            filter: ' . ($isDarkSidebar ? 'drop-shadow(0 2px 8px rgba(0, 0, 0, 0.35))' : 'none') . ' !important;
                        }

                        #fi-main-sidebar .fi-sidebar-header-brand-name,
                        #fi-main-sidebar .fi-sidebar-header a,
                        #fi-main-sidebar .fi-sidebar-header span {
                            color: ' . $sidebarBrandText . ' !important;
                            font-weight: 800 !important;
                        }

                        #fi-main-sidebar .fi-sidebar-header button {
                            color: ' . ($isDarkSidebar ? '#94a3b8' : '#64748b') . ' !important;
                        }

                        /* 3. Navigation Container & Custom Ultra-Sleek Scrollbar */
                        #fi-main-sidebar .fi-sidebar-nav {
                            padding: 1rem 0.75rem 2.5rem 0.75rem !important;
                            scrollbar-width: thin !important;
                            scrollbar-color: ' . ($isDarkSidebar ? 'rgba(255, 255, 255, 0.15) transparent' : 'rgba(0, 0, 0, 0.15) transparent') . ' !important;
                        }

                        #fi-main-sidebar .fi-sidebar-nav::-webkit-scrollbar {
                            width: 5px !important;
                        }

                        #fi-main-sidebar .fi-sidebar-nav::-webkit-scrollbar-track {
                            background: transparent !important;
                        }

                        #fi-main-sidebar .fi-sidebar-nav::-webkit-scrollbar-thumb {
                            background: ' . ($isDarkSidebar ? 'rgba(255, 255, 255, 0.14)' : 'rgba(0, 0, 0, 0.12)') . ' !important;
                            border-radius: 9999px !important;
                        }

                        #fi-main-sidebar .fi-sidebar-nav::-webkit-scrollbar-thumb:hover {
                            background: ' . ($isDarkSidebar ? 'rgba(255, 255, 255, 0.3)' : 'rgba(0, 0, 0, 0.25)') . ' !important;
                        }

                        #fi-main-sidebar .fi-sidebar-nav-groups {
                            display: flex !important;
                            flex-direction: column !important;
                            gap: 1.25rem !important;
                            margin: 0 !important;
                        }

                        /* 4. Sidebar Group Headings */
                        #fi-main-sidebar .fi-sidebar-group {
                            display: flex !important;
                            flex-direction: column !important;
                            gap: 0.35rem !important;
                        }

                        #fi-main-sidebar .fi-sidebar-group-btn {
                            padding: 0.45rem 0.75rem 0.35rem 0.75rem !important;
                            border-radius: 0.5rem !important;
                            transition: all 0.18s ease !important;
                            margin-bottom: 0.15rem !important;
                            border-bottom: ' . ($isDarkSidebar ? '1px solid rgba(255, 255, 255, 0.04)' : '1px solid rgba(0, 0, 0, 0.04)') . ' !important;
                        }

                        #fi-main-sidebar .fi-sidebar-group-label {
                            font-size: 0.72rem !important;
                            font-weight: 800 !important;
                            letter-spacing: 0.06em !important;
                            text-transform: uppercase !important;
                            color: ' . ($isDarkSidebar ? '#94a3b8' : '#64748b') . ' !important;
                            opacity: 0.9 !important;
                        }

                        #fi-main-sidebar .fi-sidebar-group-btn .fi-icon {
                            color: ' . ($isDarkSidebar ? '#64748b' : '#94a3b8') . ' !important;
                            width: 0.95rem !important;
                            height: 0.95rem !important;
                            transition: transform 0.2s ease !important;
                        }

                        #fi-main-sidebar .fi-sidebar-group-items {
                            display: flex !important;
                            flex-direction: column !important;
                            gap: 0.25rem !important;
                        }

                        /* 5. Sidebar Menu Items */
                        #fi-main-sidebar .fi-sidebar-item {
                            margin: 0 !important;
                        }

                        #fi-main-sidebar .fi-sidebar-item-btn {
                            display: flex !important;
                            align-items: center !important;
                            gap: 0.75rem !important;
                            padding: 0.65rem 0.9rem !important;
                            border-radius: 0.75rem !important;
                            font-size: 0.875rem !important;
                            font-weight: 600 !important;
                            color: ' . ($isDarkSidebar ? '#cbd5e1' : '#334155') . ' !important;
                            background: transparent !important;
                            border: 1px solid transparent !important;
                            transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1) !important;
                            position: relative !important;
                        }

                        #fi-main-sidebar .fi-sidebar-item-label {
                            color: inherit !important;
                            font-weight: inherit !important;
                            font-size: inherit !important;
                            white-space: nowrap !important;
                            overflow: hidden !important;
                            text-overflow: ellipsis !important;
                            flex: 1 !important;
                        }

                        #fi-main-sidebar .fi-sidebar-item-icon {
                            width: 1.25rem !important;
                            height: 1.25rem !important;
                            color: ' . ($isDarkSidebar ? '#64748b' : '#94a3b8') . ' !important;
                            transition: all 0.18s ease !important;
                            flex-shrink: 0 !important;
                        }

                        /* Regular item hover */
                        #fi-main-sidebar .fi-sidebar-item:not(.fi-active) .fi-sidebar-item-btn:hover {
                            background: ' . ($isDarkSidebar ? 'rgba(255, 255, 255, 0.07)' : 'rgba(0, 0, 0, 0.04)') . ' !important;
                            color: ' . ($isDarkSidebar ? '#ffffff' : '#0f172a') . ' !important;
                            border-color: ' . ($isDarkSidebar ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.05)') . ' !important;
                            transform: translateX(-2px) !important;
                        }

                        #fi-main-sidebar .fi-sidebar-item:not(.fi-active) .fi-sidebar-item-btn:hover .fi-sidebar-item-icon {
                            color: ' . ($isDarkSidebar ? '#f8fafc' : '#0f172a') . ' !important;
                            transform: scale(1.08) !important;
                        }

                        /* 6. Active Item (Radiant Glowing Modern Look) */
                        #fi-main-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
                            background: linear-gradient(135deg, rgba(' . $primaryRgb . ', 0.22) 0%, rgba(' . $primaryRgb . ', 0.08) 100%) !important;
                            color: ' . ($isDarkSidebar ? '#ffffff' : $primaryColorHex) . ' !important;
                            font-weight: 800 !important;
                            border: 1px solid rgba(' . $primaryRgb . ', 0.35) !important;
                            box-shadow: 0 4px 16px rgba(0, 0, 0, ' . ($isDarkSidebar ? '0.35' : '0.06') . '), inset 0 1px 0 rgba(255, 255, 255, 0.1) !important;
                        }

                        #fi-main-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn::before {
                            content: "" !important;
                            position: absolute !important;
                            top: 18% !important;
                            bottom: 18% !important;
                            inset-inline-start: -0.45rem !important;
                            width: 4px !important;
                            border-radius: 9999px !important;
                            background: ' . $primaryColorHex . ' !important;
                            box-shadow: 0 0 10px ' . $primaryColorHex . ' !important;
                        }

                        #fi-main-sidebar .fi-sidebar-item.fi-active .fi-sidebar-item-icon {
                            color: ' . $primaryColorHex . ' !important;
                            filter: drop-shadow(0 0 8px rgba(' . $primaryRgb . ', 0.7)) !important;
                            transform: scale(1.06) !important;
                        }

                        #fi-main-sidebar .fi-sidebar-item.fi-active .fi-sidebar-item-label {
                            color: ' . ($isDarkSidebar ? '#ffffff' : $primaryColorHex) . ' !important;
                        }

                        /* 7. Badges (Counters) */
                        #fi-main-sidebar .fi-badge {
                            border-radius: 9999px !important;
                            font-size: 0.72rem !important;
                            font-weight: 800 !important;
                            padding: 0.15rem 0.55rem !important;
                            background: ' . ($isDarkSidebar ? 'rgba(255, 255, 255, 0.08)' : '#f1f5f9') . ' !important;
                            color: ' . ($isDarkSidebar ? '#94a3b8' : '#475569') . ' !important;
                            border: 1px solid ' . ($isDarkSidebar ? 'rgba(255, 255, 255, 0.1)' : '#e2e8f0') . ' !important;
                        }

                        #fi-main-sidebar .fi-sidebar-item.fi-active .fi-badge {
                            background: ' . $primaryColorHex . ' !important;
                            color: #ffffff !important;
                            border-color: ' . $primaryColorHex . ' !important;
                            box-shadow: 0 2px 8px rgba(' . $primaryRgb . ', 0.4) !important;
                        }

                        /* 8. Topbar Modern Tweaks & Seamless Header */
                        .fi-topbar {
                            background: #ffffff !important;
                            backdrop-filter: blur(12px) !important;
                            border-bottom: 1px solid #e2e8f0 !important;
                            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03) !important;
                        }
                        .dark .fi-topbar {
                            background: #090e1a !important;
                            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
                        }

                        @media (min-width: 1024px) {
                            .fi-topbar {
                                padding-inline-start: 0 !important;
                            }

                            .fi-topbar-start {
                                background: ' . $sidebarHeaderBg . ' !important;
                                height: 4rem !important;
                                min-height: 4rem !important;
                                border-inline-end: ' . $sidebarBorder . ' !important;
                                width: var(--sidebar-width, 18rem) !important;
                                min-width: var(--sidebar-width, 18rem) !important;
                                max-width: var(--sidebar-width, 18rem) !important;
                                padding-inline: 1.15rem !important;
                                margin-inline-end: 1rem !important;
                                display: flex !important;
                                flex-direction: row-reverse !important;
                                justify-content: space-between !important;
                                align-items: center !important;
                                box-sizing: border-box !important;
                                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
                            }

                            .fi-topbar-start a,
                            .fi-topbar-start span {
                                color: ' . $sidebarBrandText . ' !important;
                                font-weight: 800 !important;
                            }

                            .fi-topbar-start .fi-logo {
                                max-height: 2.5rem !important;
                                object-fit: contain !important;
                                filter: ' . ($isDarkSidebar ? 'drop-shadow(0 2px 8px rgba(0, 0, 0, 0.4))' : 'none') . ' !important;
                            }

                            .fi-topbar-start button,
                            .fi-topbar-start .fi-icon-btn {
                                color: ' . ($isDarkSidebar ? '#94a3b8' : '#64748b') . ' !important;
                                background: ' . ($isDarkSidebar ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.04)') . ' !important;
                                border-radius: 0.5rem !important;
                                transition: all 0.2s ease !important;
                            }

                            .fi-topbar-start button:hover,
                            .fi-topbar-start .fi-icon-btn:hover {
                                color: ' . ($isDarkSidebar ? '#ffffff' : '#0f172a') . ' !important;
                                background: ' . ($isDarkSidebar ? 'rgba(255, 255, 255, 0.12)' : 'rgba(0, 0, 0, 0.08)') . ' !important;
                            }
                        }

                        /* Header Actions Spacing & Button Hierarchy */
                        .fi-header-actions,
                        .fi-ac {
                            display: flex !important;
                            align-items: center !important;
                            gap: 0.65rem !important;
                            flex-wrap: wrap !important;
                        }

                        .fi-header-actions .fi-btn,
                        .fi-ac .fi-btn {
                            border-radius: 0.625rem !important;
                            padding: 0.5rem 1rem !important;
                            font-size: 0.85rem !important;
                            font-weight: 700 !important;
                            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
                            transition: all 0.2s ease !important;
                        }

                        .fi-header-actions .fi-btn:hover,
                        .fi-ac .fi-btn:hover {
                            transform: translateY(-1px);
                            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08) !important;
                        }

                        /* Modern slim scrollbar */
                        ::-webkit-scrollbar {
                            width: 6px;
                            height: 6px;
                        }
                        ::-webkit-scrollbar-track {
                            background: transparent;
                        }
                        ::-webkit-scrollbar-thumb {
                            background: rgba(156, 163, 175, 0.35);
                            border-radius: 9999px;
                        }
                        ::-webkit-scrollbar-thumb:hover {
                            background: rgba(156, 163, 175, 0.6);
                        }

                        /* Polished Table Rows */
                        .fi-ta-row:hover {
                            background-color: rgba(' . $primaryRgb . ', 0.04) !important;
                            transition: background-color 0.15s ease-in-out;
                        }

                        /* Modern Rounded Cards & Sections */
                        .fi-section {
                            border-radius: 0.875rem !important;
                        }

                        /* Sizing constraint: Prevent oversized icons inside fi-section & fi-section-content */
                        .fi-section-header-icon,
                        .fi-section-header .fi-icon,
                        .fi-section-header svg {
                            width: 1.5rem !important;
                            height: 1.5rem !important;
                            max-width: 1.5rem !important;
                            max-height: 1.5rem !important;
                            flex-shrink: 0 !important;
                        }

                        .fi-section-content svg {
                            max-width: 1.5rem;
                            max-height: 1.5rem;
                            width: 1.5rem;
                            height: 1.5rem;
                            flex-shrink: 0;
                        }

                        .fi-section-content svg[style*="min-width"],
                        .fi-section-content .fi-icon[style*="min-width"] {
                            width: 1.5rem !important;
                            height: 1.5rem !important;
                            max-width: 1.5rem !important;
                            max-height: 1.5rem !important;
                            flex-shrink: 0 !important;
                        }

                        /* Tailwind icon sizing utilities fallback across admin panel */
                        svg.w-3, .fi-section-content svg.w-3 { width: 0.75rem !important; height: 0.75rem !important; max-width: 0.75rem !important; max-height: 0.75rem !important; }
                        svg.w-4, .fi-section-content svg.w-4 { width: 1rem !important; height: 1rem !important; max-width: 1rem !important; max-height: 1rem !important; }
                        svg.w-5, .fi-section-content svg.w-5 { width: 1.25rem !important; height: 1.25rem !important; max-width: 1.25rem !important; max-height: 1.25rem !important; }
                        svg.w-6, .fi-section-content svg.w-6 { width: 1.5rem !important; height: 1.5rem !important; max-width: 1.5rem !important; max-height: 1.5rem !important; }
                        svg.w-8, .fi-section-content svg.w-8 { width: 2rem !important; height: 2rem !important; max-width: 2rem !important; max-height: 2rem !important; }

                        /* Buttons and badges inside section content */
                        .fi-section-content .fi-btn svg,
                        .fi-section-content .fi-btn-icon,
                        .fi-section-content .fi-icon-btn svg,
                        .fi-section-content .fi-icon-btn .fi-icon-btn-icon {
                            width: 1.25rem !important;
                            height: 1.25rem !important;
                            max-width: 1.25rem !important;
                            max-height: 1.25rem !important;
                        }

                        .fi-section-content .fi-badge svg {
                            width: 0.875rem !important;
                            height: 0.875rem !important;
                            max-width: 0.875rem !important;
                            max-height: 0.875rem !important;
                        }

                        /* Allow widgets or cards with explicit 2.5rem hero icons */
                        .fi-section-content .hero-icon-lg svg,
                        .fi-section-content [style*="width: 2.5rem"] svg,
                        .fi-section-content svg[style*="width: 2.5rem"],
                        .fi-section-content [style*="width: 2rem"] svg,
                        .fi-section-content svg[style*="width: 2rem"] {
                            width: 2.5rem !important;
                            height: 2.5rem !important;
                            max-width: 2.5rem !important;
                            max-height: 2.5rem !important;
                        }
                    </style>
                ')
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::SIDEBAR_FOOTER,
                fn (): string => view('filament.admin.sidebar-footer')->render()
            )
            ->spa()
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->globalSearch()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->colors([
                'primary' => $primaryColorObj,
                'secondary' => Color::Sky,
                'danger' => Color::Rose,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'info' => Color::Blue,
            ])
            ->navigationGroups([
                'شؤون العاملين والموظفين',
                'إدارة التوظيف واستقطاب المواهب',
                'إدارة النظام والصلاحيات',
                'التعليمات والمساعدة',
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('بوابة الخدمة الذاتية (ESS)')
                    ->url('/portal')
                    ->icon('heroicon-o-user-circle'),
            ])
            ->navigationItems([
                NavigationItem::make('بوابة الخدمة الذاتية (ESS)')
                    ->url('/portal', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-user-circle')
                    ->group('شؤون العاملين والموظفين')
                    ->sort(8),
                NavigationItem::make('بوابة الوظائف العامة')
                    ->url('/jobs', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->group('إدارة التوظيف واستقطاب المواهب')
                    ->visible(fn (): bool => auth()->check() && (auth()->user()->hasRole(['Super Admin', 'admin', 'المدير العام', 'مسؤول النظام']) || auth()->user()->can('view_any_talent')))
                    ->sort(4),
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
            ->authMiddleware([
                Authenticate::class,
                RedirectEmployeeToPortal::class,
            ]);

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
