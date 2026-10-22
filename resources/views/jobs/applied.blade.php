<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تم استلام طلب التقديم بنجاح | {{ $job->title }} - {{ \App\Models\Setting::get('brand_name', 'نظام إدارة الموارد البشرية') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Cairo', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '{{ $job->brand_color ?: \App\Models\Setting::primaryColor() }}',
                            600: '{{ $job->brand_color ?: \App\Models\Setting::primaryColor() }}',
                            700: '#0f766e',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Cairo', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .print-card { box-shadow: none !important; border: 1px solid #cbd5e1 !important; }
        }
    </style>
</head>
<body class="min-h-full flex flex-col justify-between text-slate-800 antialiased selection:bg-teal-500 selection:text-white">

    <!-- Top Header -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 no-print">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @php $logo = \App\Models\Setting::get('logo'); @endphp
                @if($logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($logo))
                    <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="h-9 w-auto object-contain">
                @else
                    <div class="h-9 w-9 rounded-xl bg-teal-600 flex items-center justify-center text-white font-black text-base shadow-sm">
                        {{ mb_substr(\App\Models\Setting::get('brand_name', 'HR'), 0, 1) }}
                    </div>
                @endif
                <div>
                    <span class="block text-sm font-black text-slate-900 leading-tight">{{ \App\Models\Setting::get('brand_name', 'نظام إدارة الموارد البشرية') }}</span>
                    <span class="block text-[11px] font-semibold text-slate-500">بوابة التوظيف واستقطاب الكفاءات</span>
                </div>
            </div>

            <a href="{{ route('jobs.index') }}" class="text-xs font-bold text-slate-600 hover:text-teal-600 transition-colors flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 px-3.5 py-1.5 rounded-lg">
                <svg class="w-4 h-4 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                <span>الفرص الوظيفية</span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 py-10 flex-grow w-full">
        
        <!-- Success Celebration Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden print-card">
            
            <!-- Top Banner -->
            <div class="bg-gradient-to-r from-teal-600 to-emerald-600 p-8 text-center text-white relative">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white text-emerald-600 mb-4 shadow-xl ring-8 ring-white/20 animate-bounce">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black mb-2 tracking-tight">تم استلام طلب تقديمك بنجاح!</h1>
                <p class="text-teal-50 text-sm max-w-lg mx-auto font-medium leading-relaxed">
                    {{ session('success') ?: ($job->success_message ?: 'شكراً لاهتمامك بالانضمام إلى فريق عملنا، تم قيد بياناتك بنجاح وسيتواصل معك مسؤولو التوظيف قريباً.') }}
                </p>
            </div>

            <!-- Application Details Grid -->
            <div class="p-6 sm:p-8">
                
                <!-- Reference Badge -->
                <div class="bg-teal-50 border border-teal-200 rounded-2xl p-4 sm:p-5 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-right">
                    <div>
                        <span class="block text-xs font-bold text-teal-700 uppercase tracking-wider mb-1">الرقم المرجعي لطلب التوظيف</span>
                        <span class="font-mono text-2xl font-black text-teal-950 tracking-wider">
                            {{ $talent?->talent_id ?? ('APP-' . $job->id . '-' . rand(1000, 9999)) }}
                        </span>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-800">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        قيد الفرز والمراجعة الأولية
                    </span>
                </div>

                <!-- Info Table -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-8 bg-slate-50/80 p-5 rounded-2xl border border-slate-200/70">
                    <div>
                        <span class="text-xs text-slate-400 font-bold block mb-1">الوظيفة المتقدم لها</span>
                        <strong class="text-slate-900 font-extrabold text-base">{{ $job->title }}</strong>
                    </div>

                    @if($job->department)
                    <div>
                        <span class="text-xs text-slate-400 font-bold block mb-1">القسم / الإدارة</span>
                        <strong class="text-slate-800 font-bold">{{ $job->department->name }}</strong>
                    </div>
                    @endif

                    @if($talent?->full_name)
                    <div>
                        <span class="text-xs text-slate-400 font-bold block mb-1">اسم المتقدم</span>
                        <strong class="text-slate-800 font-bold">{{ $talent->full_name }}</strong>
                    </div>
                    @endif

                    @if($talent?->email)
                    <div>
                        <span class="text-xs text-slate-400 font-bold block mb-1">البريد الإلكتروني المسجل</span>
                        <span class="text-slate-700 font-mono text-xs">{{ $talent->email }}</span>
                    </div>
                    @endif

                    @if($talent?->phone)
                    <div>
                        <span class="text-xs text-slate-400 font-bold block mb-1">رقم الهاتف</span>
                        <span class="text-slate-700 font-mono text-xs" dir="ltr">{{ $talent->phone }}</span>
                    </div>
                    @endif

                    <div>
                        <span class="text-xs text-slate-400 font-bold block mb-1">تاريخ وساعة التقديم</span>
                        <span class="text-slate-700 text-xs font-semibold">{{ now()->format('Y-m-d - h:i A') }}</span>
                    </div>
                </div>

                <!-- Recruitment Process Roadmap -->
                <div class="mb-8">
                    <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider mb-4">المراحل القادمة للطلب</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
                        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-emerald-600 text-white font-black text-[11px] mb-2">✓</span>
                            <strong class="block text-emerald-900 font-bold mb-0.5">1. استلام الطلب</strong>
                            <span class="text-[11px] text-emerald-700 leading-tight block">تم حفظ سيرتك الذاتية وتأكيد الاستلام.</span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-slate-200 text-slate-600 font-black text-[11px] mb-2">2</span>
                            <strong class="block text-slate-800 font-bold mb-0.5">2. الفرز والمطابقة</strong>
                            <span class="text-[11px] text-slate-500 leading-tight block">مراجعة المؤهلات ومطابقتها مع المعايير.</span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-slate-200 text-slate-600 font-black text-[11px] mb-2">3</span>
                            <strong class="block text-slate-800 font-bold mb-0.5">3. المقابلة الشخصية</strong>
                            <span class="text-[11px] text-slate-500 leading-tight block">التواصل لتحديد موعد مقابلة فنية وشخصية.</span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-slate-200 text-slate-600 font-black text-[11px] mb-2">4</span>
                            <strong class="block text-slate-800 font-bold mb-0.5">4. العرض الوظيفي</strong>
                            <span class="text-[11px] text-slate-500 leading-tight block">تقديم العرض الرسمي وإتمام إجراءات التعيين.</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-6 border-t border-slate-100 no-print">
                    <button type="button" onclick="window.print()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 text-xs font-bold rounded-xl text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>طباعة / حفظ إيصال التقديم</span>
                    </button>

                    <a href="{{ route('jobs.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3 text-xs font-black rounded-xl text-white bg-teal-600 hover:bg-teal-700 transition-colors shadow-lg shadow-teal-600/20">
                        <span>تصفح الفرص الوظيفية الأخرى</span>
                        <svg class="w-4 h-4 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-xs text-slate-400 no-print">
        <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('brand_name', 'نظام إدارة الموارد البشرية') }} — جميع الحقوق محفوظة</p>
    </footer>

</body>
</html>
