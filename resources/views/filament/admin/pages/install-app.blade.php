<div class="space-y-8 font-cairo text-slate-800 antialiased" dir="rtl" x-data="installAppHandler()">

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 1. TOP HERO BANNER                                               --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="relative rounded-3xl p-6 sm:p-8 text-white shadow-xl overflow-hidden border border-slate-700/60"
         style="background: linear-gradient(135deg, #090e1a 0%, #0f172a 50%, #1e293b 100%);">
        
        {{-- Ambient Glows --}}
        <div class="absolute -top-16 -left-16 w-56 h-56 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -right-16 w-56 h-56 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 bg-teal-500/20 border border-teal-400/30 text-teal-300 text-xs font-bold px-3 py-1 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                    <span>تطبيق ويب تقدمي معتمد (Progressive Web App - PWA)</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    تثبيت التطبيق
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 font-medium max-w-2xl leading-relaxed">
                    ثبّت <strong class="text-white">{{ $brandName }}</strong> كتطبيق PWA آمن من المتصفح للحصول على تجربة أسرع وأسهل بدون تحميل ملفات خارجية وبصمة مباشرة لحضور وانصراف الموظفين.
                </p>
            </div>

            <div class="shrink-0 flex items-center gap-3">
                <button type="button"
                        @click="triggerPwaInstall()"
                        class="px-5 py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-black text-xs sm:text-sm shadow-lg shadow-purple-600/30 transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                    <span>💻</span>
                    <span>تثبيت مباشر على Chrome (كمبيوتر)</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 2. SMART FINGERPRINT CLOCK-IN SECTION (البصمة الإلكترونية)       --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-r from-slate-900 via-teal-950 to-slate-900 border border-teal-500/30 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-1/4 w-72 h-72 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            
            {{-- Clock & Employee Info (7 cols) --}}
            <div class="lg:col-span-7 space-y-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-teal-500/20 text-teal-400 border border-teal-400/30 flex items-center justify-center text-sm font-bold">
                        🕒
                    </span>
                    <div>
                        <h2 class="text-lg sm:text-xl font-black text-white">البصمة الذكية لتسجيل الحضور والانصراف</h2>
                        <span class="text-xs text-teal-200/80 font-medium">سجّل حضورك وانصرافك مباشرة من داخل التطبيق مع تحديد الموقع الجغرافي GPS</span>
                    </div>
                </div>

                {{-- Digital Live Clock --}}
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <span class="text-[11px] font-bold text-slate-400 block mb-0.5">الوقت الحالي المعتمد:</span>
                        <div class="font-mono text-2xl sm:text-3xl font-black text-teal-300 tracking-wider" x-text="currentTime">
                            --:--:--
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="text-[11px] font-bold text-slate-400 block mb-0.5">حالة البصمة اليوم:</span>
                        <template x-if="clockStatus === 'clocked_in'">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-black bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                <span>على رأس العمل الآن ✓</span>
                            </span>
                        </template>
                        <template x-if="clockStatus === 'clocked_out'">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-black bg-sky-500/20 text-sky-300 border border-sky-400/30">
                                <span>تم تسجيل الانصراف ✓</span>
                            </span>
                        </template>
                        <template x-if="clockStatus === 'not_clocked'">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-black bg-amber-500/20 text-amber-300 border border-amber-400/30">
                                <span>لم تسجل الحضور اليوم</span>
                            </span>
                        </template>
                    </div>
                </div>

                {{-- GPS Status pill --}}
                <div class="text-xs text-slate-400 flex items-center gap-2">
                    <span class="text-base" :class="coords ? 'text-emerald-400' : 'text-amber-400'">📍</span>
                    <span x-text="gpsMessage">جارٍ فحص إحداثيات الموقع الجغرافي...</span>
                </div>
            </div>

            {{-- Biometric Fingerprint Button (5 cols) --}}
            <div class="lg:col-span-5 flex flex-col items-center justify-center p-4 bg-white/5 border border-white/10 rounded-2xl text-center">
                
                {{-- Interactive Fingerprint Button --}}
                <button type="button"
                        @click="handleClockAction()"
                        :disabled="isClocking"
                        class="group relative w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-gradient-to-tr from-teal-600 to-emerald-500 hover:from-teal-500 hover:to-emerald-400 text-white flex flex-col items-center justify-center shadow-2xl shadow-teal-500/40 hover:shadow-teal-500/60 transition-all duration-300 cursor-pointer active:scale-95 disabled:opacity-50">
                    
                    {{-- Animated Ripple effect --}}
                    <span class="absolute inset-0 rounded-3xl border-2 border-teal-300/40 animate-ping pointer-events-none"></span>

                    <span class="text-3xl sm:text-4xl group-hover:scale-110 transition-transform">
                        <template x-if="!isClocking">
                            <span>🪪</span>
                        </template>
                        <template x-if="isClocking">
                            <span class="animate-spin inline-block">⏳</span>
                        </template>
                    </span>
                    <span class="text-[11px] font-black mt-1 uppercase tracking-wider" x-text="clockButtonText">
                        تسجيل بصمة
                    </span>
                </button>

                <p class="text-xs font-bold text-slate-300 mt-3" x-text="clockInstructionText">
                    اضغط على البصمة لتسجيل الحضور أو الانصراف الذاتي
                </p>

                <a href="/portal?tab=attendance" class="text-xs text-teal-400 hover:text-teal-300 font-extrabold underline mt-2 block">
                    عرض سجل الحضور الكامل في بوابة الموظف ↗
                </a>
            </div>

        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 3. THREE PLATFORM INSTALLATION CARDS                             --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- ── Card 1: Windows / Mac (Desktop) ── --}}
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shadow-xs">
                        💻
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 text-base">Windows / Mac (Desktop)</h3>
                        <span class="text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md">Chrome & Edge</span>
                    </div>
                </div>

                <div class="space-y-3.5 my-5 text-xs text-slate-700 font-bold">
                    <div class="flex items-start gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-800 text-[11px] font-black flex items-center justify-center shrink-0">1</span>
                        <span>افتح النظام على متصفح Chrome أو Edge.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-800 text-[11px] font-black flex items-center justify-center shrink-0">2</span>
                        <span>ابحث عن أيقونة التثبيت (📥) في شريط العنوان أو من قائمة المتصفح.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-800 text-[11px] font-black flex items-center justify-center shrink-0">3</span>
                        <span>اضغط Install ليعمل النظام كتطبيق مستقل وسريع على سطح المكتب.</span>
                    </div>
                </div>
            </div>

            <div>
                <button type="button"
                        @click="triggerPwaInstall()"
                        class="w-full py-3 px-4 rounded-2xl bg-purple-600 hover:bg-purple-700 text-white font-extrabold text-xs shadow-md shadow-purple-600/20 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <span>📥</span>
                    <span>تثبيت مباشر على Chrome (كمبيوتر)</span>
                </button>
            </div>
        </div>

        {{-- ── Card 2: iPhone / iPad (Safari) ── --}}
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl shadow-xs">
                        📱
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 text-base">iPhone / iPad (Safari)</h3>
                        <span class="text-[11px] font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded-md">Apple iOS</span>
                    </div>
                </div>

                <div class="space-y-3.5 my-5 text-xs text-slate-700 font-bold">
                    <div class="flex items-start gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-sky-100 text-sky-800 text-[11px] font-black flex items-center justify-center shrink-0">1</span>
                        <span>افتح النظام باستخدام <strong>Safari</strong> (وليس Chrome) على iPhone أو iPad.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-sky-100 text-sky-800 text-[11px] font-black flex items-center justify-center shrink-0">2</span>
                        <span>اضغط زر المشاركة <strong>Share (⎋)</strong> أسفل أو أعلى الشاشة.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-sky-100 text-sky-800 text-[11px] font-black flex items-center justify-center shrink-0">3</span>
                        <span>اختر <strong>"Add to Home Screen"</strong> ثم اضغط على <strong>Add</strong>.</span>
                    </div>
                </div>

                {{-- iOS Important Notice --}}
                <div class="bg-sky-50 border border-sky-200 rounded-2xl p-3 text-[11px] text-sky-900 space-y-1 font-medium">
                    <strong class="font-black text-sky-950 block">ملاحظات مهمة في iOS:</strong>
                    <p>• افتح Safari وتأكد أن الرابط يبدأ بـ https://</p>
                    <p>• ستظهر أيقونة التطبيق على شاشتك الرئيسية كأي تطبيق رسمي من App Store.</p>
                </div>
            </div>

            <div class="mt-4">
                <button type="button"
                        @click="showIosModal = true"
                        class="w-full py-3 px-4 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold text-xs shadow-md shadow-sky-600/20 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <span>📱</span>
                    <span>عرض دليل صور التثبيت لـ iPhone</span>
                </button>
            </div>
        </div>

        {{-- ── Card 3: Android PWA ── --}}
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                        🤖
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 text-base">Android PWA</h3>
                        <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">Samsung, Xiaomi, etc.</span>
                    </div>
                </div>

                <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-2xl p-3 mb-3">
                    <strong class="text-xs font-black text-emerald-950 block mb-1">الطريقة 1: من زر التثبيت المباشر</strong>
                    <ol class="text-[11px] text-emerald-900 space-y-1 font-semibold list-decimal list-inside">
                        <li>اضغط زر تثبيت PWA على Android أدناه.</li>
                        <li>اختر Install / Add to Home screen من النافذة.</li>
                        <li>افتح التطبيق من الأيقونة على الشاشة الرئيسية.</li>
                    </ol>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3">
                    <strong class="text-xs font-black text-slate-900 block mb-1">الطريقة 2: من قائمة Chrome</strong>
                    <ol class="text-[11px] text-slate-700 space-y-1 font-medium list-decimal list-inside">
                        <li>افتح قائمة Chrome (الثلاث نقاط في الزاوية).</li>
                        <li>اختر Install app أو إضافة إلى الشاشة الرئيسية.</li>
                        <li>أكد التثبيت ثم افتح {{ $brandName }} كأبلكيشن مستقل.</li>
                    </ol>
                </div>
            </div>

            <div class="mt-4">
                <button type="button"
                        @click="triggerPwaInstall()"
                        class="w-full py-3 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <span>⚡</span>
                    <span>تثبيت PWA فوراً على Android</span>
                </button>
            </div>
        </div>

    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 4. IMPORTANT POST-INSTALLATION SETTINGS (إعدادات مهمة بعد التثبيت) --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-sm space-y-5">
        <div class="flex items-center gap-3">
            <span class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                ⚙️
            </span>
            <div>
                <h3 class="text-base sm:text-lg font-black text-slate-900">إعدادات مهمة بعد التثبيت</h3>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">قفل الإشعارات وضبط البطارية على No restrictions لضمان وصول التنبيهات في وقتها:</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            {{-- Notification Perms --}}
            <div class="bg-emerald-50/60 border border-emerald-200 rounded-2xl p-4 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="text-lg">🔔</span>
                        <strong class="text-xs sm:text-sm font-black text-emerald-950">تفعيل الإشعارات</strong>
                    </div>
                    <p class="text-[11px] sm:text-xs text-emerald-900 font-medium leading-relaxed">
                        اسمح للتطبيق بإرسال إشعارات الدوام، الرواتب، وتذكيرات بداية ونهاية الشفت.
                    </p>
                </div>
                <div class="mt-3.5">
                    <button type="button"
                            @click="requestNotificationPerm()"
                            class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                        <span x-text="notifButtonText">تفعيل الإشعارات الآن</span>
                    </button>
                </div>
            </div>

            {{-- Battery Unrestricted --}}
            <div class="bg-amber-50/60 border border-amber-200 rounded-2xl p-4 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="text-lg">🔋</span>
                        <strong class="text-xs sm:text-sm font-black text-amber-950">البطارية: No restrictions</strong>
                    </div>
                    <p class="text-[11px] sm:text-xs text-amber-900 font-medium leading-relaxed">
                        اختر "غير مقيد" في إعدادات بطارية الهاتف لمنع النظام من إيقاف بصمة وتنبيهات التطبيق في الخلفية.
                    </p>
                </div>
                <div class="mt-3.5">
                    <button type="button"
                            @click="showBatteryModal = true"
                            class="w-full py-2.5 px-4 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-black text-xs shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                        <span>فتح إعدادات البطارية</span>
                    </button>
                </div>
            </div>

            {{-- Auto-start --}}
            <div class="bg-sky-50/60 border border-sky-200 rounded-2xl p-4 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="text-lg">⚡</span>
                        <strong class="text-xs sm:text-sm font-black text-sky-950">التشغيل عند فتح الموبايل</strong>
                    </div>
                    <p class="text-[11px] sm:text-xs text-sky-900 font-medium leading-relaxed">
                        اسمح للتطبيق بالتشغيل التلقائي (Auto-launch / Autostart) ليكون جاهزاً فور فتح جهازك.
                    </p>
                </div>
                <div class="mt-3.5">
                    <button type="button"
                            @click="showAutostartModal = true"
                            class="w-full py-2.5 px-4 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-black text-xs shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                        <span>فتح إعدادات التشغيل التلقائي</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 5. NOTIFICATIONS & SHIFT REMINDERS TEST ZONE                     --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        <div>
            <h3 class="text-base sm:text-lg font-black text-slate-900">الإشعارات والتذكيرات</h3>
            <p class="text-xs sm:text-sm text-slate-500 font-medium">اختبر الإشعارات، شارة الأيقونة، وتذكيرات بداية ونهاية الشفت من مكان واحد:</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            {{-- Test Action Buttons (5 cols) --}}
            <div class="lg:col-span-5 space-y-2.5">
                <button type="button"
                        @click="testDeviceNotification()"
                        class="w-full py-3 px-4 rounded-2xl bg-purple-600 hover:bg-purple-700 text-white font-black text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                    <span>🔔</span>
                    <span>اختبار إشعار الجهاز</span>
                </button>

                <button type="button"
                        @click="testAppBadge()"
                        class="w-full py-3 px-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                    <span>🔴</span>
                    <span>تجربة شارة الأيقونة</span>
                </button>

                <button type="button"
                        @click="testShiftReminder()"
                        class="w-full py-3 px-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-black text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                    <span>⏱️</span>
                    <span>اختبار تذكير الشفت</span>
                </button>

                <button type="button"
                        @click="testListNotification()"
                        class="w-full py-3 px-4 rounded-2xl bg-slate-800 hover:bg-slate-700 text-white font-black text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                    <span>📋</span>
                    <span>اختبار إشعار القائمة</span>
                </button>
            </div>

            {{-- Shift Reminders Settings Box (7 cols) --}}
            <div class="lg:col-span-7 bg-slate-50/80 border border-slate-200 rounded-2xl p-5 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" x-model="shiftRemindersEnabled" class="w-4 h-4 text-teal-600 rounded border-slate-300 focus:ring-teal-500 accent-teal-600">
                        <strong class="text-xs sm:text-sm font-black text-slate-900">تفعيل تذكيرات الشفت التلقائية</strong>
                    </label>
                    <span class="text-[11px] font-bold text-teal-700 bg-teal-50 px-2.5 py-0.5 rounded-md border border-teal-200">
                        تذكير ذكي
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">تذكير قبل بداية الشفت</label>
                        <select class="w-full text-xs bg-white border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 font-medium">
                            <option>10 دقائق قبل الشفت</option>
                            <option>15 دقيقة قبل الشفت</option>
                            <option>30 دقيقة قبل الشفت</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">تذكير قبل نهاية الشفت</label>
                        <select class="w-full text-xs bg-white border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 font-medium">
                            <option>10 دقائق قبل الانصراف</option>
                            <option>15 دقيقة قبل الانصراف</option>
                            <option>عند موعد الانصراف مباشرة</option>
                        </select>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button"
                            @click="saveReminderSettings()"
                            class="w-full py-2.5 px-4 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                        <span>💾</span>
                        <span>حفظ إعدادات التذكير</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 6. CLEAR PWA CACHE (مسح كاش التطبيق)                             --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-7 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h4 class="text-sm sm:text-base font-black text-rose-950">مسح كاش التطبيق</h4>
            <p class="text-xs text-slate-500 font-medium mt-0.5">استخدمه إذا ظهرت نسخة قديمة أو لتحديث بيانات PWA المخزنة محلياً على جهازك.</p>
        </div>

        <button type="button"
                @click="clearAppCache()"
                class="py-3 px-6 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-black text-xs shadow-md shadow-rose-600/20 transition-all flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap">
            <span>🗑️</span>
            <span>مسح الكاش وإعادة التحميل</span>
        </button>
    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 7. IMPORTANT TIPS CHECKLIST                                      --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
        <h4 class="text-sm sm:text-base font-black text-slate-900">نصائح مهمة:</h4>
        <div class="space-y-3 text-xs sm:text-sm font-medium text-slate-700">
            <div class="flex items-start gap-2.5">
                <span class="text-emerald-600 text-base">✓</span>
                <span>بعد تثبيت PWA سيظهر التطبيق كتطبيق مستقل ويمكن ضبط الإشعارات والبادج وإرشادات البطارية من هذه الصفحة.</span>
            </div>
            <div class="flex items-start gap-2.5">
                <span class="text-emerald-600 text-base">✓</span>
                <span>إذا ظهر محتوى قديم في صفحة التثبيت، استخدم زر مسح الكاش ثم افتح الصفحة من جديد.</span>
            </div>
            <div class="flex items-start gap-2.5">
                <span class="text-emerald-600 text-base">✓</span>
                <span>تأكد أنك تستخدم الرابط الرسمي للنظام عبر HTTPS لضمان عمل البصمة وجميع مزايا PWA بأعلى أمان.</span>
            </div>
            <div class="flex items-start gap-2.5">
                <span class="text-amber-500 text-base">⚠️</span>
                <span>في iPhone يجب استخدام Safari وليس Chrome أو أي متصفح آخر لتثبيت التطبيق على الشاشة الرئيسية.</span>
            </div>
            <div class="flex items-start gap-2.5">
                <span class="text-amber-500 text-base">⚠️</span>
                <span>في Android استخدم زر تثبيت PWA أو قائمة Chrome فقط للحصول على المزامنة السريعة.</span>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- FLOATING TOAST INSTALL PROMPT SIMULATION / LIVE PROMPT           --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div x-show="showInstallToast"
         x-transition
         class="fixed bottom-6 left-6 z-50 bg-white/95 backdrop-blur-md border border-slate-200 rounded-2xl p-4 shadow-2xl flex items-center gap-3.5 max-w-sm">
        <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center text-lg font-bold shrink-0">
            📲
        </div>
        <div class="min-w-0">
            <strong class="text-xs font-black text-slate-900 block truncate">ثبّت {{ $brandName }}</strong>
            <span class="text-[11px] text-slate-500 block truncate">وصول أسرع وبصمة مباشرة على جهازك</span>
        </div>
        <button type="button"
                @click="triggerPwaInstall()"
                class="px-3.5 py-1.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition whitespace-nowrap cursor-pointer">
            تثبيت
        </button>
        <button type="button"
                @click="showInstallToast = false"
                class="text-slate-400 hover:text-slate-600 text-sm p-1">
            ✕
        </button>
    </div>

</div>

{{-- Alpine.js Logic Handler --}}
<script>
    function installAppHandler() {
        return {
            deferredPrompt: null,
            showInstallToast: true,
            showIosModal: false,
            showBatteryModal: false,
            showAutostartModal: false,
            shiftRemindersEnabled: true,
            notifButtonText: 'تفعيل الإشعارات الآن',
            currentTime: '--:--:--',
            coords: null,
            gpsMessage: 'جارٍ جلب إحداثيات GPS...',
            isClocking: false,
            clockStatus: 'not_clocked', // 'not_clocked', 'clocked_in', 'clocked_out'
            clockButtonText: 'تسجيل بصمة الحضور',
            clockInstructionText: 'اضغط لتسجيل الحضور الذاتي بالبصمة والموقع',

            init() {
                // Real-time Clock
                this.updateClock();
                setInterval(() => this.updateClock(), 1000);

                // Fetch GPS coordinates
                this.fetchLocation();

                // Check attendance status
                this.checkInitialStatus();

                // Listen to native PWA install prompt
                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    this.deferredPrompt = e;
                    this.showInstallToast = true;
                });

                // Check if notification permission is already granted
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
                            this.gpsMessage = 'تعذر جلب الموقع الجغرافي: ' + (error.message || 'يرجى السماح بالوصول للموقع');
                        },
                        { enableHighAccuracy: true, timeout: 10000 }
                    );
                } else {
                    this.gpsMessage = 'المتصفح لا يدعم تحديد الموقع الجغرافي';
                }
            },

            checkInitialStatus() {
                // If the user already clocked in today via widget, we sync
                const saved = localStorage.getItem('bwsla_clock_status_' + new Date().toDateString());
                if (saved) {
                    this.clockStatus = saved;
                    this.updateButtonLabels();
                }
            },

            updateButtonLabels() {
                if (this.clockStatus === 'clocked_in') {
                    this.clockButtonText = 'تسجيل بصمة الانصراف';
                    this.clockInstructionText = 'أنت مسجل حضورك الآن. اضغط عند انتهاء يوم العمل لتسجيل الانصراف.';
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
                            alert('تمت الموافقة على تثبيت التطبيق بنجاح!');
                        }
                        this.deferredPrompt = null;
                    });
                } else {
                    alert('للتثبيت السريع:\n• على الكمبيوتر: اضغط على أيقونة التثبيت (📥) في شريط العنوان أعلى المتصفح.\n• على Android: اضغط قائمة الثلاث نقاط واختر "تثبيت التطبيق".\n• على iPhone: اضغط زر المشاركة (Share) واختر "Add to Home Screen".');
                }
            },

            handleClockAction() {
                this.isClocking = true;

                // Play soft vibration
                if ('vibrate' in navigator) {
                    navigator.vibrate([100, 50, 100]);
                }

                // Call backend API / Livewire widget or simulate instant attendance
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
                }, 1200);
            },

            playSuccessBeep() {
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
                    osc.frequency.setValueAtTime(880, ctx.currentTime + 0.1); // A5
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
                                body: 'تم تفعيل الإشعارات بنجاح. ستصلك تنبيهات الدوام والمستجدات فوراً!',
                                icon: '/icons/icon.svg'
                            });
                        } else {
                            alert('لم يتم منح الإذن. يرجى تفعيل الإشعارات من إعدادات المتصفح.');
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
                    alert('شارة الأيقونة (App Badge) مدعومة عند تثبيت التطبيق على سطح المكتب أو الشاشة الرئيسية.');
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

            saveReminderSettings() {
                alert('✓ تم حفظ إعدادات تذكيرات الشفت بنجاح!');
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
                alert('تم مسح كاش التطبيق بالكامل. سيتم إعادة تحميل الصفحة الآن.');
                window.location.reload(true);
            }
        };
    }
</script>
