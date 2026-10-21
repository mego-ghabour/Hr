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
        .announcement-card-info {
            background: #f0fdfa;
            border-color: #99f6e4;
            border-right: 4px solid #0d9488;
        }
        .announcement-card-warning {
            background: #fffbeb;
            border-color: #fde68a;
            border-right: 4px solid #f59e0b;
        }
        .announcement-card-danger {
            background: #fff1f2;
            border-color: #fecdd3;
            border-right: 4px solid #f43f5e;
        }
        .announcement-card-success {
            background: #ecfdf5;
            border-color: #a7f3d0;
            border-right: 4px solid #10b981;
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
                        <h2 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0;">التعاميم والقرارات الإدارية</h2>
                        <span style="background: #ccfbf1; color: #0f766e; font-size: 0.75rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 9999px;">
                            {{ $announcements->count() }} نشط
                        </span>
                    </div>
                    <p style="font-size: 0.75rem; color: #64748b; margin: 0.15rem 0 0 0;">أحدث التوجيهات الرسمية الصادرة من الإدارة العليا للشركة</p>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 0.75rem;">
                @if($canCreate)
                    <a href="{{ url('/admin/announcements/create') }}" 
                       style="background: #ffffff; color: #0f766e; border: 1px solid #ccfbf1; padding: 0.4rem 0.85rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                        <x-filament::icon icon="heroicon-o-plus-circle" style="width: 1rem; height: 1rem;" />
                        <span>إضافة تعميم</span>
                    </a>
                @endif
                <a href="{{ url('/admin/announcements') }}" 
                   style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-decoration: none;">
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
                        $titleColor = match($type) {
                            'warning' => '#92400e',
                            'danger'  => '#9f1239',
                            'success' => '#065f46',
                            default   => '#134e4a',
                        };
                    @endphp

                    <div class="announcement-card {{ $cardClass }}">
                        <div>
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span style="font-size: 0.7rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 0.35rem; background: rgba(0,0,0,0.06); color: {{ $titleColor }};">
                                    {{ $badgeText }}
                                </span>
                                <span style="font-size: 0.7rem; color: #64748b; display: flex; align-items: center; gap: 0.25rem;">
                                    <x-filament::icon icon="heroicon-o-clock" style="width: 0.75rem; height: 0.75rem;" />
                                    {{ $announcement->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <h3 style="font-size: 0.95rem; font-weight: 800; color: {{ $titleColor }}; margin: 0 0 0.35rem 0;">
                                {{ $announcement->title }}
                            </h3>

                            <p style="font-size: 0.8rem; line-height: 1.5; color: #475569; margin: 0;">
                                {{ $announcement->content }}
                            </p>
                        </div>

                        <div style="margin-top: 0.85rem; padding-top: 0.5rem; border-top: 1px solid rgba(0,0,0,0.06); display: flex; align-items: center; justify-content: space-between; font-size: 0.7rem; color: #94a3b8;">
                            <span style="font-weight: 700; color: #0d9488;">الإدارة العامة</span>
                            <span dir="ltr" style="font-family: monospace;">{{ $announcement->created_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 2rem 1rem; color: #059669; background: #ffffff; border-radius: 0.75rem; border: 1px dashed #a7f3d0;">
                <p style="font-size: 0.9rem; font-weight: 700; margin: 0;">لا توجد تعاميم إدارية جديدة حالياً</p>
                <p style="font-size: 0.75rem; color: #64748b; margin: 0.25rem 0 0 0;">كافة التوجيهات مستقرة وبيئة العمل تسير بانتظام تام.</p>
            </div>
        @endif
    </div>
</x-filament-widgets::widget>
