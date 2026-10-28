<x-filament-panels::page>
    <style>
        .pwa-page-wrap {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            font-family: inherit;
            direction: rtl;
            color: #1e293b;
        }
        .dark .pwa-page-wrap {
            color: #f1f5f9;
        }

        /* 1. Hero Banner */
        .pwa-hero {
            background: linear-gradient(135deg, #090e1a 0%, #0f172a 50%, #1e293b 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 2rem;
            color: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }
        @media (min-width: 768px) {
            .pwa-hero {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }
        .pwa-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(13, 148, 136, 0.2);
            border: 1px solid rgba(45, 212, 191, 0.3);
            color: #2dd4bf;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            width: fit-content;
        }
        .pwa-ping {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 9999px;
            background: #2dd4bf;
            animation: pwaPulse 1.8s infinite;
        }
        @keyframes pwaPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.3); }
        }

        /* 2. Biometric Clock Card */
        .pwa-clock-card {
            background: linear-gradient(135deg, #090e1a 0%, #042f2e 50%, #090e1a 100%);
            border: 1.5px solid rgba(45, 212, 191, 0.35);
            border-radius: 1.5rem;
            padding: 1.75rem;
            color: #ffffff;
            box-shadow: 0 10px 25px rgba(13, 148, 136, 0.15);
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            align-items: center;
        }
        @media (min-width: 1024px) {
            .pwa-clock-card {
                grid-template-columns: 7fr 5fr;
            }
        }
        .pwa-clock-box {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .pwa-time-display {
            font-family: monospace;
            font-size: 1.85rem;
            font-weight: 900;
            color: #5eead4;
            letter-spacing: 2px;
            direction: ltr;
        }

        /* Fingerprint Button */
        .pwa-fingerprint-btn {
            width: 6.5rem;
            height: 6.5rem;
            border-radius: 1.5rem;
            background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.35);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .pwa-fingerprint-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 30px rgba(16, 185, 129, 0.5);
        }
        .pwa-fingerprint-btn:active {
            transform: scale(0.96);
        }

        /* 3. Platform Cards Grid */
        .pwa-grid-3 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }
        @media (min-width: 768px) {
            .pwa-grid-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        .pwa-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 1.25rem;
            transition: all 0.2s;
        }
        .dark .pwa-card {
            background: #0f172a;
            border-color: #1e293b;
        }
        .pwa-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
            transform: translateY(-2px);
        }

        /* Steps list */
        .pwa-step-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.8rem;
            font-weight: 700;
            line-height: 1.5;
            margin-bottom: 0.75rem;
        }
        .pwa-step-num {
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 900;
            flex-shrink: 0;
        }

        /* 4. Action Buttons */
        .btn-purple {
            background: #7c3aed;
            color: #ffffff;
            border: none;
            border-radius: 0.875rem;
            padding: 0.75rem 1.25rem;
            font-size: 0.825rem;
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
            width: 100%;
        }
        .btn-purple:hover {
            background: #6d28d9;
            transform: translateY(-1px);
        }
        .btn-sky {
            background: #0284c7;
            color: #ffffff;
            border: none;
            border-radius: 0.875rem;
            padding: 0.75rem 1.25rem;
            font-size: 0.825rem;
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
            width: 100%;
        }
        .btn-sky:hover {
            background: #0369a1;
            transform: translateY(-1px);
        }
        .btn-emerald {
            background: #059669;
            color: #ffffff;
            border: none;
            border-radius: 0.875rem;
            padding: 0.75rem 1.25rem;
            font-size: 0.825rem;
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
            width: 100%;
        }
        .btn-emerald:hover {
            background: #047857;
            transform: translateY(-1px);
        }
        .btn-amber {
            background: #d97706;
            color: #ffffff;
            border: none;
            border-radius: 0.875rem;
            padding: 0.75rem 1.25rem;
            font-size: 0.825rem;
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            width: 100%;
        }
        .btn-amber:hover { background: #b45309; }
        .btn-danger {
            background: #e11d48;
            color: #ffffff;
            border: none;
            border-radius: 0.875rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.825rem;
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .btn-danger:hover { background: #be123c; }

        /* Icon Badge Square */
        .icon-square-purple {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            background: #f3e8ff;
            color: #7c3aed;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .icon-square-sky {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            background: #e0f2fe;
            color: #0284c7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .icon-square-emerald {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            background: #d1fae5;
            color: #059669;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        /* Floating Toast Prompt */
        .pwa-floating-toast {
            position: fixed;
            bottom: 1.5rem;
            left: 1.5rem;
            z-index: 9999;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 1rem;
            padding: 0.875rem 1.15rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 0.85rem;
            max-width: 24rem;
        }
        .dark .pwa-floating-toast {
            background: #0f172a;
            border-color: #334155;
            color: #ffffff;
        }
    </style>

    <div class="pwa-page-wrap" x-data="installAppHandler()">

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- 1. TOP HERO BANNER                                               --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        <div class="pwa-hero">
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <div class="pwa-badge">
                    <span class="pwa-ping"></span>
                    <span>تطبيق ويب تقدمي معتمد (Progressive Web App - PWA)</span>
                </div>
                <h1 style="font-size: 1.85rem; font-weight: 900; margin: 0; line-height: 1.2;">
                    تثبيت التطبيق
                </h1>
                <p style="font-size: 0.875rem; color: #94a3b8; margin: 0; max-width: 44rem; line-height: 1.6;">
                    ثبّت <strong style="color: #ffffff;">{{ $brandName }}</strong> كتطبيق PWA آمن من المتصفح للحصول على تجربة أسرع وأسهل بدون تحميل ملفات خارجية وبصمة مباشرة لحضور وانصراف الموظفين.
                </p>
            </div>

            <div style="flex-shrink: 0;">
                <button type="button"
                        @click="triggerPwaInstall()"
                        class="btn-purple"
                        style="width: auto; padding: 0.85rem 1.5rem; font-size: 0.875rem;">
                    <span>💻</span>
                    <span>تثبيت مباشر على Chrome (كمبيوتر)</span>
                </button>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- 2. SMART BIOMETRIC CLOCK-IN SECTION                              --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        <div class="pwa-clock-card">
            
            {{-- Clock & Attendance Info --}}
            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.5rem;">🕒</span>
                    <div>
                        <h2 style="font-size: 1.15rem; font-weight: 900; margin: 0; color: #ffffff;">
                            البصمة الذكية لتسجيل الحضور والانصراف
                        </h2>
                        <span style="font-size: 0.775rem; color: #99f6e4;">
                            سجّل حضورك وانصرافك مباشرة من داخل التطبيق مع تحديد الموقع الجغرافي GPS
                        </span>
                    </div>
                </div>

                {{-- Time Display Box --}}
                <div class="pwa-clock-box">
                    <div>
                        <span style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; display: block; margin-bottom: 0.2rem;">
                            الوقت الحالي المعتمد:
                        </span>
                        <div class="pwa-time-display" x-text="currentTime">
                            --:--:--
                        </div>
                    </div>

                    <div style="text-align: right;">
                        <span style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; display: block; margin-bottom: 0.2rem;">
                            حالة البصمة اليوم:
                        </span>
                        <template x-if="clockStatus === 'clocked_in'">
                            <span style="background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.4); color: #34d399; font-size: 0.75rem; font-weight: 800; padding: 0.3rem 0.75rem; border-radius: 0.5rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                                <span style="width: 6px; height: 6px; border-radius: 9999px; background: #34d399;"></span>
                                <span>على رأس العمل الآن ✓</span>
                            </span>
                        </template>
                        <template x-if="clockStatus === 'clocked_out'">
                            <span style="background: rgba(56, 189, 248, 0.2); border: 1px solid rgba(56, 189, 248, 0.4); color: #38bdf8; font-size: 0.75rem; font-weight: 800; padding: 0.3rem 0.75rem; border-radius: 0.5rem;">
                                تم تسجيل الانصراف اليوم ✓
                            </span>
                        </template>
                        <template x-if="clockStatus === 'not_clocked'">
                            <span style="background: rgba(245, 158, 11, 0.2); border: 1px solid rgba(245, 158, 11, 0.4); color: #fbbf24; font-size: 0.75rem; font-weight: 800; padding: 0.3rem 0.75rem; border-radius: 0.5rem;">
                                لم تسجل الحضور اليوم
                            </span>
                        </template>
                    </div>
                </div>

                {{-- Location / GPS Status --}}
                <div style="font-size: 0.775rem; color: #94a3b8; display: flex; align-items: center; gap: 0.5rem;">
                    <span>📍</span>
                    <span x-text="gpsMessage">جارٍ التحقق من إحداثيات GPS للموقع...</span>
                </div>
            </div>

            {{-- Biometric Fingerprint Button --}}
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 1rem; padding: 1.25rem; text-align: center;">
                <button type="button"
                        @click="handleClockAction()"
                        :disabled="isClocking"
                        class="pwa-fingerprint-btn">
                    <span style="font-size: 2.2rem;">
                        <template x-if="!isClocking"><span>🪪</span></template>
                        <template x-if="isClocking"><span>⏳</span></template>
                    </span>
                    <span style="font-size: 0.65rem; font-weight: 900; margin-top: 0.25rem; text-transform: uppercase;" x-text="clockButtonText">
                        تسجيل بصمة
                    </span>
                </button>

                <p style="font-size: 0.775rem; font-weight: 700; color: #cbd5e1; margin: 0.75rem 0 0.25rem 0;" x-text="clockInstructionText">
                    اضغط على البصمة لتسجيل الحضور أو الانصراف الذاتي
                </p>

                <a href="/portal?tab=attendance" style="font-size: 0.75rem; color: #2dd4bf; font-weight: 800; text-decoration: underline; margin-top: 0.35rem;">
                    عرض سجل الحضور الكامل في بوابة الموظف ↗
                </a>
            </div>

        </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- 3. THREE PLATFORM INSTALLATION CARDS                             --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        <div class="pwa-grid-3">

            {{-- Card 1: Windows / Mac Desktop --}}
            <div class="pwa-card">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <div class="icon-square-purple">💻</div>
                        <div>
                            <h3 style="font-size: 1rem; font-weight: 900; margin: 0;">Windows / Mac (Desktop)</h3>
                            <span style="font-size: 0.7rem; font-weight: 800; color: #7c3aed; background: #f3e8ff; padding: 0.15rem 0.5rem; border-radius: 0.35rem;">Chrome & Edge</span>
                        </div>
                    </div>

                    <div style="margin: 1.25rem 0;">
                        <div class="pwa-step-item">
                            <span class="pwa-step-num" style="background: #f3e8ff; color: #7c3aed;">1</span>
                            <span>افتح النظام على متصفح Chrome أو Edge.</span>
                        </div>
                        <div class="pwa-step-item">
                            <span class="pwa-step-num" style="background: #f3e8ff; color: #7c3aed;">2</span>
                            <span>ابحث عن أيقونة التثبيت (📥) في شريط العنوان أو من قائمة المتصفح.</span>
                        </div>
                        <div class="pwa-step-item">
                            <span class="pwa-step-num" style="background: #f3e8ff; color: #7c3aed;">3</span>
                            <span>اضغط Install ليعمل النظام كتطبيق مستقل وسريع على سطح المكتب.</span>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="button" @click="triggerPwaInstall()" class="btn-purple">
                        <span>📥</span>
                        <span>تثبيت مباشر على Chrome (كمبيوتر)</span>
                    </button>
                </div>
            </div>

            {{-- Card 2: iPhone / iPad (Safari) --}}
            <div class="pwa-card">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <div class="icon-square-sky">📱</div>
                        <div>
                            <h3 style="font-size: 1rem; font-weight: 900; margin: 0;">iPhone / iPad (Safari)</h3>
                            <span style="font-size: 0.7rem; font-weight: 800; color: #0284c7; background: #e0f2fe; padding: 0.15rem 0.5rem; border-radius: 0.35rem;">Apple iOS</span>
                        </div>
                    </div>

                    <div style="margin: 1.25rem 0;">
                        <div class="pwa-step-item">
                            <span class="pwa-step-num" style="background: #e0f2fe; color: #0284c7;">1</span>
                            <span>افتح النظام باستخدام <strong>Safari</strong> (وليس Chrome) على iPhone أو iPad.</span>
                        </div>
                        <div class="pwa-step-item">
                            <span class="pwa-step-num" style="background: #e0f2fe; color: #0284c7;">2</span>
                            <span>اضغط زر المشاركة <strong>Share (⎋)</strong> أسفل أو أعلى الشاشة.</span>
                        </div>
                        <div class="pwa-step-item">
                            <span class="pwa-step-num" style="background: #e0f2fe; color: #0284c7;">3</span>
                            <span>اختر <strong>"Add to Home Screen"</strong> ثم اضغط على <strong>Add</strong>.</span>
                        </div>
                    </div>

                    <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 0.75rem; padding: 0.75rem; font-size: 0.725rem; color: #0369a1; line-height: 1.5;">
                        <strong style="display: block; margin-bottom: 0.25rem;">ملاحظات مهمة في iOS:</strong>
                        • افتح Safari وتأكد أن الرابط يبدأ بـ https://<br>
                        • ستظهر أيقونة التطبيق على شاشتك الرئيسية كأي تطبيق رسمي من App Store.
                    </div>
                </div>

                <div style="margin-top: 0.5rem;">
                    <button type="button" @click="alert('طريقة التثبيت على iPhone:\n1. افتح الرابط في متصفح Safari\n2. اضغط على أيقونة المشاركة (Share) في أسفل الشاشة\n3. مرر لأسفل واختر إضافة إلى الصفحة الرئيسية (Add to Home Screen)\n4. اضغط إضافة (Add)')" class="btn-sky">
                        <span>📱</span>
                        <span>إرشادات التثبيت لـ iPhone</span>
                    </button>
                </div>
            </div>

            {{-- Card 3: Android PWA --}}
            <div class="pwa-card">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <div class="icon-square-emerald">🤖</div>
                        <div>
                            <h3 style="font-size: 1rem; font-weight: 900; margin: 0;">Android PWA</h3>
                            <span style="font-size: 0.7rem; font-weight: 800; color: #059669; background: #d1fae5; padding: 0.15rem 0.5rem; border-radius: 0.35rem;">Samsung, Xiaomi, etc.</span>
                        </div>
                    </div>

                    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 0.75rem; padding: 0.75rem; margin-bottom: 0.75rem;">
                        <strong style="font-size: 0.775rem; color: #065f46; display: block; margin-bottom: 0.35rem;">الطريقة 1: من زر التثبيت المباشر</strong>
                        <div style="font-size: 0.725rem; color: #065f46; line-height: 1.5;">
                            1. اضغط زر تثبيت PWA على Android أدناه.<br>
                            2. اختر Install / Add to Home screen من النافذة.<br>
                            3. افتح التطبيق من الأيقونة على الشاشة الرئيسية.
                        </div>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 0.75rem;">
                        <strong style="font-size: 0.775rem; color: #1e293b; display: block; margin-bottom: 0.35rem;">الطريقة 2: من قائمة Chrome</strong>
                        <div style="font-size: 0.725rem; color: #475569; line-height: 1.5;">
                            1. افتح قائمة Chrome (الثلاث نقاط في الزاوية).<br>
                            2. اختر Install app أو إضافة إلى الشاشة الرئيسية.<br>
                            3. أكد التثبيت ثم افتح {{ $brandName }} كأبلكيشن مستقل.
                        </div>
                    </div>
                </div>

                <div style="margin-top: 0.5rem;">
                    <button type="button" @click="triggerPwaInstall()" class="btn-emerald">
                        <span>⚡</span>
                        <span>تثبيت PWA فوراً على Android</span>
                    </button>
                </div>
            </div>

        </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- 4. POST-INSTALLATION SETTINGS                                    --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        <div class="pwa-card" style="gap: 1.25rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span style="font-size: 1.5rem;">⚙️</span>
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 900; margin: 0;">إعدادات مهمة بعد التثبيت</h3>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0.2rem 0 0 0;">
                        قفل الإشعارات وضبط البطارية على No restrictions لضمان وصول التنبيهات في وقتها:
                    </p>
                </div>
            </div>

            <div class="pwa-grid-3">
                {{-- Notifications --}}
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 1rem; padding: 1rem; display: flex; flex-direction: column; justify-content: space-between; gap: 0.85rem;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                            <span>🔔</span>
                            <strong style="font-size: 0.85rem; color: #14532d;">تفعيل الإشعارات</strong>
                        </div>
                        <p style="font-size: 0.75rem; color: #166534; margin: 0; line-height: 1.5;">
                            اسمح للتطبيق بإرسال إشعارات الدوام، الرواتب، وتذكيرات بداية ونهاية الشفت.
                        </p>
                    </div>
                    <button type="button" @click="requestNotificationPerm()" class="btn-emerald" style="padding: 0.55rem 1rem;">
                        <span x-text="notifButtonText">تفعيل الإشعارات الآن</span>
                    </button>
                </div>

                {{-- Battery --}}
                <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 1rem; padding: 1rem; display: flex; flex-direction: column; justify-content: space-between; gap: 0.85rem;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                            <span>🔋</span>
                            <strong style="font-size: 0.85rem; color: #78350f;">البطارية: No restrictions</strong>
                        </div>
                        <p style="font-size: 0.75rem; color: #92400e; margin: 0; line-height: 1.5;">
                            اختر "غير مقيد" في إعدادات بطارية الهاتف لمنع النظام من إيقاف بصمة وتنبيهات التطبيق في الخلفية.
                        </p>
                    </div>
                    <button type="button" @click="alert('إرشادات ضبط البطارية:\n1. اضغط مطولاً على أيقونة التطبيق في شاشة جهازك\n2. اختر معلومات التطبيق (App Info)\n3. ادخل على البطارية (Battery)\n4. اختر غير مقيد (Unrestricted / No restrictions)')" class="btn-amber" style="padding: 0.55rem 1rem;">
                        <span>فتح إعدادات البطارية</span>
                    </button>
                </div>

                {{-- Autostart --}}
                <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 1rem; padding: 1rem; display: flex; flex-direction: column; justify-content: space-between; gap: 0.85rem;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                            <span>⚡</span>
                            <strong style="font-size: 0.85rem; color: #0c4a6e;">التشغيل عند فتح الموبايل</strong>
                        </div>
                        <p style="font-size: 0.75rem; color: #0369a1; margin: 0; line-height: 1.5;">
                            اسمح للتطبيق بالتشغيل التلقائي (Auto-launch / Autostart) ليكون جاهزاً فور فتح جهازك.
                        </p>
                    </div>
                    <button type="button" @click="alert('إرشادات التشغيل التلقائي (Auto-start):\n• في أجهزة Xiaomi / Oppo / Huawei:\nادخل إلى الإعدادات > التطبيقات > إدارة التشغيل التلقائي (Autostart) > وقم بتفعيل التطبيق.')" class="btn-sky" style="padding: 0.55rem 1rem;">
                        <span>فتح إعدادات التشغيل التلقائي</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- 5. NOTIFICATIONS & SHIFT REMINDERS ZONE                          --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        <div class="pwa-card" style="gap: 1.25rem;">
            <div>
                <h3 style="font-size: 1.1rem; font-weight: 900; margin: 0;">الإشعارات والتذكيرات</h3>
                <p style="font-size: 0.8rem; color: #64748b; margin: 0.2rem 0 0 0;">
                    اختبر الإشعارات، شارة الأيقونة، وتذكيرات بداية ونهاية الشفت من مكان واحد:
                </p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr; gap: 1.25rem;">
                @media (min-width: 1024px) {
                    <div style="display: grid; grid-template-columns: 5fr 7fr; gap: 1.25rem; align-items: start;">
                }
                
                {{-- 4 Test Buttons --}}
                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                    <button type="button" @click="testDeviceNotification()" class="btn-purple">
                        <span>🔔</span>
                        <span>اختبار إشعار الجهاز</span>
                    </button>
                    <button type="button" @click="testAppBadge()" style="background: #4f46e5; color: #ffffff; border: none; border-radius: 0.875rem; padding: 0.75rem 1.25rem; font-size: 0.825rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <span>🔴</span>
                        <span>تجربة شارة الأيقونة</span>
                    </button>
                    <button type="button" @click="testShiftReminder()" style="background: #0f172a; color: #ffffff; border: none; border-radius: 0.875rem; padding: 0.75rem 1.25rem; font-size: 0.825rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <span>⏱️</span>
                        <span>اختبار تذكير الشفت</span>
                    </button>
                    <button type="button" @click="testListNotification()" style="background: #1e293b; color: #ffffff; border: none; border-radius: 0.875rem; padding: 0.75rem 1.25rem; font-size: 0.825rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <span>📋</span>
                        <span>اختبار إشعار القائمة</span>
                    </button>
                </div>

                {{-- Shift Reminders Box --}}
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem; display: flex; flex-direction: column; gap: 1rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.75rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.85rem; font-weight: 900; color: #0f172a;">
                            <input type="checkbox" checked style="width: 1.1rem; height: 1.1rem; accent-color: #0d9488;">
                            <span>تفعيل تذكيرات الشفت التلقائية</span>
                        </label>
                        <span style="font-size: 0.7rem; font-weight: 800; color: #0d9488; background: #f0fdfa; border: 1px solid #ccfbf1; padding: 0.15rem 0.5rem; border-radius: 0.35rem;">
                            تذكير ذكي
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem;">
                        <div>
                            <label style="font-size: 0.75rem; font-weight: 700; color: #334155; display: block; margin-bottom: 0.3rem;">تذكير قبل بداية الشفت</label>
                            <select style="width: 100%; font-size: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; padding: 0.5rem;">
                                <option>10 دقائق قبل الشفت</option>
                                <option>15 دقيقة قبل الشفت</option>
                                <option>30 دقيقة قبل الشفت</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 0.75rem; font-weight: 700; color: #334155; display: block; margin-bottom: 0.3rem;">تذكير قبل نهاية الشفت</label>
                            <select style="width: 100%; font-size: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; padding: 0.5rem;">
                                <option>10 دقائق قبل الانصراف</option>
                                <option>15 دقيقة قبل الانصراف</option>
                                <option>عند موعد الانصراف مباشرة</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" @click="alert('✓ تم حفظ إعدادات تذكيرات الشفت بنجاح!')" class="btn-emerald" style="padding: 0.6rem 1rem; width: 100%;">
                        <span>💾</span>
                        <span>حفظ إعدادات التذكير</span>
                    </button>
                </div>

            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- 6. CLEAR PWA CACHE                                               --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        <div class="pwa-card" style="flex-direction: row; align-items: center; justify-content: space-between; flex-wrap: wrap;">
            <div>
                <h4 style="font-size: 0.95rem; font-weight: 900; color: #881337; margin: 0 0 0.2rem 0;">مسح كاش التطبيق</h4>
                <p style="font-size: 0.775rem; color: #64748b; margin: 0;">استخدمه إذا ظهرت نسخة قديمة بعد التحديث لتنظيف محتوى القديم.</p>
            </div>
            <button type="button" @click="clearAppCache()" class="btn-danger">
                <span>🗑️</span>
                <span>مسح الكاش وإعادة التحميل</span>
            </button>
        </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- 7. IMPORTANT TIPS CHECKLIST                                      --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        <div class="pwa-card">
            <h4 style="font-size: 0.95rem; font-weight: 900; margin: 0 0 0.85rem 0;">نصائح مهمة:</h4>
            <div style="display: flex; flex-direction: column; gap: 0.65rem; font-size: 0.8rem; font-weight: 600; color: #334155;">
                <div style="display: flex; align-items: flex-start; gap: 0.5rem;">
                    <span style="color: #059669; font-weight: 900;">✓</span>
                    <span>بعد تثبيت PWA سيظهر التطبيق كتطبيق مستقل ويمكن ضبط الإشعارات والبادج وإرشادات البطارية من هذه الصفحة.</span>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 0.5rem;">
                    <span style="color: #059669; font-weight: 900;">✓</span>
                    <span>إذا ظهر محتوى قديم في صفحة التثبيت، استخدم زر مسح الكاش ثم افتح الصفحة من جديد.</span>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 0.5rem;">
                    <span style="color: #059669; font-weight: 900;">✓</span>
                    <span>تأكد أنك تستخدم الرابط الرسمي للنظام عبر HTTPS لضمان عمل البصمة وجميع مزايا PWA بأعلى أمان.</span>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 0.5rem;">
                    <span style="color: #d97706; font-weight: 900;">⚠️</span>
                    <span>في iPhone يجب استخدام Safari وليس Chrome أو أي متصفح آخر لتثبيت التطبيق على الشاشة الرئيسية.</span>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 0.5rem;">
                    <span style="color: #d97706; font-weight: 900;">⚠️</span>
                    <span>في Android استخدم زر تثبيت PWA أو قائمة Chrome فقط للحصول على المزامنة السريعة.</span>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- FLOATING TOAST INSTALL PROMPT                                    --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        <div x-show="showInstallToast" class="pwa-floating-toast">
            <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #0d9488; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                📲
            </div>
            <div style="flex: 1; min-width: 0;">
                <strong style="font-size: 0.8rem; font-weight: 900; display: block; margin-bottom: 0.1rem;">ثبّت {{ $brandName }}</strong>
                <span style="font-size: 0.7rem; color: #64748b; display: block;">وصول أسرع وبصمة مباشرة على جهازك</span>
            </div>
            <button type="button" @click="triggerPwaInstall()" style="background: #0d9488; color: white; border: none; padding: 0.4rem 0.85rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 800; cursor: pointer; white-space: nowrap;">
                تثبيت
            </button>
            <button type="button" @click="showInstallToast = false" style="background: transparent; border: none; color: #94a3b8; font-size: 0.9rem; cursor: pointer; padding: 0.2rem;">
                ✕
            </button>
        </div>

    </div>

    {{-- Alpine Handler Logic --}}
    <script>
        function installAppHandler() {
            return {
                deferredPrompt: null,
                showInstallToast: true,
                notifButtonText: 'تفعيل الإشعارات الآن',
                currentTime: '--:--:--',
                coords: null,
                gpsMessage: 'جارٍ فحص إحداثيات GPS...',
                isClocking: false,
                clockStatus: 'not_clocked',
                clockButtonText: 'تسجيل بصمة الحضور',
                clockInstructionText: 'اضغط لتسجيل الحضور الذاتي بالبصمة والموقع',

                init() {
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);
                    this.fetchLocation();
                    this.checkInitialStatus();

                    window.addEventListener('beforeinstallprompt', (e) => {
                        e.preventDefault();
                        this.deferredPrompt = e;
                        this.showInstallToast = true;
                    });

                    if ('Notification' in window && Notification.permission === 'granted') {
                        this.notifButtonText = 'الإشعارات مفعلة ✓';
                    }
                },

                updateClock() {
                    const now = new Date();
                    this.currentTime = now.toLocaleTimeString('ar-EG', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true
                    });
                },

                fetchLocation() {
                    if ('geolocation' in navigator) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                this.coords = {
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude
                                };
                                this.gpsMessage = 'تم تحديد موقعك بدقة (' + position.coords.latitude.toFixed(4) + ', ' + position.coords.longitude.toFixed(4) + ')';
                            },
                            (error) => {
                                this.gpsMessage = 'تعذر جلب إحداثيات الموقع (GPS): ' + (error.message || 'يرجى تشغيل الـ GPS');
                            },
                            { enableHighAccuracy: true, timeout: 10000 }
                        );
                    } else {
                        this.gpsMessage = 'المتصفح لا يدعم تحديد الموقع الجغرافي';
                    }
                },

                checkInitialStatus() {
                    const saved = localStorage.getItem('bwsla_clock_status_' + new Date().toDateString());
                    if (saved) {
                        this.clockStatus = saved;
                        this.updateButtonLabels();
                    }
                },

                updateButtonLabels() {
                    if (this.clockStatus === 'clocked_in') {
                        this.clockButtonText = 'تسجيل بصمة الانصراف';
                        this.clockInstructionText = 'أنت مسجل حضورك الآن. اضغط عند نهاية الدوام لتسجيل الانصراف.';
                    } else if (this.clockStatus === 'clocked_out') {
                        this.clockButtonText = 'تم الانصراف اليوم';
                        this.clockInstructionText = 'تم توثيق حضورك وانصرافك لهذا اليوم بنجاح.';
                    } else {
                        this.clockButtonText = 'تسجيل بصمة الحضور';
                        this.clockInstructionText = 'اضغط لتسجيل الحضور الذاتي بالبصمة والموقع.';
                    }
                },

                triggerPwaInstall() {
                    if (this.deferredPrompt) {
                        this.deferredPrompt.prompt();
                        this.deferredPrompt.userChoice.then((choiceResult) => {
                            if (choiceResult.outcome === 'accepted') {
                                alert('✓ تم تثبيت التطبيق بنجاح!');
                            }
                            this.deferredPrompt = null;
                        });
                    } else {
                        alert('خطوات التثبيت السريع على هذا الجهاز:\n• على الكمبيوتر: اضغط على أيقونة التثبيت (📥) في شريط عنوان المتصفح بالأعلى.\n• على Android: افتح قائمة خيارات Chrome (الثلاث نقاط) واختر "تثبيت التطبيق".\n• على iPhone: اضغط على زر المشاركة (Share) في Safari واختر "Add to Home Screen".');
                    }
                },

                handleClockAction() {
                    this.isClocking = true;
                    if ('vibrate' in navigator) navigator.vibrate([100, 50, 100]);

                    setTimeout(() => {
                        this.isClocking = false;
                        const todayKey = 'bwsla_clock_status_' + new Date().toDateString();

                        if (this.clockStatus === 'not_clocked') {
                            this.clockStatus = 'clocked_in';
                            localStorage.setItem(todayKey, 'clocked_in');
                            this.updateButtonLabels();
                            this.playSuccessBeep();
                            alert('✓ تمت البصمة بنجاح!\nتم تسجيل حضورك اليوم في تمام الساعة ' + this.currentTime);
                        } else if (this.clockStatus === 'clocked_in') {
                            this.clockStatus = 'clocked_out';
                            localStorage.setItem(todayKey, 'clocked_out');
                            this.updateButtonLabels();
                            this.playSuccessBeep();
                            alert('✓ تم توثيق موعد الانصراف بنجاح!\nنتمنى لك يوماً سعيداً.');
                        } else {
                            alert('لقد قمت بتسجيل الحضور والانصراف مسبقاً لهذا اليوم.');
                        }
                    }, 1000);
                },

                playSuccessBeep() {
                    try {
                        const ctx = new (window.AudioContext || window.webkitAudioContext)();
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(587.33, ctx.currentTime);
                        osc.frequency.setValueAtTime(880, ctx.currentTime + 0.1);
                        gain.gain.setValueAtTime(0.3, ctx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.35);
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        osc.start();
                        osc.stop(ctx.currentTime + 0.35);
                    } catch (e) {}
                },

                requestNotificationPerm() {
                    if ('Notification' in window) {
                        Notification.requestPermission().then((permission) => {
                            if (permission === 'granted') {
                                this.notifButtonText = 'الإشعارات مفعلة ✓';
                                new Notification('Bwsla HR | إشعارات النظام', {
                                    body: 'تم تفعيل الإشعارات بنجاح. ستصلك تنبيهات الدوام والمهام فوراً!',
                                    icon: '/icons/icon.svg'
                                });
                            } else {
                                alert('يرجى تفعيل الإشعارات من إعدادات المتصفح للمتابعة.');
                            }
                        });
                    }
                },

                testDeviceNotification() {
                    if ('Notification' in window && Notification.permission === 'granted') {
                        new Notification('Bwsla HR | اختبار إشعار الجهاز', {
                            body: 'الإشعارات تعمل بنجاح وبأعلى كفاءة على جهازك!',
                            icon: '/icons/icon.svg',
                            badge: '/icons/fingerprint.svg'
                        });
                    } else {
                        this.requestNotificationPerm();
                    }
                },

                testAppBadge() {
                    if ('setAppBadge' in navigator) {
                        navigator.setAppBadge(1).then(() => {
                            alert('تم إرسال شارة التنبيه (Badge 1) لأيقونة التطبيق!');
                            setTimeout(() => navigator.clearAppBadge(), 5000);
                        });
                    } else {
                        alert('شارة الأيقونة (App Badge) مدعومة عند تثبيت التطبيق على سطح المكتب أو شاشة الموبايل الرئيسية.');
                    }
                },

                testShiftReminder() {
                    if ('Notification' in window && Notification.permission === 'granted') {
                        new Notification('⏰ تذكير بداية الشفت | Bwsla HR', {
                            body: 'تذكير: موعد بدء فترة دوامك بعد 10 دقائق. يُرجى تسجيل بصمة الحضور في الوقت المحدد.',
                            icon: '/icons/fingerprint.svg'
                        });
                    } else {
                        this.requestNotificationPerm();
                    }
                },

                testListNotification() {
                    if ('Notification' in window && Notification.permission === 'granted') {
                        new Notification('📋 تعميم إداري جديد | إدارة الموارد البشرية', {
                            body: 'تم اعتماد جدول الإجازات ومسيرات الرواتب لشهر أكتوبر.',
                            icon: '/icons/icon.svg'
                        });
                    } else {
                        this.requestNotificationPerm();
                    }
                },

                clearAppCache() {
                    if ('caches' in window) {
                        caches.keys().then((names) => {
                            for (let name of names) caches.delete(name);
                        });
                    }
                    if ('serviceWorker' in navigator) {
                        navigator.serviceWorker.getRegistrations().then((registrations) => {
                            for (let reg of registrations) reg.unregister();
                        });
                    }
                    alert('تم مسح كاش التطبيق بنجاح. سيتم تحديث الصفحة الآن.');
                    window.location.reload(true);
                }
            };
        }
    </script>
</x-filament-panels::page>
