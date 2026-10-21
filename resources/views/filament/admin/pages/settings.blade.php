<x-filament-panels::page>
    <style>
        /* Force Full Width Layout & Remove Sub-Navigation Sidebar */
        .fi-page-has-sub-navigation {
            grid-template-columns: 1fr !important;
            display: block !important;
        }
        .fi-page-sub-navigation,
        aside.fi-page-sub-navigation-sidebar,
        nav.fi-page-sub-navigation {
            display: none !important;
        }
        .fi-page-main-ctn,
        .fi-page-content {
            width: 100% !important;
            max-width: 100% !important;
        }

        .settings-hub-container {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            width: 100%;
        }

        /* Top Breadcrumb Nav Bar */
        .settings-breadcrumb-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 0.85rem 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .settings-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 700;
        }

        .settings-crumb-link {
            color: #64748b;
            text-decoration: none;
            cursor: pointer;
            transition: color 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .settings-crumb-link:hover {
            color: #0d9488;
        }

        .settings-crumb-current {
            color: #0d9488;
            font-weight: 800;
        }

        /* Section Category Header */
        .settings-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 2rem 0 1rem 0;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .settings-section-header:first-of-type {
            margin-top: 0.5rem;
        }

        .settings-section-title-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .settings-section-badge-icon {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .settings-section-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .settings-section-subtitle {
            font-size: 0.8rem;
            color: #64748b;
            margin: 0;
        }

        /* ── DIRECT FLAT GRID (CARDS) ── */
        .settings-flat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .settings-flat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 220px;
            position: relative;
            overflow: hidden;
        }

        .settings-flat-card:hover {
            transform: translateY(-3px);
            border-color: #0d9488;
            box-shadow: 0 12px 28px -6px rgba(13, 148, 136, 0.12), 0 2px 4px rgba(0,0,0,0.04);
        }

        .settings-flat-icon-wrap {
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.1rem;
            transition: transform 0.2s ease;
        }

        .settings-flat-card:hover .settings-flat-icon-wrap {
            transform: scale(1.08);
        }

        .settings-flat-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.45rem 0;
        }

        .settings-flat-desc {
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.55;
            margin: 0 0 1rem 0;
        }

        .settings-flat-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 0.85rem;
            border-top: 1px solid #f1f5f9;
            font-size: 0.825rem;
            font-weight: 700;
        }

        .settings-flat-action {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            color: #0d9488;
            transition: transform 0.15s ease;
        }

        .settings-flat-card:hover .settings-flat-action {
            transform: translateX(-3px);
        }

        /* ── DETAIL PANELS & FORMS ── */
        .settings-detail-panel {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 1.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        .settings-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 1.25rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn-back-nav {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            padding: 0.55rem 1.15rem;
            border-radius: 0.65rem;
            font-size: 0.825rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            transition: all 0.15s ease;
        }

        .btn-back-nav:hover {
            background: #0d9488;
            border-color: #0d9488;
            color: #ffffff;
        }

        .btn-action-primary {
            background: #0d9488;
            color: #ffffff;
            border: none;
            padding: 0.65rem 1.35rem;
            border-radius: 0.65rem;
            font-size: 0.85rem;
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.15s ease;
            box-shadow: 0 2px 6px rgba(13, 148, 136, 0.22);
        }

        .btn-action-primary:hover {
            background: #0f766e;
            transform: translateY(-1px);
        }

        .btn-action-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 0.65rem 1.1rem;
            border-radius: 0.65rem;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-action-secondary:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* Form Controls */
        .form-label {
            display: block;
            font-size: 0.825rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        .form-input {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 0.65rem;
            padding: 0.6rem 0.85rem;
            font-size: 0.875rem;
            font-family: inherit;
            color: #0f172a;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            background: #ffffff;
        }

        .form-input:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
        }

        /* Table */
        .crud-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            text-align: right;
        }

        .crud-table th {
            background: #f8fafc;
            padding: 0.8rem 1rem;
            font-weight: 800;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.775rem;
        }

        .crud-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
        }

        .crud-table tr:hover td {
            background: #f8fafc;
        }

        .badge-pill {
            display: inline-block;
            padding: 0.25rem 0.7rem;
            border-radius: 9999px;
            font-size: 0.725rem;
            font-weight: 700;
        }

        .smart-tag-chip {
            display: inline-flex;
            align-items: center;
            background: #f0fdfa;
            color: #0f766e;
            border: 1px solid #ccfbf1;
            padding: 0.2rem 0.55rem;
            border-radius: 0.45rem;
            font-family: monospace;
            font-size: 0.75rem;
            cursor: pointer;
            margin: 0.15rem;
        }
    </style>

    <div class="settings-hub-container">

        <!-- Top Breadcrumb & Quick Navigation -->
        <div class="settings-breadcrumb-bar">
            <div class="settings-breadcrumb">
                <span class="settings-crumb-link {{ $activeTab === 'grid' ? 'settings-crumb-current' : '' }}" wire:click="backToGrid">
                    <x-filament::icon icon="heroicon-o-squares-2x2" style="width: 1.15rem; height: 1.15rem;" />
                    <span>مركز الإعدادات الموحد</span>
                </span>

                @if($activeTab !== 'grid')
                    <span style="color: #cbd5e1;">&larr;</span>
                    <span class="settings-crumb-current">
                        @switch($activeTab)
                            @case('departments') الأقسام الإدارية @break
                            @case('locations') المواقع والفروع @break
                            @case('general') إعدادات النظام والهوية والعملة @break
                            @case('shifts') مواعيد وساعات الدوام الرسمي @break
                            @case('sources') مصادر الترشيح @break
                            @case('statuses') حالات ومراحل المرشحين @break
                            @case('templates') قوالب المراسلة الذكية @break
                            @case('availability') خيارات الإتاحة للعمل @break
                            @case('leaves') أنواع وسياسات الإجازات @break
                            @case('alerts') تنبيهات العقود والإشعارات @break
                            @default {{ $activeTab }}
                        @endswitch
                    </span>
                @endif
            </div>

            <div style="display: flex; align-items: center; gap: 0.5rem;">
                @if($activeTab !== 'grid')
                    <button type="button" wire:click="backToGrid" class="btn-back-nav" style="padding: 0.4rem 0.85rem; font-size: 0.75rem;">
                        <x-filament::icon icon="heroicon-o-arrow-right" style="width: 0.95rem; height: 0.95rem;" />
                        <span>العودة للشبكة الرئيسية</span>
                    </button>
                @endif

                <div style="font-size: 0.75rem; color: #64748b; font-weight: 700; display: flex; align-items: center; gap: 0.35rem; padding-right: 0.5rem;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></span>
                    <span>النظام متزامن</span>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- LEVEL 1: UNIFIED FLAT GRID HUB                                    --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($activeTab === 'grid')

            {{-- ───────────────────────────────────────────────────────────── --}}
            {{-- Section 1: General & Organization                             --}}
            {{-- ───────────────────────────────────────────────────────────── --}}
            <div class="settings-section-header">
                <div class="settings-section-title-wrap">
                    <div class="settings-section-badge-icon" style="background: #f0fdfa; color: #0d9488;">
                        <x-filament::icon icon="heroicon-o-building-office-2" style="width: 1.35rem; height: 1.35rem;" />
                    </div>
                    <div>
                        <h3 class="settings-section-title">الإعدادات العامة والهيكل التنظيمي</h3>
                        <p class="settings-section-subtitle">إدارة فروع العمل، الأقسام، الهوية البصرية، وأوقات الدوام الرسمي</p>
                    </div>
                </div>
            </div>

            <div class="settings-flat-grid">
                <!-- Card 1: الأقسام -->
                <div class="settings-flat-card" wire:click="openSection('departments')">
                    <div>
                        <div class="settings-flat-icon-wrap" style="background: #f0fdfa; color: #0d9488;">
                            <x-filament::icon icon="heroicon-o-building-office" style="width: 1.7rem; height: 1.7rem;" />
                        </div>
                        <h4 class="settings-flat-title">الأقسام</h4>
                        <p class="settings-flat-desc">
                            الهيكل الإداري للشركة، توزيع الموظفين والمرشحين، ومسميات الإدارات وتنسيقها.
                        </p>
                    </div>
                    <div class="settings-flat-footer">
                        <span class="badge-pill" style="background: #ccfbf1; color: #0f766e;">{{ $allDepartmentsCount }} قسم مسجل</span>
                        <span class="settings-flat-action">إدارة الأقسام &larr;</span>
                    </div>
                </div>

                <!-- Card 2: المواقع -->
                <div class="settings-flat-card" wire:click="openSection('locations')">
                    <div>
                        <div class="settings-flat-icon-wrap" style="background: #eff6ff; color: #2563eb;">
                            <x-filament::icon icon="heroicon-o-map-pin" style="width: 1.7rem; height: 1.7rem;" />
                        </div>
                        <h4 class="settings-flat-title">المواقع</h4>
                        <p class="settings-flat-desc">
                            المقرات الرئيسية وفروع العمل الجغرافية لتحديد موقع الوظائف وسجلات الدوام.
                        </p>
                    </div>
                    <div class="settings-flat-footer">
                        <span class="badge-pill" style="background: #dbeafe; color: #1e40af;">{{ $allLocationsCount }} فرع وموقع</span>
                        <span class="settings-flat-action" style="color: #2563eb;">إدارة المواقع &larr;</span>
                    </div>
                </div>

                <!-- Card 3: إعدادات النظام -->
                <div class="settings-flat-card" wire:click="openSection('general')">
                    <div>
                        <div class="settings-flat-icon-wrap" style="background: #faf5ff; color: #9333ea;">
                            <x-filament::icon icon="heroicon-o-cog-6-tooth" style="width: 1.7rem; height: 1.7rem;" />
                        </div>
                        <h4 class="settings-flat-title">إعدادات النظام</h4>
                        <p class="settings-flat-desc">
                            اسم النظام، الشعار، العملة الافتراضية، المنطقة الزمنية وصيغة التاريخ وكلمة المرور.
                        </p>
                    </div>
                    <div class="settings-flat-footer">
                        <span class="badge-pill" style="background: #f3e8ff; color: #6b21a8;">{{ $currency }} ({{ $currency_symbol }})</span>
                        <span class="settings-flat-action" style="color: #9333ea;">ضبط النظام &larr;</span>
                    </div>
                </div>

                <!-- Card 4: مواعيد الدوام الرسمي -->
                <div class="settings-flat-card" wire:click="openSection('shifts')">
                    <div>
                        <div class="settings-flat-icon-wrap" style="background: #fffbeb; color: #d97706;">
                            <x-filament::icon icon="heroicon-o-clock" style="width: 1.7rem; height: 1.7rem;" />
                        </div>
                        <h4 class="settings-flat-title">مواعيد الدوام الرسمي</h4>
                        <p class="settings-flat-desc">
                            ساعات الحضور والانصراف الرسمية، دقائق سماح التأخير، وأيام العطلة الأسبوعية.
                        </p>
                    </div>
                    <div class="settings-flat-footer">
                        <span class="badge-pill" style="background: #fef3c7; color: #92400e;">{{ $work_start_time }} - {{ $work_end_time }}</span>
                        <span class="settings-flat-action" style="color: #d97706;">تعديل المواعيد &larr;</span>
                    </div>
                </div>
            </div>

            {{-- ───────────────────────────────────────────────────────────── --}}
            {{-- Section 2: Recruitment & Talent Pipeline                      --}}
            {{-- ───────────────────────────────────────────────────────────── --}}
            <div class="settings-section-header">
                <div class="settings-section-title-wrap">
                    <div class="settings-section-badge-icon" style="background: #eff6ff; color: #2563eb;">
                        <x-filament::icon icon="heroicon-o-funnel" style="width: 1.35rem; height: 1.35rem;" />
                    </div>
                    <div>
                        <h3 class="settings-section-title">إعدادات النظام ومسار التوظيف</h3>
                        <p class="settings-section-subtitle">إدارة قنوات ومراحل استقطاب الكفاءات، قوالب المراسلة، وخيارات الإتاحة</p>
                    </div>
                </div>
            </div>

            <div class="settings-flat-grid">
                <!-- Card 5: مصادر الترشيح -->
                <div class="settings-flat-card" wire:click="openSection('sources')">
                    <div>
                        <div class="settings-flat-icon-wrap" style="background: #fff7ed; color: #ea580c;">
                            <x-filament::icon icon="heroicon-o-arrow-trending-up" style="width: 1.7rem; height: 1.7rem;" />
                        </div>
                        <h4 class="settings-flat-title">مصادر الترشيح</h4>
                        <p class="settings-flat-desc">
                            قنوات استقطاب المواهب (LinkedIn، الموقع الإلكتروني، المعارض) لتتبع العائد والإنتاجية.
                        </p>
                    </div>
                    <div class="settings-flat-footer">
                        <span class="badge-pill" style="background: #ffedd5; color: #c2410c;">{{ $allSourcesCount }} مصدر نشط</span>
                        <span class="settings-flat-action" style="color: #ea580c;">إدارة المصادر &larr;</span>
                    </div>
                </div>

                <!-- Card 6: حالات المرشحين -->
                <div class="settings-flat-card" wire:click="openSection('statuses')">
                    <div>
                        <div class="settings-flat-icon-wrap" style="background: #fdf2f8; color: #db2777;">
                            <x-filament::icon icon="heroicon-o-funnel" style="width: 1.7rem; height: 1.7rem;" />
                        </div>
                        <h4 class="settings-flat-title">حالات المرشحين</h4>
                        <p class="settings-flat-desc">
                            مراحل مسار التوظيف وتدفق الطلبات مع تخصيص الألوان والشارة الافتراضية للتقديم.
                        </p>
                    </div>
                    <div class="settings-flat-footer">
                        <span class="badge-pill" style="background: #fce7f3; color: #9d174d;">{{ $allStatusesCount }} حالة للمسار</span>
                        <span class="settings-flat-action" style="color: #db2777;">تخصيص الحالات &larr;</span>
                    </div>
                </div>

                <!-- Card 7: قوالب المراسلة -->
                <div class="settings-flat-card" wire:click="openSection('templates')">
                    <div>
                        <div class="settings-flat-icon-wrap" style="background: #f0fdf4; color: #16a34a;">
                            <x-filament::icon icon="heroicon-o-chat-bubble-left-right" style="width: 1.7rem; height: 1.7rem;" />
                        </div>
                        <h4 class="settings-flat-title">قوالب المراسلة</h4>
                        <p class="settings-flat-desc">
                            رسائل الواتساب والإيميل التلقائية للمقابلات والعروض مع المتغيرات الذكية الفورية.
                        </p>
                    </div>
                    <div class="settings-flat-footer">
                        <span class="badge-pill" style="background: #dcfce7; color: #15803d;">واتساب & إيميل مخصص</span>
                        <span class="settings-flat-action" style="color: #16a34a;">تعديل القوالب &larr;</span>
                    </div>
                </div>

                <!-- Card 8: خيارات الإتاحة -->
                <div class="settings-flat-card" wire:click="openSection('availability')">
                    <div>
                        <div class="settings-flat-icon-wrap" style="background: #fefce8; color: #ca8a04;">
                            <x-filament::icon icon="heroicon-o-calendar-days" style="width: 1.7rem; height: 1.7rem;" />
                        </div>
                        <h4 class="settings-flat-title">خيارات الإتاحة</h4>
                        <p class="settings-flat-desc">
                            فترات الإشعار والجاهزية للبدء بالعمل المتاحة للمرشحين في نماذج التقديم والتوظيف.
                        </p>
                    </div>
                    <div class="settings-flat-footer">
                        <span class="badge-pill" style="background: #fef9c3; color: #854d0e;">{{ $allAvailCount }} خيارات إتاحة</span>
                        <span class="settings-flat-action" style="color: #ca8a04;">إدارة الخيارات &larr;</span>
                    </div>
                </div>
            </div>

            {{-- ───────────────────────────────────────────────────────────── --}}
            {{-- Section 3: HR Policies & Notifications                        --}}
            {{-- ───────────────────────────────────────────────────────────── --}}
            <div class="settings-section-header">
                <div class="settings-section-title-wrap">
                    <div class="settings-section-badge-icon" style="background: #fdf4ff; color: #c026d3;">
                        <x-filament::icon icon="heroicon-o-shield-check" style="width: 1.35rem; height: 1.35rem;" />
                    </div>
                    <div>
                        <h3 class="settings-section-title">سياسات الموارد البشرية والتنبيهات</h3>
                        <p class="settings-section-subtitle">أنواع وأرصدة الإجازات السنوية، وسياسات التنبيه المبكر لانتهاء العقود</p>
                    </div>
                </div>
            </div>

            <div class="settings-flat-grid">
                <!-- Card 9: سياسات الإجازات -->
                <div class="settings-flat-card" wire:click="openSection('leaves')">
                    <div>
                        <div class="settings-flat-icon-wrap" style="background: #fdf4ff; color: #c026d3;">
                            <x-filament::icon icon="heroicon-o-calendar" style="width: 1.7rem; height: 1.7rem;" />
                        </div>
                        <h4 class="settings-flat-title">أنواع وسياسات الإجازات</h4>
                        <p class="settings-flat-desc">
                            إدارة أنواع الإجازات (سنوية، مرضية، عارضة)، الرصيد السنوي وسياسات الموافقة المسبقة.
                        </p>
                    </div>
                    <div class="settings-flat-footer">
                        <span class="badge-pill" style="background: #fae8ff; color: #86198f;">{{ $allLeaveTypesCount }} نوع معتمد</span>
                        <span class="settings-flat-action" style="color: #c026d3;">إدارة الإجازات &larr;</span>
                    </div>
                </div>

                <!-- Card 10: تنبيهات العقود والإشعارات -->
                <div class="settings-flat-card" wire:click="openSection('alerts')">
                    <div>
                        <div class="settings-flat-icon-wrap" style="background: #fff1f2; color: #e11d48;">
                            <x-filament::icon icon="heroicon-o-bell-alert" style="width: 1.7rem; height: 1.7rem;" />
                        </div>
                        <h4 class="settings-flat-title">تنبيهات العقود والإشعارات</h4>
                        <p class="settings-flat-desc">
                            أيام الإنذار المبكر قبل انتهاء عقود الموظفين، وإشعارات وصول طلبات التوظيف الجديدة.
                        </p>
                    </div>
                    <div class="settings-flat-footer">
                        <span class="badge-pill" style="background: #ffe4e6; color: #9f1239;">قبل {{ $alert_contract_expiry_days }} يوم</span>
                        <span class="settings-flat-action" style="color: #e11d48;">ضبط التنبيهات &larr;</span>
                    </div>
                </div>
            </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- LEVEL 2: DEPARTMENTS DETAIL VIEW                                  --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'departments')
            <div class="settings-detail-panel">
                <div class="settings-panel-header">
                    <div>
                        <button type="button" wire:click="backToGrid" class="btn-back-nav">
                            <x-filament::icon icon="heroicon-o-arrow-right" style="width: 0.95rem; height: 0.95rem;" />
                            <span>العودة لمركز الإعدادات</span>
                        </button>
                        <h2 style="font-size: 1.25rem; font-weight: 900; color: #0f172a; margin: 0.75rem 0 0.25rem 0;">إدارة الأقسام والوحدات الإدارية</h2>
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0;">إضافة وتعديل الأقسام وتتبع عدد الموظفين والمرشحين المرتبطين بكل قسم.</p>
                    </div>

                    <div style="width: 260px;">
                        <input type="text" wire:model.live.debounce.250ms="searchQuery" placeholder="بحث في الأقسام..." class="form-input" />
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <!-- Form Box -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem;">
                        <h3 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0 0 1rem 0;">
                            {{ $editing_dept_id ? 'تعديل بيانات القسم' : 'إضافة قسم جديد' }}
                        </h3>

                        <form wire:submit="{{ $editing_dept_id ? 'updateDepartment' : 'createDepartment' }}">
                            <div style="margin-bottom: 1rem;">
                                <label class="form-label">اسم القسم الرسمي</label>
                                <input type="text" wire:model="dept_name" placeholder="مثال: إدارة تقنية المعلومات" class="form-input" required />
                                @error('dept_name') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <div style="display: flex; gap: 0.5rem;">
                                <button type="submit" class="btn-action-primary">
                                    <x-filament::icon icon="heroicon-o-check" style="width: 1rem; height: 1rem;" />
                                    <span>{{ $editing_dept_id ? 'حفظ التعديلات' : 'إضافة القسم' }}</span>
                                </button>

                                @if($editing_dept_id)
                                    <button type="button" wire:click="cancelEditDepartment" class="btn-action-secondary">إلغاء</button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Table Box -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; overflow: hidden;">
                        <table class="crud-table">
                            <thead>
                                <tr>
                                    <th>اسم القسم</th>
                                    <th>المرشحون</th>
                                    <th>الموظفون</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departments as $dept)
                                    <tr>
                                        <td style="font-weight: 700;">{{ $dept->name }}</td>
                                        <td><span class="badge-pill" style="background: #e0f2fe; color: #0369a1;">{{ $dept->talents_count }}</span></td>
                                        <td><span class="badge-pill" style="background: #ecfdf5; color: #047857;">{{ $dept->employees_count }}</span></td>
                                        <td>
                                            <div style="display: flex; gap: 0.5rem;">
                                                <button type="button" wire:click="editDepartment({{ $dept->id }})" style="color: #0d9488; background: none; border: none; font-weight: 700; cursor: pointer;">تعديل</button>
                                                <button type="button" wire:click="deleteDepartment({{ $dept->id }})" wire:confirm="هل أنت متأكد من رغبتك في حذف هذا القسم؟" style="color: #dc2626; background: none; border: none; font-weight: 700; cursor: pointer;">حذف</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 2rem; color: #94a3b8;">لا توجد أقسام مسجلة حالياً مطابقة للبحث.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- LEVEL 2: LOCATIONS DETAIL VIEW                                    --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'locations')
            <div class="settings-detail-panel">
                <div class="settings-panel-header">
                    <div>
                        <button type="button" wire:click="backToGrid" class="btn-back-nav">
                            <x-filament::icon icon="heroicon-o-arrow-right" style="width: 0.95rem; height: 0.95rem;" />
                            <span>العودة لمركز الإعدادات</span>
                        </button>
                        <h2 style="font-size: 1.25rem; font-weight: 900; color: #0f172a; margin: 0.75rem 0 0.25rem 0;">إدارة المواقع والفروع الجغرافية</h2>
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0;">إدارة الفروع ومواقع العمل للربط مع الشواغر الوظيفية وسجلات الحضور.</p>
                    </div>

                    <div style="width: 260px;">
                        <input type="text" wire:model.live.debounce.250ms="searchQuery" placeholder="بحث في المواقع..." class="form-input" />
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <!-- Form Box -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem;">
                        <h3 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0 0 1rem 0;">
                            {{ $editing_loc_id ? 'تعديل بيانات الموقع' : 'إضافة موقع / فرع جديد' }}
                        </h3>

                        <form wire:submit="{{ $editing_loc_id ? 'updateLocation' : 'createLocation' }}">
                            <div style="margin-bottom: 1rem;">
                                <label class="form-label">اسم الفرع / المدينة</label>
                                <input type="text" wire:model="loc_name" placeholder="مثال: الفرع الرئيسي - الرياض" class="form-input" required />
                                @error('loc_name') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <div style="display: flex; gap: 0.5rem;">
                                <button type="submit" class="btn-action-primary">
                                    <x-filament::icon icon="heroicon-o-check" style="width: 1rem; height: 1rem;" />
                                    <span>{{ $editing_loc_id ? 'حفظ التعديلات' : 'إضافة الموقع' }}</span>
                                </button>

                                @if($editing_loc_id)
                                    <button type="button" wire:click="cancelEditLocation" class="btn-action-secondary">إلغاء</button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Table Box -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; overflow: hidden;">
                        <table class="crud-table">
                            <thead>
                                <tr>
                                    <th>اسم الموقع / الفرع</th>
                                    <th>عدد المرشحين</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($locations as $loc)
                                    <tr>
                                        <td style="font-weight: 700;">{{ $loc->name }}</td>
                                        <td><span class="badge-pill" style="background: #e0f2fe; color: #0369a1;">{{ $loc->talents_count }}</span></td>
                                        <td>
                                            <div style="display: flex; gap: 0.5rem;">
                                                <button type="button" wire:click="editLocation({{ $loc->id }})" style="color: #0d9488; background: none; border: none; font-weight: 700; cursor: pointer;">تعديل</button>
                                                <button type="button" wire:click="deleteLocation({{ $loc->id }})" wire:confirm="هل أنت متأكد من حذف هذا الموقع؟" style="color: #dc2626; background: none; border: none; font-weight: 700; cursor: pointer;">حذف</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" style="text-align: center; padding: 2rem; color: #94a3b8;">لا توجد مواقع مسجلة حالياً.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- LEVEL 2: GENERAL SYSTEM SETTINGS DETAIL VIEW                      --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'general')
            <div class="settings-detail-panel">
                <div class="settings-panel-header">
                    <div>
                        <button type="button" wire:click="backToGrid" class="btn-back-nav">
                            <x-filament::icon icon="heroicon-o-arrow-right" style="width: 0.95rem; height: 0.95rem;" />
                            <span>العودة لمركز الإعدادات</span>
                        </button>
                        <h2 style="font-size: 1.25rem; font-weight: 900; color: #0f172a; margin: 0.75rem 0 0.25rem 0;">إعدادات النظام العامة والهوية البصرية</h2>
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0;">التحكم في العملة الافتراضية، المنطقة الزمنية، صيغ التاريخ، اسم وهوية النظام، وشعار الشركة.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.75rem; align-items: start;">
                    
                    <!-- Core System & Regional Settings Form -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1.15rem; padding: 1.5rem;">
                        <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0 0 1.25rem 0; display: flex; align-items: center; gap: 0.5rem;">
                            <x-filament::icon icon="heroicon-o-globe-alt" style="width: 1.25rem; height: 1.25rem; color: #0d9488;" />
                            <span>بيانات الهوية والمنطقة والعملة</span>
                        </h3>

                        <form wire:submit="saveGeneralSettings">
                            <!-- Brand Name -->
                            <div style="margin-bottom: 1.15rem;">
                                <label class="form-label">اسم النظام / الشركة الرسمي</label>
                                <input type="text" wire:model="brand_name" class="form-input" required />
                                @error('brand_name') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <!-- Currency & Currency Symbol -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1.15rem;">
                                <div>
                                    <label class="form-label">العملة الافتراضية</label>
                                    <select wire:model.live="currency" class="form-input" style="cursor: pointer;">
                                        <option value="EGP">EGP - الجنيه المصري</option>
                                        <option value="SAR">SAR - الريال السعودي</option>
                                        <option value="AED">AED - الدرهم الإماراتي</option>
                                        <option value="USD">USD - الدولار الأمريكي</option>
                                        <option value="EUR">EUR - اليورو</option>
                                        <option value="KWD">KWD - الدينار الكويتي</option>
                                        <option value="QAR">QAR - الريال القطري</option>
                                        <option value="BHD">BHD - الدينار البحريني</option>
                                        <option value="OMR">OMR - الريال العماني</option>
                                        <option value="JOD">JOD - الدينار الأردني</option>
                                    </select>
                                    @error('currency') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="form-label">رمز العملة المختصر</label>
                                    <input type="text" wire:model="currency_symbol" placeholder="مثال: ج.م أو ر.س أو $" class="form-input" required />
                                    @error('currency_symbol') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Timezone & Date Format -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1.15rem;">
                                <div>
                                    <label class="form-label">المنطقة الزمنية (التوقيت)</label>
                                    <select wire:model="timezone" class="form-input" style="cursor: pointer;">
                                        <option value="Africa/Cairo">Africa/Cairo (توقيت القاهرة)</option>
                                        <option value="Asia/Riyadh">Asia/Riyadh (توقيت مكة والرياض)</option>
                                        <option value="Asia/Dubai">Asia/Dubai (توقيت الإمارات ودبي)</option>
                                        <option value="Asia/Kuwait">Asia/Kuwait (توقيت الكويت)</option>
                                        <option value="Asia/Qatar">Asia/Qatar (توقيت الدوحة)</option>
                                        <option value="Asia/Amman">Asia/Amman (توقيت عمّان)</option>
                                        <option value="Europe/London">Europe/London (توقيت لندن GMT)</option>
                                        <option value="UTC">UTC (التوقيت العالمي الموحد)</option>
                                    </select>
                                    @error('timezone') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="form-label">صيغة عرض التاريخ</label>
                                    <select wire:model="date_format" class="form-input" style="cursor: pointer;">
                                        <option value="Y-m-d">سنة-شهر-يوم ({{ date('Y-m-d') }})</option>
                                        <option value="d/m/Y">يوم/شهر/سنة ({{ date('d/m/Y') }})</option>
                                        <option value="d F Y">يوم اسم_الشهر سنة ({{ date('d M Y') }})</option>
                                    </select>
                                    @error('date_format') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Dashboard Identity Color & Presets -->
                            <div style="margin-bottom: 1.5rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.875rem; padding: 1.15rem;">
                                <label class="form-label" style="display: flex; align-items: center; justify-content: space-between;">
                                    <span style="font-weight: 800; color: #0f172a;">لون الهوية الأساسي للداش بورد (Primary Brand Color)</span>
                                    <span style="font-family: monospace; font-size: 0.8rem; font-weight: 700; color: {{ $brand_primary_color }}; background: #f1f5f9; padding: 0.15rem 0.5rem; border-radius: 0.35rem;">{{ $brand_primary_color }}</span>
                                </label>
                                
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.5rem; margin-bottom: 0.85rem;">
                                    <input type="color" wire:model.live="brand_primary_color" style="width: 3.25rem; height: 2.5rem; border-radius: 0.5rem; border: 1px solid #cbd5e1; cursor: pointer; padding: 2px;" />
                                    <input type="text" wire:model.live="brand_primary_color" class="form-input" style="font-family: monospace; max-width: 140px;" placeholder="#0d9488" />
                                    <span style="font-size: 0.75rem; color: #64748b;">ينعكس فورياً على الأزرار والشارات والقائمة والعناصر النشطة.</span>
                                </div>

                                <!-- Preset Quick Palettes -->
                                <div>
                                    <span style="display: block; font-size: 0.725rem; font-weight: 700; color: #64748b; margin-bottom: 0.45rem;">لوحات ألوان احترافية جاهزة للاختيار السريع:</span>
                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 0.5rem;">
                                        @php
                                            $colorPresets = [
                                                ['name' => 'تيل زمردي', 'color' => '#0d9488'],
                                                ['name' => 'أزرق ملكي', 'color' => '#2563eb'],
                                                ['name' => 'نيلي حديث', 'color' => '#4f46e5'],
                                                ['name' => 'أخضر احترافي', 'color' => '#059669'],
                                                ['name' => 'بنفسجي فاخر', 'color' => '#7c3aed'],
                                                ['name' => 'ياقوتي جذاب', 'color' => '#e11d48'],
                                                ['name' => 'كهرماني دافئ', 'color' => '#d97706'],
                                                ['name' => 'أردوازي تقني', 'color' => '#475569'],
                                            ];
                                        @endphp
                                        @foreach($colorPresets as $cp)
                                            <button type="button" 
                                                    wire:click="setPrimaryColor('{{ $cp['color'] }}')"
                                                    style="display: flex; align-items: center; gap: 0.45rem; padding: 0.45rem 0.65rem; border-radius: 0.5rem; border: {{ strtolower($brand_primary_color) === strtolower($cp['color']) ? '2px solid ' . $cp['color'] : '1px solid #e2e8f0' }}; background: {{ strtolower($brand_primary_color) === strtolower($cp['color']) ? '#f8fafc' : '#ffffff' }}; cursor: pointer; transition: all 0.15s ease;">
                                                <span style="width: 0.9rem; height: 0.9rem; border-radius: 50%; background: {{ $cp['color'] }}; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.15);"></span>
                                                <span style="font-size: 0.725rem; font-weight: 700; color: #334155;">{{ $cp['name'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                @error('brand_primary_color') <span style="color: #dc2626; font-size: 0.75rem; display: block; margin-top: 0.35rem;">{{ $message }}</span> @enderror
                            </div>

                            <!-- Dashboard Font Family Customization -->
                            <div style="margin-bottom: 1.5rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.875rem; padding: 1.15rem;">
                                <label class="form-label" style="font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">
                                    اسم ونوع الخط للداش بورد (Dashboard Typography / Font)
                                </label>
                                
                                <select wire:model.live="font_family" class="form-input" style="cursor: pointer; font-size: 0.9rem; font-weight: 700;">
                                    <option value="Cairo">خط القاهرة (Cairo) — الخط العربي الافتراضي الأكثر عصرية ووضوحاً</option>
                                    <option value="Tajawal">خط تجوال (Tajawal) — خط عربي انسيابي حديث وخفيف</option>
                                    <option value="IBM Plex Sans Arabic">خط آي بي إم بلكس (IBM Plex Sans Arabic) — عالي الدقة والاحترافية المؤسسية</option>
                                    <option value="Almarai">خط المراعي (Almarai) — خط متزن ورسمي ملائم للتقارير والأعمال</option>
                                    <option value="Readex Pro">خط ريديكس برو (Readex Pro) — خط هندسي عصري بتصميم تقني أنيق</option>
                                    <option value="Alexandria">خط الإسكندرية (Alexandria) — خط هندسي دقيق مستوحى من الخط الكوفي</option>
                                    <option value="Noto Sans Arabic">خط نوتو العربي (Noto Sans Arabic) — خط كلاسيكي فائق القراءة</option>
                                </select>
                                
                                <!-- Live Font Sample Preview -->
                                <div style="margin-top: 0.75rem; padding: 0.75rem 1rem; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 0.65rem;">
                                    <span style="display: block; font-size: 0.7rem; font-weight: 700; color: #64748b; margin-bottom: 0.25rem;">معاينة حية لشكل النص بالخط المحدد ({{ $font_family }}):</span>
                                    <p style="font-family: '{{ $font_family }}', sans-serif; font-size: 0.95rem; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.6;">
                                        نظام إدارة الموارد البشرية والرواتب — سهولة وسرعة في تسجيل الحضور والانصراف، إدارة الموظفين، واستقطاب الكفاءات.
                                    </p>
                                </div>
                                @error('font_family') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <!-- Sidebar Appearance & Distinct Color Themes -->
                            <div style="margin-bottom: 1.5rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.875rem; padding: 1.15rem;">
                                <label class="form-label" style="font-weight: 800; color: #0f172a; margin-bottom: 0.35rem;">
                                    ستايل ولون القائمة الجانبية (Sidebar Design & Color Theme)
                                </label>
                                <span style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 0.85rem;">
                                    اختر المظهر اللوني المفضل للقائمة الجانبية لإعطائها طابعاً بصرياً فريداً ومميزاً عن خلفية المحتوى.
                                </span>

                                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.75rem;">
                                    @php
                                        $sidebarThemes = [
                                            [
                                                'id' => 'dark_navy',
                                                'title' => 'كحلي داكن فاخر',
                                                'desc' => 'المظهر العصري الموصى به (Midnight Slate)',
                                                'bg' => 'linear-gradient(135deg, #090e1a 0%, #0f172a 100%)',
                                                'border' => '#1e293b',
                                                'tag' => 'موصى به',
                                            ],
                                            [
                                                'id' => 'deep_black',
                                                'title' => 'أسود ملكي فاخر',
                                                'desc' => 'داكن كلاسيكي فاحم (Obsidian)',
                                                'bg' => '#09090b',
                                                'border' => '#27272a',
                                                'tag' => null,
                                            ],
                                            [
                                                'id' => 'brand_tinted',
                                                'title' => 'متناغم مع لون الهوية',
                                                'desc' => 'يتدرج مع اللون الأساسي المختار',
                                                'bg' => 'linear-gradient(135deg, ' . $brand_primary_color . ' 0%, #0a0e1a 80%)',
                                                'border' => $brand_primary_color,
                                                'tag' => 'ديناميكي',
                                            ],
                                            [
                                                'id' => 'slate_gray',
                                                'title' => 'رمادي أردوازي أنيق',
                                                'desc' => 'مظهر هادئ وأنيق (Slate Gray)',
                                                'bg' => '#1e293b',
                                                'border' => '#334155',
                                                'tag' => null,
                                            ],
                                            [
                                                'id' => 'light_clean',
                                                'title' => 'أبيض عصري ناصع',
                                                'desc' => 'خلفية بيضاء ناصعة لمن يفضل الفاتح',
                                                'bg' => '#ffffff',
                                                'border' => '#e2e8f0',
                                                'tag' => null,
                                            ],
                                        ];
                                    @endphp

                                    @foreach($sidebarThemes as $st)
                                        <button type="button"
                                                wire:click="setSidebarTheme('{{ $st['id'] }}')"
                                                style="text-align: right; border-radius: 0.75rem; border: {{ $sidebar_theme === $st['id'] ? '2px solid ' . $brand_primary_color : '1px solid #e2e8f0' }}; background: #ffffff; padding: 0.85rem; cursor: pointer; transition: all 0.15s ease; box-shadow: {{ $sidebar_theme === $st['id'] ? '0 4px 12px rgba(0,0,0,0.08)' : 'none' }}; display: flex; flex-direction: column; justify-content: space-between;">
                                            <div>
                                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.65rem;">
                                                    <div style="width: 2.25rem; height: 1.5rem; border-radius: 0.375rem; background: {{ $st['bg'] }}; border: 1px solid {{ $st['border'] }}; box-shadow: inset 0 1px 2px rgba(0,0,0,0.2);"></div>
                                                    
                                                    @if($st['tag'])
                                                        <span style="font-size: 0.65rem; font-weight: 800; padding: 0.1rem 0.4rem; border-radius: 9999px; background: #e0f2fe; color: #0284c7;">{{ $st['tag'] }}</span>
                                                    @endif
                                                </div>

                                                <span style="display: block; font-size: 0.825rem; font-weight: 800; color: {{ $sidebar_theme === $st['id'] ? $brand_primary_color : '#0f172a' }};">
                                                    {{ $st['title'] }}
                                                </span>
                                                <span style="display: block; font-size: 0.7rem; color: #64748b; margin-top: 0.2rem; line-height: 1.3;">
                                                    {{ $st['desc'] }}
                                                </span>
                                            </div>

                                            <div style="margin-top: 0.65rem; display: flex; align-items: center; gap: 0.35rem; font-size: 0.725rem; font-weight: 700; color: {{ $sidebar_theme === $st['id'] ? $brand_primary_color : '#94a3b8' }};">
                                                <span>{{ $sidebar_theme === $st['id'] ? '✓ المظهر المختار' : 'اختيار' }}</span>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                                @error('sidebar_theme') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <!-- Logo Upload -->
                            <div style="margin-bottom: 1.5rem; padding: 1rem; background: #ffffff; border: 1px dashed #cbd5e1; border-radius: 0.75rem;">
                                <label class="form-label">شعار الشركة (Logo)</label>
                                
                                @if($existing_logo)
                                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.85rem;">
                                        <img src="{{ asset('storage/' . $existing_logo) }}" alt="Logo" style="max-height: 45px; border-radius: 0.35rem; border: 1px solid #e2e8f0; padding: 4px; background: #ffffff;" />
                                        <button type="button" wire:click="removeLogo" style="color: #dc2626; font-size: 0.775rem; font-weight: 700; background: none; border: none; cursor: pointer;">حذف الشعار الحالي</button>
                                    </div>
                                @endif

                                <input type="file" wire:model="new_logo" accept="image/*" class="form-input" style="background: #ffffff;" />
                                <span style="display: block; font-size: 0.7rem; color: #94a3b8; margin-top: 0.35rem;">الصيغ المدعومة: PNG, JPG, SVG بحد أقصى 2MB.</span>
                                @error('new_logo') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit" class="btn-action-primary" style="width: 100%; justify-content: center;">
                                <x-filament::icon icon="heroicon-o-check" style="width: 1.1rem; height: 1.1rem;" />
                                <span>حفظ وتطبيق إعدادات النظام</span>
                            </button>
                        </form>
                    </div>

                    <!-- Security & Password Update Form -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1.15rem; padding: 1.5rem;">
                        <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0 0 1.25rem 0; display: flex; align-items: center; gap: 0.5rem;">
                            <x-filament::icon icon="heroicon-o-shield-check" style="width: 1.25rem; height: 1.25rem; color: #eab308;" />
                            <span>تحديث أمان الحساب وكلمة المرور</span>
                        </h3>

                        <form wire:submit="savePassword">
                            <div style="margin-bottom: 1.15rem;">
                                <label class="form-label">كلمة المرور الحالية</label>
                                <input type="password" wire:model="current_password" class="form-input" required />
                                @error('current_password') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <div style="margin-bottom: 1.15rem;">
                                <label class="form-label">كلمة المرور الجديدة</label>
                                <input type="password" wire:model="new_password" class="form-input" required />
                                @error('new_password') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                                <input type="password" wire:model="new_password_confirmation" class="form-input" required />
                                @error('new_password_confirmation') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit" class="btn-action-primary" style="background: #eab308; width: 100%; justify-content: center;">
                                <x-filament::icon icon="heroicon-o-lock-closed" style="width: 1.1rem; height: 1.1rem;" />
                                <span>تحديث كلمة المرور</span>
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- LEVEL 2: WORKING HOURS & SHIFTS DETAIL VIEW                       --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'shifts')
            <div class="settings-detail-panel">
                <div class="settings-panel-header">
                    <div>
                        <button type="button" wire:click="backToGrid" class="btn-back-nav">
                            <x-filament::icon icon="heroicon-o-arrow-right" style="width: 0.95rem; height: 0.95rem;" />
                            <span>العودة لمركز الإعدادات</span>
                        </button>
                        <h2 style="font-size: 1.25rem; font-weight: 900; color: #0f172a; margin: 0.75rem 0 0.25rem 0;">مواعيد وساعات الدوام الرسمي وسماح التأخير</h2>
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0;">تحديد وقت بدء وانتهاء الوردية الرسمية، فترة السماح قبل تسجيل التأخير، وأيام العطلة الأسبوعية.</p>
                    </div>
                </div>

                <div style="max-width: 760px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1.15rem; padding: 1.75rem;">
                    <form wire:submit="saveShiftSettings">
                        
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                            <!-- Work Start Time -->
                            <div>
                                <label class="form-label">وقت بدء الدوام الرسمي</label>
                                <input type="time" wire:model="work_start_time" class="form-input" required />
                                @error('work_start_time') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <!-- Work End Time -->
                            <div>
                                <label class="form-label">وقت انتهاء الدوام الرسمي</label>
                                <input type="time" wire:model="work_end_time" class="form-input" required />
                                @error('work_end_time') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <!-- Grace Period Minutes -->
                            <div>
                                <label class="form-label">فترة السماح بالتأخير (بالدقائق)</label>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="number" wire:model="grace_period_minutes" min="0" max="120" class="form-input" required />
                                    <span style="font-size: 0.85rem; color: #64748b; white-space: nowrap;">دقيقة</span>
                                </div>
                                <span style="font-size: 0.7rem; color: #94a3b8; display: block; margin-top: 0.25rem;">مثال: 15 دقيقة بعد وقت بدء الدوام</span>
                                @error('grace_period_minutes') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Weekend Days Selection -->
                        <div style="margin-bottom: 1.5rem; padding: 1.25rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.85rem;">
                            <label class="form-label" style="margin-bottom: 0.75rem;">أيام العطلة الأسبوعية (الإجازة الرسمية)</label>
                            
                            <div style="display: flex; flex-wrap: wrap; gap: 1.25rem;">
                                @php
                                    $daysMap = [
                                        'sunday'    => 'الأحد',
                                        'monday'    => 'الإثنين',
                                        'tuesday'   => 'الثلاثاء',
                                        'wednesday' => 'الأربعاء',
                                        'thursday'  => 'الخميس',
                                        'friday'    => 'الجمعة',
                                        'saturday'  => 'السبت',
                                    ];
                                @endphp

                                @foreach($daysMap as $dayKey => $dayLabel)
                                    <label style="display: inline-flex; align-items: center; gap: 0.45rem; font-size: 0.85rem; font-weight: 700; color: #334155; cursor: pointer;">
                                        <input type="checkbox" value="{{ $dayKey }}" wire:model="weekend_days" style="width: 1.1rem; height: 1.1rem; accent-color: #0d9488;" />
                                        <span>{{ $dayLabel }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- GPS Geofencing Section -->
                        <div style="margin: 1.5rem 0; padding: 1.25rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem;">
                            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 1rem;">
                                <div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <x-filament::icon icon="heroicon-o-map-pin" style="width: 1.25rem; height: 1.25rem; color: #0d9488;" />
                                        <label for="enable_gps_clockin_chk" style="font-size: 0.95rem; font-weight: 800; color: #0f172a; cursor: pointer;">
                                            تفعيل التحقق من الموقع الجغرافي للبصمة (GPS Geo-Fencing)
                                        </label>
                                    </div>
                                    <p style="font-size: 0.775rem; color: #64748b; margin: 0.35rem 0 0 0; line-height: 1.5;">
                                        عند التفعيل، يشترط النظام تواجد الموظف داخل النطاق الجغرافي المسموح به لمقر الشركة لتسجيل الحضور. في حال التعطيل (أو إذا كان الموظف يعمل عن بُعد)، يُسمح بالبصمة من أي مكان.
                                    </p>
                                </div>
                                <input type="checkbox" id="enable_gps_clockin_chk" wire:model.live="enable_gps_clockin" style="width: 1.4rem; height: 1.4rem; accent-color: #0d9488; cursor: pointer; flex-shrink: 0;" />
                            </div>

                            @if($enable_gps_clockin)
                                <div style="padding-top: 1rem; border-top: 1px dashed #cbd5e1; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                                    <div>
                                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.35rem;">خط العرض (Latitude)</label>
                                        <input type="text" wire:model="company_latitude" class="settings-input" placeholder="24.713600" />
                                    </div>
                                    <div>
                                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.35rem;">خط الطول (Longitude)</label>
                                        <input type="text" wire:model="company_longitude" class="settings-input" placeholder="46.675300" />
                                    </div>
                                    <div>
                                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.35rem;">نطاق السماح بالمتر (Radius)</label>
                                        <select wire:model="company_geofence_radius" class="settings-input">
                                            <option value="100">100 متر (مقر صغير)</option>
                                            <option value="200">200 متر (مبنى عادي - موصى به)</option>
                                            <option value="350">350 متر (مجمع شركات / مبنى متعدد)</option>
                                            <option value="500">500 متر (منطقة عمل واسعة)</option>
                                            <option value="1000">1000 متر (1 كم - نطاق مفتوح)</option>
                                        </select>
                                    </div>
                                </div>

                                <div style="margin-top: 0.85rem; display: flex; align-items: center; justify-content: flex-end;">
                                    <button type="button" 
                                            onclick="if(navigator.geolocation){navigator.geolocation.getCurrentPosition(function(p){@this.setCompanyLocation(p.coords.latitude, p.coords.longitude);}, function(e){alert('تعذر الوصول للموقع الجغرافي: ' + e.message);})}else{alert('المتصفح لا يدعم Geolocation');}" 
                                            class="btn-action-outline" 
                                            style="padding: 0.45rem 1rem; font-size: 0.775rem;">
                                        <x-filament::icon icon="heroicon-o-crosshair" style="width: 0.95rem; height: 0.95rem;" />
                                        <span>التقاط موقعي الحالي كمقر للشركة</span>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Allow Remote Clock-in Fallback Note -->
                        <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.65rem;">
                            <input type="checkbox" id="remote_clockin_chk" wire:model="allow_remote_clock_in" style="width: 1.15rem; height: 1.15rem; accent-color: #0d9488;" />
                            <label for="remote_clockin_chk" style="font-size: 0.85rem; font-weight: 700; color: #1e293b; cursor: pointer;">
                                السماح بالعمل عن بُعد للموظفين المحددين في ملفاتهم كـ (موظف عن بُعد)
                            </label>
                        </div>

                        <button type="submit" class="btn-action-primary" style="padding: 0.75rem 2rem;">
                            <x-filament::icon icon="heroicon-o-check" style="width: 1.1rem; height: 1.1rem;" />
                            <span>حفظ إعدادات وساعات الدوام ونطاق الـ GPS</span>
                        </button>
                    </form>
                </div>
            </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- LEVEL 2: SOURCES DETAIL VIEW                                      --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'sources')
            <div class="settings-detail-panel">
                <div class="settings-panel-header">
                    <div>
                        <button type="button" wire:click="backToGrid" class="btn-back-nav">
                            <x-filament::icon icon="heroicon-o-arrow-right" style="width: 0.95rem; height: 0.95rem;" />
                            <span>العودة لمركز الإعدادات</span>
                        </button>
                        <h2 style="font-size: 1.25rem; font-weight: 900; color: #0f172a; margin: 0.75rem 0 0.25rem 0;">إدارة مصادر الترشيح واستقطاب المواهب</h2>
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0;">تتبع القنوات التي يأتي منها المرشحون لتحليل العائد على قنوات التوظيف.</p>
                    </div>

                    <div style="width: 260px;">
                        <input type="text" wire:model.live.debounce.250ms="searchQuery" placeholder="بحث في المصادر..." class="form-input" />
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <!-- Form Box -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem;">
                        <h3 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0 0 1rem 0;">
                            {{ $editing_source_id ? 'تعديل بيانات المصدر' : 'إضافة مصدر ترشيح جديد' }}
                        </h3>

                        <form wire:submit="{{ $editing_source_id ? 'updateSource' : 'createSource' }}">
                            <div style="margin-bottom: 1rem;">
                                <label class="form-label">اسم المصدر / القناة</label>
                                <input type="text" wire:model="source_name" placeholder="مثال: لينكد إن (LinkedIn)" class="form-input" required />
                                @error('source_name') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <div style="display: flex; gap: 0.5rem;">
                                <button type="submit" class="btn-action-primary">
                                    <x-filament::icon icon="heroicon-o-check" style="width: 1rem; height: 1rem;" />
                                    <span>{{ $editing_source_id ? 'حفظ التعديلات' : 'إضافة المصدر' }}</span>
                                </button>

                                @if($editing_source_id)
                                    <button type="button" wire:click="cancelEditSource" class="btn-action-secondary">إلغاء</button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Table Box -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; overflow: hidden;">
                        <table class="crud-table">
                            <thead>
                                <tr>
                                    <th>اسم مصدر الترشيح</th>
                                    <th>عدد المرشحين المستقطبين</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sources as $src)
                                    <tr>
                                        <td style="font-weight: 700;">{{ $src->name }}</td>
                                        <td><span class="badge-pill" style="background: #fed7aa; color: #9a3412;">{{ $src->talents_count }} مرشح</span></td>
                                        <td>
                                            <div style="display: flex; gap: 0.5rem;">
                                                <button type="button" wire:click="editSource({{ $src->id }})" style="color: #0d9488; background: none; border: none; font-weight: 700; cursor: pointer;">تعديل</button>
                                                <button type="button" wire:click="deleteSource({{ $src->id }})" wire:confirm="هل أنت متأكد من حذف هذا المصدر؟" style="color: #dc2626; background: none; border: none; font-weight: 700; cursor: pointer;">حذف</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" style="text-align: center; padding: 2rem; color: #94a3b8;">لا توجد مصادر ترشيح مسجلة.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- LEVEL 2: STATUSES DETAIL VIEW                                     --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'statuses')
            <div class="settings-detail-panel">
                <div class="settings-panel-header">
                    <div>
                        <button type="button" wire:click="backToGrid" class="btn-back-nav">
                            <x-filament::icon icon="heroicon-o-arrow-right" style="width: 0.95rem; height: 0.95rem;" />
                            <span>العودة لمركز الإعدادات</span>
                        </button>
                        <h2 style="font-size: 1.25rem; font-weight: 900; color: #0f172a; margin: 0.75rem 0 0.25rem 0;">حالات ومراحل مسار التوظيف</h2>
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0;">تحديد حالات انتقال المرشح من التقديم حتى التعيين أو الرفض مع ألوان الشارات.</p>
                    </div>

                    <div style="width: 260px;">
                        <input type="text" wire:model.live.debounce.250ms="searchQuery" placeholder="بحث في الحالات..." class="form-input" />
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <!-- Form Box -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem;">
                        <h3 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0 0 1rem 0;">
                            {{ $editing_status_id ? 'تعديل بيانات الحالة' : 'إضافة حالة مرشح جديدة' }}
                        </h3>

                        <form wire:submit="{{ $editing_status_id ? 'updateStatus' : 'createStatus' }}">
                            <div style="margin-bottom: 1rem;">
                                <label class="form-label">اسم الحالة</label>
                                <input type="text" wire:model="status_name" placeholder="مثال: قيد المقابلة الأولى" class="form-input" required />
                                @error('status_name') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <div style="margin-bottom: 1rem;">
                                <label class="form-label">لون الشارة</label>
                                <select wire:model="status_color" class="form-input">
                                    <option value="primary">تركواز (Primary / Teal)</option>
                                    <option value="success">أخضر (Success / Emerald)</option>
                                    <option value="warning">برتقالي (Warning / Amber)</option>
                                    <option value="danger">أحمر (Danger / Rose)</option>
                                    <option value="info">أزرق (Info / Sky)</option>
                                    <option value="gray">رمادي (Neutral / Slate)</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" id="is_default_chk" wire:model="status_is_default" style="width: 1.1rem; height: 1.1rem; accent-color: #0d9488;" />
                                <label for="is_default_chk" style="font-size: 0.825rem; font-weight: 700; color: #334155; cursor: pointer;">تعيين كحالة افتراضية للطلبات الجديدة</label>
                            </div>

                            <div style="display: flex; gap: 0.5rem;">
                                <button type="submit" class="btn-action-primary">
                                    <x-filament::icon icon="heroicon-o-check" style="width: 1rem; height: 1rem;" />
                                    <span>{{ $editing_status_id ? 'حفظ التعديلات' : 'إضافة الحالة' }}</span>
                                </button>

                                @if($editing_status_id)
                                    <button type="button" wire:click="cancelEditStatus" class="btn-action-secondary">إلغاء</button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Table Box -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; overflow: hidden;">
                        <table class="crud-table">
                            <thead>
                                <tr>
                                    <th>اسم الحالة</th>
                                    <th>معاينة الشارة</th>
                                    <th>المرشحون</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($statuses as $st)
                                    <tr>
                                        <td style="font-weight: 700;">
                                            <span>{{ $st->name }}</span>
                                            @if($st->is_default)
                                                <span style="font-size: 0.65rem; background: #ccfbf1; color: #0f766e; padding: 0.1rem 0.4rem; border-radius: 9999px; margin-right: 0.25rem;">الافتراضي</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $bg = match($st->color) {
                                                    'success' => '#dcfce7; color: #15803d;',
                                                    'warning' => '#fef3c7; color: #b45309;',
                                                    'danger'  => '#fee2e2; color: #b91c1c;',
                                                    'info'    => '#e0f2fe; color: #0369a1;',
                                                    'gray'    => '#f1f5f9; color: #475569;',
                                                    default   => '#ccfbf1; color: #0f766e;',
                                                };
                                            @endphp
                                            <span class="badge-pill" style="background: {{ $bg }}">{{ $st->name }}</span>
                                        </td>
                                        <td><span class="badge-pill" style="background: #f1f5f9; color: #475569;">{{ $st->talents_count }}</span></td>
                                        <td>
                                            <div style="display: flex; gap: 0.5rem;">
                                                <button type="button" wire:click="editStatus({{ $st->id }})" style="color: #0d9488; background: none; border: none; font-weight: 700; cursor: pointer;">تعديل</button>
                                                <button type="button" wire:click="deleteStatus({{ $st->id }})" wire:confirm="هل أنت متأكد من حذف هذه الحالة؟" style="color: #dc2626; background: none; border: none; font-weight: 700; cursor: pointer;">حذف</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 2rem; color: #94a3b8;">لا توجد حالات مسجلة.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- LEVEL 2: TEMPLATES DETAIL VIEW                                    --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'templates')
            <div class="settings-detail-panel">
                <div class="settings-panel-header">
                    <div>
                        <button type="button" wire:click="backToGrid" class="btn-back-nav">
                            <x-filament::icon icon="heroicon-o-arrow-right" style="width: 0.95rem; height: 0.95rem;" />
                            <span>العودة لمركز الإعدادات</span>
                        </button>
                        <h2 style="font-size: 1.25rem; font-weight: 900; color: #0f172a; margin: 0.75rem 0 0.25rem 0;">قوالب المراسلة الذكية (واتساب & بريد إلكتروني)</h2>
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0;">تعديل نصوص الدعوات للمقابلات والعروض الوظيفية؛ يتم استبدال المتغيرات الذكية تلقائياً ببيانات المرشح والشركة.</p>
                    </div>

                    <div style="background: #f0fdfa; border: 1px solid #ccfbf1; padding: 0.5rem 0.85rem; border-radius: 0.75rem; font-size: 0.75rem; color: #0f766e;">
                        <strong>المتغيرات المتاحة:</strong>
                        <span class="smart-tag-chip">{name}</span> اسم المرشح
                        <span class="smart-tag-chip">{brand}</span> اسم الشركة
                        <span class="smart-tag-chip">{job_title}</span> الوظيفة
                    </div>
                </div>

                <form wire:submit="saveTemplates">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
                        
                        <!-- WhatsApp Templates Section -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1.15rem; padding: 1.5rem;">
                            <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0 0 1.25rem 0; display: flex; align-items: center; gap: 0.5rem;">
                                <x-filament::icon icon="heroicon-o-chat-bubble-oval-left-ellipsis" style="width: 1.25rem; height: 1.25rem; color: #16a34a;" />
                                <span>قوالب رسائل الواتساب (WhatsApp)</span>
                            </h3>

                            <div style="margin-bottom: 1.25rem;">
                                <label class="form-label">قالب دعوة المقابلة (Interview)</label>
                                <textarea wire:model="wa_template_interview" rows="4" class="form-input" style="line-height: 1.5;" required></textarea>
                            </div>

                            <div style="margin-bottom: 1.25rem;">
                                <label class="form-label">قالب طلب مستندات إضافية (Documents Request)</label>
                                <textarea wire:model="wa_template_docs" rows="4" class="form-input" style="line-height: 1.5;" required></textarea>
                            </div>
                        </div>

                        <!-- Email Templates Section -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1.15rem; padding: 1.5rem;">
                            <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0 0 1.25rem 0; display: flex; align-items: center; gap: 0.5rem;">
                                <x-filament::icon icon="heroicon-o-envelope" style="width: 1.25rem; height: 1.25rem; color: #0284c7;" />
                                <span>قوالب البريد الإلكتروني (Email)</span>
                            </h3>

                            <!-- Interview Email -->
                            <div style="margin-bottom: 1.25rem;">
                                <label class="form-label">عنوان إيميل المقابلة</label>
                                <input type="text" wire:model="email_template_interview_subject" class="form-input" style="margin-bottom: 0.35rem;" required />
                                <label class="form-label" style="font-size: 0.75rem; color: #64748b;">نص إيميل المقابلة</label>
                                <textarea wire:model="email_template_interview_body" rows="3" class="form-input" style="line-height: 1.5;" required></textarea>
                            </div>

                            <!-- Offer Email -->
                            <div style="margin-bottom: 1.25rem;">
                                <label class="form-label">عنوان إيميل العرض الوظيفي (Job Offer)</label>
                                <input type="text" wire:model="email_template_offer_subject" class="form-input" style="margin-bottom: 0.35rem;" required />
                                <label class="form-label" style="font-size: 0.75rem; color: #64748b;">نص إيميل العرض الوظيفي</label>
                                <textarea wire:model="email_template_offer_body" rows="3" class="form-input" style="line-height: 1.5;" required></textarea>
                            </div>

                            <!-- Rejection Email -->
                            <div style="margin-bottom: 1.25rem;">
                                <label class="form-label">عنوان إيميل الاعتذار والرفض (Rejection)</label>
                                <input type="text" wire:model="email_template_reject_subject" class="form-input" style="margin-bottom: 0.35rem;" required />
                                <label class="form-label" style="font-size: 0.75rem; color: #64748b;">نص إيميل الاعتذار</label>
                                <textarea wire:model="email_template_reject_body" rows="3" class="form-input" style="line-height: 1.5;" required></textarea>
                            </div>

                            <!-- Application Received Email -->
                            <div style="margin-bottom: 1.25rem;">
                                <label class="form-label">إيميل استلام الطلب التلقائي للتقديم الخارجي</label>
                                <input type="text" wire:model="email_template_application_received_subject" class="form-input" style="margin-bottom: 0.35rem;" required />
                                <label class="form-label" style="font-size: 0.75rem; color: #64748b;">نص إيميل استلام الطلب</label>
                                <textarea wire:model="email_template_application_received_body" rows="3" class="form-input" style="line-height: 1.5;" required></textarea>
                            </div>
                        </div>

                    </div>

                    <div style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
                        <button type="submit" class="btn-action-primary" style="padding: 0.75rem 2rem; font-size: 0.95rem;">
                            <x-filament::icon icon="heroicon-o-check" style="width: 1.25rem; height: 1.25rem;" />
                            <span>حفظ وتحديث كافة قوالب المراسلة</span>
                        </button>
                    </div>
                </form>
            </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- LEVEL 2: AVAILABILITY DETAIL VIEW                                 --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'availability')
            <div class="settings-detail-panel">
                <div class="settings-panel-header">
                    <div>
                        <button type="button" wire:click="backToGrid" class="btn-back-nav">
                            <x-filament::icon icon="heroicon-o-arrow-right" style="width: 0.95rem; height: 0.95rem;" />
                            <span>العودة لمركز الإعدادات</span>
                        </button>
                        <h2 style="font-size: 1.25rem; font-weight: 900; color: #0f172a; margin: 0.75rem 0 0.25rem 0;">خيارات الإتاحة والجاهزية للبدء</h2>
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0;">خيارات تحديد فترة الإشعار وتاريخ البدء المتاح للمرشحين في نماذج التقديم.</p>
                    </div>

                    <div style="width: 260px;">
                        <input type="text" wire:model.live.debounce.250ms="searchQuery" placeholder="بحث في الخيارات..." class="form-input" />
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <!-- Form Box -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem;">
                        <h3 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0 0 1rem 0;">
                            {{ $editing_avail_id ? 'تعديل خيار الإتاحة' : 'إضافة خيار إتاحة جديد' }}
                        </h3>

                        <form wire:submit="{{ $editing_avail_id ? 'updateAvailabilityOption' : 'createAvailabilityOption' }}">
                            <div style="margin-bottom: 1rem;">
                                <label class="form-label">نص خيار الإتاحة</label>
                                <input type="text" wire:model="avail_name" placeholder="مثال: متاح فوراً (Immediately)" class="form-input" required />
                                @error('avail_name') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <div style="display: flex; gap: 0.5rem;">
                                <button type="submit" class="btn-action-primary">
                                    <x-filament::icon icon="heroicon-o-check" style="width: 1rem; height: 1rem;" />
                                    <span>{{ $editing_avail_id ? 'حفظ التعديلات' : 'إضافة الخيار' }}</span>
                                </button>

                                @if($editing_avail_id)
                                    <button type="button" wire:click="cancelEditAvailabilityOption" class="btn-action-secondary">إلغاء</button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Table Box -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; overflow: hidden;">
                        <table class="crud-table">
                            <thead>
                                <tr>
                                    <th>خيار الإتاحة</th>
                                    <th>عدد المرشحين المسجلين</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($availabilityOptions as $ao)
                                    <tr>
                                        <td style="font-weight: 700;">{{ $ao->name }}</td>
                                        <td><span class="badge-pill" style="background: #fef08a; color: #854d0e;">{{ $ao->talents_count }}</span></td>
                                        <td>
                                            <div style="display: flex; gap: 0.5rem;">
                                                <button type="button" wire:click="editAvailabilityOption({{ $ao->id }})" style="color: #0d9488; background: none; border: none; font-weight: 700; cursor: pointer;">تعديل</button>
                                                <button type="button" wire:click="deleteAvailabilityOption({{ $ao->id }})" wire:confirm="هل أنت متأكد من حذف هذا الخيار؟" style="color: #dc2626; background: none; border: none; font-weight: 700; cursor: pointer;">حذف</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" style="text-align: center; padding: 2rem; color: #94a3b8;">لا توجد خيارات إتاحة مسجلة.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- LEVEL 2: LEAVE POLICIES & TYPES DETAIL VIEW                       --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'leaves')
            <div class="settings-detail-panel">
                <div class="settings-panel-header">
                    <div>
                        <button type="button" wire:click="backToGrid" class="btn-back-nav">
                            <x-filament::icon icon="heroicon-o-arrow-right" style="width: 0.95rem; height: 0.95rem;" />
                            <span>العودة لمركز الإعدادات</span>
                        </button>
                        <h2 style="font-size: 1.25rem; font-weight: 900; color: #0f172a; margin: 0.75rem 0 0.25rem 0;">أنواع وسياسات الإجازات والرصيد السنوي</h2>
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0;">إدارة أنواع الإجازات المتاحة للموظفين، الرصيد السنوي المخصص، وسياسات اشتراط الموافقة المسبقة.</p>
                    </div>

                    <div style="width: 260px;">
                        <input type="text" wire:model.live.debounce.250ms="searchQuery" placeholder="بحث في أنواع الإجازات..." class="form-input" />
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <!-- Form Box -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem;">
                        <h3 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0 0 1rem 0;">
                            {{ $editing_leave_type_id ? 'تعديل نوع الإجازة' : 'إضافة نوع إجازة جديد' }}
                        </h3>

                        <form wire:submit="{{ $editing_leave_type_id ? 'updateLeaveType' : 'createLeaveType' }}">
                            <div style="margin-bottom: 1rem;">
                                <label class="form-label">اسم نوع الإجازة</label>
                                <input type="text" wire:model="leave_type_name" placeholder="مثال: إجازة اعتيادية سنوية" class="form-input" required />
                                @error('leave_type_name') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <div style="margin-bottom: 1rem;">
                                <label class="form-label">الرصيد السنوي الافتراضي (أيام)</label>
                                <input type="number" wire:model="leave_type_days" min="1" max="365" class="form-input" required />
                                @error('leave_type_days') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                            </div>

                            <div style="margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" id="requires_approval_chk" wire:model="leave_type_requires_approval" style="width: 1.1rem; height: 1.1rem; accent-color: #0d9488;" />
                                <label for="requires_approval_chk" style="font-size: 0.825rem; font-weight: 700; color: #334155; cursor: pointer;">
                                    تشترط موافقة المدير المباشر / الموارد البشرية
                                </label>
                            </div>

                            <div style="display: flex; gap: 0.5rem;">
                                <button type="submit" class="btn-action-primary">
                                    <x-filament::icon icon="heroicon-o-check" style="width: 1rem; height: 1rem;" />
                                    <span>{{ $editing_leave_type_id ? 'حفظ التعديلات' : 'إضافة نوع الإجازة' }}</span>
                                </button>

                                @if($editing_leave_type_id)
                                    <button type="button" wire:click="cancelEditLeaveType" class="btn-action-secondary">إلغاء</button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Table Box -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; overflow: hidden;">
                        <table class="crud-table">
                            <thead>
                                <tr>
                                    <th>نوع الإجازة</th>
                                    <th>الرصيد السنوي</th>
                                    <th>اشتراط الموافقة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaveTypes as $lt)
                                    <tr>
                                        <td style="font-weight: 700;">{{ $lt->name }}</td>
                                        <td><span class="badge-pill" style="background: #e0e7ff; color: #3730a3;">{{ $lt->default_days_per_year }} يوم</span></td>
                                        <td>
                                            @if($lt->requires_approval)
                                                <span class="badge-pill" style="background: #ecfdf5; color: #047857;">نعم تشترط</span>
                                            @else
                                                <span class="badge-pill" style="background: #f1f5f9; color: #64748b;">تلقائية</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="display: flex; gap: 0.5rem;">
                                                <button type="button" wire:click="editLeaveType({{ $lt->id }})" style="color: #0d9488; background: none; border: none; font-weight: 700; cursor: pointer;">تعديل</button>
                                                <button type="button" wire:click="deleteLeaveType({{ $lt->id }})" wire:confirm="هل أنت متأكد من حذف نوع الإجازة هذا؟" style="color: #dc2626; background: none; border: none; font-weight: 700; cursor: pointer;">حذف</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 2rem; color: #94a3b8;">لا توجد أنواع إجازات مسجلة حالياً.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- LEVEL 2: CONTRACT ALERTS & NOTIFICATIONS DETAIL VIEW              --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @elseif($activeTab === 'alerts')
            <div class="settings-detail-panel">
                <div class="settings-panel-header">
                    <div>
                        <button type="button" wire:click="backToGrid" class="btn-back-nav">
                            <x-filament::icon icon="heroicon-o-arrow-right" style="width: 0.95rem; height: 0.95rem;" />
                            <span>العودة لمركز الإعدادات</span>
                        </button>
                        <h2 style="font-size: 1.25rem; font-weight: 900; color: #0f172a; margin: 0.75rem 0 0.25rem 0;">إعدادات التنبيهات وإشعارات انتهاء العقود</h2>
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0;">ضبط مواعيد التنبيه المبكر لانتهاء عقود العمل وتخصيص إشعارات التقديم الجديدة للموارد البشرية.</p>
                    </div>
                </div>

                <div style="max-width: 680px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1.15rem; padding: 1.75rem;">
                    <form wire:submit="saveAlertSettings">
                        
                        <!-- Contract Expiry Alert Days -->
                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label">مهلة الإنذار المبكر قبل انتهاء عقود الموظفين</label>
                            <div style="display: flex; align-items: center; gap: 0.75rem; max-width: 240px;">
                                <input type="number" wire:model="alert_contract_expiry_days" min="5" max="180" class="form-input" required />
                                <span style="font-size: 0.85rem; color: #64748b; white-space: nowrap;">يوماً قبل الانتهاء</span>
                            </div>
                            <span style="font-size: 0.75rem; color: #94a3b8; display: block; margin-top: 0.35rem;">
                                سيظهر تنبيه أحمر في لوحة التحكم وتنبيهات النظام للموظفين الذين تنتهي عقودهم خلال هذه المدة.
                            </span>
                            @error('alert_contract_expiry_days') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                        </div>

                        <!-- HR Notification Email -->
                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label">البريد الإلكتروني المخصص لإشعارات الموارد البشرية</label>
                            <input type="email" wire:model="hr_notification_email" placeholder="hr@company.com" class="form-input" />
                            <span style="font-size: 0.75rem; color: #94a3b8; display: block; margin-top: 0.35rem;">
                                تُرسل إليه إشعارات تقديم المرشحين الجدد وتنبيهات العقود المنتهية.
                            </span>
                            @error('hr_notification_email') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                        </div>

                        <!-- New Application Notification Toggle -->
                        <div style="margin-bottom: 1.75rem; display: flex; align-items: center; gap: 0.65rem;">
                            <input type="checkbox" id="notify_app_chk" wire:model="notify_new_application" style="width: 1.15rem; height: 1.15rem; accent-color: #0d9488;" />
                            <label for="notify_app_chk" style="font-size: 0.85rem; font-weight: 700; color: #1e293b; cursor: pointer;">
                                إرسال إشعار فوري عند استلام سيرة ذاتية أو طلب توظيف جديد عبر بوابة التوظيف
                            </label>
                        </div>

                        <button type="submit" class="btn-action-primary" style="padding: 0.75rem 2rem;">
                            <x-filament::icon icon="heroicon-o-check" style="width: 1.1rem; height: 1.1rem;" />
                            <span>حفظ إعدادات التنبيهات</span>
                        </button>
                    </form>
                </div>
            </div>

        @endif

    </div>
</x-filament-panels::page>