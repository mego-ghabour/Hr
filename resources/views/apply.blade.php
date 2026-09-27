<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استمارة الانضمام - {{ \App\Models\Setting::get('brand_name', 'نظام إدارة المرشحين') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen text-gray-800 antialiased py-10 px-4 sm:px-6 lg:px-8">

    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        
        <!-- Header -->
        <div class="bg-indigo-900 text-white p-8 text-center relative overflow-hidden">
            <div class="relative z-10 flex flex-col items-center justify-center">
                @php
                    $logo = \App\Models\Setting::get('logo');
                @endphp
                @if($logo)
                    <img src="{{ str_starts_with($logo, 'http') || str_starts_with($logo, '/') ? $logo : \Illuminate\Support\Facades\Storage::disk('public')->url($logo) }}" alt="Logo" class="h-16 mb-4 object-contain">
                @endif
                <h1 class="text-3xl font-bold tracking-tight">{{ \App\Models\Setting::get('brand_name', 'نظام إدارة المرشحين') }}</h1>
                <p class="text-indigo-200 mt-2 text-sm sm:text-base">استمارة التقديم على الوظائف وتحديث البيانات</p>
            </div>
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-800 rounded-full opacity-50 blur-xl"></div>
            <div class="absolute -left-10 -top-10 w-40 h-40 bg-indigo-700 rounded-full opacity-40 blur-xl"></div>
        </div>

        <!-- Form Body -->
        <div class="p-6 sm:p-10">

            <!-- Success Alert -->
            @if(session('success'))
                <div class="mb-8 p-5 bg-emerald-50 border-r-4 border-emerald-500 rounded-xl text-emerald-800 flex items-start gap-3 shadow-sm">
                    <svg class="w-6 h-6 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-bold text-lg">تم إرسال طلبك بنجاح!</h4>
                        <p class="text-sm text-emerald-700 mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Errors Alert -->
            @if ($errors->any())
                <div class="mb-8 p-5 bg-rose-50 border-r-4 border-rose-500 rounded-xl text-rose-800 shadow-sm">
                    <h4 class="font-bold text-base mb-2">يرجى تصحيح الأخطاء التالية للتقديم:</h4>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('apply.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                @csrf

                <!-- Section 1: البيانات الشخصية -->
                <div>
                    <div class="flex items-center gap-2 mb-6 pb-2 border-b border-gray-100 text-indigo-900">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h2 class="text-xl font-bold">1. البيانات الشخصية</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- الاسم الكامل -->
                        <div>
                            <label for="full_name" class="block text-sm font-semibold text-gray-700 mb-2">الاسم الكامل <span class="text-rose-500">*</span></label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                placeholder="مثال: أحمد محمد علي">
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                placeholder="ahmed@example.com">
                        </div>

                        <!-- رقم الموبايل -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">رقم الموبايل <span class="text-rose-500">*</span></label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors dir-ltr text-right"
                                placeholder="+20 1xx xxxx xxxx">
                        </div>

                        <!-- رابط LinkedIn -->
                        <div>
                            <label for="linkedin_url" class="block text-sm font-semibold text-gray-700 mb-2">رابط حساب لينكد إن (LinkedIn)</label>
                            <input type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url') }}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors dir-ltr text-right"
                                placeholder="https://linkedin.com/in/username">
                        </div>

                        <!-- معرض الأعمال / البورتفوليو -->
                        <div class="md:col-span-2">
                            <label for="portfolio_url" class="block text-sm font-semibold text-gray-700 mb-2">رابط معرض الأعمال / الموقع الشخصي (Portfolio)</label>
                            <input type="url" name="portfolio_url" id="portfolio_url" value="{{ old('portfolio_url') }}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors dir-ltr text-right"
                                placeholder="https://myportfolio.com">
                        </div>
                    </div>
                </div>

                <!-- Section 2: التفاصيل المهنية والخبرة -->
                <div>
                    <div class="flex items-center gap-2 mb-6 pb-2 border-b border-gray-100 text-indigo-900">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <h2 class="text-xl font-bold">2. الخبرة والتفاصيل المهنية</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- المسمى الوظيفي الحالي -->
                        <div>
                            <label for="current_job_title" class="block text-sm font-semibold text-gray-700 mb-2">المسمى الوظيفي الحالي / الأخير</label>
                            <input type="text" name="current_job_title" id="current_job_title" value="{{ old('current_job_title') }}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                placeholder="مثال: Senior Software Engineer">
                        </div>

                        <!-- الشركة الحالية -->
                        <div>
                            <label for="current_company" class="block text-sm font-semibold text-gray-700 mb-2">الشركة الحالية / الأخيرة</label>
                            <input type="text" name="current_company" id="current_company" value="{{ old('current_company') }}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                placeholder="اسم الشركة">
                        </div>

                        <!-- سنوات الخبرة -->
                        <div>
                            <label for="years_of_experience" class="block text-sm font-semibold text-gray-700 mb-2">سنوات الخبرة الإجمالية</label>
                            <input type="number" step="0.5" name="years_of_experience" id="years_of_experience" value="{{ old('years_of_experience') }}" min="0" max="50"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                placeholder="مثال: 4">
                        </div>

                        <!-- الراتب المتوقع -->
                        <div>
                            <label for="expected_salary" class="block text-sm font-semibold text-gray-700 mb-2">الراتب المتوقع (بالجنيه/الشهري)</label>
                            <input type="number" name="expected_salary" id="expected_salary" value="{{ old('expected_salary') }}" min="0"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                placeholder="مثال: 25000">
                        </div>

                        <!-- مستوى الخبرة -->
                        <div>
                            <label for="seniority_level" class="block text-sm font-semibold text-gray-700 mb-2">مستوى الخبرة (Seniority Level)</label>
                            <select name="seniority_level" id="seniority_level"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white">
                                <option value="">-- اختر المستوى --</option>
                                <option value="intern" {{ old('seniority_level') == 'intern' ? 'selected' : '' }}>متدرب (Intern)</option>
                                <option value="junior" {{ old('seniority_level') == 'junior' ? 'selected' : '' }}>مبتدئ (Junior)</option>
                                <option value="mid" {{ old('seniority_level') == 'mid' ? 'selected' : '' }}>متوسط (Mid-Level)</option>
                                <option value="senior" {{ old('seniority_level') == 'senior' ? 'selected' : '' }}>سينيور (Senior)</option>
                                <option value="lead" {{ old('seniority_level') == 'lead' ? 'selected' : '' }}>قائد فريق (Team Lead)</option>
                                <option value="manager" {{ old('seniority_level') == 'manager' ? 'selected' : '' }}>مدير (Manager)</option>
                                <option value="director" {{ old('seniority_level') == 'director' ? 'selected' : '' }}>مدير تنفيذي (Director)</option>
                                <option value="c_level" {{ old('seniority_level') == 'c_level' ? 'selected' : '' }}>C-Level</option>
                            </select>
                        </div>

                        <!-- نظام العمل المفضل -->
                        <div>
                            <label for="work_preference" class="block text-sm font-semibold text-gray-700 mb-2">نظام العمل المفضل</label>
                            <select name="work_preference" id="work_preference"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white">
                                <option value="">-- اختر نظام العمل --</option>
                                <option value="remote" {{ old('work_preference') == 'remote' ? 'selected' : '' }}>عن بُعد (Remote)</option>
                                <option value="onsite" {{ old('work_preference') == 'onsite' ? 'selected' : '' }}>في المقر (On-site)</option>
                                <option value="hybrid" {{ old('work_preference') == 'hybrid' ? 'selected' : '' }}>هجين (Hybrid)</option>
                                <option value="flexible" {{ old('work_preference') == 'flexible' ? 'selected' : '' }}>مرن (Flexible)</option>
                            </select>
                        </div>

                        <!-- الإتاحة للبدء -->
                        <div class="md:col-span-2">
                            <label for="availability" class="block text-sm font-semibold text-gray-700 mb-2">الإتاحة للبدء في العمل</label>
                            <select name="availability" id="availability"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white">
                                <option value="">-- اختر موعد الإتاحة --</option>
                                <option value="immediately" {{ old('availability') == 'immediately' ? 'selected' : '' }}>متاح فوراً (Immediately)</option>
                                <option value="two_weeks" {{ old('availability') == 'two_weeks' ? 'selected' : '' }}>بعد أسبوعين (2 Weeks)</option>
                                <option value="one_month" {{ old('availability') == 'one_month' ? 'selected' : '' }}>بعد شهر (1 Month)</option>
                                <option value="two_months" {{ old('availability') == 'two_months' ? 'selected' : '' }}>بعد شهرين (2 Months)</option>
                                <option value="not_available" {{ old('availability') == 'not_available' ? 'selected' : '' }}>غير متاح حالياً (Not Available)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 3: القسم، الموقع والسيرة الذاتية -->
                <div>
                    <div class="flex items-center gap-2 mb-6 pb-2 border-b border-gray-100 text-indigo-900">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <h2 class="text-xl font-bold">3. التخصيص والمستندات</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- القسم المطلوب -->
                        <div>
                            <label for="department_id" class="block text-sm font-semibold text-gray-700 mb-2">القسم / المجال المطلوب</label>
                            <select name="department_id" id="department_id"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white">
                                <option value="">-- اختر القسم --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- المدينة / الموقع -->
                        <div>
                            <label for="location_id" class="block text-sm font-semibold text-gray-700 mb-2">المدينة / الموقع الجغرافي</label>
                            <select name="location_id" id="location_id"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white">
                                <option value="">-- اختر المدينة --</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- رفع السيرة الذاتية -->
                        <div class="md:col-span-2">
                            <label for="resume" class="block text-sm font-semibold text-gray-700 mb-2">رفع السيرة الذاتية (CV) <span class="text-rose-500">*</span></label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-indigo-500 transition-colors bg-gray-50/50">
                                <input type="file" name="resume" id="resume" required accept=".pdf,.doc,.docx" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors cursor-pointer">
                                <p class="text-xs text-gray-500 mt-2">الملفات المسموح بها: PDF, DOC, DOCX (الحجم الأقصى: 10MB)</p>
                            </div>
                        </div>

                        <!-- ملاحظات إضافية / نبذة -->
                        <div class="md:col-span-2">
                            <label for="general_notes" class="block text-sm font-semibold text-gray-700 mb-2">نبذة مختصرة عنك أو ملاحظات إضافية</label>
                            <textarea name="general_notes" id="general_notes" rows="4"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                placeholder="اكتب هنا أي معلومات ترغب في إضافتها لحسابك...">{{ old('general_notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-indigo-900 hover:bg-indigo-800 text-white font-bold rounded-xl shadow-lg hover:shadow-indigo-900/30 transition-all text-base flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                        إرسال الطلب وحفظ البيانات
                    </button>
                </div>
            </form>

        </div>
    </div>

</body>
</html>
