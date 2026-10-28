<div class="min-h-screen bg-slate-50/70 relative py-8 sm:py-10 px-4 sm:px-6 lg:px-8 font-cairo text-slate-800 antialiased" dir="rtl">

    {{-- Subtle Ambient Background Gradient --}}
    <div class="absolute inset-0 bg-gradient-to-b from-teal-50/30 via-white/60 to-slate-100/50 -z-10 pointer-events-none"></div>

    @php
        $typeInfo = [
            'administrative' => [
                'label' => 'إدارية أو مالية',
                'icon' => '🏛️',
                'desc' => 'رواتب، بدلات، مكافآت، ساعات عمل، إجازات'
            ],
            'workplace' => [
                'label' => 'بيئة العمل والتجهيزات',
                'icon' => '🏢',
                'desc' => 'مكاتب، أجهزة، تكييف، أدوات وتجهيزات السلامة'
            ],
            'behavioral' => [
                'label' => 'سلوكية ونزاع عمل',
                'icon' => '🤝',
                'desc' => 'معاملة، سوء تفاهم، بيئة الفريق والتعامل المهني'
            ],
            'suggestion' => [
                'label' => 'مقترح تطويري ومبادرة',
                'icon' => '💡',
                'desc' => 'مبادرة، أفكار تحسين، كفاءة وتطوير منظومة العمل'
            ],
            'other' => [
                'label' => 'أخرى',
                'icon' => '📋',
                'desc' => 'موضوعات أو ملاحظات عامة أخرى'
            ],
        ];

        $priorityInfo = [
            'low' => ['label' => 'منخفضة (استفسار / فكرة)', 'badge' => 'bg-slate-100 text-slate-700 border-slate-300', 'icon' => '🟢'],
            'normal' => ['label' => 'عادية (طبيعية)', 'badge' => 'bg-teal-50 text-teal-800 border-teal-300', 'icon' => '🔵'],
            'high' => ['label' => 'عالية (تؤثر على العمل)', 'badge' => 'bg-amber-50 text-amber-800 border-amber-300', 'icon' => '🟠'],
            'urgent' => ['label' => 'عاجلة جداً (أمر حرج ⚠️)', 'badge' => 'bg-rose-50 text-rose-800 border-rose-300', 'icon' => '🔴'],
        ];
    @endphp

    <div class="max-w-5xl mx-auto">

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- TOP NAVIGATION / BRAND BAR                                       --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        <header class="flex items-center justify-between bg-white/90 backdrop-blur-md border border-slate-200/90 rounded-2xl px-5 sm:px-6 py-3.5 mb-8 shadow-xs">
            <div class="flex items-center gap-3.5">
                @if($brandLogo)
                    <img src="{{ asset('storage/' . $brandLogo) }}" alt="{{ $brandName }}" class="h-10 w-auto object-contain max-w-[130px]">
                @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-600 to-emerald-600 flex items-center justify-center text-white text-lg font-black shadow-md shadow-teal-600/20">
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
                    <a href="{{ route('portal.dashboard') }}" 
                       class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 px-3.5 py-2 rounded-xl transition">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="hidden sm:inline">بوابة الموظف:</span>
                        <span class="truncate max-w-[120px]">{{ $authUser->name }}</span>
                        <span class="text-slate-400">↗</span>
                    </a>
                @else
                    <a href="{{ route('portal.login', ['redirect' => '/complaints']) }}" 
                       class="text-xs font-bold text-teal-700 hover:text-teal-800 bg-teal-50 hover:bg-teal-100 border border-teal-200 px-3.5 py-2 rounded-xl transition inline-flex items-center gap-1.5">
                        <span>👤</span>
                        <span>دخول الموظفين</span>
                    </a>
                @endif
            </div>
        </header>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- HERO SECTION (REFINED & CALM)                                    --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        <div class="text-center mb-8 max-w-2xl mx-auto">
            <div class="inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs font-bold px-3.5 py-1.5 rounded-full shadow-xs mb-3">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-600"></span>
                </span>
                <span>منصة سرية ومحايدة 100% | حوكمة إدارية لحفظ حقوقك وبيئة العمل</span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight mb-2.5">
                صوتك مسموع، وبيئة عملك أمانة
            </h1>
            <p class="text-xs sm:text-sm lg:text-base font-medium text-slate-600 leading-relaxed">
                مساحتك الآمنة لمشاركة الملاحظات والشكاوى والمقترحات بسرية تامة ومتابعة محايدة من فريق الموارد البشرية.
            </p>
        </div>

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- MODE SWITCHER (TABS)                                             --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($viewMode !== 'success')
            <div class="flex bg-slate-200/70 p-1.5 rounded-2xl max-w-xs sm:max-w-sm mx-auto mb-8 shadow-inner">
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
        {{-- MODE 1: SUBMIT (MULTI-STEP WIZARD)                               --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($viewMode === 'submit')
            <div class="space-y-6">

                {{-- ── Wizard Stepper Header ── --}}
                <div class="bg-white border border-slate-200/90 rounded-3xl shadow-sm overflow-hidden">
                    {{-- Progress Line --}}
                    <div class="h-1.5 w-full bg-slate-100">
                        <div class="h-full bg-gradient-to-r from-teal-500 to-emerald-500 transition-all duration-300"
                             style="width: {{ $currentStep === 1 ? '33.33%' : ($currentStep === 2 ? '66.66%' : '100%') }}"></div>
                    </div>

                    <div class="p-3 sm:p-5">
                        <div class="grid grid-cols-3 gap-2 sm:gap-4">

                            {{-- Step 1 Button --}}
                            <button type="button"
                                    wire:click="goToStep(1)"
                                    class="flex items-center gap-2 sm:gap-3.5 p-2 sm:p-3 rounded-2xl text-right transition-all {{ $currentStep === 1 ? 'bg-teal-50/80 border border-teal-200/80 text-teal-950 shadow-xs' : ($currentStep > 1 ? 'hover:bg-slate-50 text-slate-700 cursor-pointer' : 'text-slate-400') }}">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center text-xs sm:text-sm font-black shrink-0 transition-all {{ $currentStep > 1 ? 'bg-emerald-600 text-white shadow-xs' : ($currentStep === 1 ? 'bg-teal-600 text-white shadow-md shadow-teal-600/20 ring-2 ring-teal-600/30' : 'bg-slate-100 text-slate-400 border border-slate-200') }}">
                                    {{ $currentStep > 1 ? '✓' : '1' }}
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] sm:text-xs font-bold block {{ $currentStep === 1 ? 'text-teal-700' : ($currentStep > 1 ? 'text-emerald-700' : 'text-slate-400') }}">
                                        الخطوة 1
                                    </span>
                                    <strong class="text-xs sm:text-sm font-extrabold truncate block">
                                        التصنيف والخصوصية
                                    </strong>
                                </div>
                            </button>

                            {{-- Step 2 Button --}}
                            <button type="button"
                                    @if($currentStep > 2) wire:click="goToStep(2)" @endif
                                    class="flex items-center gap-2 sm:gap-3.5 p-2 sm:p-3 rounded-2xl text-right transition-all {{ $currentStep === 2 ? 'bg-teal-50/80 border border-teal-200/80 text-teal-950 shadow-xs' : ($currentStep > 2 ? 'hover:bg-slate-50 text-slate-700 cursor-pointer' : 'text-slate-400') }}">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center text-xs sm:text-sm font-black shrink-0 transition-all {{ $currentStep > 2 ? 'bg-emerald-600 text-white shadow-xs' : ($currentStep === 2 ? 'bg-teal-600 text-white shadow-md shadow-teal-600/20 ring-2 ring-teal-600/30' : 'bg-slate-100 text-slate-400 border border-slate-200') }}">
                                    {{ $currentStep > 2 ? '✓' : '2' }}
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] sm:text-xs font-bold block {{ $currentStep === 2 ? 'text-teal-700' : ($currentStep > 2 ? 'text-emerald-700' : 'text-slate-400') }}">
                                        الخطوة 2
                                    </span>
                                    <strong class="text-xs sm:text-sm font-extrabold truncate block">
                                        تفاصيل الموضوع
                                    </strong>
                                </div>
                            </button>

                            {{-- Step 3 Button --}}
                            <button type="button"
                                    class="flex items-center gap-2 sm:gap-3.5 p-2 sm:p-3 rounded-2xl text-right transition-all {{ $currentStep === 3 ? 'bg-teal-50/80 border border-teal-200/80 text-teal-950 shadow-xs' : 'text-slate-400' }}">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center text-xs sm:text-sm font-black shrink-0 transition-all {{ $currentStep === 3 ? 'bg-teal-600 text-white shadow-md shadow-teal-600/20 ring-2 ring-teal-600/30' : 'bg-slate-100 text-slate-400 border border-slate-200') }}">
                                    3
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] sm:text-xs font-bold block {{ $currentStep === 3 ? 'text-teal-700' : 'text-slate-400') }}">
                                        الخطوة 3
                                    </span>
                                    <strong class="text-xs sm:text-sm font-extrabold truncate block">
                                        المرفقات والمراجعة
                                    </strong>
                                </div>
                            </button>

                        </div>
                    </div>
                </div>

                {{-- ── Main Wizard Form Container ── --}}
                <div class="bg-white border border-slate-200/90 rounded-3xl shadow-xl shadow-slate-200/40 overflow-hidden">

                    <form wire:submit="submit">

                        {{-- ──────────────────────────────────────────────────────── --}}
                        {{-- WIZARD STEP 1: CATEGORY & PRIVACY                        --}}
                        {{-- ──────────────────────────────────────────────────────── --}}
                        @if($currentStep === 1)
                            <div class="p-6 sm:p-8 lg:p-10 space-y-8 animate-fadeIn">

                                {{-- Step Section Title --}}
                                <div class="border-b border-slate-100 pb-5">
                                    <div class="flex items-center justify-between flex-wrap gap-2">
                                        <div>
                                            <h3 class="text-base sm:text-lg font-black text-slate-900">الخطوة الأولى: تحديد التصنيف ووضع الخصوصية</h3>
                                            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">اختر المجال الأنسب لموضوعك وحدد إذا كنت ترغب في التقديم المجهول أو الرسمي</p>
                                        </div>
                                        <span class="text-xs font-extrabold text-teal-800 bg-teal-50 border border-teal-200/70 px-3 py-1 rounded-xl">
                                            1 من 3
                                        </span>
                                    </div>
                                </div>

                                {{-- Category Selection Grid --}}
                                <div>
                                    <label class="block text-xs sm:text-sm font-extrabold text-slate-900 mb-3">
                                        1. تصنيف الموضوع أو الشكوى <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">

                                        {{-- 1: Administrative --}}
                                        <div wire:click="$set('type', 'administrative')"
                                             class="relative cursor-pointer rounded-2xl p-4 sm:p-5 border-2 text-right transition-all duration-200 flex flex-col justify-between group {{ $type === 'administrative' ? 'border-teal-600 bg-teal-50/70 shadow-sm ring-2 ring-teal-600/20' : 'border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50/60' }}">
                                            @if($type === 'administrative')
                                                <span class="absolute top-3 left-3 w-5 h-5 rounded-full bg-teal-600 text-white text-xs flex items-center justify-center font-bold shadow-xs">✓</span>
                                            @endif
                                            <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl mb-3 transition-colors {{ $type === 'administrative' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700 group-hover:bg-slate-200' }}">
                                                🏛️
                                            </div>
                                            <div>
                                                <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug">إدارية أو مالية</h4>
                                                <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-1 leading-relaxed">رواتب، بدلات، مكافآت، ساعات عمل، إجازات</p>
                                            </div>
                                        </div>

                                        {{-- 2: Workplace --}}
                                        <div wire:click="$set('type', 'workplace')"
                                             class="relative cursor-pointer rounded-2xl p-4 sm:p-5 border-2 text-right transition-all duration-200 flex flex-col justify-between group {{ $type === 'workplace' ? 'border-teal-600 bg-teal-50/70 shadow-sm ring-2 ring-teal-600/20' : 'border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50/60' }}">
                                            @if($type === 'workplace')
                                                <span class="absolute top-3 left-3 w-5 h-5 rounded-full bg-teal-600 text-white text-xs flex items-center justify-center font-bold shadow-xs">✓</span>
                                            @endif
                                            <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl mb-3 transition-colors {{ $type === 'workplace' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700 group-hover:bg-slate-200' }}">
                                                🏢
                                            </div>
                                            <div>
                                                <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug">بيئة ومكان العمل</h4>
                                                <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-1 leading-relaxed">مكاتب، أجهزة، تكييف، أدوات وتجهيزات السلامة</p>
                                            </div>
                                        </div>

                                        {{-- 3: Behavioral --}}
                                        <div wire:click="$set('type', 'behavioral')"
                                             class="relative cursor-pointer rounded-2xl p-4 sm:p-5 border-2 text-right transition-all duration-200 flex flex-col justify-between group {{ $type === 'behavioral' ? 'border-teal-600 bg-teal-50/70 shadow-sm ring-2 ring-teal-600/20' : 'border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50/60' }}">
                                            @if($type === 'behavioral')
                                                <span class="absolute top-3 left-3 w-5 h-5 rounded-full bg-teal-600 text-white text-xs flex items-center justify-center font-bold shadow-xs">✓</span>
                                            @endif
                                            <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl mb-3 transition-colors {{ $type === 'behavioral' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700 group-hover:bg-slate-200' }}">
                                                🤝
                                            </div>
                                            <div>
                                                <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug">سلوكية ونزاع عمل</h4>
                                                <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-1 leading-relaxed">معاملة، سوء تفاهم، بيئة الفريق والتعامل المهني</p>
                                            </div>
                                        </div>

                                        {{-- 4: Suggestion --}}
                                        <div wire:click="$set('type', 'suggestion')"
                                             class="relative cursor-pointer rounded-2xl p-4 sm:p-5 border-2 text-right transition-all duration-200 flex flex-col justify-between group {{ $type === 'suggestion' ? 'border-teal-600 bg-teal-50/70 shadow-sm ring-2 ring-teal-600/20' : 'border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50/60' }}">
                                            @if($type === 'suggestion')
                                                <span class="absolute top-3 left-3 w-5 h-5 rounded-full bg-teal-600 text-white text-xs flex items-center justify-center font-bold shadow-xs">✓</span>
                                            @endif
                                            <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl mb-3 transition-colors {{ $type === 'suggestion' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700 group-hover:bg-slate-200' }}">
                                                💡
                                            </div>
                                            <div>
                                                <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug">مقترح تطويري</h4>
                                                <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-1 leading-relaxed">مبادرة، أفكار تحسين، كفاءة وتطوير منظومة العمل</p>
                                            </div>
                                        </div>

                                    </div>
                                    @error('type') <span class="text-xs font-bold text-rose-600 mt-2 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Privacy & Identity Selection --}}
                                <div class="bg-slate-50/70 border border-slate-200/90 rounded-2xl p-5 sm:p-6">
                                    <label class="block text-xs sm:text-sm font-extrabold text-slate-900 mb-3">
                                        2. تحديد وضع الخصوصية وهوية مُقدّم الطلب <span class="text-rose-500">*</span>
                                    </label>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                        {{-- Option A: Anonymous --}}
                                        <div wire:click="$set('is_anonymous', true)"
                                             class="cursor-pointer rounded-2xl p-4 sm:p-5 border-2 transition-all duration-200 flex items-start gap-4 {{ $is_anonymous ? 'border-emerald-600 bg-emerald-50/70 shadow-xs ring-2 ring-emerald-600/20' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                                            <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl shrink-0">
                                                🔒
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <strong class="text-xs sm:text-sm font-extrabold text-slate-900">تقديم سري ومجهول الهوية 100%</strong>
                                                    <span class="text-[10px] font-extrabold bg-emerald-200 text-emerald-950 px-2 py-0.5 rounded-md">مُوصى به</span>
                                                </div>
                                                <p class="text-xs text-slate-600 font-medium leading-relaxed">
                                                    لن يتم تسجيل اسمك، بريدك، أو هويتك مطلقاً. ستستلم كود تذكرة مشفر لمتابعة النتائج والإجراءات الإدارية بنفسك.
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Option B: Named --}}
                                        <div wire:click="$set('is_anonymous', false)"
                                             class="cursor-pointer rounded-2xl p-4 sm:p-5 border-2 transition-all duration-200 flex items-start gap-4 {{ ! $is_anonymous ? 'border-teal-600 bg-teal-50/70 shadow-xs ring-2 ring-teal-600/20' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                                            <div class="w-11 h-11 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center text-xl shrink-0">
                                                👤
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <strong class="text-xs sm:text-sm font-extrabold text-slate-900">تقديم بالهوية الرسمية</strong>
                                                    <span class="text-[10px] font-bold text-teal-800 bg-teal-100 px-2 py-0.5 rounded-md">متابعة مباشرة</span>
                                                </div>
                                                <p class="text-xs text-slate-600 font-medium leading-relaxed">
                                                    يتيح لإدارة الموارد البشرية التواصل المباشر معك لسماع التفاصيل وموافاتك بالإجراءات المتخذة.
                                                </p>
                                            </div>
                                        </div>

                                    </div>

                                    {{-- Submitter Inputs (when NOT anonymous) --}}
                                    @if(! $is_anonymous)
                                        <div class="mt-5 pt-5 border-t border-slate-200 animate-fadeIn">
                                            @if($isAuth && $authUser)
                                                <div class="bg-white border border-teal-200 rounded-2xl p-4 flex items-center gap-3.5 shadow-xs">
                                                    <span class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center font-black text-sm">✓</span>
                                                    <div class="text-xs sm:text-sm">
                                                        <span class="text-slate-500 font-medium">سيتم التقديم رسمياً عبر ملفك الوظيفي المعتمد:</span>
                                                        <strong class="text-slate-900 block font-black mt-0.5">{{ $submitter_name ?: $authUser->name }} ({{ $submitter_contact ?: $authUser->email }})</strong>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الاسم الكامل <span class="text-rose-500">*</span></label>
                                                        <input type="text"
                                                               wire:model="submitter_name"
                                                               placeholder="اكتب اسمك الكامل أو الكود الوظيفي"
                                                               class="w-full text-xs sm:text-sm bg-white border border-slate-300 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                                                        @error('submitter_name') <span class="text-xs font-bold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم الهاتف أو البريد الإلكتروني (اختياري للتواصل)</label>
                                                        <input type="text"
                                                               wire:model="submitter_contact"
                                                               placeholder="مثال: 05xxxxxxx أو email@company.com"
                                                               class="w-full text-xs sm:text-sm bg-white border border-slate-300 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                                                        @error('submitter_contact') <span class="text-xs font-bold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                {{-- Step 1 Actions --}}
                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-3">
                                    <span class="text-xs text-slate-500 font-medium">
                                        🔒 خاضع لسياسة الحوكمة والسرية التامة
                                    </span>

                                    <button type="button"
                                            wire:click="nextStep"
                                            class="inline-flex items-center gap-2 py-3.5 px-6 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-black text-xs sm:text-sm shadow-md shadow-teal-600/20 hover:shadow-teal-600/30 transition-all cursor-pointer">
                                        <span>المتابعة للخطوة التالية: تفاصيل الموضوع</span>
                                        <span class="text-base">←</span>
                                    </button>
                                </div>

                            </div>
                        @endif

                        {{-- ──────────────────────────────────────────────────────── --}}
                        {{-- WIZARD STEP 2: DETAILS & CONTENT                         --}}
                        {{-- ──────────────────────────────────────────────────────── --}}
                        @if($currentStep === 2)
                            <div class="p-6 sm:p-8 lg:p-10 space-y-7 animate-fadeIn">

                                {{-- Step Section Title --}}
                                <div class="border-b border-slate-100 pb-5">
                                    <div class="flex items-center justify-between flex-wrap gap-2">
                                        <div>
                                            <h3 class="text-base sm:text-lg font-black text-slate-900">الخطوة الثانية: تفاصيل ووقائع الموضوع</h3>
                                            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">اكتب البيانات بدقة وأمانة لتسهيل الفحص والتحقيق الإداري المحايد</p>
                                        </div>
                                        <span class="text-xs font-extrabold text-teal-800 bg-teal-50 border border-teal-200/70 px-3 py-1 rounded-xl">
                                            2 من 3
                                        </span>
                                    </div>
                                </div>

                                {{-- Title Input --}}
                                <div>
                                    <label class="block text-xs sm:text-sm font-extrabold text-slate-900 mb-2">
                                        1. موضوع الشكوى أو عنوان المقترح <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                            📝
                                        </span>
                                        <input type="text"
                                               wire:model="title"
                                               placeholder="اكتب عنواناً موجزاً وواضحاً يلخص المشكلة أو الفكرة..."
                                               class="w-full text-xs sm:text-sm bg-white border border-slate-300 rounded-2xl pr-11 pl-4 py-3.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition font-medium">
                                    </div>
                                    @error('title') <span class="text-xs font-bold text-rose-600 mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Priority Selection Pills --}}
                                <div>
                                    <label class="block text-xs sm:text-sm font-extrabold text-slate-900 mb-2">
                                        2. درجة الأهمية والاستعجال <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                        @foreach($priorityInfo as $pk => $pData)
                                            <div wire:click="$set('priority', '{{ $pk }}')"
                                                 class="cursor-pointer rounded-2xl p-3 border-2 text-center font-bold text-xs transition-all flex items-center justify-center gap-2 {{ $priority === $pk ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                                                <span>{{ $pData['icon'] }}</span>
                                                <span class="truncate">{{ $pData['label'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('priority') <span class="text-xs font-bold text-rose-600 mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Department & Direct Escalation Guard --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-stretch">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">القسم أو الإدارة المعنية (اختياري)</label>
                                        <select wire:model="department_id"
                                                class="w-full text-xs sm:text-sm bg-white border border-slate-300 rounded-2xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                                            <option value="">— غير محدد / يخص الإدارة العامة —</option>
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Anti-Retaliation Guard Card --}}
                                    <div class="bg-rose-50/70 border border-rose-200/90 rounded-2xl p-3.5 flex items-center">
                                        <label class="flex items-start gap-3 cursor-pointer select-none">
                                            <input type="checkbox" wire:model="against_manager" class="w-4 h-4 mt-1 text-rose-600 rounded border-rose-300 focus:ring-rose-500 accent-rose-600 shrink-0">
                                            <div>
                                                <strong class="text-xs sm:text-sm font-black text-rose-950 block">حجب عن الإدارة المباشرة (تصعيد مباشر للـ HR) 🛡️</strong>
                                                <span class="text-[11px] text-rose-700 block leading-tight mt-0.5">
                                                    يتم فحص الشكوى حصراً بواسطة إدارة الموارد البشرية العليا دون إطلاع مديرك المباشر لمنع الحرج المهني.
                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Detailed Content Textarea --}}
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs sm:text-sm font-extrabold text-slate-900">
                                            3. التفاصيل والوقائع الكاملة <span class="text-rose-500">*</span>
                                        </label>
                                        <span class="text-[11px] text-slate-400 font-medium">الحد الأدنى 15 حرفاً</span>
                                    </div>
                                    <textarea wire:model="content"
                                              rows="6"
                                              placeholder="يرجى ذكر الوقائع والتواريخ وأي تفاصيل داعمة بوضوح وأمانة لمساعدة فريق المتابعة في فحص الموضوع..."
                                              class="w-full text-xs sm:text-sm bg-white border border-slate-300 rounded-2xl p-4 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition font-medium leading-relaxed"></textarea>
                                    @error('content') <span class="text-xs font-bold text-rose-600 mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Step 2 Actions --}}
                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-3">
                                    <button type="button"
                                            wire:click="previousStep"
                                            class="inline-flex items-center gap-2 py-3 px-5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm border border-slate-200 transition cursor-pointer">
                                        <span class="text-base">→</span>
                                        <span>السابق: التصنيف والخصوصية</span>
                                    </button>

                                    <button type="button"
                                            wire:click="nextStep"
                                            class="inline-flex items-center gap-2 py-3.5 px-6 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-black text-xs sm:text-sm shadow-md shadow-teal-600/20 hover:shadow-teal-600/30 transition-all cursor-pointer">
                                        <span>المتابعة للمرفقات والمراجعة</span>
                                        <span class="text-base">←</span>
                                    </button>
                                </div>

                            </div>
                        @endif

                        {{-- ──────────────────────────────────────────────────────── --}}
                        {{-- WIZARD STEP 3: ATTACHMENTS & REVIEW/SUBMIT               --}}
                        {{-- ──────────────────────────────────────────────────────── --}}
                        @if($currentStep === 3)
                            <div class="p-6 sm:p-8 lg:p-10 space-y-7 animate-fadeIn">

                                {{-- Step Section Title --}}
                                <div class="border-b border-slate-100 pb-5">
                                    <div class="flex items-center justify-between flex-wrap gap-2">
                                        <div>
                                            <h3 class="text-base sm:text-lg font-black text-slate-900">الخطوة الثالثة: المرفقات والمراجعة النهائية</h3>
                                            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">أرفق أي ملفات داعمة إن وجدت، وراجع بيانات طلبك قبل الاعتماد والإرسال</p>
                                        </div>
                                        <span class="text-xs font-extrabold text-teal-800 bg-teal-50 border border-teal-200/70 px-3 py-1 rounded-xl">
                                            3 من 3
                                        </span>
                                    </div>
                                </div>

                                {{-- File Upload Area --}}
                                <div>
                                    <label class="block text-xs sm:text-sm font-extrabold text-slate-900 mb-2">
                                        1. مستند أو صورة مؤيدة (اختياري)
                                    </label>

                                    @if(! $attachment)
                                        <label class="border-2 border-dashed border-slate-300 hover:border-teal-500 bg-slate-50/70 hover:bg-teal-50/30 rounded-2xl p-6 flex flex-col items-center justify-center cursor-pointer transition group">
                                            <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">📎</span>
                                            <span class="text-xs sm:text-sm font-bold text-slate-700 group-hover:text-teal-700">اضغط هنا لرفع ملف أو اسحب الملف وأفلته هنا</span>
                                            <span class="text-[11px] text-slate-400 mt-1">الصيغ المدعومة: PDF, Word, JPG, PNG (الحد الأقصى 5 ميجابايت)</span>
                                            <input type="file" wire:model="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="hidden">
                                        </label>
                                    @else
                                        <div class="bg-teal-50 border border-teal-200 rounded-2xl p-4 flex items-center justify-between">
                                            <div class="flex items-center gap-3.5">
                                                <span class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center text-base font-bold shadow-xs">📄</span>
                                                <div class="text-xs sm:text-sm">
                                                    <strong class="text-slate-900 block font-bold truncate max-w-[280px] sm:max-w-md">{{ $attachment->getClientOriginalName() }}</strong>
                                                    <span class="text-teal-700 font-semibold">{{ number_format($attachment->getSize() / 1024, 1) }} KB</span>
                                                </div>
                                            </div>
                                            <button type="button" 
                                                    wire:click="removeAttachment"
                                                    class="text-xs font-bold text-rose-600 hover:text-rose-800 bg-white border border-rose-200 px-3.5 py-1.5 rounded-xl hover:bg-rose-50 transition cursor-pointer">
                                                ✕ حذف الملف
                                            </button>
                                        </div>
                                    @endif

                                    <div wire:loading wire:target="attachment" class="text-xs font-bold text-teal-700 mt-2 flex items-center gap-2">
                                        <span class="animate-spin">⏳</span>
                                        <span>جارٍ معالجة ورفع الملف...</span>
                                    </div>
                                    @error('attachment') <span class="text-xs font-bold text-rose-600 mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Live Review Summary Card --}}
                                <div>
                                    <label class="block text-xs sm:text-sm font-extrabold text-slate-900 mb-2.5">
                                        2. ملخص مراجعة الطلب قبل التوثيق الرسمي
                                    </label>
                                    <div class="bg-gradient-to-b from-slate-50 to-slate-100/60 border border-slate-200 rounded-2xl p-5 sm:p-6 space-y-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                                            {{-- Category --}}
                                            <div class="bg-white rounded-xl p-3 border border-slate-200/80">
                                                <span class="text-[10px] font-bold text-slate-400 block mb-0.5">نوع الموضوع</span>
                                                <div class="flex items-center gap-1.5 text-xs font-extrabold text-slate-900">
                                                    <span>{{ $typeInfo[$type]['icon'] ?? '📋' }}</span>
                                                    <span>{{ $typeInfo[$type]['label'] ?? $type }}</span>
                                                </div>
                                            </div>

                                            {{-- Privacy Mode --}}
                                            <div class="bg-white rounded-xl p-3 border border-slate-200/80">
                                                <span class="text-[10px] font-bold text-slate-400 block mb-0.5">وضع الخصوصية</span>
                                                <div class="text-xs font-extrabold text-slate-900 flex items-center gap-1">
                                                    @if($is_anonymous)
                                                        <span class="text-emerald-600">🔒</span>
                                                        <span>سري ومجهول الهوية</span>
                                                    @else
                                                        <span class="text-teal-600">👤</span>
                                                        <span class="truncate">{{ $submitter_name ?: 'بالهوية الرسمية' }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Priority --}}
                                            <div class="bg-white rounded-xl p-3 border border-slate-200/80">
                                                <span class="text-[10px] font-bold text-slate-400 block mb-0.5">درجة الأهمية</span>
                                                <div class="flex items-center gap-1.5 text-xs font-extrabold text-slate-900">
                                                    <span>{{ $priorityInfo[$priority]['icon'] ?? '🔵' }}</span>
                                                    <span>{{ $priorityInfo[$priority]['label'] ?? $priority }}</span>
                                                </div>
                                            </div>

                                            {{-- Escalation --}}
                                            <div class="bg-white rounded-xl p-3 border border-slate-200/80">
                                                <span class="text-[10px] font-bold text-slate-400 block mb-0.5">مسار الفحص</span>
                                                <div class="text-xs font-extrabold {{ $against_manager ? 'text-rose-600' : 'text-slate-900' }}">
                                                    {{ $against_manager ? '🛡️ حجب عن الإدارة المباشرة' : 'مسار اعتيادي' }}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Title & Content Snippet --}}
                                        <div class="bg-white rounded-xl p-4 border border-slate-200/80 space-y-2">
                                            <div>
                                                <span class="text-[10px] font-bold text-slate-400 block">العنوان:</span>
                                                <strong class="text-xs sm:text-sm font-extrabold text-slate-900 block mt-0.5">{{ $title ?: 'لم يتم تحديد عنوان' }}</strong>
                                            </div>
                                            <div>
                                                <span class="text-[10px] font-bold text-slate-400 block">نص الوقائع والتفاصيل:</span>
                                                <p class="text-xs text-slate-700 font-medium leading-relaxed mt-0.5 line-clamp-3 whitespace-pre-line">{{ $content ?: 'لا توجد تفاصيل' }}</p>
                                            </div>
                                        </div>

                                        <div class="text-[11px] font-medium text-slate-500 flex items-center gap-1.5">
                                            <span>ℹ️</span>
                                            <span>بمجرد الضغط على زر الإرسال، سيتم حفظ الشكوى مشفرة وتوليد كود التتبع السري فوراً.</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Step 3 Actions (Submit) --}}
                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-3">
                                    <button type="button"
                                            wire:click="previousStep"
                                            class="inline-flex items-center gap-2 py-3 px-5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm border border-slate-200 transition cursor-pointer">
                                        <span class="text-base">→</span>
                                        <span>السابق: تعديل التفاصيل</span>
                                    </button>

                                    <button type="submit"
                                            wire:loading.attr="disabled"
                                            class="py-4 px-8 rounded-2xl bg-gradient-to-r from-teal-600 via-emerald-600 to-teal-700 hover:from-teal-700 hover:to-emerald-700 text-white font-black text-xs sm:text-base shadow-lg shadow-teal-600/25 hover:shadow-teal-600/35 active:scale-[0.99] transition-all flex items-center justify-center gap-2.5 cursor-pointer disabled:opacity-60">
                                        <span wire:loading.remove wire:target="submit">🚀 إرسال وتوثيق الشكوى رسمياً</span>
                                        <span wire:loading wire:target="submit" class="inline-flex items-center gap-2">
                                            <span class="animate-spin text-lg">⏳</span>
                                            <span>جارٍ التوثيق وتوليد رقم التذكرة المشفر...</span>
                                        </span>
                                    </button>
                                </div>

                            </div>
                        @endif

                    </form>
                </div>

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

                <h2 class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 mb-2">
                    تم استلام وتوثيق طلبك بنجاح!
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 max-w-lg mx-auto leading-relaxed mb-6 font-medium">
                    شكراً لحرصك على بيئة العمل والنزاهة المؤسسية. تم تسجيل الموضوع في النظام الإداري بنجاح وإحالته للمراجعة الفورية.
                </p>

                {{-- Ticket Voucher Box --}}
                <div class="bg-gradient-to-b from-slate-50 to-white border-2 border-dashed border-teal-500 rounded-3xl p-6 sm:p-8 max-w-md mx-auto mb-8 shadow-sm relative overflow-hidden">
                    <span class="text-[11px] font-extrabold text-teal-800 uppercase tracking-wider block mb-1">
                        رقم تذكرة المتابعة السرية الخاصة بك
                    </span>

                    <div class="font-mono text-2xl sm:text-3xl font-black text-teal-700 tracking-wider py-2 select-all direction-ltr" id="ticketNumberDisplay">
                        {{ $submittedTicketNumber }}
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-[11px] font-bold text-amber-900 my-3.5">
                        ⚠️ احتفظ بهذا الرقم في مكان آمن، فهو وسيلتك الوحيدة لمتابعة نتيجة التحقيق وقرار الإدارة لاحقاً.
                    </div>

                    {{-- Copy & WhatsApp Actions --}}
                    <div class="flex items-center justify-center gap-2.5 flex-wrap">
                        <button type="button"
                                onclick="navigator.clipboard.writeText('{{ $submittedTicketNumber }}'); const b = this; b.innerText = '✓ تم النسخ!'; setTimeout(() => b.innerText = '📋 نسخ الرقم', 2000);"
                                class="inline-flex items-center gap-1.5 text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white px-4 py-2.5 rounded-xl transition cursor-pointer">
                            📋 نسخ الرقم
                        </button>

                        <a href="https://api.whatsapp.com/send?text={{ urlencode('رقم تذكرة الشكوى/المقترح الخاصة بي في شركة ' . $brandName . ' هي: ' . $submittedTicketNumber . "\n" . 'رابط المتابعة: ' . url('/complaints?ticket=' . $submittedTicketNumber)) }}" 
                           target="_blank"
                           class="inline-flex items-center gap-1.5 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl transition">
                            <span>💬</span>
                            <span>إرسال لنفسي عبر واتساب</span>
                        </a>
                    </div>
                </div>

                {{-- Primary Action Buttons --}}
                <div class="flex items-center justify-center gap-3 flex-wrap">
                    <button type="button"
                            wire:click="trackSubmitted"
                            class="px-6 py-3.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs sm:text-sm shadow-md transition cursor-pointer">
                        متابعة حالة التذكرة الآن ↗
                    </button>

                    <button type="button"
                            wire:click="resetForm"
                            class="px-6 py-3.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-extrabold text-xs sm:text-sm border border-slate-200 transition cursor-pointer">
                        تقديم موضوع أو شكوى جديدة
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
                <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-lg shadow-slate-200/40">
                    <div class="flex items-center gap-3.5 mb-2">
                        <span class="w-10 h-10 rounded-2xl bg-teal-100 text-teal-800 flex items-center justify-center text-xl font-bold">
                            🔍
                        </span>
                        <div>
                            <h3 class="font-black text-slate-900 text-sm sm:text-base">استعلام ومتابعة حالة التذكرة</h3>
                            <p class="text-xs text-slate-500 font-medium">أدخل رقم التذكرة للاطلاع على مسار التحقيق والرد الإداري الرسمي</p>
                        </div>
                    </div>

                    <form wire:submit="lookupTicket" class="mt-5 flex flex-col sm:flex-row gap-2.5">
                        <div class="relative flex-1">
                            <input type="text"
                                   wire:model="searchTicket"
                                   placeholder="مثال: GRV-2610-0001"
                                   class="w-full font-mono text-sm sm:text-base font-extrabold uppercase direction-ltr text-left bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                        </div>
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="py-3.5 px-7 bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-sm transition flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap">
                            <span wire:loading.remove wire:target="lookupTicket">بحث واستعلام</span>
                            <span wire:loading wire:target="lookupTicket" class="inline-flex items-center gap-1.5">
                                <span class="animate-spin">⏳</span>
                                <span>جارٍ الفحص...</span>
                            </span>
                        </button>
                    </form>

                    @if($trackError)
                        <div class="mt-4 bg-rose-50 border border-rose-200 rounded-2xl p-4 text-xs font-bold text-rose-800 flex items-center gap-2">
                            <span>⚠️</span>
                            <span>{{ $trackError }}</span>
                        </div>
                    @endif
                </div>

                {{-- Search Results Details --}}
                @if($trackedGrievance)
                    <div class="bg-white border border-slate-200 rounded-3xl shadow-xl overflow-hidden animate-fadeIn">

                        {{-- Header with Ticket Status --}}
                        <div class="bg-gradient-to-r from-slate-50 via-teal-50/50 to-slate-50 p-6 sm:p-7 border-b border-slate-200 flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2.5 mb-2">
                                    <span class="font-mono text-xs font-black bg-teal-600 text-white px-3 py-1 rounded-lg direction-ltr">
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
                                <span class="inline-block px-4 py-2 rounded-full text-xs font-extrabold border {{ $statusBadge['bg'] }}">
                                    {{ $statusBadge['label'] }}
                                </span>
                            </div>
                        </div>

                        {{-- 3-Step Visual Progress Tracker --}}
                        <div class="p-6 sm:p-8 border-b border-slate-200 bg-white">
                            <span class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-6">
                                المسار الإداري للتذكرة:
                            </span>

                            <div class="grid grid-cols-3 gap-2 text-center relative max-w-xl mx-auto">
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
                        <div class="p-6 sm:p-8 space-y-6">

                            {{-- Submitter Details Preview --}}
                            <div>
                                <span class="text-xs font-bold text-slate-500 block mb-1.5">تفاصيل الشكوى المسجلة:</span>
                                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line font-medium">
                                    {{ $trackedGrievance->content }}
                                </div>
                            </div>

                            {{-- Official HR Resolution Box --}}
                            @if($trackedGrievance->hr_response)
                                <div class="bg-emerald-50/80 border-2 border-emerald-300 rounded-2xl p-5 sm:p-6 shadow-xs">
                                    <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-emerald-200 flex-wrap gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xl">🛡️</span>
                                            <strong class="text-xs sm:text-sm font-black text-emerald-950">رد وقرار إدارة الموارد البشرية الرسمي</strong>
                                        </div>
                                        @if($trackedGrievance->resolved_at)
                                            <span class="text-[11px] font-bold text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-lg">
                                                بتاريخ: {{ $trackedGrievance->resolved_at->format('Y/m/d') }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs sm:text-sm font-bold text-emerald-900 leading-relaxed whitespace-pre-line">
                                        {{ $trackedGrievance->hr_response }}
                                    </p>
                                </div>
                            @else
                                <div class="bg-sky-50 border border-sky-200 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm font-bold text-sky-800 flex items-center gap-3">
                                    <span class="text-xl">⏳</span>
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
            <p class="text-xs sm:text-sm text-slate-500 font-medium">
                هل أنت موظف بالشركة وتبحث عن خدمات الإجازات والرواتب؟
                <a href="{{ route('portal.dashboard') }}" class="text-teal-700 hover:text-teal-900 font-black underline mr-1">
                    الدخول إلى بوابة الموظف الذاتية (ESS)
                </a>
            </p>
            <p class="text-[11px] text-slate-400 mt-1.5">
                {{ $brandName }} &copy; {{ date('Y') }} — جميع الحقوق محفوظة | نظام الحوكمة وإدارة الموارد البشرية
            </p>
        </footer>

    </div>
</div>
