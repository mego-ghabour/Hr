<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الوظائف المتاحة - {{ \App\Models\Setting::get('brand_name', 'نظام إدارة الموارد البشرية') }}</title>
    
    @php
        $primaryColor = \App\Models\Setting::get('primary_color', '#0d9488');
        $logo = \App\Models\Setting::get('logo');
        $brandName = \App\Models\Setting::get('brand_name', 'نظام إدارة الموارد البشرية');
    @endphp

    <!-- Social Media Sharing Meta Tags (Open Graph) -->
    <meta property="og:title" content="الوظائف المتاحة - {{ $brandName }}">
    <meta property="og:description" content="تصفح أحدث الوظائف الشاغرة لدينا وقدم سيرتك الذاتية الآن للانضمام إلى فريق العمل.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($logo)
        <meta property="og:image" content="{{ str_starts_with($logo, 'http') || str_starts_with($logo, '/') ? $logo : \Illuminate\Support\Facades\Storage::disk('public')->url($logo) }}">
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
        .hero-gradient {
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, color-mix(in srgb, {{ $primaryColor }} 70%, black) 100%);
        }
        .filter-btn {
            background-color: {{ $primaryColor }};
        }
        .filter-btn:hover {
            background-color: color-mix(in srgb, {{ $primaryColor }} 85%, black);
        }
        .brand-focus:focus {
            border-color: {{ $primaryColor }};
            box-shadow: 0 0 0 3px color-mix(in srgb, {{ $primaryColor }} 20%, transparent);
            outline: none;
        }
    </style>
</head>
<body class="min-h-screen text-slate-800 antialiased py-8 px-4 sm:px-6 lg:px-8">

    <div class="max-w-5xl mx-auto">
        <!-- Hero Header -->
        <div class="hero-gradient text-white rounded-3xl shadow-xl p-8 sm:p-12 mb-8 text-center relative overflow-hidden border border-white/10">
            <!-- Background glow accents -->
            <div class="absolute -top-24 -right-24 w-72 h-72 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-black/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col items-center justify-center">
                @if($logo)
                    <img src="{{ str_starts_with($logo, 'http') || str_starts_with($logo, '/') ? $logo : \Illuminate\Support\Facades\Storage::disk('public')->url($logo) }}" alt="Logo" class="h-16 mb-4 object-contain drop-shadow">
                @else
                    <div class="h-14 w-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white font-black text-2xl shadow-sm mb-4 border border-white/20">
                        HR
                    </div>
                @endif
                <span class="inline-block px-3.5 py-1 bg-white/20 text-white text-xs font-bold rounded-full mb-3 backdrop-blur-xs border border-white/20">
                    بوابة التوظيف الرسمية
                </span>
                <h1 class="text-3xl sm:text-5xl font-black tracking-tight mb-3">الوظائف والفرص الشاغرة</h1>
                <p class="text-white/90 text-base sm:text-lg max-w-xl mx-auto">انضم إلى فريق عملنا واكتشف بيئة عمل تُلهمك للنمو والإبداع</p>
            </div>
        </div>

        <!-- Filters Section -->
        <form method="GET" action="{{ route('jobs.list') }}" class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 mb-8 flex flex-col md:flex-row gap-4 items-end">
            <!-- Search -->
            <div class="w-full md:w-2/5">
                <label class="block text-xs font-bold text-slate-600 mb-1.5">البحث بالكلمات المفتاحية</label>
                <div class="relative">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="brand-focus block w-full rounded-xl border border-slate-200 pl-4 pr-10 py-2.5 text-sm bg-slate-50/50 transition-all" placeholder="المسمى الوظيفي، الكلمات المفتاحية...">
                </div>
            </div>
            
            <!-- Department -->
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-bold text-slate-600 mb-1.5">القسم / الإدارة</label>
                <div class="relative">
                    <select name="department_id" class="brand-focus block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm bg-slate-50/50 appearance-none transition-all cursor-pointer">
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
                <label class="block text-xs font-bold text-slate-600 mb-1.5">مقر العمل</label>
                <div class="relative">
                    <select name="location_id" class="brand-focus block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm bg-slate-50/50 appearance-none transition-all cursor-pointer">
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
                <button type="submit" class="filter-btn w-full md:w-auto text-white font-bold py-2.5 px-7 rounded-xl transition-all shadow hover:shadow-md flex justify-center items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    تصفية
                </button>
            </div>
        </form>

        <!-- Job Listings -->
        @if($jobs->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm">
                <div class="w-20 h-20 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-1">لا توجد وظائف معلنة حالياً تطابق بحثك</h3>
                <p class="text-slate-500 text-sm">يمكنك تجربة تغيير شروط التصفية أو العودة لاحقاً لمتابعة الوظائف الجديدة.</p>
                @if(request('search') || request('department_id') || request('location_id'))
                    <a href="{{ route('jobs.list') }}" class="inline-block mt-4 text-sm font-bold underline" style="color: {{ $primaryColor }}">
                        إعادة ضبط التصفية وعرض كافة الوظائف
                    </a>
                @endif
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2">
                @foreach($jobs as $job)
                    @php
                        $jobColor = $job->brand_color ?: $primaryColor;
                    @endphp
                    <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl border border-slate-200/80 p-7 flex flex-col h-full hover:-translate-y-1 transition-all duration-300 relative group overflow-hidden">
                        
                        <!-- Top Accent Bar using Job's Brand Color -->
                        <div class="absolute top-0 right-0 left-0 h-1.5 transition-all duration-300"
                             style="background: linear-gradient(to right, {{ $jobColor }}, color-mix(in srgb, {{ $jobColor }} 60%, white));"></div>
                        
                        <div class="mb-5">
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900 mb-3 transition-colors"
                                onmouseover="this.style.color='{{ $jobColor }}'"
                                onmouseout="this.style.color=''">
                                {{ $job->title }}
                            </h2>
                            <div class="flex flex-wrap gap-2 text-xs font-semibold">
                                @if($job->department)
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border font-bold"
                                          style="background-color: color-mix(in srgb, {{ $jobColor }} 8%, white); color: {{ $jobColor }}; border-color: color-mix(in srgb, {{ $jobColor }} 25%, white);">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        {{ $job->department->name }}
                                    </span>
                                @endif
                                @if($job->location)
                                    <span class="inline-flex items-center gap-1 bg-slate-50 text-slate-600 px-3 py-1.5 rounded-lg border border-slate-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        {{ $job->location->name }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center gap-1 bg-slate-50 text-slate-400 px-3 py-1.5 rounded-lg border border-slate-200 font-mono">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $job->created_at->locale('ar')->diffForHumans(['parts' => 1]) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="prose prose-sm text-slate-500 mb-6 flex-grow leading-relaxed line-clamp-3 text-xs sm:text-sm">
                            {!! strip_tags($job->description) !!}
                        </div>
                        
                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-bold" style="color: {{ $jobColor }};">
                                متاح التقديم الآن
                            </span>
                            <a href="{{ route('jobs.apply', $job->id) }}" 
                               class="inline-flex items-center gap-2 text-white font-bold py-2.5 px-5 rounded-xl transition-all duration-200 shadow hover:shadow-lg cursor-pointer"
                               style="background: linear-gradient(135deg, {{ $jobColor }}, color-mix(in srgb, {{ $jobColor }} 85%, black));"
                               onmouseover="this.style.opacity='0.9'"
                               onmouseout="this.style.opacity='1'">
                                <span>التقديم على الوظيفة</span>
                                <svg class="w-4 h-4 rtl:rotate-180 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <footer class="text-center mt-12 text-xs text-slate-400">
            جميع الحقوق محفوظة &copy; {{ date('Y') }} - {{ $brandName }}
        </footer>
    </div>
</body>
</html>
