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
class="w-full -mt-2 -mx-2 sm:-mx-4 font-cairo select-none"
dir="rtl">

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- MODAL TOP HEADER: PURPLE BRAND GRADIENT                          --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="rounded-2xl sm:rounded-3xl p-5 sm:p-6 mb-4 shadow-md text-white relative overflow-hidden"
         style="background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%);">
        
        {{-- Subtle background decoration --}}
        <div class="absolute -top-12 -left-12 w-40 h-40 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
        <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-purple-400/20 rounded-full blur-lg pointer-events-none"></div>

        <div class="relative z-10 flex items-center justify-between">
            <div>
                <h2 class="text-xl sm:text-2xl font-black tracking-tight leading-tight">
                    {{ $brandName }}
                </h2>
                <p class="text-xs sm:text-sm font-bold text-purple-200 mt-0.5 opacity-90">
                    {{ $targetTitle }}
                </p>
            </div>

            <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-sm border border-white/20 flex items-center justify-center text-white text-lg font-bold shadow-xs">
                🛡️
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- GUIDANCE INFO BANNER (LIGHT BLUE)                                --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="bg-sky-50 border border-sky-200 text-sky-800 rounded-2xl py-3 px-4 text-center text-xs sm:text-sm font-extrabold shadow-2xs mb-3">
        حدد الصفحات التي يمكن لهذا الموظف الوصول إليها
    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- QUICK ACTION LINKS: SELECT ALL / DESELECT ALL                    --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="flex items-center justify-between px-2 py-1 mb-3">
        <button type="button"
                @click="selectAll"
                class="text-xs sm:text-sm font-black text-purple-600 hover:text-purple-700 transition cursor-pointer flex items-center gap-1">
            <span>✓</span>
            <span>تحديد الكل</span>
        </button>

        <button type="button"
                @click="deselectAll"
                class="text-xs sm:text-sm font-bold text-slate-500 hover:text-slate-800 transition cursor-pointer flex items-center gap-1">
            <span>✕</span>
            <span>إلغاء تحديد الكل</span>
        </button>
    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 2-COLUMN GRID OF PERMISSION CARDS                                --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="max-h-[460px] overflow-y-auto px-1 py-1 space-y-2.5 sm:space-y-0 sm:grid sm:grid-cols-2 sm:gap-3 custom-scrollbar">
        @foreach($allModules as $mKey => $m)
            <div @click="toggle('{{ $mKey }}')"
                 class="group relative rounded-2xl p-3 sm:p-3.5 border-2 transition-all duration-200 flex items-center justify-between cursor-pointer select-none shadow-2xs hover:shadow-xs"
                 :class="modules.includes('{{ $mKey }}') 
                    ? 'border-purple-300 bg-purple-50/30' 
                    : 'border-slate-200/90 bg-white hover:border-slate-300 hover:bg-slate-50/50'">

                {{-- Right Side (RTL Start): Icon Badge + Title --}}
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center text-lg sm:text-xl shrink-0 transition-colors shadow-2xs"
                         :class="modules.includes('{{ $mKey }}')
                            ? 'bg-purple-100 text-purple-700'
                            : 'bg-purple-50/70 text-purple-600 group-hover:bg-purple-100'">
                        {{ $m['icon'] }}
                    </div>

                    <div class="min-w-0 text-right">
                        <strong class="text-xs sm:text-sm font-black text-slate-900 block truncate leading-snug">
                            {{ $m['title'] }}
                        </strong>
                        <span class="text-[10px] sm:text-[11px] font-medium text-slate-400 block truncate mt-0.5">
                            {{ $m['description'] ?? '' }}
                        </span>
                    </div>
                </div>

                {{-- Left Side (RTL End): Smooth iOS Toggle Switch --}}
                <div class="shrink-0 mr-3">
                    <div class="relative w-12 h-6.5 rounded-full transition-colors duration-200 flex items-center p-0.5"
                         :class="modules.includes('{{ $mKey }}') ? 'bg-emerald-500 justify-end' : 'bg-slate-300 justify-start'">
                        <span class="w-5 h-5 rounded-full bg-white shadow-md transition-all duration-200 transform"></span>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

    {{-- Hidden Input for form submission safety --}}
    <input type="hidden" name="{{ $statePath }}" :value="JSON.stringify(modules)">

</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
