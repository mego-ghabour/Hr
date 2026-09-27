<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الوظائف المتاحة - {{ \App\Models\Setting::get('brand_name', 'نظام إدارة الموارد البشرية') }}</title>
    <!-- Social Media Sharing Meta Tags (Open Graph) -->
    <meta property="og:title" content="الوظائف المتاحة - {{ \App\Models\Setting::get('brand_name', 'انضم لفريقنا') }}">
    <meta property="og:description" content="تصفح أحدث الوظائف الشاغرة لدينا وقدم سيرتك الذاتية الآن للانضمام إلى فريق العمل.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if(\App\Models\Setting::get('logo'))
    <meta property="og:image" content="{{ Storage::disk('public')->url(\App\Models\Setting::get('logo')) }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen text-gray-800 antialiased py-10 px-4 sm:px-6 lg:px-8">

    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="bg-indigo-900 text-white rounded-2xl shadow-xl p-8 mb-8 text-center relative overflow-hidden border border-gray-100">
            <div class="relative z-10 flex flex-col items-center justify-center">
                @php
                    $logo = \App\Models\Setting::get('logo');
                @endphp
                @if($logo)
                    <img src="{{ str_starts_with($logo, 'http') || str_starts_with($logo, '/') ? $logo : \Illuminate\Support\Facades\Storage::disk('public')->url($logo) }}" alt="Logo" class="h-16 mb-4 object-contain">
                @endif
                <h1 class="text-4xl font-bold tracking-tight mb-2">الوظائف المتاحة</h1>
                <p class="text-indigo-200 text-lg">انضم إلى فريقنا واكتشف فرص العمل المناسبة لك</p>
            </div>
        </div>

        <!-- Filters Section -->
        <form method="GET" action="{{ route('jobs.list') }}" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-8 flex flex-col md:flex-row gap-4 items-end relative overflow-visible">
            <!-- Search -->
            <div class="w-full md:w-2/5">
                <label class="block text-sm font-bold text-gray-700 mb-2">البحث عن وظيفة</label>
                <div class="relative">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full rounded-xl border border-gray-200 pl-4 pr-10 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none bg-gray-50 transition-all" placeholder="المسمى الوظيفي، الكلمات المفتاحية...">
                </div>
            </div>
            
            <!-- Department -->
            <div class="w-full md:w-1/4">
                <label class="block text-sm font-bold text-gray-700 mb-2">القسم</label>
                <div class="relative">
                    <select name="department_id" class="block w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none bg-gray-50 appearance-none transition-all cursor-pointer">
                        <option value="">جميع الأقسام</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="w-full md:w-1/4">
                <label class="block text-sm font-bold text-gray-700 mb-2">الموقع</label>
                <div class="relative">
                    <select name="location_id" class="block w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none bg-gray-50 appearance-none transition-all cursor-pointer">
                        <option value="">جميع المواقع</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-md hover:shadow-lg flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    تصفية
                </button>
            </div>
        </form>

        @if($jobs->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
                <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="text-xl font-bold text-gray-700 mb-2">لا توجد وظائف متاحة حالياً</h3>
                <p class="text-gray-500">يرجى التحقق لاحقاً لرؤية الشواغر الجديدة.</p>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2">
                @foreach($jobs as $job)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex flex-col h-full hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group overflow-hidden">
                        
                        <!-- Decorative top accent -->
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 to-purple-500 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                        
                        <div class="mb-6">
                            <h2 class="text-2xl font-extrabold text-gray-900 mb-4 group-hover:text-indigo-700 transition-colors">{{ $job->title }}</h2>
                            <div class="flex flex-wrap gap-3 text-sm font-medium text-gray-600">
                                @if($job->department)
                                    <span class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-lg border border-indigo-100">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        {{ $job->department->name }}
                                    </span>
                                @endif
                                @if($job->location)
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-lg border border-emerald-100">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        {{ $job->location->name }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center gap-1.5 bg-gray-50 text-gray-700 px-3 py-1.5 rounded-lg border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $job->created_at->locale('ar')->diffForHumans(['parts' => 1]) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="prose prose-sm text-gray-500 mb-8 flex-grow leading-relaxed line-clamp-3">
                            {!! strip_tags($job->description) !!}
                        </div>
                        
                        <div class="mt-auto">
                            <a href="{{ route('jobs.apply', $job->id) }}" class="inline-flex justify-between items-center w-full bg-gray-900 hover:bg-indigo-600 text-white font-bold py-3.5 px-6 rounded-xl transition-all duration-300 shadow-md hover:shadow-xl group/btn">
                                <span>التقديم على الوظيفة</span>
                                <div class="bg-white/20 p-1.5 rounded-lg group-hover/btn:-translate-x-1 transition-transform rtl:group-hover/btn:translate-x-1">
                                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
