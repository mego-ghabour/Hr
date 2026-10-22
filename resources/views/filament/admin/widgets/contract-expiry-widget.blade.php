<x-filament-widgets::widget>
    <style>
        .contract-widget-box {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
        }
        .contract-header-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }
        .dark .contract-header-title {
            color: #f8fafc !important;
        }
        .contract-header-sub {
            font-size: 0.75rem;
            color: #6b7280;
            margin: 0;
        }
        .dark .contract-header-sub {
            color: #94a3b8 !important;
        }
        .contract-card-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.85rem 1.15rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }
        .contract-card-item:hover {
            transform: translateY(-1px);
        }
        .contract-card-expired {
            border: 1px solid #fecaca;
            background-color: #fff5f5;
        }
        .dark .contract-card-expired {
            background-color: rgba(239, 68, 68, 0.1) !important;
            border-color: rgba(248, 113, 113, 0.25) !important;
        }
        .contract-card-warning {
            border: 1px solid #fed7aa;
            background-color: #fffaf0;
        }
        .dark .contract-card-warning {
            background-color: rgba(249, 115, 22, 0.1) !important;
            border-color: rgba(251, 146, 60, 0.25) !important;
        }
        .contract-emp-name {
            font-weight: 700;
            color: #111827;
            font-size: 0.9rem;
        }
        .dark .contract-emp-name {
            color: #f8fafc !important;
        }
        .contract-emp-meta {
            font-size: 0.75rem;
            color: #6b7280;
        }
        .dark .contract-emp-meta {
            color: #94a3b8 !important;
        }
        .contract-date-text {
            font-size: 0.75rem;
            color: #6b7280;
        }
        .dark .contract-date-text {
            color: #94a3b8 !important;
        }
        .contract-date-text strong {
            color: #111827;
        }
        .dark .contract-date-text strong {
            color: #f8fafc !important;
        }
        .contract-renew-btn {
            padding: 0.45rem 0.9rem;
            border-radius: 0.5rem;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            font-size: 0.8rem;
            font-weight: 700;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .contract-renew-btn:hover {
            background-color: #0d9488;
            border-color: #0d9488;
            color: #ffffff;
        }
        .dark .contract-renew-btn {
            background-color: rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            color: #f8fafc !important;
        }
        .dark .contract-renew-btn:hover {
            background-color: #0d9488 !important;
            border-color: #0d9488 !important;
            color: #ffffff !important;
        }
        .contract-empty-alert {
            text-align: center;
            padding: 1.75rem 1rem;
            color: #059669;
            font-size: 0.9rem;
            font-weight: 700;
            background-color: #ecfdf5;
            border-radius: 0.75rem;
            border: 1px solid #a7f3d0;
        }
        .dark .contract-empty-alert {
            background-color: rgba(16, 185, 129, 0.1) !important;
            border-color: rgba(52, 211, 153, 0.25) !important;
            color: #34d399 !important;
        }
    </style>

    <x-filament::section class="contract-widget-box">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background-color: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;" class="dark:!bg-amber-500/20 dark:!text-amber-400">
                    <x-filament::icon icon="heroicon-o-document-text" style="width: 1.35rem; height: 1.35rem;" />
                </div>
                <div>
                    <h2 class="contract-header-title">متابعة انتهاء عقود العمل</h2>
                    <p class="contract-header-sub">تنبيهات فورية بالعقود المنتهية والتي تقترب من الانتهاء (خلال 60 يوماً)</p>
                </div>
            </div>
            <a href="{{ \App\Filament\Admin\Resources\Employees\EmployeeResource::getUrl('index') }}" style="font-size: 0.8rem; font-weight: 700; color: #0d9488; text-decoration: none;" class="dark:!text-teal-400">
                استعراض كافة الموظفين &larr;
            </a>
        </div>

        @if(count($expiringEmployees) > 0)
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @foreach($expiringEmployees as $emp)
                    @php
                        $isExp = $emp['is_expired'];
                    @endphp
                    <div class="contract-card-item {{ $isExp ? 'contract-card-expired' : 'contract-card-warning' }}">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 2.25rem; height: 2.25rem; border-radius: 50%; background-color: {{ $isExp ? '#fee2e2' : '#ffedd5' }}; color: {{ $isExp ? '#dc2626' : '#ea580c' }}; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.875rem;" class="dark:!bg-white/10 dark:!text-white">
                                {{ mb_substr($emp['name'], 0, 1) }}
                            </div>
                            <div>
                                <div class="contract-emp-name">{{ $emp['name'] }}</div>
                                <div class="contract-emp-meta">{{ $emp['job_title'] }} • {{ $emp['department'] }}</div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="text-align: left;">
                                <div class="contract-date-text">تاريخ الانتهاء: <strong dir="ltr">{{ $emp['contract_end_date'] }}</strong></div>
                                <div style="margin-top: 0.15rem;">
                                    @if($isExp)
                                        <span style="display: inline-block; padding: 0.15rem 0.6rem; border-radius: 9999px; background-color: #fee2e2; color: #b91c1c; font-size: 0.75rem; font-weight: 800;" class="dark:!bg-rose-500/20 dark:!text-rose-400">
                                            منتهي منذ {{ abs($emp['days_remaining']) }} يوم!
                                        </span>
                                    @else
                                        <span style="display: inline-block; padding: 0.15rem 0.6rem; border-radius: 9999px; background-color: #ffedd5; color: #c2410c; font-size: 0.75rem; font-weight: 800;" class="dark:!bg-amber-500/20 dark:!text-amber-400">
                                            متبقي {{ $emp['days_remaining'] }} يوم
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ \App\Filament\Admin\Resources\Employees\EmployeeResource::getUrl('edit', ['record' => $emp['id']]) }}" class="contract-renew-btn">
                                <span>تجديد العقد</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="contract-empty-alert">
                جميع عقود العمل سارية المفعول ولا توجد عقود تقترب من الانتهاء حالياً.
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
