<div class="min-h-screen bg-slate-50 relative py-8 px-4 sm:px-6 lg:px-8 font-cairo text-slate-800 antialiased" dir="rtl">

    {{-- Subtle Ambient Glows --}}
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-teal-200/40 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute top-48 left-1/4 w-96 h-96 bg-sky-200/40 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <div class="max-w-3xl mx-auto">

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- TOP NAVIGATION / BRAND BAR                                       --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        <header class="flex items-center justify-between bg-white/80 backdrop-blur-md border border-slate-200/80 rounded-2xl px-5 py-3.5 mb-8 shadow-sm">
            <div class="flex items-center gap-3">
                @if($brandLogo)
                    <img src="{{ asset('storage/' . $brandLogo) }}" alt="{{ $brandName }}" class="h-10 w-auto object-contain max-w-[130px]">
                @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-600 to-emerald-600 flex items-center justify-center text-white text-xl font-black shadow-md shadow-teal-600/20">
                        🛡️
                    </div>
                @endif
                <div>
                    <h2 class="font-extrabold text-slate-900 text-sm sm:text-base leading-tight">{{ $brandName }}</h2>
                    <span class="text-xs font-semibold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md border border-teal-200/60 inline-block mt-0.5">
                        صندوق الشكاوى والمقترحات الرقمي
                    </span>
                </div>
            </div>

            <div>
                @if($isAuth && $authUser)
                    <div class="flex items-center gap-2">
                        <a href="{{ route('portal.dashboard') }}" 
                           class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-xl transition">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="hidden sm:inline">بوابة الموظف:</span>
                            <span class="truncate max-w-[100px]">{{ $authUser->name }}</span>
                            <span class="text-slate-400">↗</span>
                        </a>
                    </div>
                @else
                    <a href="{{ route('portal.login', ['redirect' => '/complaints']) }}" 
                       class="text-xs font-bold text-teal-700 hover:text-teal-800 bg-teal-50 hover:bg-teal-100 border border-teal-200 px-3.5 py-1.5 rounded-xl transition inline-flex items-center gap-1.5">
                        <span>👤</span>
                        <span>دخول الموظفين</span>
                    </a>
                @endif
            </div>
        </header>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- HERO SECTION & TRUST STATEMENT                                   --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        <div class="text-center mb-8">
            {{-- Trust Badge --}}
            <div class="inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-extrabold px-3.5 py-1.5 rounded-full shadow-sm mb-4">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-600"></span>
                </span>
                <span>منصة سرية ومحايدة 100% | حوكمة إدارية لحفظ حقوقك وبيئة العمل</span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2.5">
                صوتك مسموع، وبيئة عملك أمانة
            </h1>
            <p class="text-sm sm:text-base font-medium text-slate-600 max-w-xl mx-auto leading-relaxed">
                مساحتك الآمنة لمشاركة الملاحظات، الشكاوى، أو الأفكار التطويرية. نضمن لك التحقيق المحايد، التوثيق الآلي، والسرية المطلقة دون أي تعارض مصالح.
            </p>

            {{-- 3 Value Pillars --}}
            <div class="grid grid-cols-3 gap-2 sm:gap-3 max-w-lg mx-auto mt-6">
                <div class="bg-white/80 border border-slate-200/80 rounded-xl p-2.5 text-center shadow-xs">
                    <span class="text-lg block mb-0.5">🔒</span>
                    <strong class="text-xs font-bold text-slate-800 block">سرية تامة</strong>
                    <span class="text-[10px] text-slate-500">حجب الهوية اختياري</span>
                </div>
                <div class="bg-white/80 border border-slate-200/80 rounded-xl p-2.5 text-center shadow-xs">
                    <span class="text-lg block mb-0.5">⚡</span>
                    <strong class="text-xs font-bold text-slate-800 block">تتبع فوري</strong>
                    <span class="text-[10px] text-slate-500">برقم تذكرة مشفر</span>
                </div>
                <div class="bg-white/80 border border-slate-200/80 rounded-xl p-2.5 text-center shadow-xs">
                    <span class="text-lg block mb-0.5">⚖️</span>
                    <strong class="text-xs font-bold text-slate-800 block">تحقيق محايد</strong>
                    <span class="text-[10px] text-slate-500">فريق HR مخصص</span>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- MODE SWITCHER (TABS)                                             --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($viewMode !== 'success')
            <div class="flex bg-slate-200/70 p-1.5 rounded-2xl max-w-sm mx-auto mb-6 shadow-inner">
                <button type="button"
                        wire:click="switchMode('submit')"
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-xs sm:text-sm font-extrabold transition-all duration-200 {{ $viewMode === 'submit' ? 'bg-white text-teal-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    <span>✍️</span>
                    <span>تقديم جديد</span>
                </button>
                <button type="button"
                        wire:click="switchMode('track')"
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-xs sm:text-sm font-extrabold transition-all duration-200 {{ $viewMode === 'track' ? 'bg-white text-teal-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    <span>🔍</span>
                    <span>متابعة تذكرة</span>
                </button>
            </div>
        @endif

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- MODE 1: SUBMIT NEW GRIEVANCE / SUGGESTION                        --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($viewMode === 'submit')
            <div class="bg-white border border-slate-200 rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden">

                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-teal-50 via-slate-50 to-emerald-50 border-b border-slate-200 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-teal-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                            1
                        </span>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">استمارة تسجيل شكوى أو مقترح</h3>
                            <p class="text-xs text-slate-500 font-medium">املأ الحقول التالية بدقة؛ جميع البيانات مشفرة وتخضع لسياسة الخصوصية</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-bold text-slate-500 bg-white border border-slate-200 px-2.5 py-1 rounded-lg">
                        حقول إلزامية (<span class="text-rose-500">*</span>)
                    </span>
                </div>

                <form wire:submit="submit" class="p-6 sm:p-8 space-y-7">

                    {{-- ── Step 1: Category Selection Cards ── --}}
                    <div>
                        <label class="block text-xs sm:text-sm font-extrabold text-slate-900 mb-2.5">
                            1. تصنيف الموضوع أو الطلب <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">

                            {{-- Option 1: Administrative --}}
                            <div wire:click="$set('type', 'administrative')"
                                 class="relative cursor-pointer rounded-2xl p-3.5 border-2 text-right transition-all duration-200 flex flex-col justify-between group {{ $type === 'administrative' ? 'border-teal-600 bg-teal-50/70 shadow-sm ring-2 ring-teal-600/20' : 'border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50/50' }}">
                                @if($type === 'administrative')
                                    <span class="absolute top-2.5 left-2.5 w-4 h-4 rounded-full bg-teal-600 text-white text-[10px] flex items-center justify-center font-bold">✓</span>
                                @endif
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg mb-2 {{ $type === 'administrative' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700 group-hover:bg-slate-200' }}">
                                    🏛️
                                </div>
                                <div>
                                    <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug">إدارية أو مالية</h4>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 leading-tight">رواتب، بدلات، إجازات، ساعات عمل</p>
                                </div>
                            </div>

                            {{-- Option 2: Workplace --}}
                            <div wire:click="$set('type', 'workplace')"
                                 class="relative cursor-pointer rounded-2xl p-3.5 border-2 text-right transition-all duration-200 flex flex-col justify-between group {{ $type === 'workplace' ? 'border-teal-600 bg-teal-50/70 shadow-sm ring-2 ring-teal-600/20' : 'border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50/50' }}">
                                @if($type === 'workplace')
                                    <span class="absolute top-2.5 left-2.5 w-4 h-4 rounded-full bg-teal-600 text-white text-[10px] flex items-center justify-center font-bold">✓</span>
                                @endif
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg mb-2 {{ $type === 'workplace' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700 group-hover:bg-slate-200' }}">
                                    🏢
                                </div>
                                <div>
                                    <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug">بيئة العمل</h4>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 leading-tight">مكاتب، أجهزة، تكييف، أدوات عمل</p>
                                </div>
                            </div>

                            {{-- Option 3: Behavioral --}}
                            <div wire:click="$set('type', 'behavioral')"
                                 class="relative cursor-pointer rounded-2xl p-3.5 border-2 text-right transition-all duration-200 flex flex-col justify-between group {{ $type === 'behavioral' ? 'border-teal-600 bg-teal-50/70 shadow-sm ring-2 ring-teal-600/20' : 'border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50/50' }}">
                                @if($type === 'behavioral')
                                    <span class="absolute top-2.5 left-2.5 w-4 h-4 rounded-full bg-teal-600 text-white text-[10px] flex items-center justify-center font-bold">✓</span>
                                @endif
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg mb-2 {{ $type === 'behavioral' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700 group-hover:bg-slate-200' }}">
                                    🤝
                                </div>
                                <div>
                                    <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug">سلوكية ونزاع</h4>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 leading-tight">معاملة، سوء تفاهم، بيئة الفريق</p>
                                </div>
                            </div>

                            {{-- Option 4: Suggestion --}}
                            <div wire:click="$set('type', 'suggestion')"
                                 class="relative cursor-pointer rounded-2xl p-3.5 border-2 text-right transition-all duration-200 flex flex-col justify-between group {{ $type === 'suggestion' ? 'border-teal-600 bg-teal-50/70 shadow-sm ring-2 ring-teal-600/20' : 'border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50/50' }}">
                                @if($type === 'suggestion')
                                    <span class="absolute top-2.5 left-2.5 w-4 h-4 rounded-full bg-teal-600 text-white text-[10px] flex items-center justify-center font-bold">✓</span>
                                @endif
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg mb-2 {{ $type === 'suggestion' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700 group-hover:bg-slate-200' }}">
                                    💡
                                </div>
                                <div>
                                    <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug">مقترح تطويري</h4>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 leading-tight">مبادرة، أفكار تحسين، كفاءة العمل</p>
                                </div>
                            </div>

                        </div>
                        @error('type') <span class="text-xs font-bold text-rose-600 mt-1.5 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- ── Step 2: Privacy & Identity Selection ── --}}
                    <div class="bg-slate-50 border border-slate-200/90 rounded-2xl p-4 sm:p-5">
                        <label class="block text-xs sm:text-sm font-extrabold text-slate-900 mb-3">
                            2. وضع الخصوصية وهوية مُقدّم الطلب <span class="text-rose-500">*</span>
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            {{-- Option A: Anonymous --}}
                            <div wire:click="$set('is_anonymous', true)"
                                 class="cursor-pointer rounded-2xl p-4 border-2 transition-all duration-200 flex items-start gap-3.5 {{ $is_anonymous ? 'border-emerald-600 bg-emerald-50/80 shadow-xs ring-2 ring-emerald-600/20' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl shrink-0">
                                    🔒
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <strong class="text-xs sm:text-sm font-extrabold text-slate-900">سري ومجهول الهوية بالكامل</strong>
                                        <span class="text-[10px] font-extrabold bg-emerald-200 text-emerald-900 px-2 py-0.5 rounded-md">مُوصى به</span>
                                    </div>
                                    <p class="text-xs text-slate-600 font-medium mt-1 leading-relaxed">
                                        لن يتم تسجيل اسمك، إيميلك، أو هويتك مطلقاً. ستحصل على كود تذكرة مشفر لمتابعة النتيجة بمفردك.
                                    </p>
                                </div>
                            </div>

                            {{-- Option B: Named --}}
                            <div wire:click="$set('is_anonymous', false)"
                                 class="cursor-pointer rounded-2xl p-4 border-2 transition-all duration-200 flex items-start gap-3.5 {{ ! $is_anonymous ? 'border-teal-600 bg-teal-50/80 shadow-xs ring-2 ring-teal-600/20' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                                <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center text-xl shrink-0">
                                    👤
                                </div>
                                <div class="flex-1">
                                    <strong class="text-xs sm:text-sm font-extrabold text-slate-900">تقديم بالهوية الرسمية</strong>
                                    <p class="text-xs text-slate-600 font-medium mt-1 leading-relaxed">
                                        يسمح لفريق الموارد البشرية بالتواصل المباشر معك لسماع التفاصيل وموافاتك بالإجراءات المتخذة.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Submitter Inputs (when NOT anonymous) --}}
                        @if(! $is_anonymous)
                            <div class="mt-4 pt-4 border-t border-slate-200 grid grid-cols-1 sm:grid-cols-2 gap-3.5 animate-fadeIn">
                                @if($isAuth && $authUser)
                                    <div class="sm:col-span-2 bg-white border border-teal-200 rounded-xl p-3 flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-lg bg-teal-600 text-white flex items-center justify-center font-bold text-xs">✓</span>
                                        <div class="text-xs">
                                            <span class="text-slate-500 font-medium">سيتم التقديم رسمياً بحسابك المعتمد:</span>
                                            <strong class="text-slate-900 block font-bold mt-0.5">{{ $submitter_name ?: $authUser->name }} ({{ $submitter_contact ?: $authUser->email }})</strong>
                                        </div>
                                    </div>
                                @else
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">الاسم الكامل <span class="text-rose-500">*</span></label>
                                        <input type="text"
                                               wire:model="submitter_name"
                                               placeholder="اكتب اسمك الكامل أو الكود الوظيفي"
                                               class="w-full text-xs sm:text-sm bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                                        @error('submitter_name') <span class="text-xs font-bold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">رقم الهاتف أو الإيميل (اختياري للتواصل)</label>
                                        <input type="text"
                                               wire:model="submitter_contact"
                                               placeholder="مثال: 05xxxxxxx أو email@company.com"
                                               class="w-full text-xs sm:text-sm bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                                        @error('submitter_contact') <span class="text-xs font-bold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- ── Step 3: Priority Selection ── --}}
                    <div>
                        <label class="block text-xs sm:text-sm font-extrabold text-slate-900 mb-2">
                            3. درجة الأهمية والاستعجال <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @php
                                $priorities = [
                                    'low' => ['label' => 'منخفضة (استفسار / فكرة)', 'icon' => '🟢', 'color' => 'peer-checked:border-slate-800 peer-checked:bg-slate-900 peer-checked:text-white'],
                                    'normal' => ['label' => 'عادية (طبيعية)', 'icon' => '🔵', 'color' => 'peer-checked:border-teal-600 peer-checked:bg-teal-600 peer-checked:text-white'],
                                    'high' => ['label' => 'عالية (تؤثر على العمل)', 'icon' => '🟠', 'color' => 'peer-checked:border-amber-600 peer-checked:bg-amber-600 peer-checked:text-white'],
                                    'urgent' => ['label' => 'عاجلة جداً (أمر حرج ⚠️)', 'icon' => '🔴', 'color' => 'peer-checked:border-rose-600 peer-checked:bg-rose-600 peer-checked:text-white'],
                                ];
                            @endphp
                            @foreach($priorities as $pk => $pData)
                                <div wire:click="$set('priority', '{{ $pk }}')"
                                     class="cursor-pointer rounded-xl p-2.5 border text-center font-bold text-xs transition flex items-center justify-center gap-1.5 {{ $priority === $pk ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                                    <span>{{ $pData['icon'] }}</span>
                                    <span>{{ $pData['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Step 4: Subject Title ── --}}
                    <div>
                        <label class="block text-xs sm:text-sm font-extrabold text-slate-900 mb-1.5">
                            4. موضوع الشكوى أو عنوان المقترح <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                                📝
                            </span>
                            <input type="text"
                                   wire:model="title"
                                   placeholder="اكتب عنواناً موجزاً وواضحاً يلخص المشكلة أو الفكرة..."
                                   class="w-full text-xs sm:text-sm bg-white border border-slate-300 rounded-xl pr-10 pl-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition font-medium">
                        </div>
                        @error('title') <span class="text-xs font-bold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- ── Step 5: Department & Direct HR Escalation ── --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 items-center">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">القسم أو الإدارة المعنية (اختياري)</label>
                            <select wire:model="department_id"
                                    class="w-full text-xs sm:text-sm bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                                <option value="">— غير محدد / يخص الإدارة العامة —</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Bypass / Anti-Retaliation Guard --}}
                        <div class="bg-rose-50 border border-rose-200/90 rounded-2xl p-3.5">
                            <label class="flex items-start gap-2.5 cursor-pointer">
                                <input type="checkbox" wire:model="against_manager" class="w-4 h-4 mt-0.5 text-rose-600 rounded border-rose-300 focus:ring-rose-500 accent-rose-600 shrink-0">
                                <div>
                                    <strong class="text-xs font-black text-rose-950 block">حجب عن الإدارة المباشرة (تصعيد للـ HR) ⚠️</strong>
                                    <span class="text-[11px] text-rose-700 block leading-tight mt-0.5">
                                        توجيه الشكوى فوراً للإدارة العليا لمنع أي تعارض مصالح أو حرج مهني.
                                    </span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- ── Step 6: Detailed Content ── --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs sm:text-sm font-extrabold text-slate-900">
                                5. التفاصيل والوقائع الكاملة <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-slate-400 font-medium">الحد الأدنى 15 حرفاً</span>
                        </div>
                        <textarea wire:model="content"
                                  rows="5"
                                  placeholder="يرجى ذكر الوقائع والتواريخ وأي تفاصيل داعمة بوضوح وأمانة لمساعدة فريق المتابعة في فحص الموضوع..."
                                  class="w-full text-xs sm:text-sm bg-white border border-slate-300 rounded-2xl p-4 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition font-medium leading-relaxed"></textarea>
                        @error('content') <span class="text-xs font-bold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- ── Step 7: Smart File Attachment Zone ── --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            مستند أو صورة مؤيدة (اختياري)
                        </label>

                        @if(! $attachment)
                            <label class="border-2 border-dashed border-slate-300 hover:border-teal-500 bg-slate-50/70 hover:bg-teal-50/30 rounded-2xl p-5 flex flex-col items-center justify-center cursor-pointer transition group">
                                <span class="text-2xl mb-1 group-hover:scale-110 transition-transform">📎</span>
                                <span class="text-xs font-bold text-slate-700 group-hover:text-teal-700">اضغط هنا لرفع ملف أو اسحب الملف وأفلته هنا</span>
                                <span class="text-[11px] text-slate-400 mt-0.5">الصيغ المدعومة: PDF, Word, JPG, PNG (الحد الأقصى 5 ميجابايت)</span>
                                <input type="file" wire:model="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="hidden">
                            </label>
                        @else
                            <div class="bg-teal-50 border border-teal-200 rounded-2xl p-3.5 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center text-sm font-bold">📄</span>
                                    <div class="text-xs">
                                        <strong class="text-slate-900 block font-bold truncate max-w-[240px]">{{ $attachment->getClientOriginalName() }}</strong>
                                        <span class="text-teal-700 font-semibold">{{ number_format($attachment->getSize() / 1024, 1) }} KB</span>
                                    </div>
                                </div>
                                <button type="button" 
                                        wire:click="removeAttachment"
                                        class="text-xs font-bold text-rose-600 hover:text-rose-800 bg-white border border-rose-200 px-3 py-1.5 rounded-xl hover:bg-rose-50 transition">
                                    ✕ حذف الملف
                                </button>
                            </div>
                        @endif

                        <div wire:loading wire:target="attachment" class="text-xs font-bold text-teal-700 mt-2 flex items-center gap-2">
                            <span class="animate-spin">⏳</span>
                            <span>جارٍ معالجة ورفع الملف...</span>
                        </div>
                        @error('attachment') <span class="text-xs font-bold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- ── Submit Button ── --}}
                    <div class="pt-3 border-t border-slate-100">
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-teal-600 via-emerald-600 to-teal-700 hover:from-teal-700 hover:to-emerald-700 text-white font-black text-sm sm:text-base shadow-lg shadow-teal-600/25 hover:shadow-teal-600/35 active:scale-[0.99] transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60">
                            <span wire:loading.remove wire:target="submit">🚀 إرسال وتوثيق الشكوى رسمياً</span>
                            <span wire:loading wire:target="submit" class="inline-flex items-center gap-2">
                                <span class="animate-spin text-lg">⏳</span>
                                <span>جارٍ التوثيق وتوليد رقم التذكرة المشفر...</span>
                            </span>
                        </button>
                    </div>

                </form>
            </div>
        @endif

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- MODE 2: SUBMISSION SUCCESS (TICKET VOUCHER)                      --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($viewMode === 'success')
            <div class="bg-white border border-slate-200 rounded-3xl shadow-xl p-6 sm:p-10 text-center animate-fadeIn">

                {{-- Animated Checkmark --}}
                <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 text-3xl font-black flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-500/20 ring-8 ring-emerald-50">
                    ✓
                </div>

                <h2 class="text-xl sm:text-2xl font-black text-slate-900 mb-2">
                    تم استلام وتوثيق طلبك بنجاح!
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto leading-relaxed mb-6 font-medium">
                    شكراً لحرصك على بيئة العمل والنزاهة المؤسسية. تم تسجيل الموضوع في النظام الإداري بنجاح وإحالته للمراجعة الفورية.
                </p>

                {{-- Ticket Voucher Box --}}
                <div class="bg-gradient-to-b from-slate-50 to-white border-2 border-dashed border-teal-500 rounded-3xl p-6 max-w-md mx-auto mb-6 shadow-sm relative overflow-hidden">
                    <span class="text-[11px] font-extrabold text-teal-800 uppercase tracking-wider block mb-1">
                        رقم تذكرة المتابعة السرية الخاصة بك
                    </span>

                    <div class="font-mono text-2xl sm:text-3xl font-black text-teal-700 tracking-wider py-2 select-all direction-ltr" id="ticketNumberDisplay">
                        {{ $submittedTicketNumber }}
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-2.5 text-[11px] font-bold text-amber-900 my-3">
                        ⚠️ احتفظ بهذا الرقم في مكان آمن، فهو وسيلتك الوحيدة لمتابعة نتيجة التحقيق وقرار الإدارة لاحقاً.
                    </div>

                    {{-- Copy & WhatsApp Actions --}}
                    <div class="flex items-center justify-center gap-2 flex-wrap">
                        <button type="button"
                                onclick="navigator.clipboard.writeText('{{ $submittedTicketNumber }}'); const b = this; b.innerText = '✓ تم النسخ!'; setTimeout(() => b.innerText = '📋 نسخ الرقم', 2000);"
                                class="inline-flex items-center gap-1.5 text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-xl transition cursor-pointer">
                            📋 نسخ الرقم
                        </button>

                        <a href="https://api.whatsapp.com/send?text={{ urlencode('رقم تذكرة الشكوى/المقترح الخاصة بي في شركة ' . $brandName . ' هي: ' . $submittedTicketNumber . "\n" . 'رابط المتابعة: ' . url('/complaints?ticket=' . $submittedTicketNumber)) }}" 
                           target="_blank"
                           class="inline-flex items-center gap-1.5 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl transition">
                            <span>💬</span>
                            <span>إرسال لنفسي عبر واتساب</span>
                        </a>
                    </div>
                </div>

                {{-- Primary Action Buttons --}}
                <div class="flex items-center justify-center gap-3 flex-wrap">
                    <button type="button"
                            wire:click="trackSubmitted"
                            class="px-6 py-3 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs sm:text-sm shadow-md transition cursor-pointer">
                        متابعة حالة التذكرة الآن ↗
                    </button>

                    <button type="button"
                            wire:click="resetForm"
                            class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-extrabold text-xs sm:text-sm border border-slate-200 transition cursor-pointer">
                        تقديم موضوع آخر
                    </button>
                </div>

            </div>
        @endif

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- MODE 3: TICKET TRACKING & STATUS TIMELINE                         --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($viewMode === 'track')
            <div class="space-y-6">

                {{-- Search Box Card --}}
                <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-7 shadow-lg shadow-slate-200/40">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-9 h-9 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center text-lg font-bold">
                            🔍
                        </span>
                        <div>
                            <h3 class="font-black text-slate-900 text-sm sm:text-base">استعلام ومتابعة حالة التذكرة</h3>
                            <p class="text-xs text-slate-500 font-medium">أدخل رقم التذكرة للاطلاع على مسار التحقيق والرد الإداري الرسمي</p>
                        </div>
                    </div>

                    <form wire:submit="lookupTicket" class="mt-4 flex flex-col sm:flex-row gap-2.5">
                        <div class="relative flex-1">
                            <input type="text"
                                   wire:model="searchTicket"
                                   placeholder="مثال: GRV-2610-0001"
                                   class="w-full font-mono text-sm sm:text-base font-extrabold uppercase direction-ltr text-left bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                        </div>
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="py-3 px-6 bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-sm transition flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap">
                            <span wire:loading.remove wire:target="lookupTicket">بحث واستعلام</span>
                            <span wire:loading wire:target="lookupTicket" class="inline-flex items-center gap-1.5">
                                <span class="animate-spin">⏳</span>
                                <span>جارٍ الفحص...</span>
                            </span>
                        </button>
                    </form>

                    @if($trackError)
                        <div class="mt-4 bg-rose-50 border border-rose-200 rounded-2xl p-3.5 text-xs font-bold text-rose-800 flex items-center gap-2">
                            <span>⚠️</span>
                            <span>{{ $trackError }}</span>
                        </div>
                    @endif
                </div>

                {{-- Search Results Details --}}
                @if($trackedGrievance)
                    <div class="bg-white border border-slate-200 rounded-3xl shadow-xl overflow-hidden animate-fadeIn">

                        {{-- Header with Ticket Status --}}
                        <div class="bg-gradient-to-r from-slate-50 via-teal-50/50 to-slate-50 p-6 border-b border-slate-200 flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2.5 mb-1.5">
                                    <span class="font-mono text-xs font-black bg-teal-600 text-white px-2.5 py-0.5 rounded-lg direction-ltr">
                                        {{ $trackedGrievance->ticket_number }}
                                    </span>
                                    <span class="text-xs text-slate-500 font-semibold">
                                        {{ $trackedGrievance->created_at->format('Y/m/d H:i') }}
                                    </span>
                                </div>
                                <h3 class="font-black text-slate-900 text-base sm:text-lg">
                                    {{ $trackedGrievance->title }}
                                </h3>
                            </div>

                            {{-- Status Badge --}}
                            <div>
                                @php
                                    $statusBadge = match($trackedGrievance->status) {
                                        'resolved' => ['bg' => 'bg-emerald-100 text-emerald-800 border-emerald-300', 'label' => 'تم الحل والمعالجة بنجاح ✓'],
                                        'in_progress' => ['bg' => 'bg-sky-100 text-sky-800 border-sky-300', 'label' => 'قيد المراجعة والتحقيق ⏳'],
                                        'rejected' => ['bg' => 'bg-slate-100 text-slate-800 border-slate-300', 'label' => 'مغلقة / مؤرشفة'],
                                        default => ['bg' => 'bg-amber-100 text-amber-800 border-amber-300', 'label' => 'جديدة مسجلة بنجاح'],
                                    };
                                @endphp
                                <span class="inline-block px-3.5 py-1.5 rounded-full text-xs font-extrabold border {{ $statusBadge['bg'] }}">
                                    {{ $statusBadge['label'] }}
                                </span>
                            </div>
                        </div>

                        {{-- 3-Step Visual Progress Tracker --}}
                        <div class="p-6 border-b border-slate-200 bg-white">
                            <span class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-5">
                                المسار الإداري للتذكرة:
                            </span>

                            <div class="grid grid-cols-3 gap-2 text-center relative">
                                {{-- Connecting Line --}}
                                <div class="absolute top-4 left-1/6 right-1/6 h-0.5 bg-slate-200 -z-0"></div>

                                {{-- Step 1 --}}
                                <div class="relative z-10 flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-black mb-1.5 shadow-sm">
                                        ✓
                                    </div>
                                    <strong class="text-xs font-extrabold text-slate-900">تم التوثيق</strong>
                                    <span class="text-[10px] text-emerald-600 font-bold">مكتمل</span>
                                </div>

                                {{-- Step 2 --}}
                                <div class="relative z-10 flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black mb-1.5 shadow-sm {{ in_array($trackedGrievance->status, ['in_progress', 'resolved']) ? 'bg-emerald-600 text-white' : 'bg-amber-500 text-white' }}">
                                        {{ in_array($trackedGrievance->status, ['in_progress', 'resolved']) ? '✓' : '2' }}
                                    </div>
                                    <strong class="text-xs font-extrabold text-slate-900">قيد التحقيق والفحص</strong>
                                    <span class="text-[10px] font-bold {{ in_array($trackedGrievance->status, ['in_progress', 'resolved']) ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $trackedGrievance->status === 'new' ? 'في انتظار البدء' : ($trackedGrievance->status === 'in_progress' ? 'جارٍ العمل عليها' : 'مكتمل') }}
                                    </span>
                                </div>

                                {{-- Step 3 --}}
                                <div class="relative z-10 flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black mb-1.5 shadow-sm {{ $trackedGrievance->status === 'resolved' ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                                        {{ $trackedGrievance->status === 'resolved' ? '✓' : '3' }}
                                    </div>
                                    <strong class="text-xs font-extrabold {{ $trackedGrievance->status === 'resolved' ? 'text-slate-900' : 'text-slate-400' }}">
                                        الحل والرد الرسمي
                                    </strong>
                                    <span class="text-[10px] font-bold {{ $trackedGrievance->status === 'resolved' ? 'text-emerald-600' : 'text-slate-400' }}">
                                        {{ $trackedGrievance->status === 'resolved' ? 'تم القرار' : 'بانتظار القرار' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Grievance Details & HR Response --}}
                        <div class="p-6 sm:p-7 space-y-5">

                            {{-- Submitter Details Preview --}}
                            <div>
                                <span class="text-xs font-bold text-slate-500 block mb-1">تفاصيل الشكوى المسجلة:</span>
                                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line font-medium">
                                    {{ $trackedGrievance->content }}
                                </div>
                            </div>

                            {{-- Official HR Resolution Box --}}
                            @if($trackedGrievance->hr_response)
                                <div class="bg-emerald-50/80 border-2 border-emerald-300 rounded-2xl p-5 shadow-xs">
                                    <div class="flex items-center justify-between mb-2 pb-2 border-b border-emerald-200">
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg">🛡️</span>
                                            <strong class="text-xs sm:text-sm font-black text-emerald-950">رد وقرار إدارة الموارد البشرية الرسمي</strong>
                                        </div>
                                        @if($trackedGrievance->resolved_at)
                                            <span class="text-[11px] font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-md">
                                                بتاريخ: {{ $trackedGrievance->resolved_at->format('Y/m/d') }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs sm:text-sm font-bold text-emerald-900 leading-relaxed whitespace-pre-line">
                                        {{ $trackedGrievance->hr_response }}
                                    </p>
                                </div>
                            @else
                                <div class="bg-sky-50 border border-sky-200 rounded-2xl p-4 text-xs font-bold text-sky-800 flex items-center gap-2.5">
                                    <span class="text-lg">⏳</span>
                                    <span>تذكرتك قيد الدراسة والمتابعة من قِبل الفريق المختص. سيتم تحديث الرد والإجراء المتخذ هنا فور صدوره.</span>
                                </div>
                            @endif

                        </div>

                    </div>
                @endif

            </div>
        @endif

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- FOOTER HELP & ESS LINK                                           --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        <footer class="text-center mt-12 pt-6 border-t border-slate-200">
            <p class="text-xs text-slate-500 font-medium">
                هل أنت موظف بالشركة وتبحث عن خدمات الإجازات والرواتب؟
                <a href="{{ route('portal.dashboard') }}" class="text-teal-700 hover:text-teal-900 font-black underline mr-1">
                    الدخول إلى بوابة الموظف الذاتية (ESS)
                </a>
            </p>
            <p class="text-[11px] text-slate-400 mt-1">
                {{ $brandName }} &copy; {{ date('Y') }} — جميع الحقوق محفوظة | نظام الحوكمة وإدارة الموارد البشرية
            </p>
        </footer>

    </div>
</div>
