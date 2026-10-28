@php
    $statePath = $getStatePath();
    $record = $getRecord();
    $brandName = \App\Models\Setting::get('brand_name', 'Eqlaa');
    
    // تحديد عنوان ووصف السجل (الموظف / الدور / المستخدم)
    $targetTitle = 'Company Owner';
    if ($record instanceof \Spatie\Permission\Models\Role) {
        $targetTitle = \App\Filament\Admin\Resources\Roles\Schemas\RoleForm::getRoleLabel($record->name);
    } elseif ($record instanceof \App\Models\User) {
        $targetTitle = ($record->employee?->full_name ?? $record->name) . ' — ' . ($record->roles->pluck('name')->first() ?? 'حساب موظف');
    } elseif ($record instanceof \App\Models\Employee) {
        $targetTitle = $record->full_name . ' — ' . ($record->job_title ?? 'موظف');
    }

    $allModules = \App\Services\PermissionModuleService::getModules();
    $initialModules = $getState() ?? [];
    if (empty($initialModules) && $record) {
        $initialModules = \App\Services\PermissionModuleService::getActiveModulesForRecord($record);
    }
@endphp

<style>
    .perm-modal-wrap {
        font-family: inherit;
        direction: rtl;
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        user-select: none;
    }
    .perm-header-banner {
        background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%);
        border-radius: 1.25rem;
        padding: 1.25rem 1.5rem;
        color: #ffffff;
        box-shadow: 0 8px 20px rgba(124, 58, 237, 0.25);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    .perm-info-banner {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        color: #0369a1;
        border-radius: 0.875rem;
        padding: 0.75rem 1rem;
        text-align: center;
        font-size: 0.85rem;
        font-weight: 800;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .perm-actions-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 0.35rem;
    }
    .perm-link-select-all {
        background: transparent;
        border: none;
        color: #7c3aed;
        font-size: 0.85rem;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        transition: color 0.15s;
    }
    .perm-link-select-all:hover { color: #6d28d9; }
    .perm-link-deselect-all {
        background: transparent;
        border: none;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        transition: color 0.15s;
    }
    .perm-link-deselect-all:hover { color: #1e293b; }
    
    .perm-grid-2 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.75rem;
        max-height: 440px;
        overflow-y: auto;
        padding: 0.25rem 0.35rem;
    }
    @media (min-width: 640px) {
        .perm-grid-2 {
            grid-template-columns: 1fr 1fr;
        }
    }
    .perm-card-item {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 1rem;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .perm-card-item:hover {
        border-color: #c4b5fd;
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.08);
        transform: translateY(-1px);
    }
    .perm-card-active {
        border-color: #a78bfa !important;
        background: #fbf9ff !important;
    }
    .perm-icon-box {
        width: 2.65rem;
        height: 2.65rem;
        border-radius: 0.75rem;
        background: #f3e8ff;
        color: #7c3aed;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }
    .perm-toggle-track {
        width: 48px;
        height: 26px;
        border-radius: 9999px;
        transition: background-color 0.2s ease;
        display: flex;
        align-items: center;
        padding: 3px;
        flex-shrink: 0;
    }
    .perm-toggle-track-on {
        background-color: #10b981;
        justify-content: flex-end;
    }
    .perm-toggle-track-off {
        background-color: #cbd5e1;
        justify-content: flex-start;
    }
    .perm-toggle-knob {
        width: 20px;
        height: 20px;
        border-radius: 9999px;
        background: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.25);
        transition: all 0.2s ease;
    }

    /* Custom Scrollbar */
    .perm-grid-2::-webkit-scrollbar {
        width: 5px;
    }
    .perm-grid-2::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 9999px;
    }
    .perm-grid-2::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }
    .perm-grid-2::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<div x-data="{
    modules: @js($initialModules),
    allKeys: @js(array_keys($allModules)),
    toggle(key) {
        if (this.modules.includes(key)) {
            this.modules = this.modules.filter(m => m !== key);
        } else {
            this.modules.push(key);
        }
        $wire.set('{{ $statePath }}', this.modules);
    },
    selectAll() {
        this.modules = [...this.allKeys];
        $wire.set('{{ $statePath }}', this.modules);
    },
    deselectAll() {
        this.modules = [];
        $wire.set('{{ $statePath }}', this.modules);
    }
}"
x-init="$wire.set('{{ $statePath }}', modules)"
class="perm-modal-wrap">

    {{-- Top Brand Banner --}}
    <div class="perm-header-banner">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 900; margin: 0; line-height: 1.2;">
                {{ $brandName }}
            </h2>
            <p style="font-size: 0.8rem; font-weight: 700; color: #e9d5ff; margin: 0.2rem 0 0 0;">
                {{ $targetTitle }}
            </p>
        </div>
        <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: rgba(255,255,255,0.18); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            🛡️
        </div>
    </div>

    {{-- Guidance Info Box --}}
    <div class="perm-info-banner">
        حدد الصفحات التي يمكن لهذا الموظف الوصول إليها
    </div>

    {{-- Select / Deselect All --}}
    <div class="perm-actions-row">
        <button type="button" @click="selectAll" class="perm-link-select-all">
            <span>✓</span>
            <span>تحديد الكل</span>
        </button>

        <button type="button" @click="deselectAll" class="perm-link-deselect-all">
            <span>✕</span>
            <span>إلغاء تحديد الكل</span>
        </button>
    </div>

    {{-- 2-Column Grid of Cards --}}
    <div class="perm-grid-2">
        @foreach($allModules as $mKey => $m)
            <div @click="toggle('{{ $mKey }}')"
                 class="perm-card-item"
                 :class="modules.includes('{{ $mKey }}') ? 'perm-card-active' : ''">

                {{-- Right Side (RTL Start): Icon + Title --}}
                <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0;">
                    <div class="perm-icon-box">
                        {{ $m['icon'] }}
                    </div>

                    <div style="min-w: 0; text-align: right;">
                        <strong style="font-size: 0.875rem; font-weight: 900; color: #0f172a; display: block; line-height: 1.3;">
                            {{ $m['title'] }}
                        </strong>
                        <span style="font-size: 0.7rem; font-weight: 600; color: #64748b; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px;">
                            {{ $m['description'] ?? '' }}
                        </span>
                    </div>
                </div>

                {{-- Left Side (RTL End): Toggle Switch --}}
                <div class="perm-toggle-track"
                     :class="modules.includes('{{ $mKey }}') ? 'perm-toggle-track-on' : 'perm-toggle-track-off'">
                    <span class="perm-toggle-knob"></span>
                </div>

            </div>
        @endforeach
    </div>

    <input type="hidden" name="{{ $statePath }}" :value="JSON.stringify(modules)">
</div>
