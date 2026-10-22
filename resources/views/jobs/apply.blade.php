<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقديم على وظيفة: {{ $job->title }}</title>
    
    @php
        $logo = \App\Models\Setting::get('logo');
        $brandColor = $job->brand_color ?: '#0d9488';
        $layout = $job->form_layout ?: 'single_page';
        $isWizard = ($layout === 'wizard');
        $config = $job->primary_fields_config ?? [];
        $phoneConfig = \App\Models\Setting::getCountryPhoneConfig();
        $isReq = fn(string $key, bool $default = false) => (bool) ($config["required_{$key}"] ?? $default);

        // Compute initial step if validation failed
        $initialStep = 1;
        if ($errors->hasAny(['current_job_title', 'current_company', 'years_of_experience', 'expected_salary', 'seniority_level', 'work_preference', 'availability'])) {
            $initialStep = 2;
        } elseif ($errors->has('resume') || $errors->has('general_notes') || collect($errors->keys())->some(fn($k) => str_starts_with($k, 'custom_'))) {
            $initialStep = 3;
        }
    @endphp

    @if($logo)
        <meta property="og:image" content="{{ str_starts_with($logo, 'http') || str_starts_with($logo, '/') ? $logo : \Illuminate\Support\Facades\Storage::disk('public')->url($logo) }}">
    @endif
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Cairo', 'sans-serif'] },
                    colors: {
                        primary: {
                            DEFAULT: '{{ $brandColor }}',
                            50: 'color-mix(in srgb, {{ $brandColor }} 8%, white)',
                            100: 'color-mix(in srgb, {{ $brandColor }} 16%, white)',
                            200: 'color-mix(in srgb, {{ $brandColor }} 30%, white)',
                            500: '{{ $brandColor }}',
                            600: 'color-mix(in srgb, {{ $brandColor }} 90%, black)',
                            700: 'color-mix(in srgb, {{ $brandColor }} 80%, black)',
                            800: 'color-mix(in srgb, {{ $brandColor }} 70%, black)',
                            900: 'color-mix(in srgb, {{ $brandColor }} 60%, black)',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --brand-primary: {{ $brandColor }};
        }
        body { 
            font-family: 'Cairo', sans-serif;
            background-color: #f8fafc;
        }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .brand-gradient {
            background: linear-gradient(135deg, {{ $brandColor }} 0%, color-mix(in srgb, {{ $brandColor }} 80%, black) 100%);
        }
        .brand-ring:focus {
            --tw-ring-color: {{ $brandColor }};
            border-color: {{ $brandColor }};
        }
    </style>
</head>
<body class="antialiased text-slate-800 selection:bg-teal-100 selection:text-teal-900 pb-16">

    <!-- Top Navigation Bar -->
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 px-4 py-3.5 sm:px-6 lg:px-8 mb-8 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                @if($logo)
                    <img src="{{ str_starts_with($logo, 'http') || str_starts_with($logo, '/') ? $logo : \Illuminate\Support\Facades\Storage::disk('public')->url($logo) }}" alt="Logo" class="h-9 sm:h-11 object-contain">
                @else
                    <div class="flex items-center gap-2">
                        <div class="h-9 w-9 rounded-xl brand-gradient flex items-center justify-center text-white font-black text-lg shadow-sm">
                            HR
                        </div>
                        <span class="font-black text-lg text-slate-900 tracking-tight">بوابة التوظيف</span>
                    </div>
                @endif
                <span class="text-xs font-bold text-slate-400 border-r border-slate-200 pr-3 mr-3 hidden sm:inline-block">فرص العمل الرسمية</span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200/80 px-3.5 py-2 rounded-xl transition-all">
                    <span>كافة الوظائف المتاحة</span>
                    <span dir="ltr">&rarr;</span>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if(session('success'))
            <!-- Success Screen -->
            <div class="max-w-2xl mx-auto text-center py-16 px-6 bg-white rounded-3xl shadow-sm border border-slate-100 my-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 text-emerald-600 mb-6 ring-8 ring-emerald-50/50 shadow-inner">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 mb-3">تم استلام طلبك بنجاح</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mb-3 tracking-tight">شكراً لاهتمامك بالانضمام إلينا!</h1>
                <div class="text-sm text-slate-600 leading-relaxed mb-6 max-w-lg mx-auto bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    {{ session('success') }}
                </div>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('jobs.index') }}" 
                       class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3 text-sm font-bold rounded-xl text-white brand-gradient shadow-md hover:opacity-95 transition-all">
                        تصفح الوظائف الأخرى
                    </a>
                </div>
            </div>
        @else

        <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-start">
            
            <!-- Sidebar: Job Details & Overview -->
            <aside class="lg:col-span-4 mb-8 lg:mb-0 lg:sticky lg:top-24">
                <div class="brand-gradient rounded-3xl p-6 sm:p-7 text-white shadow-xl shadow-slate-900/10 relative overflow-hidden border border-white/10">
                    <!-- Subtle Glow Decors -->
                    <div class="absolute -top-12 -right-12 w-44 h-44 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -bottom-12 -left-12 w-44 h-44 bg-black/10 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/20 text-white text-xs font-extrabold rounded-lg backdrop-blur-md">
                                <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                فرصة عمل نشطة
                            </span>
                            @if($isWizard)
                                <span class="inline-flex items-center gap-1 text-[11px] bg-white/15 px-2.5 py-1 rounded-md text-teal-100">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                    نموذج متعدد الخطوات
                                </span>
                            @endif
                        </div>

                        <h1 class="text-xl sm:text-2xl font-black leading-snug mb-4 drop-shadow-xs">{{ $job->title }}</h1>
                        
                        <!-- Badges -->
                        <div class="flex flex-wrap gap-2 mb-6">
                            @if($job->department)
                                <span class="inline-flex items-center gap-1.5 text-xs text-white/90 bg-white/15 px-3 py-1 rounded-lg backdrop-blur-xs">
                                    <svg class="w-3.5 h-3.5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    {{ $job->department->name }}
                                </span>
                            @endif
                            @if($job->location)
                                <span class="inline-flex items-center gap-1.5 text-xs text-white/90 bg-white/15 px-3 py-1 rounded-lg backdrop-blur-xs">
                                    <svg class="w-3.5 h-3.5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    {{ $job->location->name }}
                                </span>
                            @endif
                        </div>

                        <!-- Job Description Accordion/Container -->
                        <div class="border-t border-white/20 pt-4" x-data="{ expanded: false }">
                            <h2 class="text-xs font-bold text-white/80 mb-2 flex items-center justify-between">
                                <span>نظرة عامة على الوظيفة</span>
                                <button type="button" @click="expanded = !expanded" class="text-[11px] underline text-teal-200 hover:text-white">
                                    <span x-show="!expanded">عرض كامل</span>
                                    <span x-show="expanded" style="display:none;">طي</span>
                                </button>
                            </h2>
                            <div class="text-xs text-white/90 leading-relaxed overflow-hidden transition-all"
                                 :class="expanded ? 'max-h-none' : 'max-h-48 line-clamp-6'">
                                {!! $job->description ?: 'لا يوجد وصف تفصيلي متوفر حالياً لهذه الوظيفة.' !!}
                            </div>
                        </div>

                        <!-- Safety Guarantee -->
                        <div class="mt-6 pt-4 border-t border-white/15 flex items-center gap-2 text-[11px] text-white/80">
                            <svg class="w-4 h-4 text-emerald-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            <span>بياناتك محمية ولن تُشارك إلا مع لجنة التوظيف المعنية.</span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Application Area -->
            <div class="lg:col-span-8">

                <!-- Form Card with Alpine Controller -->
                <div x-data="jobApplicationApp()" 
                     class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    
                    <!-- Top Error Alert (If Any) -->
                    @if($errors->any())
                        <div class="bg-rose-50 p-5 border-b border-rose-100 flex items-start gap-3">
                            <div class="h-8 w-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-rose-900">تعذر إرسال الطلب، يرجى تصحيح الملاحظات التالية:</h3>
                                <ul class="mt-1 text-xs text-rose-700 list-disc list-inside space-y-0.5 font-medium">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @if($isWizard)
                        <!-- ========================================== -->
                        <!-- WIZARD STEP HEADER & PROGRESS BAR          -->
                        <!-- ========================================== -->
                        <div class="p-6 sm:p-8 bg-slate-50/70 border-b border-slate-100">
                            <!-- Step Tracker -->
                            <div class="relative">
                                <!-- Background Connecting Line -->
                                <div class="absolute top-4 right-0 left-0 h-1 bg-slate-200 z-0"></div>
                                <!-- Active Filling Line -->
                                <div class="absolute top-4 right-0 h-1 brand-gradient transition-all duration-300 z-0"
                                     :style="'width: ' + ((step - 1) / (totalSteps - 1) * 100) + '%'"></div>

                                <div class="relative z-10 flex justify-between">
                                    <!-- Step 1 Indicator -->
                                    <button type="button" @click="goToStep(1)" class="flex flex-col items-center group cursor-pointer">
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-black transition-all shadow-xs"
                                             :class="step === 1 ? 'brand-gradient text-white ring-4 ring-teal-500/20 scale-110' : (step > 1 ? 'bg-emerald-600 text-white' : 'bg-white border-2 border-slate-300 text-slate-500')">
                                            <template x-if="step > 1">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                            </template>
                                            <template x-if="step <= 1">
                                                <span>1</span>
                                            </template>
                                        </div>
                                        <span class="text-[11px] font-bold mt-2 transition-colors"
                                              :class="step === 1 ? 'text-slate-900' : 'text-slate-500'">البيانات الشخصية</span>
                                    </button>

                                    <!-- Step 2 Indicator -->
                                    <button type="button" @click="goToStep(2)" class="flex flex-col items-center group cursor-pointer">
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-black transition-all shadow-xs"
                                             :class="step === 2 ? 'brand-gradient text-white ring-4 ring-teal-500/20 scale-110' : (step > 2 ? 'bg-emerald-600 text-white' : 'bg-white border-2 border-slate-300 text-slate-500')">
                                            <template x-if="step > 2">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                            </template>
                                            <template x-if="step <= 2">
                                                <span>2</span>
                                            </template>
                                        </div>
                                        <span class="text-[11px] font-bold mt-2 transition-colors"
                                              :class="step === 2 ? 'text-slate-900' : 'text-slate-500'">المؤهلات والخبرات</span>
                                    </button>

                                    <!-- Step 3 Indicator -->
                                    <button type="button" @click="goToStep(3)" class="flex flex-col items-center group cursor-pointer">
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-black transition-all shadow-xs"
                                             :class="step === 3 ? 'brand-gradient text-white ring-4 ring-teal-500/20 scale-110' : (step > 3 ? 'bg-emerald-600 text-white' : 'bg-white border-2 border-slate-300 text-slate-500')">
                                            <template x-if="step > 3">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                            </template>
                                            <template x-if="step <= 3">
                                                <span>3</span>
                                            </template>
                                        </div>
                                        <span class="text-[11px] font-bold mt-2 transition-colors"
                                              :class="step === 3 ? 'text-slate-900' : 'text-slate-500'">المرفقات والأسئلة</span>
                                    </button>

                                    <!-- Step 4 Indicator -->
                                    <button type="button" @click="goToStep(4)" class="flex flex-col items-center group cursor-pointer">
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-black transition-all shadow-xs"
                                             :class="step === 4 ? 'brand-gradient text-white ring-4 ring-teal-500/20 scale-110' : 'bg-white border-2 border-slate-300 text-slate-500'">
                                            <span>4</span>
                                        </div>
                                        <span class="text-[11px] font-bold mt-2 transition-colors"
                                              :class="step === 4 ? 'text-slate-900' : 'text-slate-500'">المراجعة والتأكيد</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- The Actual Form -->
                    <form action="{{ route('jobs.submit', $job->id) }}" 
                          method="POST" 
                          enctype="multipart/form-data" 
                          id="jobApplicationForm"
                          @submit="isSubmitting = true">
                        @csrf

                        <div class="p-6 sm:p-10 space-y-8">
                            
                            <!-- ============================================== -->
                            <!-- STEP 1: Personal & Contact Information         -->
                            <!-- ============================================== -->
                            <div x-show="!isWizard || step === 1" x-transition.opacity>
                                <div class="border-b border-slate-100 pb-4 mb-6">
                                    <h2 class="text-base sm:text-lg font-black text-slate-900 flex items-center gap-2">
                                        <span class="flex items-center justify-center w-7 h-7 rounded-lg brand-gradient text-white text-xs font-black shadow-xs">1</span>
                                        المعلومات الشخصية وبيانات التواصل
                                    </h2>
                                    <p class="text-xs text-slate-500 mt-1">يرجى كتابة بياناتك الدقيقة حتى نتمكن من التواصل معك بشأن طلبك.</p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    @if($config['show_full_name'] ?? true)
                                    <div class="sm:col-span-2">
                                        <label for="full_name" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>الاسم الكامل @if($isReq('full_name', true))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('full_name', true))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <input type="text" 
                                               name="full_name" 
                                               id="full_name" 
                                               x-model="formData.full_name"
                                               value="{{ old('full_name') }}" 
                                               {{ $isReq('full_name', true) ? 'required' : '' }}
                                               placeholder="مثال: أحمد عبد الله محمد"
                                               :class="stepErrors.full_name ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-200 brand-ring'"
                                               class="block w-full rounded-xl py-3 px-4 text-sm shadow-xs transition-colors bg-white">
                                        <p x-show="stepErrors.full_name" x-text="stepErrors.full_name" class="mt-1 text-xs font-bold text-rose-500" style="display:none;"></p>
                                        @error('full_name')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif

                                    @if($config['show_email'] ?? true)
                                    <div>
                                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>البريد الإلكتروني @if($isReq('email', true))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('email', true))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <input type="email" 
                                               name="email" 
                                               id="email" 
                                               x-model="formData.email"
                                               value="{{ old('email') }}" 
                                               {{ $isReq('email', true) ? 'required' : '' }} 
                                               dir="ltr"
                                               placeholder="name@example.com"
                                               :class="stepErrors.email ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-200 brand-ring'"
                                               class="block w-full rounded-xl py-3 px-4 text-sm shadow-xs transition-colors bg-white text-left">
                                        <p x-show="stepErrors.email" x-text="stepErrors.email" class="mt-1 text-xs font-bold text-rose-500" style="display:none;"></p>
                                        @error('email')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif

                                    @if($config['show_phone'] ?? true)
                                    <div>
                                        <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>رقم الجوال / الهاتف @if($isReq('phone', true))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('phone', true))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <div class="relative flex items-center">
                                            <span class="inline-flex items-center gap-1 px-3 py-3 rounded-r-xl border border-l-0 border-slate-200 bg-slate-50 text-slate-700 text-xs font-bold font-mono select-none" title="{{ $phoneConfig['name'] }}">
                                                <span>{{ $phoneConfig['flag'] }}</span>
                                                <span dir="ltr">{{ $phoneConfig['dial_code'] }}</span>
                                            </span>
                                            <input type="tel" 
                                                   name="phone" 
                                                   id="phone" 
                                                   x-model="formData.phone"
                                                   value="{{ old('phone') }}" 
                                                   {{ $isReq('phone', true) ? 'required' : '' }} 
                                                   dir="ltr"
                                                   placeholder="{{ $phoneConfig['placeholder'] }}"
                                                   :class="stepErrors.phone ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-200 brand-ring'"
                                                   class="block w-full rounded-r-none rounded-l-xl py-3 px-4 text-sm shadow-xs transition-colors bg-white text-left">
                                        </div>
                                        <p class="text-[11px] text-slate-400 mt-1">{{ $phoneConfig['hint'] }}</p>
                                        <p x-show="stepErrors.phone" x-text="stepErrors.phone" class="mt-1 text-xs font-bold text-rose-500" style="display:none;"></p>
                                        @error('phone')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif

                                    @if($config['show_national_id'] ?? false)
                                    <div>
                                        <label for="national_id" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>رقم الهوية الوطنية / الإقامة @if($isReq('national_id', false))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('national_id', false))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <input type="text" 
                                               name="national_id" 
                                               id="national_id" 
                                               value="{{ old('national_id') }}" 
                                               {{ $isReq('national_id', false) ? 'required' : '' }}
                                               dir="ltr"
                                               placeholder="مثال: 10 أرقام أو 14 رقم"
                                               class="block w-full rounded-xl border-slate-200 py-3 px-4 text-sm shadow-xs brand-ring transition-colors bg-white text-left">
                                        @error('national_id')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif

                                    @if($config['show_city'] ?? false)
                                    <div>
                                        <label for="city" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>المدينة ومحل الإقامة @if($isReq('city', false))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('city', false))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <input type="text" 
                                               name="city" 
                                               id="city" 
                                               value="{{ old('city') }}" 
                                               {{ $isReq('city', false) ? 'required' : '' }}
                                               placeholder="مثال: القاهرة / الرياض / الإسكندرية / جدة"
                                               class="block w-full rounded-xl border-slate-200 py-3 px-4 text-sm shadow-xs brand-ring transition-colors bg-white">
                                        @error('city')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif

                                    @if($config['show_birth_date'] ?? false)
                                    <div>
                                        <label for="birth_date" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>تاريخ الميلاد @if($isReq('birth_date', false))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('birth_date', false))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <input type="date" 
                                               name="birth_date" 
                                               id="birth_date" 
                                               value="{{ old('birth_date') }}" 
                                               {{ $isReq('birth_date', false) ? 'required' : '' }}
                                               class="block w-full rounded-xl border-slate-200 py-3 px-4 text-sm shadow-xs brand-ring transition-colors bg-white">
                                        @error('birth_date')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif

                                    @if($config['show_gender'] ?? false)
                                    <div>
                                        <label for="gender" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>الجنس / النوع @if($isReq('gender', false))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('gender', false))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <select name="gender" 
                                                id="gender" 
                                                {{ $isReq('gender', false) ? 'required' : '' }}
                                                class="block w-full rounded-xl border-slate-200 py-3 px-4 text-sm shadow-xs brand-ring transition-colors bg-white cursor-pointer">
                                            <option value="">-- اختر --</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                        </select>
                                        @error('gender')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif

                                    @if($config['show_linkedin'] ?? true)
                                    <div>
                                        <label for="linkedin_url" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>رابط ملف لينكد إن (LinkedIn) @if($isReq('linkedin', false))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('linkedin', false))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <input type="url" 
                                               name="linkedin_url" 
                                               id="linkedin_url" 
                                               x-model="formData.linkedin_url"
                                               value="{{ old('linkedin_url') }}" 
                                               {{ $isReq('linkedin', false) ? 'required' : '' }}
                                               dir="ltr"
                                               placeholder="https://linkedin.com/in/username"
                                               class="block w-full rounded-xl border-slate-200 py-3 px-4 text-sm shadow-xs brand-ring transition-colors bg-white text-left">
                                        @error('linkedin_url')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif

                                    @if($config['show_portfolio'] ?? true)
                                    <div>
                                        <label for="portfolio_url" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>معرض الأعمال أو الموقع الشخصي @if($isReq('portfolio', false))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('portfolio', false))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <input type="url" 
                                               name="portfolio_url" 
                                               id="portfolio_url" 
                                               x-model="formData.portfolio_url"
                                               value="{{ old('portfolio_url') }}" 
                                               {{ $isReq('portfolio', false) ? 'required' : '' }}
                                               dir="ltr"
                                               placeholder="https://myportfolio.com"
                                               class="block w-full rounded-xl border-slate-200 py-3 px-4 text-sm shadow-xs brand-ring transition-colors bg-white text-left">
                                        @error('portfolio_url')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- ============================================== -->
                            <!-- STEP 2: Experience & Career Qualifications     -->
                            <!-- ============================================== -->
                            <div x-show="!isWizard || step === 2" x-transition.opacity>
                                <div class="border-b border-slate-100 pb-4 mb-6">
                                    <h2 class="text-base sm:text-lg font-black text-slate-900 flex items-center gap-2">
                                        <span class="flex items-center justify-center w-7 h-7 rounded-lg brand-gradient text-white text-xs font-black shadow-xs">2</span>
                                        المؤهلات والخبرات المهنية
                                    </h2>
                                    <p class="text-xs text-slate-500 mt-1">تفاصيل تاريخك المهني ونظام العمل الذي يناسبك.</p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    @if($config['show_current_job_title'] ?? true)
                                    <div>
                                        <label for="current_job_title" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>المسمى الوظيفي الحالي / الأخير @if($isReq('current_job_title', false))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('current_job_title', false))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <input type="text" 
                                               name="current_job_title" 
                                               id="current_job_title" 
                                               x-model="formData.current_job_title"
                                               value="{{ old('current_job_title') }}" 
                                               {{ $isReq('current_job_title', false) ? 'required' : '' }}
                                               placeholder="مثال: مطور برمجيات أول / مهندس مدني"
                                               class="block w-full rounded-xl border-slate-200 py-3 px-4 text-sm shadow-xs brand-ring transition-colors bg-white">
                                        @error('current_job_title')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif

                                    @if($config['show_current_company'] ?? true)
                                    <div>
                                        <label for="current_company" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>جهة العمل الحالية / السابقة @if($isReq('current_company', false))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('current_company', false))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <input type="text" 
                                               name="current_company" 
                                               id="current_company" 
                                               x-model="formData.current_company"
                                               value="{{ old('current_company') }}" 
                                               {{ $isReq('current_company', false) ? 'required' : '' }}
                                               placeholder="مثال: شركة الحلول المبتكرة"
                                               class="block w-full rounded-xl border-slate-200 py-3 px-4 text-sm shadow-xs brand-ring transition-colors bg-white">
                                        @error('current_company')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif

                                    @if($config['show_years_of_experience'] ?? true)
                                    <div>
                                        <label for="years_of_experience" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>سنوات الخبرة العملية @if($isReq('years_of_experience', false))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('years_of_experience', false))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <div class="relative">
                                            <input type="number" 
                                                   step="0.5" 
                                                   min="0" 
                                                   name="years_of_experience" 
                                                   id="years_of_experience" 
                                                   x-model="formData.years_of_experience"
                                                   value="{{ old('years_of_experience') }}" 
                                                   {{ $isReq('years_of_experience', false) ? 'required' : '' }}
                                                   placeholder="0"
                                                   class="block w-full rounded-xl border-slate-200 py-3 pl-14 pr-4 text-sm shadow-xs brand-ring transition-colors bg-white">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-bold text-xs">
                                                سنوات
                                            </div>
                                        </div>
                                        @error('years_of_experience')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif

                                    @if($config['show_expected_salary'] ?? true)
                                    <div>
                                        <label for="expected_salary" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>الراتب الشهري المتوقع @if($isReq('expected_salary', false))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('expected_salary', false))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <div class="relative">
                                            <input type="number" 
                                                   min="0" 
                                                   name="expected_salary" 
                                                   id="expected_salary" 
                                                   x-model="formData.expected_salary"
                                                   value="{{ old('expected_salary') }}" 
                                                   {{ $isReq('expected_salary', false) ? 'required' : '' }}
                                                   placeholder="0"
                                                   class="block w-full rounded-xl border-slate-200 py-3 pl-14 pr-4 text-sm shadow-xs brand-ring transition-colors bg-white font-mono">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-bold text-xs">
                                                {{ \App\Models\Setting::currencySymbol() }}
                                            </div>
                                        </div>
                                        @error('expected_salary')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif

                                    @if($config['show_seniority_level'] ?? true)
                                    <div>
                                        <label for="seniority_level" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>المستوى المهني @if($isReq('seniority_level', false))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('seniority_level', false))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <select name="seniority_level" 
                                                id="seniority_level" 
                                                x-model="formData.seniority_level"
                                                {{ $isReq('seniority_level', false) ? 'required' : '' }}
                                                class="block w-full rounded-xl border-slate-200 py-3 px-4 text-sm shadow-xs brand-ring transition-colors bg-white cursor-pointer">
                                            <option value="">-- يرجى الاختيار --</option>
                                            <option value="intern" {{ old('seniority_level') == 'intern' ? 'selected' : '' }}>متدرب (Intern / Trainee)</option>
                                            <option value="junior" {{ old('seniority_level') == 'junior' ? 'selected' : '' }}>مبتدئ (Junior: 1-2 سنوات)</option>
                                            <option value="mid" {{ old('seniority_level') == 'mid' ? 'selected' : '' }}>متوسط (Mid-Level: 3-5 سنوات)</option>
                                            <option value="senior" {{ old('seniority_level') == 'senior' ? 'selected' : '' }}>متقدم (Senior: +5 سنوات)</option>
                                            <option value="lead" {{ old('seniority_level') == 'lead' ? 'selected' : '' }}>قائد فريق (Lead)</option>
                                            <option value="manager" {{ old('seniority_level') == 'manager' ? 'selected' : '' }}>إداري / مدير (Manager)</option>
                                        </select>
                                        @error('seniority_level')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif

                                    @if($config['show_work_preference'] ?? true)
                                    <div>
                                        <label for="work_preference" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>نظام العمل المفضل @if($isReq('work_preference', false))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('work_preference', false))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <select name="work_preference" 
                                                id="work_preference" 
                                                x-model="formData.work_preference"
                                                {{ $isReq('work_preference', false) ? 'required' : '' }}
                                                class="block w-full rounded-xl border-slate-200 py-3 px-4 text-sm shadow-xs brand-ring transition-colors bg-white cursor-pointer">
                                            <option value="">-- يرجى الاختيار --</option>
                                            <option value="onsite" {{ old('work_preference') == 'onsite' ? 'selected' : '' }}>حضوري في مقر العمل (Onsite)</option>
                                            <option value="remote" {{ old('work_preference') == 'remote' ? 'selected' : '' }}>عن بُعد بالكامل (Remote)</option>
                                            <option value="hybrid" {{ old('work_preference') == 'hybrid' ? 'selected' : '' }}>هجين (Hybrid)</option>
                                        </select>
                                        @error('work_preference')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif

                                    @if($config['show_availability'] ?? true)
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-bold text-slate-700 mb-2 flex items-center justify-between">
                                            <span>متى يمكنك مباشرة العمل؟ @if($isReq('availability', false))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('availability', false))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                            @foreach(['immediately' => 'فوراً', 'two_weeks' => 'خلال أسبوعين', 'one_month' => 'خلال شهر', 'two_months' => 'أكثر من شهر'] as $val => $lbl)
                                                <label class="relative flex items-center justify-center p-3 border rounded-xl cursor-pointer transition-all text-center text-xs font-bold shadow-xs select-none"
                                                       :class="formData.availability === '{{ $val }}' ? 'brand-gradient text-white border-transparent' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'">
                                                    <input type="radio" 
                                                           name="availability" 
                                                           value="{{ $val }}" 
                                                           x-model="formData.availability"
                                                           class="sr-only" 
                                                           {{ old('availability', 'immediately') == $val ? 'checked' : '' }}>
                                                    <span>{{ $lbl }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('availability')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- ============================================== -->
                            <!-- STEP 3: Attachments & Additional Questions     -->
                            <!-- ============================================== -->
                            <div x-show="!isWizard || step === 3" x-transition.opacity>
                                <div class="border-b border-slate-100 pb-4 mb-6">
                                    <h2 class="text-base sm:text-lg font-black text-slate-900 flex items-center gap-2">
                                        <span class="flex items-center justify-center w-7 h-7 rounded-lg brand-gradient text-white text-xs font-black shadow-xs">3</span>
                                        السيرة الذاتية والأسئلة الإضافية
                                    </h2>
                                    <p class="text-xs text-slate-500 mt-1">يرجى رفع ملف سيرتك الذاتية والإجابة على أي متطلبات خاصة بالوظيفة.</p>
                                </div>

                                <div class="space-y-6">
                                    @if($config['show_resume'] ?? true)
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-2 flex items-center justify-between">
                                            <span>السيرة الذاتية (CV) @if($isReq('resume', true))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('resume', true))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        
                                        <!-- Drag and drop zone -->
                                        <div class="relative border-2 border-dashed rounded-2xl p-6 text-center transition-all cursor-pointer"
                                             :class="stepErrors.resume ? 'border-rose-400 bg-rose-50/30' : (fileName ? 'border-emerald-500 bg-emerald-50/20' : 'border-slate-300 hover:border-teal-500 bg-slate-50/50 hover:bg-teal-50/20')"
                                             @dragover.prevent="$el.classList.add('border-teal-500', 'bg-teal-50')"
                                             @dragleave.prevent="$el.classList.remove('border-teal-500', 'bg-teal-50')"
                                             @drop.prevent="$el.classList.remove('border-teal-500', 'bg-teal-50'); handleFileDrop($event)"
                                             @click="$refs.resumeInput.click()">
                                            
                                            <!-- When no file selected -->
                                            <div x-show="!fileName" class="flex flex-col items-center">
                                                <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                    </svg>
                                                </div>
                                                <p class="text-sm font-bold text-slate-800">
                                                    انقر لاختيار ملف السيرة الذاتية أو اسحبه وأفلته هنا
                                                </p>
                                                <p class="text-xs text-slate-400 mt-1">
                                                    الصيغ المدعومة: PDF, DOC, DOCX (الحد الأقصى: 10 ميجابايت)
                                                </p>
                                            </div>

                                            <!-- When file is selected -->
                                            <div x-show="fileName" style="display:none;" class="flex items-center justify-between p-3 bg-white rounded-xl border border-emerald-200">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-10 w-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="text-xs font-bold text-slate-900" x-text="fileName"></p>
                                                        <p class="text-[10px] text-emerald-600 font-medium" x-text="fileSize"></p>
                                                    </div>
                                                </div>
                                                <button type="button" @click.stop="clearFile()" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 text-xs font-bold">
                                                    إزالة وتغيير
                                                </button>
                                            </div>

                                            <input type="file" 
                                                   name="resume" 
                                                   id="resume" 
                                                   x-ref="resumeInput" 
                                                   class="sr-only" 
                                                   {{ $isReq('resume', true) ? 'required' : '' }} 
                                                   accept=".pdf,.doc,.docx"
                                                   @change="handleFileChange($event)">
                                        </div>

                                        <p x-show="stepErrors.resume" x-text="stepErrors.resume" class="mt-1.5 text-xs font-bold text-rose-500" style="display:none;"></p>
                                        @error('resume')
                                            <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif

                                    <!-- Custom Job Schema Questions -->
                                    @if($job->form_schema && count($job->form_schema) > 0)
                                        <div class="pt-4 border-t border-slate-100 space-y-4">
                                            <h3 class="text-xs font-bold text-slate-800">أسئلة إضافية متطلبة للوظيفة</h3>

                                            @foreach($job->form_schema as $block)
                                                @php
                                                    $data = $block['data'];
                                                    $fieldName = 'custom_' . $data['name'];
                                                    $isRequired = $data['is_required'] ?? false;
                                                @endphp

                                                <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-100">
                                                    @if($block['type'] === 'checkbox')
                                                        <label class="flex items-start cursor-pointer select-none">
                                                            <input type="checkbox" 
                                                                   name="{{ $fieldName }}" 
                                                                   value="1" 
                                                                   {{ old($fieldName) ? 'checked' : '' }} 
                                                                   {{ $isRequired ? 'required' : '' }}
                                                                   class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500 mt-0.5">
                                                            <span class="mr-2.5 text-xs font-bold text-slate-800">
                                                                {{ $data['label'] }}
                                                                @if($isRequired)<span class="text-rose-500">*</span>@endif
                                                            </span>
                                                        </label>
                                                    @else
                                                        <label class="block text-xs font-bold text-slate-800 mb-1.5">
                                                            {{ $data['label'] }}
                                                            @if($isRequired)
                                                                <span class="text-rose-500">*</span>
                                                            @else
                                                                <span class="text-slate-400 font-normal text-[10px] mr-1 bg-white px-1.5 py-0.5 rounded border border-slate-200">اختياري</span>
                                                            @endif
                                                        </label>

                                                        @if($block['type'] === 'text')
                                                            <input type="text" 
                                                                   name="{{ $fieldName }}" 
                                                                   value="{{ old($fieldName) }}" 
                                                                   {{ $isRequired ? 'required' : '' }}
                                                                   data-required-custom="{{ $isRequired ? 'true' : 'false' }}"
                                                                   data-label="{{ $data['label'] }}"
                                                                   class="block w-full rounded-xl border-slate-200 py-2.5 px-3.5 text-xs shadow-xs brand-ring transition-colors bg-white">

                                                        @elseif($block['type'] === 'textarea')
                                                            <textarea name="{{ $fieldName }}" 
                                                                      rows="3" 
                                                                      {{ $isRequired ? 'required' : '' }}
                                                                      data-required-custom="{{ $isRequired ? 'true' : 'false' }}"
                                                                      data-label="{{ $data['label'] }}"
                                                                      class="block w-full rounded-xl border-slate-200 py-2.5 px-3.5 text-xs shadow-xs brand-ring transition-colors bg-white">{{ old($fieldName) }}</textarea>

                                                        @elseif($block['type'] === 'select')
                                                            @php $isMultiple = $data['is_multiple'] ?? false; @endphp
                                                            @if($isMultiple)
                                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-1">
                                                                    @foreach($data['options'] ?? [] as $option)
                                                                        <label class="flex items-center p-2.5 border border-slate-200 rounded-xl cursor-pointer hover:bg-white transition-colors bg-white text-xs">
                                                                            <input type="checkbox" 
                                                                                   name="{{ $fieldName }}[]" 
                                                                                   value="{{ $option }}" 
                                                                                   {{ (is_array(old($fieldName)) && in_array($option, old($fieldName))) ? 'checked' : '' }}
                                                                                   class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                                                                            <span class="mr-2 font-medium text-slate-700">{{ $option }}</span>
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <select name="{{ $fieldName }}" 
                                                                        {{ $isRequired ? 'required' : '' }}
                                                                        data-required-custom="{{ $isRequired ? 'true' : 'false' }}"
                                                                        data-label="{{ $data['label'] }}"
                                                                        class="block w-full rounded-xl border-slate-200 py-2.5 px-3.5 text-xs shadow-xs brand-ring transition-colors bg-white cursor-pointer">
                                                                    <option value="">-- يرجى الاختيار --</option>
                                                                    @foreach($data['options'] ?? [] as $option)
                                                                        <option value="{{ $option }}" {{ old($fieldName) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @endif

                                                        @elseif($block['type'] === 'file')
                                                            <input type="file" 
                                                                   name="{{ $fieldName }}" 
                                                                   {{ $isRequired ? 'required' : '' }}
                                                                   accept="{{ $data['accepted_file_types'] ?? '*' }}"
                                                                   class="block w-full text-xs text-slate-700 border border-slate-200 rounded-xl cursor-pointer bg-white file:py-2.5 file:px-4 file:border-0 file:text-xs file:font-bold file:bg-slate-100 hover:file:bg-slate-200 transition-colors">

                                                        @elseif($block['type'] === 'date')
                                                            <input type="date" 
                                                                   name="{{ $fieldName }}" 
                                                                   value="{{ old($fieldName) }}" 
                                                                   {{ $isRequired ? 'required' : '' }}
                                                                   class="block w-full rounded-xl border-slate-200 py-2.5 px-3.5 text-xs shadow-xs brand-ring transition-colors bg-white">

                                                        @elseif($block['type'] === 'rating')
                                                            <div class="flex items-center gap-1.5" x-data="{ rating: {{ old($fieldName) ?? 0 }} }">
                                                                <input type="hidden" name="{{ $fieldName }}" x-model="rating" {{ $isRequired ? 'required' : '' }}>
                                                                <template x-for="i in 5">
                                                                    <button type="button" @click="rating = i" class="p-1 focus:outline-none transition-transform hover:scale-125">
                                                                        <svg class="w-7 h-7 drop-shadow-xs" 
                                                                             :class="rating >= i ? 'text-amber-400' : 'text-slate-200 hover:text-slate-300'" 
                                                                             fill="currentColor" viewBox="0 0 20 20">
                                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                        </svg>
                                                                    </button>
                                                                </template>
                                                                <span class="text-xs text-slate-500 mr-2 font-mono" x-show="rating > 0" x-text="rating + ' / 5'"></span>
                                                            </div>
                                                        @endif

                                                        @error($fieldName)
                                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                                        @enderror
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($config['show_general_notes'] ?? true)
                                    <div>
                                        <label for="general_notes" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                            <span>ملاحظات أو رسالة إضافية تود إرفاقها @if($isReq('general_notes', false))<span class="text-rose-500">*</span>@endif</span>
                                            @if(!$isReq('general_notes', false))<span class="text-slate-400 font-normal text-[10px] bg-slate-100 px-2 py-0.5 rounded">اختياري</span>@endif
                                        </label>
                                        <textarea id="general_notes" 
                                                  name="general_notes" 
                                                  x-model="formData.general_notes"
                                                  rows="3" 
                                                  {{ $isReq('general_notes', false) ? 'required' : '' }}
                                                  placeholder="اكتب هنا أي تفاصيل تود توضيحها لفريق التوظيف..."
                                                  class="block w-full rounded-xl border-slate-200 py-3 px-4 text-xs shadow-xs brand-ring transition-colors bg-white">{{ old('general_notes') }}</textarea>
                                        @error('general_notes')
                                            <p class="mt-1 text-xs font-bold text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- ============================================== -->
                            <!-- STEP 4: Review & Final Confirmation (Wizard)   -->
                            <!-- ============================================== -->
                            @if($isWizard)
                            <div x-show="step === 4" x-transition.opacity>
                                <div class="border-b border-slate-100 pb-4 mb-6">
                                    <h2 class="text-base sm:text-lg font-black text-slate-900 flex items-center gap-2">
                                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-600 text-white text-xs font-black shadow-xs">4</span>
                                        مراجعة البيانات قبل الإرسال النهائي
                                    </h2>
                                    <p class="text-xs text-slate-500 mt-1">تأكد من صحة بياناتك ومرفقاتك أدناه قبل الضغط على تأكيد وإرسال الطلب.</p>
                                </div>

                                <div class="space-y-4">
                                    <!-- Summary: Personal Info -->
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                                        <div class="flex items-center justify-between mb-2 pb-2 border-b border-slate-200/60">
                                            <span class="text-xs font-bold text-slate-800">بيانات التواصل</span>
                                            <button type="button" @click="step = 1" class="text-[11px] font-bold text-teal-700 hover:underline">تعديل</button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                            <div><span class="text-slate-400">الاسم:</span> <strong class="text-slate-800" x-text="formData.full_name || '-'"></strong></div>
                                            <div><span class="text-slate-400">البريد:</span> <strong class="text-slate-800 font-mono" x-text="formData.email || '-'"></strong></div>
                                            <div><span class="text-slate-400">الهاتف:</span> <strong class="text-slate-800 font-mono" x-text="formData.phone || '-'"></strong></div>
                                            <div><span class="text-slate-400">لينكد إن:</span> <strong class="text-slate-800" x-text="formData.linkedin_url ? 'مرفق' : '-'"></strong></div>
                                        </div>
                                    </div>

                                    <!-- Summary: Experience -->
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                                        <div class="flex items-center justify-between mb-2 pb-2 border-b border-slate-200/60">
                                            <span class="text-xs font-bold text-slate-800">الخبرات والعمل</span>
                                            <button type="button" @click="step = 2" class="text-[11px] font-bold text-teal-700 hover:underline">تعديل</button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                            <div><span class="text-slate-400">المسمى الحالي:</span> <strong class="text-slate-800" x-text="formData.current_job_title || '-'"></strong></div>
                                            <div><span class="text-slate-400">سنوات الخبرة:</span> <strong class="text-slate-800" x-text="(formData.years_of_experience || '0') + ' سنوات'"></strong></div>
                                            <div><span class="text-slate-400">الراتب المتوقع:</span> <strong class="text-slate-800" x-text="(formData.expected_salary || '-') + ' ' + '{{ \App\Models\Setting::currencySymbol() }}'"></strong></div>
                                            <div><span class="text-slate-400">مباشرة العمل:</span> <strong class="text-slate-800" x-text="formData.availability || 'فوراً'"></strong></div>
                                        </div>
                                    </div>

                                    <!-- Summary: Resume File -->
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                                        <div class="flex items-center justify-between mb-2 pb-2 border-b border-slate-200/60">
                                            <span class="text-xs font-bold text-slate-800">السيرة الذاتية</span>
                                            <button type="button" @click="step = 3" class="text-[11px] font-bold text-teal-700 hover:underline">تعديل</button>
                                        </div>
                                        <div class="text-xs flex items-center gap-2">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <span class="text-slate-500">الملف المحدد:</span>
                                            <strong class="text-emerald-700 font-bold" x-text="fileName || 'لم يتم اختيار ملف بعد'"></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>

                        <!-- ============================================== -->
                        <!-- FOOTER ACTION BUTTONS                          -->
                        <!-- ============================================== -->
                        <div class="bg-slate-50/80 border-t border-slate-100 px-6 sm:px-10 py-5 flex items-center justify-between gap-4">
                            @if($isWizard)
                                <!-- Previous Step Button -->
                                <div>
                                    <button type="button" 
                                            x-show="step > 1" 
                                            @click="prevStep()" 
                                            class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-700 hover:bg-slate-100 transition-all cursor-pointer">
                                        <span>&rarr;</span>
                                        <span>السابق</span>
                                    </button>
                                </div>

                                <!-- Next / Submit Buttons -->
                                <div class="flex items-center gap-3">
                                    <button type="button" 
                                            x-show="step < totalSteps" 
                                            @click="nextStep()" 
                                            class="inline-flex items-center gap-2 px-7 py-3 rounded-xl brand-gradient text-xs sm:text-sm font-bold text-white shadow-md hover:opacity-95 transition-all cursor-pointer">
                                        <span>المتابعة للخطوة التالية</span>
                                        <span>&larr;</span>
                                    </button>

                                    <button type="submit" 
                                            x-show="step === totalSteps" 
                                            :disabled="isSubmitting"
                                            class="inline-flex items-center gap-2 px-9 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-sm font-black text-white shadow-lg shadow-emerald-600/20 transition-all cursor-pointer disabled:opacity-75">
                                        <span x-show="!isSubmitting">تأكيد وإرسال طلب التقديم</span>
                                        <span x-show="isSubmitting" style="display:none;" class="flex items-center gap-2">
                                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            جاري الإرسال...
                                        </span>
                                    </button>
                                </div>
                            @else
                                <!-- Single Page Submit Button -->
                                <p class="text-xs text-slate-500 font-medium">يرجى التأكد من صحة كافة البيانات المذكورة قبل الإرسال.</p>
                                <button type="submit" 
                                        :disabled="isSubmitting"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-9 py-3.5 rounded-xl brand-gradient text-sm font-black text-white shadow-lg shadow-slate-900/10 hover:opacity-95 transition-all cursor-pointer disabled:opacity-75">
                                    <span x-show="!isSubmitting">إرسال طلب التقديم</span>
                                    <span x-show="isSubmitting" style="display:none;" class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        جاري الإرسال...
                                    </span>
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

    </main>

    <script>
        function jobApplicationApp() {
            return {
                isWizard: {{ $isWizard ? 'true' : 'false' }},
                step: {{ $initialStep ?? 1 }},
                totalSteps: 4,
                isSubmitting: false,
                fileName: '',
                fileSize: '',
                formData: {
                    full_name: @json(old('full_name', '')),
                    email: @json(old('email', '')),
                    phone: @json(old('phone', '')),
                    linkedin_url: @json(old('linkedin_url', '')),
                    portfolio_url: @json(old('portfolio_url', '')),
                    current_job_title: @json(old('current_job_title', '')),
                    current_company: @json(old('current_company', '')),
                    years_of_experience: @json(old('years_of_experience', '')),
                    expected_salary: @json(old('expected_salary', '')),
                    seniority_level: @json(old('seniority_level', '')),
                    work_preference: @json(old('work_preference', '')),
                    availability: @json(old('availability', 'immediately')),
                    general_notes: @json(old('general_notes', ''))
                },
                stepErrors: {},

                handleFileChange(event) {
                    const files = event.target.files;
                    if (files && files[0]) {
                        this.setFile(files[0]);
                    }
                },

                handleFileDrop(event) {
                    const files = event.dataTransfer.files;
                    if (files && files[0]) {
                        this.$refs.resumeInput.files = files;
                        this.setFile(files[0]);
                    }
                },

                setFile(file) {
                    this.fileName = file.name;
                    const sizeInMb = (file.size / (1024 * 1024)).toFixed(2);
                    this.fileSize = sizeInMb + ' MB';
                    delete this.stepErrors.resume;
                },

                clearFile() {
                    this.fileName = '';
                    this.fileSize = '';
                    this.$refs.resumeInput.value = '';
                },

                nextStep() {
                    if (this.validateCurrentStep()) {
                        this.step++;
                        window.scrollTo({ top: 120, behavior: 'smooth' });
                    }
                },

                prevStep() {
                    if (this.step > 1) {
                        this.step--;
                        window.scrollTo({ top: 120, behavior: 'smooth' });
                    }
                },

                goToStep(target) {
                    if (target < this.step) {
                        this.step = target;
                        window.scrollTo({ top: 120, behavior: 'smooth' });
                    } else if (target > this.step) {
                        if (this.validateCurrentStep()) {
                            this.step = target;
                            window.scrollTo({ top: 120, behavior: 'smooth' });
                        }
                    }
                },

                validateCurrentStep() {
                    this.stepErrors = {};
                    let isValid = true;

                    if (this.step === 1) {
                        const nameEl = document.getElementById('full_name');
                        const name = this.formData.full_name?.trim();
                        if (nameEl && nameEl.hasAttribute('required') && (!name || name.length < 3)) {
                            this.stepErrors.full_name = 'يرجى إدخال الاسم الكامل (3 أحرف على الأقل).';
                            isValid = false;
                        }

                        const emailEl = document.getElementById('email');
                        const email = this.formData.email?.trim();
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (emailEl) {
                            if (emailEl.hasAttribute('required') && !email) {
                                this.stepErrors.email = 'يرجى إدخال البريد الإلكتروني.';
                                isValid = false;
                            } else if (email && !emailRegex.test(email)) {
                                this.stepErrors.email = 'يرجى إدخال بريد إلكتروني صحيح.';
                                isValid = false;
                            }
                        }

                        const phoneEl = document.getElementById('phone');
                        const phone = this.formData.phone?.trim();
                        if (phoneEl && phoneEl.hasAttribute('required') && (!phone || phone.length < 8)) {
                            this.stepErrors.phone = 'يرجى إدخال رقم هاتف صحيح.';
                            isValid = false;
                        }
                    } else if (this.step === 3) {
                        const fileInput = document.getElementById('resume');
                        if (fileInput && fileInput.hasAttribute('required') && !this.fileName) {
                            this.stepErrors.resume = 'يرجى إرفاق السيرة الذاتية (CV).';
                            isValid = false;
                        }
                    }

                    if (!isValid) {
                        const firstErrorKey = Object.keys(this.stepErrors)[0];
                        const el = document.getElementById(firstErrorKey);
                        if (el) el.focus();
                    }

                    return isValid;
                }
            }
        }
    </script>
</body>
</html>
