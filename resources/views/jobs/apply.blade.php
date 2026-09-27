<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقديم على وظيفة: {{ $job->title }}</title>
    @if(\App\Models\Setting::get('logo'))
    <meta property="og:image" content="{{ Storage::disk('public')->url(\App\Models\Setting::get('logo')) }}">
    @endif
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Cairo', 'sans-serif'] },
                    colors: { 
                        brand: { 50: '#f1f8fc', 100: '#e0f0f8', 200: '#bae0f0', 300: '#83c9e5', 400: '#46aed7', 500: '#2695c4', 600: '{{ $job->brand_color ?? "#1b83bf" }}', 700: '#18699d', 800: '#165882', 900: '#164a6d', 950: '#0f304a' },
                        secondary: { 50: '#fef6f0', 100: '#fdeddb', 200: '#fcd2b3', 300: '#fab382', 400: '#f88b48', 500: '#f58633', 600: '#e66a20', 700: '#bf501a', 800: '#98401a', 900: '#7a3517' } 
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #f8fafc; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased text-gray-800 selection:bg-brand-100 selection:text-brand-900 pb-12">

    <!-- Top Navigation Bar -->
    <nav class="bg-white border-b border-gray-100 px-4 py-4 sm:px-6 lg:px-8 mb-8 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                @php $logo = \App\Models\Setting::get('logo'); @endphp
                @if($logo)
                    <img src="{{ str_starts_with($logo, 'http') || str_starts_with($logo, '/') ? $logo : \Illuminate\Support\Facades\Storage::disk('public')->url($logo) }}" alt="Logo" class="h-8 sm:h-10 object-contain">
                @endif
                <span class="text-lg font-extrabold text-brand-900 border-r border-gray-200 pr-4 ml-4 hidden sm:inline-block">بوابة التوظيف</span>
            </div>
            <a href="{{ route('jobs.index') }}" class="text-sm font-bold text-gray-500 hover:text-secondary-500 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4 transform rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                العودة للوظائف
            </a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="max-w-2xl mx-auto text-center py-20">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-100 mb-8 shadow-lg shadow-green-100">
                    <svg class="w-12 h-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-4">اكتمل التقديم بنجاح!</h2>
                <p class="text-lg text-gray-600 mb-8">{{ session('success') }}</p>
                <a href="{{ route('jobs.index') }}" class="inline-flex justify-center px-8 py-3 border border-transparent text-base font-bold rounded-xl text-white bg-secondary-500 hover:bg-secondary-600 shadow-md transition-all">
                    تصفح المزيد من الشواغر
                </a>
            </div>
        @else

        <div class="lg:grid lg:grid-cols-12 lg:gap-10 items-start">
            
            <!-- Sidebar: Job Context (Sticky) -->
            <div class="lg:col-span-4 mb-8 lg:mb-0 lg:sticky lg:top-24">
                <div class="bg-gradient-to-br from-brand-900 via-brand-800 to-brand-800 rounded-3xl p-8 text-white shadow-2xl shadow-brand-900/20 relative overflow-hidden border border-brand-700/50">
                    <!-- Decor -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-brand-400 opacity-20 rounded-full blur-2xl transform -translate-x-1/2 translate-y-1/2 pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        <span class="inline-block px-3 py-1.5 bg-secondary-500 text-white text-xs font-bold rounded-lg mb-5 border border-secondary-400 shadow-sm">تقديم طلب توظيف</span>
                        <h1 class="text-2xl sm:text-3xl font-extrabold leading-tight mb-5 drop-shadow-sm">{{ $job->title }}</h1>
                        
                        <div class="flex flex-wrap gap-3 mb-8">
                            @if($job->department)
                                <div class="flex items-center gap-1.5 text-sm text-brand-100 bg-brand-800/40 px-3 py-1.5 rounded-md border border-brand-700/40 backdrop-blur-sm">
                                    <svg class="w-4 h-4 text-brand-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    {{ $job->department->name }}
                                </div>
                            @endif
                            @if($job->location)
                                <div class="flex items-center gap-1.5 text-sm text-brand-100 bg-brand-800/40 px-3 py-1.5 rounded-md border border-brand-700/40 backdrop-blur-sm">
                                    <svg class="w-4 h-4 text-brand-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    {{ $job->location->name }}
                                </div>
                            @endif
                        </div>

                        <div class="border-t border-brand-700/50 pt-6 prose prose-invert prose-sm max-w-none hide-scrollbar max-h-[45vh] overflow-y-auto">
                            <h3 class="text-brand-100 font-bold mb-3 text-base">نظرة عامة على الوظيفة</h3>
                            <div class="text-brand-50 leading-relaxed">
                                {!! $job->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Form Column -->
            <div class="lg:col-span-8">
                <form action="{{ route('jobs.submit', $job->id) }}" method="POST" enctype="multipart/form-data" 
                      x-data="{ isSubmitting: false }" 
                      @submit="isSubmitting = true"
                      class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    @csrf

                    <div class="p-6 sm:p-10 space-y-10">
                        
                        @if($errors->any())
                            <div class="bg-red-50 p-6 rounded-2xl border border-red-100 flex items-start shadow-sm">
                                <svg class="h-6 w-6 text-secondary-500 ml-3 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <h3 class="text-base font-bold text-red-800">يرجى تصحيح الأخطاء التالية قبل المتابعة:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1 font-medium">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @php $config = $job->primary_fields_config ?? []; $stepCounter = 1; @endphp

                                                <!-- Section 1 -->
                        @if(
                            ($config['show_full_name'] ?? true) || ($config['show_email'] ?? true) ||
                            ($config['show_phone'] ?? true) || ($config['show_linkedin'] ?? true) ||
                            ($config['show_portfolio'] ?? true)
                        )
                        <div class="bg-brand-50/30 p-6 sm:p-8 rounded-2xl border border-brand-50 hover:border-brand-100 transition-colors">
                            <h2 class="text-xl font-extrabold text-brand-950 mb-6 flex items-center gap-3">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-secondary-500 text-white text-sm shadow-md shadow-secondary-200">{{ $stepCounter++ }}</span>
                                المعلومات الشخصية
                            </h2>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                @if($config['show_full_name'] ?? true)
                                <div class="sm:col-span-2">
                                    <label for="full_name" class="block text-sm font-bold text-gray-700">الاسم الكامل <span class="text-secondary-500">*</span></label>
                                    <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required
                                        class="mt-2 block w-full rounded-xl border-gray-200 py-3 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors bg-white">
                                    @error('full_name')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif

                                @if($config['show_email'] ?? true)
                                <div>
                                    <label for="email" class="block text-sm font-bold text-gray-700">البريد الإلكتروني <span class="text-secondary-500">*</span></label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                        class="mt-2 block w-full rounded-xl border-gray-200 py-3 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors bg-white text-left" dir="ltr" placeholder="you@example.com">
                                    @error('email')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif

                                @if($config['show_phone'] ?? true)
                                <div>
                                    <label for="phone" class="block text-sm font-bold text-gray-700">رقم الجوال <span class="text-secondary-500">*</span></label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required pattern="^\+?[0-9][0-9\s\-\(\)]{7,20}$"
                                        class="mt-2 block w-full rounded-xl border-gray-200 py-3 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors bg-white text-left" dir="ltr" placeholder="05xxxxxxxx">
                                    @error('phone')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif

                                @if($config['show_linkedin'] ?? true)
                                <div>
                                    <label for="linkedin_url" class="block text-sm font-bold text-gray-700 flex justify-between">
                                        لينكد إن
                                        <span class="text-gray-400 font-normal text-xs bg-gray-100 px-2 py-0.5 rounded">اختياري</span>
                                    </label>
                                    <input type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url') }}" pattern="^https:\/\/(www\.)?[a-zA-Z0-9-]*\.?linkedin\.com\/.*$"
                                        class="mt-2 block w-full rounded-xl border-gray-200 py-3 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors bg-white text-left" dir="ltr" placeholder="https://linkedin.com/in/...">
                                    @error('linkedin_url')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif

                                @if($config['show_portfolio'] ?? true)
                                <div>
                                    <label for="portfolio_url" class="block text-sm font-bold text-gray-700 flex justify-between">
                                        معرض الأعمال (Portfolio)
                                        <span class="text-gray-400 font-normal text-xs bg-gray-100 px-2 py-0.5 rounded">اختياري</span>
                                    </label>
                                    <input type="url" name="portfolio_url" id="portfolio_url" value="{{ old('portfolio_url') }}"
                                        class="mt-2 block w-full rounded-xl border-gray-200 py-3 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors bg-white text-left" dir="ltr" placeholder="https://...">
                                    @error('portfolio_url')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif
                            </div>
                        </div>

                                                @endif

                        <!-- Section 2 -->
                        @if(
                            ($config['show_current_job_title'] ?? true) || ($config['show_current_company'] ?? true) ||
                            ($config['show_years_of_experience'] ?? true) || ($config['show_expected_salary'] ?? true) ||
                            ($config['show_seniority_level'] ?? true) || ($config['show_work_preference'] ?? true) || ($config['show_availability'] ?? true)
                        )
                        <div class="bg-brand-50/30 p-6 sm:p-8 rounded-2xl border border-brand-50 hover:border-brand-100 transition-colors">
                            <h2 class="text-xl font-extrabold text-brand-950 mb-6 flex items-center gap-3">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-secondary-500 text-white text-sm shadow-md shadow-secondary-200">{{ $stepCounter++ }}</span>
                                الخبرة المهنية
                            </h2>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                @if($config['show_current_job_title'] ?? true)
                                <div>
                                    <label for="current_job_title" class="block text-sm font-bold text-gray-700">المسمى الوظيفي الحالي</label>
                                    <input type="text" name="current_job_title" id="current_job_title" value="{{ old('current_job_title') }}"
                                        class="mt-2 block w-full rounded-xl border-gray-200 py-3 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors bg-white">
                                    @error('current_job_title')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif

                                @if($config['show_current_company'] ?? true)
                                <div>
                                    <label for="current_company" class="block text-sm font-bold text-gray-700">جهة العمل الحالية</label>
                                    <input type="text" name="current_company" id="current_company" value="{{ old('current_company') }}"
                                        class="mt-2 block w-full rounded-xl border-gray-200 py-3 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors bg-white">
                                    @error('current_company')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif

                                @if($config['show_years_of_experience'] ?? true)
                                <div>
                                    <label for="years_of_experience" class="block text-sm font-bold text-gray-700">سنوات الخبرة</label>
                                    <div class="relative mt-2">
                                        <input type="number" step="0.5" name="years_of_experience" id="years_of_experience" value="{{ old('years_of_experience') }}"
                                            class="block w-full rounded-xl border-gray-200 py-3 pl-12 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors bg-white">
                                    @error('years_of_experience')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-gray-400 font-bold text-sm">سنوات</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($config['show_expected_salary'] ?? true)
                                <div>
                                    <label for="expected_salary" class="block text-sm font-bold text-gray-700">الراتب المتوقع</label>
                                    <input type="number" name="expected_salary" id="expected_salary" value="{{ old('expected_salary') }}"
                                        class="mt-2 block w-full rounded-xl border-gray-200 py-3 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors bg-white">
                                    @error('expected_salary')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif

                                @if($config['show_seniority_level'] ?? true)
                                <div>
                                    <label for="seniority_level" class="block text-sm font-bold text-gray-700">المستوى المهني</label>
                                    <select name="seniority_level" id="seniority_level" class="mt-2 block w-full rounded-xl border-gray-200 py-3 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors bg-white cursor-pointer">
                                        <option value="">-- اختيار --</option>
                                        <option value="Trainee" {{ old('seniority_level') == 'Trainee' ? 'selected' : '' }}>متدرب</option>
                                        <option value="Junior" {{ old('seniority_level') == 'Junior' ? 'selected' : '' }}>مبتدئ</option>
                                        <option value="Mid-Level" {{ old('seniority_level') == 'Mid-Level' ? 'selected' : '' }}>متوسط</option>
                                        <option value="Senior" {{ old('seniority_level') == 'Senior' ? 'selected' : '' }}>متقدم</option>
                                        <option value="Lead" {{ old('seniority_level') == 'Lead' ? 'selected' : '' }}>قائد فريق</option>
                                        <option value="Manager" {{ old('seniority_level') == 'Manager' ? 'selected' : '' }}>مدير</option>
                                    </select>
                                    @error('seniority_level')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif

                                @if($config['show_work_preference'] ?? true)
                                <div>
                                    <label for="work_preference" class="block text-sm font-bold text-gray-700">نظام العمل المفضل</label>
                                    <select name="work_preference" id="work_preference" class="mt-2 block w-full rounded-xl border-gray-200 py-3 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors bg-white cursor-pointer">
                                        <option value="">-- اختيار --</option>
                                        <option value="On-site" {{ old('work_preference') == 'On-site' ? 'selected' : '' }}>مقر العمل</option>
                                        <option value="Remote" {{ old('work_preference') == 'Remote' ? 'selected' : '' }}>عن بُعد</option>
                                        <option value="Hybrid" {{ old('work_preference') == 'Hybrid' ? 'selected' : '' }}>هجين</option>
                                    </select>
                                    @error('work_preference')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif

                                @if($config['show_availability'] ?? true)
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-3">متى يمكنك البدء؟</label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        @foreach(['Immediately' => 'فوراً', 'Within 2 Weeks' => 'خلال أسبوعين', 'Within 1 Month' => 'خلال شهر', 'More than 1 Month' => 'أكثر من شهر'] as $val => $label)
                                        <label class="relative flex items-center justify-center p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-brand-50 transition-all has-[:checked]:bg-brand-600 has-[:checked]:text-white has-[:checked]:border-brand-600 text-center shadow-sm bg-white text-gray-700">
                                            <input type="radio" name="availability" value="{{ $val }}" class="sr-only" {{ old('availability') == $val ? 'checked' : '' }}>
                                            <span class="text-sm font-bold">{{ $label }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                                                <!-- Section 3 -->
                        @if(($config['show_resume'] ?? true) || ($config['show_general_notes'] ?? true))
                        <div class="bg-brand-50/30 p-6 sm:p-8 rounded-2xl border border-brand-50 hover:border-brand-100 transition-colors">
                            <h2 class="text-xl font-extrabold text-brand-950 mb-6 flex items-center gap-3">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-secondary-500 text-white text-sm shadow-md shadow-secondary-200">{{ $stepCounter++ }}</span>
                                المرفقات والتفاصيل الإضافية
                            </h2>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                                @if($config['show_resume'] ?? true)
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">السيرة الذاتية (CV) <span class="text-secondary-500">*</span></label>
                                    <div class="mt-2" x-data="{ fileName: '' }">
                                        <div class="flex justify-center px-6 py-10 border-2 border-brand-200 border-dashed rounded-2xl bg-white hover:bg-brand-50/50 transition-colors cursor-pointer relative"
                                             @dragover.prevent="$el.classList.add('border-brand-500', 'bg-brand-50')"
                                             @dragleave.prevent="$el.classList.remove('border-brand-500', 'bg-brand-50')"
                                             @drop.prevent="$el.classList.remove('border-brand-500', 'bg-brand-50'); $refs.fileInput.files = $event.dataTransfer.files; fileName = $refs.fileInput.files[0].name"
                                             @click="$refs.fileInput.click()">
                                            
                                            <div class="text-center">
                                                <svg class="mx-auto h-12 w-12 text-brand-300 mb-3" x-show="!fileName" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                <div class="mx-auto h-12 w-12 text-green-500 mb-3 bg-green-50 rounded-full flex items-center justify-center shadow-sm" x-show="fileName" style="display:none;">
                                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                                </div>
                                                
                                                <div class="text-sm font-bold text-brand-700 mb-1">
                                                    <span x-show="!fileName">انقر لاختيار ملف أو قم بسحبه وإفلاته هنا</span>
                                                    <span x-show="fileName" x-text="fileName" class="text-green-700" style="display:none;"></span>
                                                </div>
                                                <p class="text-xs text-gray-500" x-show="!fileName">PDF, DOC, DOCX بحد أقصى 10 ميغابايت</p>
                                                
                                                <input type="file" name="resume" id="resume" x-ref="fileInput" class="sr-only" required accept=".pdf,.doc,.docx" @change="fileName = $event.target.files[0].name">
                                    @error('resume')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($config['show_general_notes'] ?? true)
                                <div class="sm:col-span-2">
                                    <label for="general_notes" class="block text-sm font-bold text-gray-700">ملاحظات إضافية <span class="text-gray-400 font-normal text-xs bg-gray-100 px-2 py-0.5 rounded">اختياري</span></label>
                                    <textarea id="general_notes" name="general_notes" rows="3" 
                                        class="mt-2 block w-full rounded-xl border-gray-200 py-3 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors bg-white">{{ old('general_notes') }}</textarea>
                                    @error('general_notes')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif
                            </div>
                        </div>

                                                @endif

                        @if($job->form_schema && count($job->form_schema) > 0)
                        <!-- Section 4: Custom Questions -->
                        <div class="bg-brand-50/30 p-6 sm:p-8 rounded-2xl border border-brand-50 hover:border-brand-100 transition-colors">
                            <h2 class="text-xl font-extrabold text-brand-950 mb-6 flex items-center gap-3">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-secondary-500 text-white text-sm shadow-md shadow-secondary-200">{{ $stepCounter++ }}</span>
                                أسئلة إضافية
                            </h2>
                            
                            <div class="space-y-6">
                                @foreach($job->form_schema as $block)
                                    @php
                                        $data = $block['data'];
                                        $fieldName = 'custom_' . $data['name'];
                                        $isRequired = $data['is_required'] ?? false;
                                    @endphp

                                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                        @if($block['type'] === 'checkbox')
                                            <label class="flex items-start cursor-pointer">
                                                <div class="flex items-center h-6 mt-0.5">
                                                    <input type="checkbox" name="{{ $fieldName }}" value="1" {{ old($fieldName) ? 'checked' : '' }} {{ $isRequired ? 'required' : '' }}
                                                        class="h-5 w-5 rounded border-gray-300 text-brand-600 focus:ring-brand-600 transition-colors cursor-pointer">
                                                </div>
                                                <div class="mr-3 text-sm">
                                                    <span class="font-bold text-gray-800">{{ $data['label'] }} {!! $isRequired ? '<span class="text-secondary-500">*</span>' : '' !!}</span>
                                                </div>
                                            </label>
                                        @else
                                            <label class="block text-sm font-bold text-gray-800 mb-3">
                                                {{ $data['label'] }} 
                                                @if($isRequired)
                                                    <span class="text-secondary-500">*</span>
                                                @else
                                                    <span class="text-gray-400 font-normal text-xs mr-2 bg-gray-50 px-2 py-0.5 rounded">اختياري</span>
                                                @endif
                                            </label>

                                            @if($block['type'] === 'text')
                                                <input type="text" name="{{ $fieldName }}" value="{{ old($fieldName) }}" {{ $isRequired ? 'required' : '' }}
                                                    class="block w-full rounded-xl border-gray-200 py-3 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors">
                                    @error('{{ $fieldName }}')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                            
                                            @elseif($block['type'] === 'textarea')
                                                <textarea name="{{ $fieldName }}" rows="3" {{ $isRequired ? 'required' : '' }}
                                                    class="block w-full rounded-xl border-gray-200 py-3 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors">{{ old($fieldName) }}</textarea>
                                    @error('{{ $fieldName }}')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                            
                                            @elseif($block['type'] === 'select')
                                                @php $isMultiple = $data['is_multiple'] ?? false; @endphp
                                                
                                                @if($isMultiple)
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                        @foreach($data['options'] ?? [] as $option)
                                                            <label class="flex items-center p-3.5 border border-gray-200 rounded-xl cursor-pointer hover:bg-brand-50 transition-colors has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50 shadow-sm bg-white">
                                                                <input type="checkbox" name="{{ $fieldName }}[]" value="{{ $option }}" 
                                                                    {{ (is_array(old($fieldName)) && in_array($option, old($fieldName))) ? 'checked' : '' }}
                                                                    class="h-5 w-5 rounded border-gray-300 text-brand-600 focus:ring-brand-600">
                                                                <span class="mr-3 text-sm font-bold text-gray-700">{{ $option }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                    @error('{{ $fieldName }}')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                                @else
                                                    <select name="{{ $fieldName }}" {{ $isRequired ? 'required' : '' }}
                                                        class="block w-full rounded-xl border-gray-200 py-3 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors cursor-pointer">
                                                        <option value="">-- يرجى الاختيار --</option>
                                                        @foreach($data['options'] ?? [] as $option)
                                                            <option value="{{ $option }}" {{ old($fieldName) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                    @error('{{ $fieldName }}')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                                @endif
                                            
                                            @elseif($block['type'] === 'file')
                                                <input type="file" name="{{ $fieldName }}" {{ $isRequired ? 'required' : '' }} accept="{{ $data['accepted_file_types'] ?? '*' }}"
                                                    class="block w-full text-sm text-gray-700 border border-gray-200 rounded-xl cursor-pointer bg-white focus:outline-none file:mr-4 file:py-3 file:px-4 file:border-0 file:text-sm file:font-bold file:bg-brand-600 file:text-white hover:file:bg-brand-700 transition-colors shadow-sm">
                                    @error('{{ $fieldName }}')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                                    
                                            @elseif($block['type'] === 'date')
                                                <input type="date" name="{{ $fieldName }}" value="{{ old($fieldName) }}" {{ $isRequired ? 'required' : '' }}
                                                    class="block w-full rounded-xl border-gray-200 py-3 shadow-sm focus:border-brand-600 focus:ring-brand-600 transition-colors">
                                    @error('{{ $fieldName }}')
                                        <p class="mt-1 text-sm font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                                    
                                            @elseif($block['type'] === 'rating')
                                                <div class="flex items-center gap-2" x-data="{ rating: {{ old($fieldName) ?? 0 }} }">
                                                    <input type="hidden" name="{{ $fieldName }}" x-model="rating" {{ $isRequired ? 'required' : '' }}>
                                                    <template x-for="i in 5">
                                                        <button type="button" @click="rating = i" class="focus:outline-none transform transition-transform hover:scale-125">
                                                            <svg class="w-10 h-10 drop-shadow-sm" :class="rating >= i ? 'text-yellow-400' : 'text-gray-200 hover:text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        </button>
                                                    </template>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    </div>

                    <!-- Submit Footer -->
                    <div class="bg-gray-50 border-t border-gray-100 px-6 sm:px-10 py-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-sm text-gray-500 font-medium">يرجى التأكد من صحة بياناتك قبل الإرسال.</p>
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl bg-gradient-to-r from-secondary-500 to-secondary-600 px-10 py-4 text-base font-bold text-white shadow-lg shadow-secondary-200 hover:from-secondary-600 hover:to-secondary-700 focus:outline-none focus:ring-4 focus:ring-secondary-500/30 transition-all transform hover:-translate-y-0.5"
                                :class="{ 'opacity-75 cursor-wait': isSubmitting }">
                            <span x-show="!isSubmitting">إرسال طلب التقديم</span>
                            <span x-show="isSubmitting" style="display: none;" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                جاري الإرسال...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
        
    </div>

</body>
</html>
