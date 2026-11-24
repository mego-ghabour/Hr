<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الوظائف والفرص الشاغرة - {{ \App\Models\Setting::get('brand_name', 'فكرتك HR') }}</title>
    
    @php
        $primaryColor = \App\Models\Setting::get('brand_primary_color', \App\Models\Setting::get('primary_color', '#0d9488'));
        $logo = \App\Models\Setting::get('logo');
        $brandName = trim(\App\Models\Setting::get('brand_name', 'فكرتك HR'));
        $logoUrl = $logo ? (str_starts_with($logo, 'http') || str_starts_with($logo, '/') ? $logo : \Illuminate\Support\Facades\Storage::disk('public')->url($logo)) : null;
    @endphp

    <!-- Social Media Sharing Meta Tags (Open Graph) -->
    <meta property="og:title" content="الوظائف الشاغرة - {{ $brandName }}">
    <meta property="og:description" content="تصفح أحدث الوظائف الشاغرة لدينا وقدم سيرتك الذاتية الآن للانضمام إلى فريق العمل.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($logoUrl)
        <meta property="og:image" content="{{ $logoUrl }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Cairo', 'sans-serif'] },
                    colors: {
                        brand: {
                            DEFAULT: '{{ $primaryColor }}',
                            50: 'color-mix(in srgb, {{ $primaryColor }} 8%, white)',
                            100: 'color-mix(in srgb, {{ $primaryColor }} 16%, white)',
                            200: 'color-mix(in srgb, {{ $primaryColor }} 30%, white)',
                            500: '{{ $primaryColor }}',
                            600: 'color-mix(in srgb, {{ $primaryColor }} 90%, black)',
                            700: 'color-mix(in srgb, {{ $primaryColor }} 80%, black)',
                            800: 'color-mix(in srgb, {{ $primaryColor }} 70%, black)',
                            900: 'color-mix(in srgb, {{ $primaryColor }} 60%, black)',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --brand-primary: {{ $primaryColor }};
        }
        body { 
            font-family: 'Cairo', sans-serif;
            background-color: #f8fafc;
        }
        .filter-btn {
            background-color: {{ $primaryColor }};
        }
        .filter-btn:hover {
            background-color: color-mix(in srgb, {{ $primaryColor }} 88%, black);
        }
        .brand-focus:focus {
            border-color: {{ $primaryColor }};
            box-shadow: 0 0 0 3px color-mix(in srgb, {{ $primaryColor }} 20%, transparent);
            outline: none;
        }
    </style>
</head>
<body class="min-h-screen text-slate-800 antialiased flex flex-col justify-between selection:bg-teal-100 selection:text-teal-900 bg-slate-50/70">

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 flex-grow w-full">

        <!-- Modern Clean Hero Section (Flat Logo without Shadows, Pure High Contrast) -->
        <div class="relative bg-white border border-slate-200/80 rounded-3xl p-8 sm:p-12 mb-8 text-center overflow-hidden">
            <!-- Subtle ambient glow decoration -->
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-teal-500/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col items-center justify-center">
                <!-- Pure Flat Logo (NO drop-shadow, NO box-shadow) -->
                @if($logoUrl)
                    <div class="mb-5 flex items-center justify-center">
                        <img src="{{ $logoUrl }}" 
                             alt="{{ $brandName }}" 
                             class="h-16 sm:h-20 w-auto max-w-[260px] object-contain">
                    </div>
                @else
                    <div class="h-14 w-14 rounded-2xl bg-teal-600 flex items-center justify-center text-white font-black text-2xl mb-4">
                        {{ mb_substr($brandName, 0, 1) }}
                    </div>
                @endif

                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-teal-50 border border-teal-200/70 text-teal-800 text-xs font-bold rounded-full mb-3">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-teal-500"></span>
                    </span>
                    <span>بوابة التوظيف الرسمية &bull; {{ $jobs->count() }} {{ $jobs->count() === 1 ? 'وظيفة معلنة' : 'وظائف معلنة' }}</span>
                </div>

                <!-- Main Titles -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight mb-3">
                    الوظائف والفرص الشاغرة
                </h1>
                <p class="text-slate-500 text-sm sm:text-base max-w-xl mx-auto font-medium leading-relaxed">
                    انضم إلى فريق عملنا في <span class="font-bold text-slate-900">{{ $brandName }}</span> واكتشف بيئة عمل تُلهمك للنمو والإبداع وتحقيق أهدافك المهنية
                </p>
            </div>
        </div>

        <!-- Filters Section -->
        <form method="GET" action="{{ route('jobs.list') }}" class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 mb-8 flex flex-col md:flex-row gap-3.5 items-end">
            <!-- Search -->
            <div class="w-full md:flex-1">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">البحث بالكلمات المفتاحية</label>
                <div class="relative">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="brand-focus block w-full rounded-xl border border-slate-200 pl-4 pr-10 py-2.5 text-xs sm:text-sm bg-slate-50/50 transition-all font-medium text-slate-900" placeholder="المسمى الوظيفي، الكلمات المفتاحية...">
                </div>
            </div>
            
            <!-- Department -->
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">القسم / الإدارة</label>
                <div class="relative">
                    <select name="department_id" class="brand-focus block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-xs sm:text-sm bg-slate-50/50 appearance-none transition-all cursor-pointer font-medium text-slate-900">
                        <option value="">جميع الأقسام</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">مقر العمل</label>
                <div class="relative">
                    <select name="location_id" class="brand-focus block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-xs sm:text-sm bg-slate-50/50 appearance-none transition-all cursor-pointer font-medium text-slate-900">
                        <option value="">جميع المقرات</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="w-full md:w-auto">
                <button type="submit" class="filter-btn w-full md:w-auto text-white font-bold py-2.5 px-7 rounded-xl transition-all flex justify-center items-center gap-2 cursor-pointer text-xs sm:text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>تصفية</span>
                </button>
            </div>
        </form>

        <!-- Job Listings (3 Columns Responsive Grid) -->
        @if($jobs->isEmpty())
            <div class="bg-white rounded-3xl p-10 sm:p-14 text-center border border-slate-200/80">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-1">لا توجد وظائف معلنة حالياً تطابق بحثك</h3>
                <p class="text-slate-500 text-xs sm:text-sm">يمكنك تجربة تغيير شروط التصفية أو العودة لاحقاً لمتابعة الوظائف الجديدة.</p>
                @if(request('search') || request('department_id') || request('location_id'))
                    <a href="{{ route('jobs.list') }}" class="inline-block mt-4 text-xs sm:text-sm font-bold text-teal-700 hover:text-teal-800 underline">
                        إعادة ضبط التصفية وعرض كافة الوظائف
                    </a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                @foreach($jobs as $job)
                    @php
                        $jobColor = $job->brand_color ?: $primaryColor;
                    @endphp
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 flex flex-col h-full hover:-translate-y-1 hover:border-slate-300 hover:shadow-lg transition-all duration-300 relative group overflow-hidden">
                        
                        <!-- Top Accent Bar using Job's Brand Color -->
                        <div class="absolute top-0 right-0 left-0 h-1.5 transition-all duration-300"
                             style="background: linear-gradient(to right, {{ $jobColor }}, color-mix(in srgb, {{ $jobColor }} 60%, white));"></div>
                        
                        <div class="mb-4">
                            <h2 class="text-lg font-black text-slate-900 mb-2.5 transition-colors line-clamp-2"
                                onmouseover="this.style.color='{{ $jobColor }}'"
                                onmouseout="this.style.color=''">
                                {{ $job->title }}
                            </h2>
                            <div class="flex flex-wrap gap-1.5 text-xs font-semibold">
                                @if($job->department)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border font-bold"
                                          style="background-color: color-mix(in srgb, {{ $jobColor }} 8%, white); color: {{ $jobColor }}; border-color: color-mix(in srgb, {{ $jobColor }} 25%, white);">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        {{ $job->department->name }}
                                    </span>
                                @endif
                                @if($job->location)
                                    <span class="inline-flex items-center gap-1 bg-slate-50 text-slate-600 px-2.5 py-1 rounded-lg border border-slate-200">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        {{ $job->location->name }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center gap-1 bg-slate-50 text-slate-400 px-2.5 py-1 rounded-lg border border-slate-200 font-mono text-[11px]">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $job->created_at->locale('ar')->diffForHumans(['parts' => 1]) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="prose prose-sm text-slate-500 mb-5 flex-grow leading-relaxed line-clamp-3 text-xs sm:text-sm">
                            {!! strip_tags($job->description) !!}
                        </div>
                        
                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                            <span class="text-xs font-extrabold flex items-center gap-1 shrink-0" style="color: {{ $jobColor }};">
                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $jobColor }};"></span>
                                متاح التقديم
                            </span>
                            <a href="{{ route('jobs.apply', $job->id) }}" 
                               class="inline-flex items-center gap-1.5 text-white font-bold py-2 px-3.5 sm:px-4 rounded-xl transition-all duration-200 cursor-pointer text-xs sm:text-sm"
                               style="background: linear-gradient(135deg, {{ $jobColor }}, color-mix(in srgb, {{ $jobColor }} 85%, black));">
                                <span>التقديم على الوظيفة</span>
                                <svg class="w-3.5 h-3.5 rtl:rotate-180 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 py-6 text-center text-xs text-slate-500 mt-12">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $brandName }}" class="h-6 w-auto object-contain opacity-75">
                @endif
                <span class="font-bold text-slate-700">{{ $brandName }}</span>
            </div>
            <p class="text-[11px] text-slate-400">
                جميع الحقوق محفوظة &copy; {{ date('Y') }} &bull; بوابة استقطاب الكفاءات والتوظيف
            </p>
        </div>
    </footer>

</body>
</html>
