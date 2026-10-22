<x-filament-widgets::widget>
    <style>
        .announcement-container {
            background: linear-gradient(135deg, #ffffff 0%, #f0fdfa 100%);
            border: 1px solid #ccfbf1;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            transition: all 0.25s ease;
        }
        .dark .announcement-container {
            background: linear-gradient(135deg, #0f172a 0%, #111e38 100%) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35) !important;
        }
        .announcement-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 0.85rem;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .dark .announcement-header {
            border-bottom-color: rgba(255, 255, 255, 0.08) !important;
        }
        .announcement-title-text {
            color: #0f172a;
        }
        .dark .announcement-title-text {
            color: #f8fafc !important;
        }
        .announcement-sub-text {
            color: #64748b;
        }
        .dark .announcement-sub-text {
            color: #94a3b8 !important;
        }
        .announcement-active-badge {
            background: #ccfbf1;
            color: #0f766e;
        }
        .dark .announcement-active-badge {
            background: rgba(13, 148, 136, 0.25) !important;
            color: #2dd4bf !important;
        }
        .announcement-add-btn {
            background: #ffffff;
            color: #0f766e;
            border: 1px solid #ccfbf1;
            padding: 0.4rem 0.85rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }
        .announcement-add-btn:hover {
            transform: translateY(-1px);
        }
        .dark .announcement-add-btn {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #2dd4bf !important;
            border-color: rgba(45, 212, 191, 0.3) !important;
        }
        .dark .announcement-add-btn:hover {
            background: #0d9488 !important;
            color: #ffffff !important;
        }
        .announcement-view-all {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-decoration: none;
            transition: color 0.15s ease;
        }
        .announcement-view-all:hover {
            color: #0d9488;
        }
        .dark .announcement-view-all {
            color: #94a3b8 !important;
        }
        .dark .announcement-view-all:hover {
            color: #2dd4bf !important;
        }
        .announcement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
        }
        .announcement-card {
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            border: 1px solid;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s ease;
        }
        .announcement-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        .dark .announcement-card:hover {
            box-shadow: 0 6px 18px rgba(0,0,0,0.35);
        }
        .announcement-card-info {
            background: #f0fdfa;
            border-color: #99f6e4;
            border-right: 4px solid #0d9488;
        }
        .dark .announcement-card-info {
            background: rgba(13, 148, 136, 0.12) !important;
            border-color: rgba(45, 212, 191, 0.22) !important;
            border-right: 4px solid #0d9488 !important;
        }
        .announcement-card-warning {
            background: #fffbeb;
            border-color: #fde68a;
            border-right: 4px solid #f59e0b;
        }
        .dark .announcement-card-warning {
            background: rgba(245, 158, 11, 0.12) !important;
            border-color: rgba(251, 191, 36, 0.22) !important;
            border-right: 4px solid #f59e0b !important;
        }
        .announcement-card-danger {
            background: #fff1f2;
            border-color: #fecdd3;
            border-right: 4px solid #f43f5e;
        }
        .dark .announcement-card-danger {
            background: rgba(244, 63, 94, 0.12) !important;
            border-color: rgba(251, 113, 133, 0.22) !important;
            border-right: 4px solid #f43f5e !important;
        }
        .announcement-card-success {
            background: #ecfdf5;
            border-color: #a7f3d0;
            border-right: 4px solid #10b981;
        }
        .dark .announcement-card-success {
            background: rgba(16, 185, 129, 0.12) !important;
            border-color: rgba(52, 211, 153, 0.22) !important;
            border-right: 4px solid #10b981 !important;
        }
        .announcement-body-text {
            font-size: 0.8rem;
            line-height: 1.5;
            color: #475569;
            margin: 0;
        }
        .dark .announcement-body-text {
            color: #cbd5e1 !important;
        }
        .announcement-card-footer {
            margin-top: 0.85rem;
            padding-top: 0.5rem;
            border-top: 1px solid rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.7rem;
            color: #94a3b8;
        }
        .dark .announcement-card-footer {
            border-top-color: rgba(255, 255, 255, 0.08) !important;
            color: #94a3b8 !important;
        }
        .announcement-empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: #059669;
            background: #ffffff;
            border-radius: 0.75rem;
            border: 1px dashed #a7f3d0;
        }
        .dark .announcement-empty-state {
            background: #0f172a !important;
            border-color: rgba(16, 185, 129, 0.3) !important;
            color: #34d399 !important;
        }
    </style>

    <div class="announcement-container">
        
        <!-- Header -->
        <div class="announcement-header">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #0d9488; color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(13, 148, 136, 0.3);">
                    <x-filament::icon icon="heroicon-o-megaphone" style="width: 1.25rem; height: 1.25rem;" />
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <h2 class="announcement-title-text" style="font-size: 1rem; font-weight: 800; margin: 0;">التعاميم والقرارات الإدارية</h2>
                        <span class="announcement-active-badge" style="font-size: 0.75rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 9999px;">
                            {{ $announcements->count() }} نشط
                        </span>
                    </div>
                    <p class="announcement-sub-text" style="font-size: 0.75rem; margin: 0.15rem 0 0 0;">أحدث التوجيهات الرسمية الصادرة من الإدارة العليا للشركة</p>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 0.75rem;">
                @if($canCreate)
                    <a href="{{ url('/admin/announcements/create') }}" class="announcement-add-btn">
                        <x-filament::icon icon="heroicon-o-plus-circle" style="width: 1rem; height: 1rem;" />
                        <span>إضافة تعميم</span>
                    </a>
                @endif
                <a href="{{ url('/admin/announcements') }}" class="announcement-view-all">
                    كافة التعاميم &larr;
                </a>
            </div>
        </div>

        <!-- Announcements Content -->
        @if($announcements->count() > 0)
            <div class="announcement-grid">
                @foreach($announcements as $announcement)
                    @php
                        $type = $announcement->type ?? 'info';
                        $cardClass = match($type) {
                            'warning' => 'announcement-card-warning',
                            'danger'  => 'announcement-card-danger',
                            'success' => 'announcement-card-success',
                            default   => 'announcement-card-info',
                        };
                        $badgeText = match($type) {
                            'warning' => 'تنبيه إداري',
                            'danger'  => 'قرار عاجل ملزم',
                            'success' => 'إعلان سار',
                            default   => 'تعميم عام',
                        };
                        $titleColorLight = match($type) {
                            'warning' => '#92400e',
                            'danger'  => '#9f1239',
                            'success' => '#065f46',
                            default   => '#134e4a',
                        };
                        $titleColorDark = match($type) {
                            'warning' => '#fbbf24',
                            'danger'  => '#fb7185',
                            'success' => '#34d399',
                            default   => '#2dd4bf',
                        };
                    @endphp

                    <div class="announcement-card {{ $cardClass }}">
                        <div>
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span style="font-size: 0.7rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 0.35rem; background: rgba(0,0,0,0.06); color: {{ $titleColorLight }};" class="dark:!bg-white/10 dark:!text-white">
                                    {{ $badgeText }}
                                </span>
                                <span class="announcement-sub-text" style="font-size: 0.7rem; display: flex; align-items: center; gap: 0.25rem;">
                                    <x-filament::icon icon="heroicon-o-clock" style="width: 0.75rem; height: 0.75rem;" />
                                    {{ $announcement->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <h3 style="font-size: 0.95rem; font-weight: 800; color: {{ $titleColorLight }}; margin: 0 0 0.35rem 0;" class="dark:!text-[{{ $titleColorDark }}]">
                                {{ $announcement->title }}
                            </h3>

                            <p class="announcement-body-text">
                                {{ $announcement->content }}
                            </p>
                        </div>

                        <div class="announcement-card-footer">
                            <span style="font-weight: 700; color: #0d9488;" class="dark:!text-teal-400">الإدارة العامة</span>
                            <span dir="ltr" style="font-family: monospace;">{{ $announcement->created_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="announcement-empty-state">
                <p style="font-size: 0.9rem; font-weight: 700; margin: 0;">لا توجد تعاميم إدارية جديدة حالياً</p>
                <p class="announcement-sub-text" style="font-size: 0.75rem; margin: 0.25rem 0 0 0;">كافة التوجيهات مستقرة وبيئة العمل تسير بانتظام تام.</p>
            </div>
        @endif
    </div>
</x-filament-widgets::widget>
