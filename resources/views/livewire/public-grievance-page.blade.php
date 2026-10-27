<div class="min-h-screen bg-slate-50 py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">

        <!-- Top Header / Brand Identity -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center mb-4">
                @if($brandLogo)
                    <img src="{{ asset('storage/' . $brandLogo) }}" alt="{{ $brandName }}" class="h-14 sm:h-16 w-auto object-contain rounded-xl p-1.5 bg-white shadow-sm border border-slate-200">
                @else
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-600 to-emerald-700 flex items-center justify-center text-white shadow-md shadow-teal-700/20">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                @endif
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                صندوق الشكاوى والمقترحات الرقمي
            </h1>
            <p class="text-sm sm:text-base text-slate-600 mt-2 font-medium">
                {{ $brandName }} — منصة آمنة، سرية ومحايدة لضمان بيئة عمل عادلة وتطوير مستمر
            </p>

            <!-- Trust Shield Banner -->
            <div class="mt-4 inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm px-4 py-1.5 rounded-full font-semibold shadow-xs">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>خصوصيتك وحيادية التحقيق مضمونة بنسبة 100% وفق أعلى معايير الحوكمة</span>
            </div>
        </div>

        <!-- Mode Navigation Tabs -->
        @if($viewMode !== 'success')
            <div class="flex items-center justify-center p-1 bg-slate-200/80 rounded-2xl mb-8 max-w-md mx-auto">
                <button type="button"
                        wire:click="switchMode('submit')"
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold transition-all {{ $viewMode === 'submit' ? 'bg-white text-teal-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>تقديم جديد</span>
                </button>

                <button type="button"
                        wire:click="switchMode('track')"
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold transition-all {{ $viewMode === 'track' ? 'bg-white text-teal-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>متابعة تذكرة سابقة</span>
                </button>
            </div>
        @endif

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- MODE 1: SUBMIT NEW COMPLAINT / SUGGESTION                        --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($viewMode === 'submit')
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <form wire:submit="submit" class="p-6 sm:p-8 space-y-6">

                    <!-- Step 1: Category Selection Cards -->
                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-3">
                            1. تصنيف الموضوع <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <!-- Category 1: Administrative -->
                            <button type="button"
                                    wire:click="$set('type', 'administrative')"
                                    class="p-3.5 rounded-2xl border text-right transition-all flex flex-col justify-between {{ $type === 'administrative' ? 'border-teal-600 bg-teal-50/60 ring-2 ring-teal-500/20' : 'border-slate-200 hover:border-slate-300 bg-white' }}">
                                <div class="w-8 h-8 rounded-xl {{ $type === 'administrative' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center mb-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">إدارية أو مالية</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">رواتب، خصومات، مستحقات، إجراءات</p>
                                </div>
                            </button>

                            <!-- Category 2: Workplace -->
                            <button type="button"
                                    wire:click="$set('type', 'workplace')"
                                    class="p-3.5 rounded-2xl border text-right transition-all flex flex-col justify-between {{ $type === 'workplace' ? 'border-teal-600 bg-teal-50/60 ring-2 ring-teal-500/20' : 'border-slate-200 hover:border-slate-300 bg-white' }}">
                                <div class="w-8 h-8 rounded-xl {{ $type === 'workplace' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center mb-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">بيئة العمل والأدوات</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">أجهزة، مكاتب، مرافق، نظافة، أمان</p>
                                </div>
                            </button>

                            <!-- Category 3: Behavioral -->
                            <button type="button"
                                    wire:click="$set('type', 'behavioral')"
                                    class="p-3.5 rounded-2xl border text-right transition-all flex flex-col justify-between {{ $type === 'behavioral' ? 'border-teal-600 bg-teal-50/60 ring-2 ring-teal-500/20' : 'border-slate-200 hover:border-slate-300 bg-white' }}">
                                <div class="w-8 h-8 rounded-xl {{ $type === 'behavioral' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center mb-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">سلوكية أو نزاع مهني</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">معاملة، سوء تفاهم، بيئة الفريق</p>
                                </div>
                            </button>

                            <!-- Category 4: Suggestion -->
                            <button type="button"
                                    wire:click="$set('type', 'suggestion')"
                                    class="p-3.5 rounded-2xl border text-right transition-all flex flex-col justify-between {{ $type === 'suggestion' ? 'border-teal-600 bg-teal-50/60 ring-2 ring-teal-500/20' : 'border-slate-200 hover:border-slate-300 bg-white' }}">
                                <div class="w-8 h-8 rounded-xl {{ $type === 'suggestion' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center mb-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">فكرة أو مقترح تطويري</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">مبادرة، تحسين مسار، زيادة كفاءة</p>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Confidentiality / Anonymous Mode Toggle -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <label class="block text-sm font-bold text-slate-800 mb-2.5">
                            2. وضع الخصوصية وهوية المُرسِل <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="relative flex items-center p-3.5 rounded-xl border cursor-pointer transition-all {{ $is_anonymous ? 'border-emerald-600 bg-emerald-50/50 ring-2 ring-emerald-500/20' : 'border-slate-200 bg-white' }}">
                                <input type="radio" wire:model.live="is_anonymous" value="1" class="sr-only">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                            <span>سري ومجهول الهوية 🔒</span>
                                            <span class="text-[10px] bg-emerald-200/60 text-emerald-800 px-1.5 py-0.2 rounded font-extrabold">مُوصى به</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5">لن يتم كشف أو حفظ أي بيانات تشير إليك، وتحصل على كود تذكرة خاص لمتابعة الرد.</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center p-3.5 rounded-xl border cursor-pointer transition-all {{ ! $is_anonymous ? 'border-teal-600 bg-teal-50/50 ring-2 ring-teal-500/20' : 'border-slate-200 bg-white' }}">
                                <input type="radio" wire:model.live="is_anonymous" value="0" class="sr-only">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-900">تقديم بالهوية الشخصية 👤</div>
                                        <p class="text-[11px] text-slate-500 mt-0.5">يُتيح لفريق الموارد البشرية التواصل المباشر معك لحل الموضوع وسماع التفاصيل.</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Submitter Contact Inputs (when NOT anonymous) -->
                        @if(! $is_anonymous)
                            <div class="mt-4 pt-4 border-t border-slate-200 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">الاسم الكريم <span class="text-rose-500">*</span></label>
                                    <input type="text"
                                           wire:model="submitter_name"
                                           placeholder="اسمك الكامل أو كودك الوظيفي"
                                           class="w-full text-xs rounded-xl border-slate-300 focus:border-teal-500 focus:ring-teal-500 bg-white p-2.5 shadow-2xs">
                                    @error('submitter_name') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">رقم الهاتف أو البريد (اختياري)</label>
                                    <input type="text"
                                           wire:model="submitter_contact"
                                           placeholder="للتواصل معك وموافاتك بالمستجدات"
                                           class="w-full text-xs rounded-xl border-slate-300 focus:border-teal-500 focus:ring-teal-500 bg-white p-2.5 shadow-2xs">
                                    @error('submitter_contact') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Step 3: Priority Chips -->
                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-2">
                            3. درجة الأهمية / الاستعجال <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $priorities = [
                                    'low' => ['label' => 'منخفضة (استفسار عادي)', 'color' => 'slate'],
                                    'normal' => ['label' => 'عادية (طبيعية)', 'color' => 'blue'],
                                    'high' => ['label' => 'عالية (تؤثر على العمل)', 'color' => 'amber'],
                                    'urgent' => ['label' => 'عاجلة جداً (أمر طارئ/حرج)', 'color' => 'rose'],
                                ];
                            @endphp
                            @foreach($priorities as $pk => $pData)
                                <button type="button"
                                        wire:click="$set('priority', '{{ $pk }}')"
                                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all border {{ $priority === $pk ? 'bg-slate-900 text-white border-slate-900 shadow-xs' : 'bg-slate-100 text-slate-700 border-transparent hover:bg-slate-200' }}">
                                    {{ $pData['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 4: Subject Title -->
                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-1.5">
                            4. موضوع أو عنوان الشكوى / المقترح <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="title"
                               placeholder="اكتب عنواناً موجزاً يوضح فحوى الموضوع..."
                               class="w-full text-sm rounded-xl border-slate-300 focus:border-teal-500 focus:ring-teal-500 p-3 shadow-2xs">
                        @error('title') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Department & Manager Direct Bypass -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if($departments->count() > 0)
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">القسم المعني (اختياري)</label>
                                <select wire:model="department_id" class="w-full text-xs rounded-xl border-slate-300 focus:border-teal-500 focus:ring-teal-500 bg-white p-2.5">
                                    <option value="">— غير محدد / يخص الإدارة العامة —</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="flex items-center pt-2 sm:pt-6">
                            <label class="inline-flex items-start gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="against_manager" class="mt-0.5 rounded text-rose-600 focus:ring-rose-500 border-slate-300 w-4 h-4">
                                <span class="text-xs font-bold text-rose-900">
                                    رفع مباشر للإدارة العليا والتنفيذية (تخطي الإدارة المباشرة للنزاهة)
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Step 5: Full Content -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-sm font-bold text-slate-800">
                                5. التفاصيل والوقائع الكاملة <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-slate-400">كن واضحاً ومحدداً للمساعدة في سرعة المعالجة</span>
                        </div>
                        <textarea wire:model="content"
                                  rows="5"
                                  placeholder="يرجى ذكر الوقائع والتواريخ وأي تفاصيل داعمة للموضوع بوضوح وأمانة..."
                                  class="w-full text-sm rounded-xl border-slate-300 focus:border-teal-500 focus:ring-teal-500 p-3 shadow-2xs"></textarea>
                        @error('content') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Step 6: Attachment Upload -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            مستند أو صورة داعمة (اختياري)
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="file"
                                   wire:model="attachment"
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                   class="text-xs text-slate-500 file:mr-0 file:ml-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer">
                            <span wire:loading wire:target="attachment" class="text-xs text-teal-600 font-semibold animate-pulse">
                                جارٍ رفع الملف...
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">الملفات المدعومة: PDF, Word, صور بحد أقصى 5 ميجابايت.</p>
                        @error('attachment') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-slate-100">
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-extrabold text-sm sm:text-base shadow-md shadow-teal-600/20 transition-all cursor-pointer disabled:opacity-50">
                            <svg wire:loading.remove wire:target="submit" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <svg wire:loading wire:target="submit" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="submit">إرسال وتوثيق الشكوى رسمياً</span>
                            <span wire:loading wire:target="submit">جارٍ التوثيق وتوليد التذكرة...</span>
                        </button>
                    </div>

                </form>
            </div>
        @endif

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- MODE 2: SUBMISSION SUCCESS SCREEN                                --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($viewMode === 'success')
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-10 text-center">
                <!-- Checkmark Icon -->
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-emerald-100 text-emerald-600 rounded-full mx-auto flex items-center justify-center mb-5 ring-8 ring-emerald-50">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                    تم استلام وتوثيق طلبك بنجاح!
                </h2>
                <p class="text-sm text-slate-600 mt-2 max-w-md mx-auto leading-relaxed">
                    شكراً لثقتك وحرصك على بيئة العمل. تم تسجيل الموضوع في النظام الداخلي وإحالته للجهة المختصة للتحقيق والمتابعة.
                </p>

                <!-- Ticket Code Card -->
                <div class="my-6 p-5 sm:p-6 bg-slate-50 border-2 border-dashed border-teal-500/40 rounded-2xl max-w-md mx-auto">
                    <span class="block text-xs font-bold text-slate-500 mb-1">رقم تذكرة المتابعة السرية الخاصة بك:</span>
                    <div class="text-2xl sm:text-3xl font-mono font-black text-teal-700 tracking-wider my-2" id="ticketNumberDisplay">
                        {{ $submittedTicketNumber }}
                    </div>
                    <p class="text-xs text-amber-700 bg-amber-50 rounded-lg p-2 font-medium">
                        ⚠️ احتفظ بهذا الرقم في مكان آمن، فهو وسيلتك الوحيدة لمتابعة قرار الرد والتحقيق لاحقاً.
                    </p>

                    <!-- Copy Button -->
                    <div class="mt-4 flex justify-center">
                        <button type="button"
                                onclick="navigator.clipboard.writeText('{{ $submittedTicketNumber }}'); alert('تم نسخ رقم التذكرة بنجاح: {{ $submittedTicketNumber }}');"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-100 text-slate-800 text-xs font-extrabold rounded-xl border border-slate-300 shadow-2xs transition-all cursor-pointer">
                            <svg class="w-4 h-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            <span>نسخ رقم التذكرة</span>
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 max-w-md mx-auto">
                    <button type="button"
                            wire:click="trackSubmitted"
                            class="w-full sm:w-auto flex-1 py-3 px-5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs sm:text-sm shadow-sm transition-all cursor-pointer">
                        متابعة حالة التذكرة الآن ↗
                    </button>

                    <button type="button"
                            wire:click="resetForm"
                            class="w-full sm:w-auto py-3 px-5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm transition-all cursor-pointer">
                        تقديم موضوع آخر
                    </button>
                </div>
            </div>
        @endif

        {{-- ════════════════════════════════════════════════════════════════ --}}
        {{-- MODE 3: TRACK EXISTING TICKET                                    --}}
        {{-- ════════════════════════════════════════════════════════════════ --}}
        @if($viewMode === 'track')
            <div class="space-y-6">
                <!-- Search Box Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8">
                    <h3 class="text-base sm:text-lg font-extrabold text-slate-900 mb-2">
                        استعلام ومتابعة حالة التذكرة
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 mb-5">
                        أدخل رقم التذكرة المستلم عند تقديم الشكوى (مثال: GRV-2610-0001) للاطلاع على المستجدات والرد الإداري:
                    </p>

                    <form wire:submit="lookupTicket" class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                            </div>
                            <input type="text"
                                   wire:model="searchTicket"
                                   placeholder="GRV-2610-0001"
                                   class="w-full text-sm font-mono uppercase pr-11 pl-4 py-3 rounded-xl border-slate-300 focus:border-teal-500 focus:ring-teal-500 shadow-2xs">
                        </div>

                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="py-3 px-6 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs sm:text-sm shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50">
                            <svg wire:loading.remove wire:target="lookupTicket" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span wire:loading.remove wire:target="lookupTicket">بحث واستعلام</span>
                            <span wire:loading wire:target="lookupTicket">جارٍ البحث...</span>
                        </button>
                    </form>

                    <!-- Error Alert -->
                    @if($trackError)
                        <div class="mt-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-medium flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $trackError }}</span>
                        </div>
                    @endif
                </div>

                <!-- Search Results Card -->
                @if($trackedGrievance)
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                        <!-- Result Header -->
                        <div class="p-6 sm:p-7 border-b border-slate-100 bg-slate-50/50">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-mono font-bold text-teal-700 bg-teal-100/70 px-2.5 py-1 rounded-lg">
                                            {{ $trackedGrievance->ticket_number }}
                                        </span>
                                        <span class="text-xs text-slate-500 font-medium">
                                            {{ $trackedGrievance->created_at->format('Y-m-d H:i') }}
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-black text-slate-900 mt-2">
                                        {{ $trackedGrievance->title }}
                                    </h3>
                                </div>

                                <!-- Status Badge -->
                                <div>
                                    @php
                                        $statusStyles = [
                                            'new' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'resolved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'rejected' => 'bg-rose-100 text-rose-800 border-rose-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold border {{ $statusStyles[$trackedGrievance->status] ?? 'bg-slate-100 text-slate-800 border-slate-200' }}">
                                        <span class="w-2 h-2 rounded-full {{ $trackedGrievance->status === 'resolved' ? 'bg-emerald-500' : ($trackedGrievance->status === 'in_progress' ? 'bg-blue-500' : 'bg-amber-500') }}"></span>
                                        <span>{{ $trackedGrievance->status_label }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- 3-Step Visual Progress Tracker -->
                        <div class="p-6 sm:p-7 bg-slate-50 border-b border-slate-100">
                            <span class="block text-xs font-bold text-slate-600 mb-4">مسار معالجة التذكرة:</span>
                            <div class="grid grid-cols-3 gap-2 text-center">
                                <!-- Step 1: Received -->
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                        ✓
                                    </div>
                                    <span class="text-xs font-bold text-slate-800 mt-1.5">تم الاستلام والتوثيق</span>
                                    <span class="text-[10px] text-slate-400">مكتمل</span>
                                </div>

                                <!-- Step 2: Under Investigation -->
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full {{ in_array($trackedGrievance->status, ['in_progress', 'resolved']) ? 'bg-emerald-600 text-white' : 'bg-amber-500 text-white ring-4 ring-amber-100' }} flex items-center justify-center font-bold text-xs shadow-xs">
                                        {{ in_array($trackedGrievance->status, ['in_progress', 'resolved']) ? '✓' : '2' }}
                                    </div>
                                    <span class="text-xs font-bold {{ in_array($trackedGrievance->status, ['in_progress', 'resolved']) ? 'text-slate-800' : 'text-amber-700' }} mt-1.5">قيد الفحص والمراجعة</span>
                                    <span class="text-[10px] text-slate-400">
                                        {{ $trackedGrievance->status === 'new' ? 'في انتظار البدء' : ($trackedGrievance->status === 'in_progress' ? 'جارٍ حالياً' : 'مكتمل') }}
                                    </span>
                                </div>

                                <!-- Step 3: Resolved -->
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full {{ $trackedGrievance->status === 'resolved' ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs">
                                        {{ $trackedGrievance->status === 'resolved' ? '✓' : '3' }}
                                    </div>
                                    <span class="text-xs font-bold {{ $trackedGrievance->status === 'resolved' ? 'text-emerald-800' : 'text-slate-400' }} mt-1.5">تم اتخاذ الإجراء والحل</span>
                                    <span class="text-[10px] text-slate-400">
                                        {{ $trackedGrievance->status === 'resolved' ? 'تم الانتهاء' : 'بانتظار القرار' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Content & Official HR Response -->
                        <div class="p-6 sm:p-7 space-y-5">
                            <!-- User's complaint summary -->
                            <div>
                                <h4 class="text-xs font-bold text-slate-500 mb-1">تفاصيل الشكوى المسجلة:</h4>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-700 whitespace-pre-line leading-relaxed">
                                    {{ $trackedGrievance->content }}
                                </div>
                            </div>

                            <!-- Official Response Section -->
                            @if($trackedGrievance->hr_response)
                                <div class="p-5 rounded-2xl bg-emerald-50/80 border border-emerald-200">
                                    <div class="flex items-center gap-2 text-emerald-900 font-extrabold text-sm mb-2">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>رد وقرار إدارة الموارد البشرية الرسمي</span>
                                        @if($trackedGrievance->resolved_at)
                                            <span class="text-[11px] font-normal text-emerald-700 mr-auto">
                                                بتاريخ: {{ $trackedGrievance->resolved_at->format('Y-m-d') }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs sm:text-sm text-emerald-950 font-medium leading-relaxed whitespace-pre-line">
                                        {{ $trackedGrievance->hr_response }}
                                    </p>
                                </div>
                            @else
                                <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 flex items-center gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-xs text-blue-800 font-medium leading-normal">
                                        تذكرتك قيد الدراسة والاهتمام لدى فريق الموارد البشرية والإدارة المختصة. سيتم تحديث القرار والرد الرسمي هنا فور الانتهاء.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Footer Help / Portal Link -->
        <div class="text-center mt-10 pt-6 border-t border-slate-200">
            <p class="text-xs text-slate-500">
                هل أنت موظف بالشركة ولديك حساب؟
                <a href="{{ route('portal.dashboard') }}" class="text-teal-700 font-bold hover:underline">
                    الدخول إلى بوابة الموظف الذاتية
                </a>
            </p>
        </div>

    </div>
</div>
