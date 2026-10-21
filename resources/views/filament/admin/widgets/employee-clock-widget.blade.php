<x-filament-widgets::widget>
    <style>
        .smart-clock-terminal {
            background: linear-gradient(135deg, #0f172a 0%, #042f2e 50%, #0f172a 100%);
            border-radius: 1.25rem;
            padding: 1.5rem 2rem;
            color: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.25), 0 8px 10px -6px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.12);
            position: relative;
            overflow: hidden;
            font-family: 'Cairo', sans-serif;
            direction: rtl;
        }
        .smart-clock-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            align-items: center;
            position: relative;
            z-index: 10;
        }
        @media (min-width: 1024px) {
            .smart-clock-grid {
                grid-template-columns: 5fr 7fr;
            }
            .smart-clock-col-divider {
                border-left: 1px solid rgba(255,255,255,0.12);
                padding-left: 1.5rem;
            }
        }
        .smart-clock-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            background: rgba(13, 148, 136, 0.25);
            border: 1px solid rgba(45, 212, 191, 0.4);
            color: #2dd4bf;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .smart-clock-digits {
            font-family: monospace, ui-monospace, sans-serif;
            font-size: 2.75rem;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin: 0.5rem 0 0.25rem 0;
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }
        .smart-clock-seconds {
            color: #2dd4bf;
            font-size: 1.5rem;
            animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        .smart-clock-period {
            font-size: 1rem;
            font-weight: 700;
            color: #a7f3d0;
            font-family: 'Cairo', sans-serif;
        }
        .smart-clock-emp-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
            padding-top: 0.85rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .smart-clock-avatar {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 50%;
            background: #0d9488;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 0 0 2px rgba(45, 212, 191, 0.4);
            object-fit: cover;
        }
        .smart-clock-action-box {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            backdrop-filter: blur(8px);
        }
        @media (min-width: 640px) {
            .smart-clock-action-box {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }
        .btn-fingerprint-in {
            background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
            color: #ffffff;
            border: none;
            padding: 0.85rem 1.75rem;
            border-radius: 0.85rem;
            font-size: 0.95rem;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 4px 15px rgba(13, 148, 136, 0.4);
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .btn-fingerprint-in:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 148, 136, 0.6);
        }
        .btn-fingerprint-out {
            background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
            color: #ffffff;
            border: none;
            padding: 0.85rem 1.75rem;
            border-radius: 0.85rem;
            font-size: 0.95rem;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 4px 15px rgba(225, 29, 72, 0.4);
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .btn-fingerprint-out:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(225, 29, 72, 0.6);
        }
    </style>

    <div x-data="{
            time: '{{ now()->timezone(\App\Models\Setting::timezone())->format('h:i') }}',
            seconds: '{{ now()->timezone(\App\Models\Setting::timezone())->format('s') }}',
            period: '{{ now()->timezone(\App\Models\Setting::timezone())->format('A') == 'PM' ? 'مساءً' : 'صباحاً' }}',
            dateStr: '{{ now()->timezone(\App\Models\Setting::timezone())->locale('ar')->translatedFormat('l، d F Y') }}',
            updateClock() {
                const tz = '{{ \App\Models\Setting::timezone() }}';
                const now = new Date();
                try {
                    const parts = new Intl.DateTimeFormat('en-US', {
                        timeZone: tz,
                        hour: 'numeric',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true
                    }).formatToParts(now);

                    let h = '12', m = '00', s = '00', dayPeriod = 'AM';
                    for (const p of parts) {
                        if (p.type === 'hour') h = p.value.padStart(2, '0');
                        if (p.type === 'minute') m = p.value.padStart(2, '0');
                        if (p.type === 'second') s = p.value.padStart(2, '0');
                        if (p.type === 'dayPeriod') dayPeriod = p.value;
                    }

                    this.time = h + ':' + m;
                    this.seconds = s;
                    this.period = (dayPeriod.toLowerCase() === 'pm') ? 'مساءً' : 'صباحاً';

                    this.dateStr = new Intl.DateTimeFormat('ar-EG', {
                        timeZone: tz,
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }).format(now);
                } catch(e) {
                    let h = now.getHours();
                    const m = String(now.getMinutes()).padStart(2, '0');
                    const s = String(now.getSeconds()).padStart(2, '0');
                    this.period = h >= 12 ? 'مساءً' : 'صباحاً';
                    h = h % 12;
                    h = h ? h : 12;
                    this.time = String(h).padStart(2, '0') + ':' + m;
                    this.seconds = s;
                }
            }
         }"
         x-init="updateClock(); setInterval(() => updateClock(), 1000)"
         class="smart-clock-terminal">
        
        <div class="smart-clock-grid">
            
            <!-- Column 1: Live Clock & Profile -->
            <div class="smart-clock-col-divider">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.5rem;">
                    <div class="smart-clock-badge">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #2dd4bf; display: inline-block;"></span>
                        بوابة الحضور الذكية | توقيت {{ \App\Models\Setting::timezone() }}
                    </div>
                    <span style="font-size: 0.75rem; color: #94a3b8;" x-text="dateStr">{{ now()->timezone(\App\Models\Setting::timezone())->locale('ar')->translatedFormat('l، d F Y') }}</span>
                </div>

                <!-- Digits -->
                <div class="smart-clock-digits" dir="ltr">
                    <span x-text="time">{{ now()->format('h:i') }}</span>
                    <span class="smart-clock-seconds" x-text="':' + seconds">:{{ now()->format('s') }}</span>
                    <span class="smart-clock-period" x-text="period">{{ now()->format('A') == 'PM' ? 'مساءً' : 'صباحاً' }}</span>
                </div>
                <p style="font-size: 0.75rem; color: #94a3b8; margin: 0;">توقيت البصمة معتمد ومزامن تلقائياً مع خادم النظام</p>

                @if($employee)
                    <div class="smart-clock-emp-card">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            @if($employee->avatar_url)
                                <img src="{{ asset('storage/' . $employee->avatar_url) }}" alt="{{ $employee->full_name }}" class="smart-clock-avatar" />
                            @else
                                <div class="smart-clock-avatar">
                                    {{ mb_substr($employee->first_name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <div style="font-weight: 800; font-size: 0.95rem; color: #ffffff;">{{ $employee->full_name }}</div>
                                <div style="font-size: 0.75rem; color: #5eead4;">
                                    {{ $employee->job_title ?? 'موظف' }} • {{ $employee->department?->name ?? 'العام' }}
                                </div>
                            </div>
                        </div>

                        <div style="text-align: left;">
                            <span style="font-size: 0.65rem; color: #94a3b8; display: block;">حضور هذا الشهر</span>
                            <span style="font-size: 0.85rem; font-weight: 800; color: #34d399; font-family: monospace;">{{ $monthlyAttendances }} يوم</span>
                        </div>
                    </div>
                @else
                    <div class="smart-clock-emp-card">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="smart-clock-avatar" style="background: #334155;">
                                {{ mb_substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 800; font-size: 0.9rem; color: #ffffff;">{{ auth()->user()->name }}</div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">إدارة النظام العامة (Super Admin)</div>
                            </div>
                        </div>
                        <a href="{{ url('/admin/attendances') }}" style="color: #2dd4bf; font-size: 0.75rem; font-weight: 700; text-decoration: none;">
                            سجل الحضور &larr;
                        </a>
                    </div>
                @endif
            </div>

            <!-- Column 2: Biometric Actions -->
            <div>
                @if($employee)
                    @if(!$attendance)
                        <!-- State 1: Ready to Clock in -->
                        <div class="smart-clock-action-box">
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #34d399; display: inline-block;"></span>
                                    <span style="font-size: 0.75rem; font-weight: 700; color: #34d399;">جاهز للبصمة اليوم</span>
                                </div>
                                <div style="font-weight: 800; font-size: 1rem; color: #ffffff;">تسجيل بدء الدوام (Clock In)</div>
                                <p style="font-size: 0.75rem; color: #cbd5e1; margin: 0.25rem 0 0 0;">اضغط على زر البصمة لتوثيق حضورك الفعلي واحتساب ساعات الدوام.</p>
                            </div>

                            <button wire:click="clockIn" 
                                    wire:loading.attr="disabled"
                                    type="button" 
                                    class="btn-fingerprint-in">
                                <x-filament::icon icon="heroicon-o-finger-print" style="width: 1.75rem; height: 1.75rem;" />
                                <div style="text-align: right;">
                                    <span style="display: block; font-size: 0.95rem; font-weight: 800;">بصمة الحضور</span>
                                    <span style="display: block; font-size: 0.65rem; color: #ccfbf1; font-weight: 400;">Touch Fingerprint</span>
                                </div>
                            </button>
                        </div>
                    @elseif(!$attendance->check_out_time)
                        <!-- State 2: Active on duty -->
                        <div class="smart-clock-action-box" style="background: rgba(4, 47, 46, 0.5); border-color: rgba(45, 212, 191, 0.3);">
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #34d399; display: inline-block;"></span>
                                    <span style="font-size: 0.75rem; font-weight: 700; color: #34d399;">أنت متواجد بالدوام الآن</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 1.25rem; font-size: 0.75rem; color: #e2e8f0; margin-top: 0.5rem;">
                                    <div>
                                        <span style="color: #94a3b8; font-size: 0.65rem; display: block;">وقت الحضور:</span>
                                        <strong style="font-family: monospace; font-size: 0.95rem; color: #34d399;">{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') }}</strong>
                                    </div>
                                    <div style="width: 1px; height: 1.75rem; background: rgba(255,255,255,0.15);"></div>
                                    <div>
                                        <span style="color: #94a3b8; font-size: 0.65rem; display: block;">المدة حتى الآن:</span>
                                        <strong style="font-size: 0.95rem; color: #fde047; font-weight: 700;">{{ $workedHoursText }}</strong>
                                    </div>
                                </div>
                            </div>

                            <button wire:click="clockOut" 
                                    wire:loading.attr="disabled"
                                    type="button" 
                                    class="btn-fingerprint-out">
                                <x-filament::icon icon="heroicon-o-arrow-left-on-rectangle" style="width: 1.5rem; height: 1.5rem;" />
                                <div style="text-align: right;">
                                    <span style="display: block; font-size: 0.95rem; font-weight: 800;">بصمة الانصراف</span>
                                    <span style="display: block; font-size: 0.65rem; color: #ffe4e6; font-weight: 400;">Clock Out</span>
                                </div>
                            </button>
                        </div>
                    @else
                        <!-- State 3: Workday Completed -->
                        <div class="smart-clock-action-box" style="background: rgba(4, 47, 46, 0.4); border-color: rgba(45, 212, 191, 0.3);">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; background: rgba(45, 212, 191, 0.2); color: #2dd4bf; display: flex; align-items: center; justify-content: center;">
                                    <x-filament::icon icon="heroicon-o-check-badge" style="width: 1.75rem; height: 1.75rem;" />
                                </div>
                                <div>
                                    <div style="font-weight: 800; font-size: 0.95rem; color: #ffffff;">اكتمل دوام اليوم بنجاح!</div>
                                    <div style="font-size: 0.75rem; color: #a7f3d0;">تم توثيق بصمتي الحضور والانصراف، شكراً لجهودك.</div>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 1rem; background: rgba(255,255,255,0.06); padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.75rem;">
                                <div>
                                    <span style="color: #94a3b8; font-size: 0.65rem; display: block;">الحضور:</span>
                                    <strong style="color: #2dd4bf; font-family: monospace;">{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') }}</strong>
                                </div>
                                <div style="width: 1px; height: 1.5rem; background: rgba(255,255,255,0.1);"></div>
                                <div>
                                    <span style="color: #94a3b8; font-size: 0.65rem; display: block;">الانصراف:</span>
                                    <strong style="color: #2dd4bf; font-family: monospace;">{{ \Carbon\Carbon::parse($attendance->check_out_time)->format('h:i A') }}</strong>
                                </div>
                                <div style="width: 1px; height: 1.5rem; background: rgba(255,255,255,0.1);"></div>
                                <div>
                                    <span style="color: #94a3b8; font-size: 0.65rem; display: block;">إجمالي الدوام:</span>
                                    <strong style="color: #34d399; font-weight: 800;">{{ $workedHoursText }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Admin status -->
                    <div class="smart-clock-action-box">
                        <div>
                            <span style="font-size: 0.75rem; font-weight: 700; color: #2dd4bf; display: block; margin-bottom: 0.25rem;">متابعة حضور موظفي الشركة اليوم</span>
                            <div style="font-weight: 800; font-size: 0.95rem; color: #ffffff;">لوحة التحكم الإدارية للبصمة</div>
                            <p style="font-size: 0.75rem; color: #cbd5e1; margin: 0.25rem 0 0 0;">متابعة فورية لتواجد الموظفين ونسب الحضور والغياب اليومية.</p>
                        </div>

                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="text-align: center; padding: 0.5rem 1rem; border-radius: 0.75rem; background: rgba(13, 148, 136, 0.3); border: 1px solid rgba(45, 212, 191, 0.4);">
                                <span style="display: block; font-size: 1.25rem; font-weight: 900; color: #2dd4bf; font-family: monospace;">{{ $todayPresentCount }}</span>
                                <span style="font-size: 0.65rem; color: #cbd5e1;">حاضر اليوم</span>
                            </div>
                            <div style="text-align: center; padding: 0.5rem 1rem; border-radius: 0.75rem; background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(255,255,255,0.1);">
                                <span style="display: block; font-size: 1.25rem; font-weight: 900; color: #94a3b8; font-family: monospace;">{{ max(0, $totalEmployeesCount - $todayPresentCount) }}</span>
                                <span style="font-size: 0.65rem; color: #94a3b8;">غائب / متبقي</span>
                            </div>
                            <a href="{{ url('/admin/attendances') }}" style="background: #0d9488; color: #ffffff; padding: 0.65rem 1.15rem; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 700; text-decoration: none;">
                                سجل الحضور &larr;
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-filament-widgets::widget>
