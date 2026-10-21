<x-filament-widgets::widget>
    <style>
        .hr-overview-box {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
        }
        .hr-action-banner {
            background: linear-gradient(135deg, #f0fdfa 0%, #e0f2fe 100%);
            border: 1px solid #ccfbf1;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .hr-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .hr-kpi-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .hr-kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.06);
        }
        .hr-kpi-accent {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 4px;
        }
        .hr-kpi-num {
            font-size: 2.25rem;
            font-weight: 900;
            font-family: monospace, ui-monospace;
            color: #0f172a;
            line-height: 1.1;
            margin-top: 0.75rem;
        }
        .hr-kpi-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            margin-top: 0.25rem;
        }
        .hr-table-wrapper {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .hr-table-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .hr-table {
            width: 100%;
            border-collapse: collapse;
            text-align: right;
            font-size: 0.85rem;
        }
        .hr-table th {
            background: #f8fafc;
            padding: 0.75rem 1.25rem;
            font-weight: 700;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.8rem;
        }
        .hr-table td {
            padding: 0.85rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
        }
        .hr-table tr:hover td {
            background: #f8fafc;
        }
    </style>

    <div class="hr-overview-box">

        <!-- Action Banner -->
        <div class="hr-action-banner">
            <div style="display: flex; align-items: center; gap: 0.85rem;">
                <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #0d9488; color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(13, 148, 136, 0.3);">
                    <x-filament::icon icon="heroicon-o-bolt" style="width: 1.25rem; height: 1.25rem;" />
                </div>
                <div>
                    <h3 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0;">الوصول السريع لعمليات الموارد البشرية</h3>
                    <p style="font-size: 0.75rem; color: #64748b; margin: 0.15rem 0 0 0;">إضافة موظف أو تسجيل مرشح جديد بنقرة واحدة - تنعكس التغييرات فورياً في النظام.</p>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <a href="{{ \App\Filament\Admin\Resources\TalentResource::getUrl('create') }}" 
                   style="background: #ffffff; color: #0f766e; border: 1px solid #ccfbf1; padding: 0.5rem 1.15rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <x-filament::icon icon="heroicon-o-user-plus" style="width: 1rem; height: 1rem;" />
                    <span>إضافة مرشح جديد</span>
                </a>
                
                {{ $this->addEmployeeAction }}
            </div>
        </div>

        <!-- Section Title -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <h2 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">مؤشرات الأداء الرئيسية</h2>
                <span style="background: #f1f5f9; color: #475569; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 9999px;">محدث لحظياً</span>
            </div>
        </div>

        <!-- KPI Grid -->
        <div class="hr-kpi-grid">
            
            <!-- Card 1: Active Employees -->
            <div class="hr-kpi-card">
                <div class="hr-kpi-accent" style="background: #0d9488;"></div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; background: #ccfbf1; color: #0d9488; display: flex; align-items: center; justify-content: center;">
                        <x-filament::icon icon="heroicon-o-users" style="width: 1.25rem; height: 1.25rem;" />
                    </div>
                    <span style="background: #ecfdf5; color: #047857; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 9999px;">نشط ومستمر</span>
                </div>
                <div>
                    <div class="hr-kpi-num">{{ $totalEmployees }}</div>
                    <div class="hr-kpi-label">إجمالي الموظفين الفعليين</div>
                </div>
            </div>

            <!-- Card 2: Total Talents -->
            <div class="hr-kpi-card">
                <div class="hr-kpi-accent" style="background: #0284c7;"></div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center;">
                        <x-filament::icon icon="heroicon-o-academic-cap" style="width: 1.25rem; height: 1.25rem;" />
                    </div>
                    <span style="background: #f0f9ff; color: #0369a1; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 9999px;">قاعدة المواهب</span>
                </div>
                <div>
                    <div class="hr-kpi-num">{{ $totalTalents }}</div>
                    <div class="hr-kpi-label">إجمالي المرشحين المسجلين</div>
                </div>
            </div>

            <!-- Card 3: Pending Leaves -->
            <div class="hr-kpi-card">
                <div class="hr-kpi-accent" style="background: #f59e0b;"></div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;">
                        <x-filament::icon icon="heroicon-o-clock" style="width: 1.25rem; height: 1.25rem;" />
                    </div>
                    <span style="background: #fffbeb; color: #b45309; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 9999px;">
                        {{ $pendingLeaves > 0 ? 'تتطلب إجراء' : 'مستقر' }}
                    </span>
                </div>
                <div>
                    <div class="hr-kpi-num">{{ $pendingLeaves }}</div>
                    <div class="hr-kpi-label">طلبات إجازات معلقة</div>
                </div>
            </div>

            <!-- Card 4: Overdue Followups -->
            <div class="hr-kpi-card">
                <div class="hr-kpi-accent" style="background: {{ $overdueFollowUps > 0 ? '#ef4444' : '#10b981' }};"></div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; background: {{ $overdueFollowUps > 0 ? '#fee2e2' : '#d1fae5' }}; color: {{ $overdueFollowUps > 0 ? '#dc2626' : '#059669' }}; display: flex; align-items: center; justify-content: center;">
                        <x-filament::icon icon="heroicon-o-bell-alert" style="width: 1.25rem; height: 1.25rem;" />
                    </div>
                    <span style="background: {{ $overdueFollowUps > 0 ? '#fef2f2' : '#f0fdf4' }}; color: {{ $overdueFollowUps > 0 ? '#b91c1c' : '#15803d' }}; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 9999px;">
                        {{ $overdueFollowUps > 0 ? 'متابعة عاجلة' : 'طبيعي' }}
                    </span>
                </div>
                <div>
                    <div class="hr-kpi-num">{{ $overdueFollowUps }}</div>
                    <div class="hr-kpi-label">متابعات متأخرة</div>
                </div>
            </div>

        </div>

        <!-- Department Movement Table -->
        <div class="hr-table-wrapper">
            <div class="hr-table-header">
                <div>
                    <h3 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0;">حركة الأقسام وتوزيع الكوادر</h3>
                    <p style="font-size: 0.75rem; color: #64748b; margin: 0.15rem 0 0 0;">إحصائية حية لتوزيع الموظفين الفعليين والمرشحين قيد الإجراء لكل إدارة</p>
                </div>
                <span style="font-size: 0.75rem; font-weight: 700; color: #0d9488;">
                    {{ count($departmentStats) }} قسم مسجل
                </span>
            </div>

            <div style="overflow-x: auto;">
                <table class="hr-table">
                    <thead>
                        <tr>
                            <th>الإدارة / القسم</th>
                            <th>الموظفين الفعليين</th>
                            <th>المرشحين قيد الإجراء</th>
                            <th>حالة القسم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departmentStats as $stat)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: #ccfbf1; color: #0f766e; display: flex; align-items: center; justify-content: center; font-weight: 800;">
                                            <x-filament::icon icon="heroicon-o-building-office-2" style="width: 1rem; height: 1rem;" />
                                        </div>
                                        <div>
                                            <div style="font-weight: 800; color: #0f172a;">{{ $stat['name'] }}</div>
                                            <div style="font-size: 0.7rem; color: #94a3b8;">{{ $stat['manager'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-family: monospace; font-weight: 800; font-size: 0.9rem; color: #0f172a;">{{ $stat['active_employees'] }}</span>
                                    <span style="font-size: 0.7rem; color: #64748b;">موظف</span>
                                </td>
                                <td>
                                    <span style="display: inline-block; padding: 0.15rem 0.5rem; border-radius: 0.35rem; background: #e0f2fe; color: #0369a1; font-weight: 800; font-size: 0.75rem; font-family: monospace;">
                                        {{ $stat['pending_candidates'] }} متقدم
                                    </span>
                                </td>
                                <td>
                                    <span style="display: inline-block; padding: 0.15rem 0.5rem; border-radius: 9999px; background: #ecfdf5; color: #047857; font-weight: 700; font-size: 0.7rem;">
                                        {{ $stat['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2rem; color: #94a3b8;">
                                    لا توجد أقسام مسجلة حالياً
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
