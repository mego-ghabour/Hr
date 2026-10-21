@php
    $sidebarTheme = \App\Models\Setting::get('sidebar_theme', 'dark_navy');
    $isDarkSidebar = ($sidebarTheme !== 'light_clean');
    $primaryColor = \App\Models\Setting::get('brand_primary_color', '#0d9488');
@endphp

<div class="fi-sidebar-footer-container" style="flex-shrink: 0; width: 100%; padding: 0.75rem 0.75rem 1rem 0.75rem; margin-top: auto; border-top: {{ $isDarkSidebar ? '1px solid rgba(255, 255, 255, 0.08)' : '1px solid #e2e8f0' }}; background: {{ $isDarkSidebar ? 'rgba(0, 0, 0, 0.3)' : '#f8fafc' }}; backdrop-filter: blur(12px);">
    <div class="fi-sidebar-footer-inner" style="display: flex; align-items: center; justify-content: space-between; gap: 0.65rem; background: {{ $isDarkSidebar ? 'rgba(255, 255, 255, 0.04)' : '#ffffff' }}; border: {{ $isDarkSidebar ? '1px solid rgba(255, 255, 255, 0.08)' : '1px solid #e2e8f0' }}; border-radius: 0.875rem; padding: 0.6rem 0.75rem; box-shadow: {{ $isDarkSidebar ? '0 4px 12px rgba(0, 0, 0, 0.25)' : '0 1px 3px rgba(0, 0, 0, 0.05)' }};">
        
        <!-- User Profile Avatar & Details -->
        <div style="display: flex; align-items: center; gap: 0.65rem; min-width: 0; flex: 1;">
            <div style="position: relative; flex-shrink: 0;">
                <div style="display: flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; border-radius: 0.625rem; background: linear-gradient(135deg, {{ $primaryColor }} 0%, #0f172a 100%); color: #ffffff; font-weight: 800; font-size: 0.95rem; border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.35);">
                    {{ mb_substr(auth()->user()->name ?? 'م', 0, 1) }}
                </div>
                <!-- Online Green Dot -->
                <span style="position: absolute; bottom: -2px; left: -2px; width: 8px; height: 8px; border-radius: 50%; background-color: #10b981; border: 2px solid {{ $isDarkSidebar ? '#090e1a' : '#ffffff' }}; box-shadow: 0 0 6px #10b981;"></span>
            </div>
            
            <div style="flex: 1; min-width: 0;">
                <h4 class="fi-sidebar-footer-name" style="font-size: 0.825rem; font-weight: 800; color: {{ $isDarkSidebar ? '#f8fafc' : '#0f172a' }}; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.2;">
                    {{ auth()->user()->name ?? 'المستخدم' }}
                </h4>
                <div style="margin-top: 0.15rem;">
                    <span style="display: inline-block; font-size: 0.65rem; font-weight: 700; padding: 0.1rem 0.45rem; border-radius: 9999px; background: {{ $isDarkSidebar ? 'rgba(255, 255, 255, 0.08)' : '#e2e8f0' }}; color: {{ $isDarkSidebar ? '#94a3b8' : '#475569' }}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">
                        {{ auth()->user()->roles->first()?->name ?? 'مدير النظام' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Dedicated Logout Button -->
        <button type="button"
                class="fi-sidebar-footer-btn"
                onclick="document.getElementById('sidebar-logout-form').submit();"
                title="تسجيل الخروج من النظام"
                style="flex-shrink: 0; width: 2rem; height: 2rem; border-radius: 0.55rem; border: {{ $isDarkSidebar ? '1px solid rgba(255, 255, 255, 0.1)' : '1px solid #e2e8f0' }}; background: {{ $isDarkSidebar ? 'rgba(255, 255, 255, 0.06)' : '#ffffff' }}; color: {{ $isDarkSidebar ? '#94a3b8' : '#64748b' }}; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);"
                onmouseover="this.style.background='#ef4444'; this.style.borderColor='#ef4444'; this.style.color='#ffffff'; this.style.transform='scale(1.05)';"
                onmouseout="this.style.background='{{ $isDarkSidebar ? 'rgba(255, 255, 255, 0.06)' : '#ffffff' }}'; this.style.borderColor='{{ $isDarkSidebar ? 'rgba(255, 255, 255, 0.1)' : '#e2e8f0' }}'; this.style.color='{{ $isDarkSidebar ? '#94a3b8' : '#64748b' }}'; this.style.transform='scale(1)';">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" style="width: 1.1rem; height: 1.1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
            </svg>
        </button>
    </div>

    <form id="sidebar-logout-form" action="{{ filament()->getLogoutUrl() }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>
