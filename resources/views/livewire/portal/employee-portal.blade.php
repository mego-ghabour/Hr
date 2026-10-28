<div class="min-h-screen bg-slate-50 flex flex-col" x-data="{ 
    currentTime: '',
    isLocating: false,
    updateClock() {
        const now = new Date();
        this.currentTime = now.toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
    },
    triggerClock(action, isGpsRequired, isRemote) {
        if (isGpsRequired && !isRemote) {
            if (!navigator.geolocation) {
                alert('عذراً، متصفحك أو جهازك لا يدعم تحديد الموقع الجغرافي (GPS).');
                return;
            }
            this.isLocating = true;
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.isLocating = false;
                    if (action === 'in') {
                        $wire.clockIn(pos.coords.latitude, pos.coords.longitude);
                    } else {
                        $wire.clockOut(pos.coords.latitude, pos.coords.longitude);
                    }
                },
                (err) => {
                    this.isLocating = false;
                    let msg = 'تعذر تحديد موقعك الجغرافي. يرجى تفعيل إذن الوصول للموقع (GPS) على جهازك للمتابعة.';
                    if (err.code === 1) {
                        msg = 'تم رفض إذن الوصول للموقع الجغرافي. يتطلب النظام التحقق من تواجدك داخل نطاق مقر الشركة.';
                    } else if (err.code === 2) {
                        msg = 'إشارة الموقع الجغرافي غير متوفرة حالياً. يرجى التأكد من تشغيل الـ GPS.';
                    } else if (err.code === 3) {
                        msg = 'استغرق تحديد الموقع وقتاً طويلاً. يرجى المحاولة مرة أخرى.';
                    }
                    alert(msg);
                },
                { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
            );
        } else {
            // Remote employee or GPS disabled globally: grab coordinates optionally without blocking
            if (navigator.geolocation) {
                this.isLocating = true;
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        this.isLocating = false;
                        if (action === 'in') {
                            $wire.clockIn(pos.coords.latitude, pos.coords.longitude);
                        } else {
                            $wire.clockOut(pos.coords.latitude, pos.coords.longitude);
                        }
                    },
                    (err) => {
                        this.isLocating = false;
                        if (action === 'in') {
                            $wire.clockIn(null, null);
                        } else {
                            $wire.clockOut(null, null);
                        }
                    },
                    { timeout: 3000 }
                );
            } else {
                if (action === 'in') {
                    $wire.clockIn(null, null);
                } else {
                    $wire.clockOut(null, null);
                }
            }
        }
    }
}" x-init="updateClock(); setInterval(() => updateClock(), 1000)">

    <!-- Top Navigation Header -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    @if($logo)
                        <img src="{{ str_starts_with($logo, 'http') || str_starts_with($logo, '/') ? $logo : \Illuminate\Support\Facades\Storage::disk('public')->url($logo) }}" 
                             alt="{{ $brandName }}" 
                             class="h-9 sm:h-11 w-auto object-contain">
                    @else
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white flex items-center justify-center font-black shadow-md shadow-indigo-500/20">
                            {{ mb_substr($brandName, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <span class="text-base sm:text-lg font-black text-slate-900 block leading-tight">
                            {{ $brandName }}
                        </span>
                        <span class="text-[11px] font-bold text-brand-600 uppercase tracking-wider block">
                            بوابة الخدمة الذاتية (ESS)
                        </span>
                    </div>
                </div>

                <!-- Clock & Live Date (Hidden on mobile) -->
                <div class="hidden md:flex items-center gap-4 bg-slate-100/80 px-4 py-2 rounded-2xl border border-slate-200/60">
                    <div class="flex items-center gap-2 text-slate-700 text-xs font-bold">
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('l، d F Y') }}</span>
                    </div>
                    <span class="text-slate-300">|</span>
                    <div class="flex items-center gap-1.5 text-brand-700 text-xs font-black font-mono" dir="ltr">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span x-text="currentTime"></span>
                    </div>
                </div>

                <!-- User Profile & Actions -->
                <div class="flex items-center gap-2 sm:gap-4">
                    
                    <!-- Admin Employee Preview Switcher (if admin) -->
                    @if($allEmployees->isNotEmpty())
                        <div class="hidden lg:flex items-center gap-1.5 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-xl text-xs text-amber-900">
                            <span class="font-bold">معاينة موظف:</span>
                            <select wire:model.live="previewEmployeeId" class="bg-transparent font-medium border-none p-0 text-xs focus:ring-0 cursor-pointer">
                                @foreach($allEmployees as $empOption)
                                    <option value="{{ $empOption->id }}">{{ $empOption->full_name }} ({{ $empOption->job_title }})</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Admin Panel Link -->
                    @if($user && ($user->isAdministrative() || $user->can('view_any_employee')))
                        <a href="/admin" 
                           class="hidden sm:inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-brand-600 bg-slate-100 hover:bg-slate-200 px-3 py-2 rounded-xl transition-colors border border-slate-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>لوحة الإدارة</span>
                        </a>
                    @endif

                    <!-- Employee Avatar & Name -->
                    @if($employee)
                        <div class="flex items-center gap-2 pl-2">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gradient-to-tr from-brand-600 to-indigo-500 text-white font-black text-sm flex items-center justify-center ring-2 ring-brand-100 shadow-sm overflow-hidden">
                                @if($employee->avatar_url)
                                    <img src="{{ str_starts_with($employee->avatar_url, 'http') || str_starts_with($employee->avatar_url, '/') ? $employee->avatar_url : \Illuminate\Support\Facades\Storage::disk('public')->url($employee->avatar_url) }}" 
                                         alt="{{ $employee->full_name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    {{ mb_substr($employee->first_name, 0, 1) }}
                                @endif
                            </div>
                            <div class="text-right hidden sm:block">
                                <span class="text-xs sm:text-sm font-black text-slate-800 block leading-tight">
                                    {{ $employee->full_name }}
                                </span>
                                <span class="text-[11px] font-medium text-slate-500 block">
                                    {{ $employee->job_title ?? 'موظف' }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Logout Form -->
                    <form action="{{ route('portal.logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                title="تسجيل الخروج"
                                class="p-2 sm:px-3 sm:py-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all border border-transparent hover:border-rose-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Desktop Navigation Tabs -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-slate-100 hidden sm:block">
            <nav class="flex items-center gap-1 sm:gap-2 py-2 overflow-x-auto scrollbar-none">
                <button wire:click="setTab('overview')" 
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'overview' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>الرئيسية</span>
                </button>

                <button wire:click="setTab('attendance')" 
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'attendance' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>البصمة والحضور</span>
                </button>

                <button wire:click="setTab('leaves')" 
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'leaves' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>الإجازات والأرصدة</span>
                </button>

                <button wire:click="setTab('excuses')" 
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'excuses' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>الاستئذانات</span>
                </button>

                <button wire:click="setTab('payrolls')" 
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'payrolls' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>الرواتب ومفردات الراتب</span>
                </button>

                <button wire:click="setTab('profile')" 
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'profile' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>ملفي الوظيفي</span>
                </button>

                <button wire:click="setTab('grievances')" 
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'grievances' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>الشكاوى والمقترحات</span>
                </button>

                <button wire:click="setTab('evaluations')" 
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'evaluations' ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    <span>التقييمات والاستبيانات</span>
                </button>
            </nav>
        </div>
    </header>

    <!-- Notification Toast -->
    @if($flashMessage)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="p-4 rounded-2xl flex items-center justify-between shadow-lg border transition-all animate-bounce-once {{ $flashType === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : ($flashType === 'warning' ? 'bg-amber-50 border-amber-200 text-amber-900' : 'bg-rose-50 border-rose-200 text-rose-900') }}">
                <div class="flex items-center gap-3">
                    @if($flashType === 'success')
                        <span class="p-2 rounded-xl bg-emerald-500 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </span>
                    @elseif($flashType === 'warning')
                        <span class="p-2 rounded-xl bg-amber-500 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </span>
                    @else
                        <span class="p-2 rounded-xl bg-rose-500 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </span>
                    @endif
                    <span class="text-sm font-bold">{{ $flashMessage }}</span>
                </div>
                <button wire:click="clearNotification" class="text-slate-400 hover:text-slate-600 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 sm:pb-8">

        @if(!$employee)
            <!-- Unlinked Account Warning -->
            <div class="bg-white rounded-3xl p-8 sm:p-12 text-center shadow-sm border border-slate-200">
                <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-800 mb-2">حسابك غير مرتبط بملف موظف</h2>
                <p class="text-slate-500 max-w-md mx-auto text-sm mb-6">
                    يبدو أن هذا الحساب لا يملك ملف موظف نشط في النظام. إذا كنت مديراً أو مسؤولاً يمكنك ربط الحساب من قسم الموظفين في لوحة الإدارة.
                </p>
                <a href="/admin" class="inline-flex items-center gap-2 px-6 py-3 bg-brand-600 text-white rounded-xl font-bold text-sm hover:bg-brand-700 transition-colors">
                    <span>الانتقال إلى لوحة تحكم المسؤول</span>
                </a>
            </div>
        @else

            <!-- TAB 1: OVERVIEW -->
            @if($activeTab === 'overview')
                <div class="space-y-6">
                    
                    <!-- Welcome Hero & Quick Clock -->
                    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
                        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -left-10 -top-10 w-60 h-60 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                            <div>
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-indigo-200 text-xs font-semibold backdrop-blur-sm mb-3">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>{{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('l، d F Y') }}</span>
                                </div>
                                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                                    أهلاً بك، {{ $employee->first_name }} 👋
                                </h1>
                                <p class="text-slate-300 text-sm mt-1">
                                    {{ $employee->job_title ?? 'موظف' }} • {{ $employee->department->name ?? 'الإدارة العامة' }}
                                </p>
                            </div>

                            <!-- Live Quick Clock Card -->
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/10 flex flex-col sm:flex-row items-center gap-4">
                                <div class="text-center sm:text-right">
                                    <span class="text-xs text-indigo-200 block font-bold mb-0.5">البصمة الذكية اليوم</span>
                                    <div class="text-xl sm:text-2xl font-black font-mono tracking-wider text-white" dir="ltr" x-text="currentTime"></div>
                                    @if($todayAttendance && $todayAttendance->check_in_time)
                                        <span class="text-[11px] text-emerald-300 font-bold block mt-1">
                                            حضور: {{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('h:i A') }}
                                            @if($todayAttendance->check_out_time)
                                                • انصراف: {{ \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('h:i A') }}
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-[11px] text-slate-300 block mt-1">لم تسجل الحضور اليوم بعد</span>
                                    @endif

                                    <!-- Geofence & Location Status Badge -->
                                    <div class="mt-2 flex items-center justify-center sm:justify-start gap-1">
                                        @if($isGpsRequired && !$isRemoteEmployee)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-200 bg-white/10 px-2 py-0.5 rounded-md">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                مقر العمل (نطاق {{ $companyRadius }} م)
                                            </span>
                                        @elseif($isRemoteEmployee)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-sky-200 bg-sky-500/20 px-2 py-0.5 rounded-md border border-sky-400/30">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                                بصمة عن بُعد (من أي مكان)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-slate-300 bg-white/10 px-2 py-0.5 rounded-md">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                                تسجيل متاح من أي مكان
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div>
                                    @if(!$todayAttendance)
                                        <button type="button"
                                                @click="triggerClock('in', {{ $isGpsRequired ? 'true' : 'false' }}, {{ $isRemoteEmployee ? 'true' : 'false' }})" 
                                                :disabled="isLocating"
                                                wire:loading.attr="disabled"
                                                class="px-6 py-3.5 bg-emerald-500 hover:bg-emerald-600 active:scale-95 text-white font-black text-sm rounded-xl shadow-lg shadow-emerald-500/30 transition-all flex items-center gap-2 disabled:opacity-60 disabled:cursor-wait">
                                            <template x-if="!isLocating">
                                                <span class="flex items-center gap-2">
                                                    <svg wire:loading.remove wire:target="clockIn" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                                                    <span wire:loading.remove wire:target="clockIn">تسجيل حضور الآن</span>
                                                    <span wire:loading wire:target="clockIn">جارِ التسجيل...</span>
                                                </span>
                                            </template>
                                            <template x-if="isLocating">
                                                <span class="flex items-center gap-2">
                                                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                                    <span>جارِ تحديد الـ GPS...</span>
                                                </span>
                                            </template>
                                        </button>
                                    @elseif(!$todayAttendance->check_out_time)
                                        <button type="button"
                                                @click="triggerClock('out', {{ $isGpsRequired ? 'true' : 'false' }}, {{ $isRemoteEmployee ? 'true' : 'false' }})" 
                                                :disabled="isLocating"
                                                wire:loading.attr="disabled"
                                                class="px-6 py-3.5 bg-gradient-to-r from-amber-500 to-rose-500 hover:from-amber-600 hover:to-rose-600 active:scale-95 text-white font-black text-sm rounded-xl shadow-lg shadow-rose-500/30 transition-all flex items-center gap-2 animate-pulse disabled:opacity-60 disabled:cursor-wait">
                                            <template x-if="!isLocating">
                                                <span class="flex items-center gap-2">
                                                    <svg wire:loading.remove wire:target="clockOut" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                                    <span wire:loading.remove wire:target="clockOut">تسجيل انصراف الآن</span>
                                                    <span wire:loading wire:target="clockOut">جارِ التسجيل...</span>
                                                </span>
                                            </template>
                                            <template x-if="isLocating">
                                                <span class="flex items-center gap-2">
                                                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                                    <span>جارِ تحديد الـ GPS...</span>
                                                </span>
                                            </template>
                                        </button>
                                    @else
                                        <div class="px-5 py-2.5 bg-white/20 rounded-xl text-xs font-bold text-emerald-200 border border-emerald-400/30 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            <span>تمت البصمة لليوم ({{ $workedHoursText }})</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4 Quick KPI Cards -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                        <!-- Card 1: Attendance this month -->
                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center gap-4 hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-500 block">الحضور هذا الشهر</span>
                                <span class="text-xl sm:text-2xl font-black text-slate-900 block mt-0.5">{{ $presentDaysCount }} يوم</span>
                            </div>
                        </div>

                        <!-- Card 2: Remaining Leaves -->
                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center gap-4 hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-500 block">رصيد الإجازات السنوية</span>
                                @php
                                    $annual = collect($leaveBalancesData)->first();
                                @endphp
                                <span class="text-xl sm:text-2xl font-black text-slate-900 block mt-0.5">
                                    {{ $annual['remaining'] ?? 0 }} يوم
                                </span>
                            </div>
                        </div>

                        <!-- Card 3: Salary -->
                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center gap-4 hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-500 block">الراتب الأساسي</span>
                                <span class="text-lg sm:text-2xl font-black text-slate-900 block mt-0.5">
                                    {{ number_format($employee->salary ?? 0, 0) }} {{ $currency }}
                                </span>
                            </div>
                        </div>

                        <!-- Card 4: Excuses -->
                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center gap-4 hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-500 block">الاستئذانات المعتمدة</span>
                                <span class="text-xl sm:text-2xl font-black text-slate-900 block mt-0.5">{{ $approvedExcusesCount }} طلب</span>
                            </div>
                        </div>
                    </div>

                    <!-- Announcements & Quick Shortcuts Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Announcements Column -->
                        <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="p-2 rounded-xl bg-brand-50 text-brand-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                                    </span>
                                    <h2 class="text-base font-black text-slate-900">التعاميم والقرارات الإدارية</h2>
                                </div>
                                <span class="text-xs font-bold text-slate-400">آخر المستجدات</span>
                            </div>

                            @if($announcements->isEmpty())
                                <div class="p-8 text-center text-slate-400 text-sm">
                                    لا توجد تعاميم إدارية نشطة حالياً.
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($announcements as $announce)
                                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                                            <div class="p-2 rounded-xl {{ $announce->type === 'urgent' ? 'bg-rose-100 text-rose-600' : ($announce->type === 'warning' ? 'bg-amber-100 text-amber-600' : 'bg-brand-100 text-brand-600') }} flex-shrink-0 mt-0.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <h3 class="text-sm font-bold text-slate-900">{{ $announce->title }}</h3>
                                                    <span class="text-[11px] text-slate-400">{{ $announce->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $announce->content }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Quick Shortcuts Box -->
                        <div class="bg-gradient-to-br from-brand-600 to-indigo-700 rounded-3xl p-6 text-white shadow-lg flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-indigo-200 block mb-1">الخدمات السريعة</span>
                                <h3 class="text-lg font-black mb-3">ماذا تريد أن تفعل اليوم؟</h3>
                                <p class="text-indigo-100 text-xs leading-relaxed mb-6">
                                    يمكنك تقديم طلباتك ومتابعتها مباشرة بدون مراجعة قسم الموارد البشرية يدوياً.
                                </p>
                            </div>

                            <div class="space-y-2.5">
                                <button wire:click="setTab('leaves')" class="w-full py-3 px-4 bg-white/10 hover:bg-white/20 active:bg-white/25 rounded-2xl text-xs font-bold backdrop-blur-sm transition-all flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        <span>تقديم طلب إجازة جديدة</span>
                                    </div>
                                    <span>←</span>
                                </button>

                                <button wire:click="setTab('excuses')" class="w-full py-3 px-4 bg-white/10 hover:bg-white/20 active:bg-white/25 rounded-2xl text-xs font-bold backdrop-blur-sm transition-all flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" /></svg>
                                        <span>طلب إذن واستئذان ساعي</span>
                                    </div>
                                    <span>←</span>
                                </button>

                                <button wire:click="setTab('payrolls')" class="w-full py-3 px-4 bg-white/10 hover:bg-white/20 active:bg-white/25 rounded-2xl text-xs font-bold backdrop-blur-sm transition-all flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        <span>استعراض مسير ومفردات الراتب</span>
                                    </div>
                                    <span>←</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- TAB 2: ATTENDANCE & CLOCK TERMINAL -->
            @if($activeTab === 'attendance')
                <div class="space-y-6">
                    
                    <!-- Main Interactive Clock Card -->
                    <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-slate-200/80 text-center">
                        <div class="max-w-md mx-auto">
                            <!-- Geofence & Mode Badge -->
                            <div class="mb-4">
                                @if($isGpsRequired && !$isRemoteEmployee)
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>بصمة جغرافية ملزمة (مقر الشركة - نطاق {{ $companyRadius }} م)</span>
                                    </span>
                                @elseif($isRemoteEmployee)
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-sky-50 text-sky-800 border border-sky-200">
                                        <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        <span>نظام العمل عن بُعد مفعّل لحسابك (تسجيل من أي مكان)</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                        جهاز البصمة الإلكترونية المباشر
                                    </span>
                                @endif
                            </div>

                            <div class="text-4xl sm:text-6xl font-black font-mono tracking-widest text-slate-900 my-4" dir="ltr" x-text="currentTime"></div>
                            
                            <p class="text-xs sm:text-sm text-slate-500 font-bold mb-8">
                                {{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('l، d F Y') }}
                            </p>

                            <!-- Big Action Button -->
                            <div class="mb-6">
                                @if(!$todayAttendance)
                                    <button type="button"
                                            @click="triggerClock('in', {{ $isGpsRequired ? 'true' : 'false' }}, {{ $isRemoteEmployee ? 'true' : 'false' }})" 
                                            :disabled="isLocating"
                                            wire:loading.attr="disabled"
                                            class="w-full sm:w-auto px-10 py-5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black text-lg rounded-2xl shadow-xl shadow-emerald-500/25 transition-all transform active:scale-95 flex items-center justify-center gap-3 mx-auto disabled:opacity-60 disabled:cursor-wait">
                                        <template x-if="!isLocating">
                                            <span class="flex items-center gap-3">
                                                <svg wire:loading.remove wire:target="clockIn" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                                                <span wire:loading.remove wire:target="clockIn">تسجيل حضور الآن</span>
                                                <span wire:loading wire:target="clockIn">جارِ تسجيل البصمة...</span>
                                            </span>
                                        </template>
                                        <template x-if="isLocating">
                                            <span class="flex items-center gap-3">
                                                <svg class="animate-spin w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                                <span>جارِ تحديد الموقع الجغرافي (GPS)...</span>
                                            </span>
                                        </template>
                                    </button>
                                @elseif(!$todayAttendance->check_out_time)
                                    <button type="button"
                                            @click="triggerClock('out', {{ $isGpsRequired ? 'true' : 'false' }}, {{ $isRemoteEmployee ? 'true' : 'false' }})" 
                                            :disabled="isLocating"
                                            wire:loading.attr="disabled"
                                            class="w-full sm:w-auto px-10 py-5 bg-gradient-to-r from-amber-500 to-rose-600 hover:from-amber-600 hover:to-rose-700 text-white font-black text-lg rounded-2xl shadow-xl shadow-rose-500/25 transition-all transform active:scale-95 flex items-center justify-center gap-3 mx-auto animate-pulse disabled:opacity-60 disabled:cursor-wait">
                                        <template x-if="!isLocating">
                                            <span class="flex items-center gap-3">
                                                <svg wire:loading.remove wire:target="clockOut" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                                <span wire:loading.remove wire:target="clockOut">تسجيل انصراف الآن</span>
                                                <span wire:loading wire:target="clockOut">جارِ توثيق الانصراف...</span>
                                            </span>
                                        </template>
                                        <template x-if="isLocating">
                                            <span class="flex items-center gap-3">
                                                <svg class="animate-spin w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                                <span>جارِ تحديد الموقع الجغرافي (GPS)...</span>
                                            </span>
                                        </template>
                                    </button>
                                @else
                                    <div class="inline-flex items-center gap-2 p-4 px-6 rounded-2xl bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold text-sm">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span>اكتمل تسجيل الحضور والانصراف لهذا اليوم بنجاح ({{ $workedHoursText }})</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Today Summary Badges -->
                            <div class="grid grid-cols-2 gap-3 pt-6 border-t border-slate-100">
                                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <span class="text-[11px] font-bold text-slate-400 block">وقت تسجيل الحضور</span>
                                    <span class="text-sm font-black text-slate-800 block mt-0.5">
                                        {{ $todayAttendance && $todayAttendance->check_in_time ? \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('h:i A') : '—' }}
                                    </span>
                                </div>
                                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <span class="text-[11px] font-bold text-slate-400 block">وقت تسجيل الانصراف</span>
                                    <span class="text-sm font-black text-slate-800 block mt-0.5">
                                        {{ $todayAttendance && $todayAttendance->check_out_time ? \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('h:i A') : '—' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Log Table This Month -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-base font-black text-slate-900">سجل البصمات للشهر الحالي</h2>
                            <span class="text-xs font-bold text-slate-400">{{ $attendances->count() }} تسجيل</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-right text-xs sm:text-sm">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-bold">
                                        <th class="py-3 px-4">التاريخ</th>
                                        <th class="py-3 px-4">اليوم</th>
                                        <th class="py-3 px-4">الحضور</th>
                                        <th class="py-3 px-4">الانصراف</th>
                                        <th class="py-3 px-4">نوع البصمة / الموقع</th>
                                        <th class="py-3 px-4">إجمالي الساعات</th>
                                        <th class="py-3 px-4 text-center">الحالة</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($attendances as $att)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="py-3.5 px-4 font-bold text-slate-800">{{ $att->date }}</td>
                                            <td class="py-3.5 px-4 text-slate-600">{{ \Carbon\Carbon::parse($att->date)->locale('ar')->translatedFormat('l') }}</td>
                                            <td class="py-3.5 px-4 font-mono font-bold text-emerald-600">{{ $att->check_in_time ? \Carbon\Carbon::parse($att->check_in_time)->format('h:i A') : '—' }}</td>
                                            <td class="py-3.5 px-4 font-mono font-bold text-amber-600">{{ $att->check_out_time ? \Carbon\Carbon::parse($att->check_out_time)->format('h:i A') : '—' }}</td>
                                            <td class="py-3.5 px-4">
                                                @if($att->is_remote)
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                                        <svg class="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                                        عن بُعد
                                                    </span>
                                                @elseif(!is_null($att->distance_meters))
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" title="المسافة لمقر الشركة: {{ round($att->distance_meters) }} م">
                                                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                        مقر الشركة ({{ round($att->distance_meters) }} م)
                                                    </span>
                                                @else
                                                    <span class="text-xs text-slate-400 font-medium">تسجيل مباشر</span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-4 text-slate-700 font-semibold">{{ $att->total_hours ?? '—' }}</td>
                                            <td class="py-3.5 px-4 text-center">
                                                @if($att->status === 'present')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">حاضر</span>
                                                @elseif($att->status === 'late')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">متأخر</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">{{ $att->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="py-8 text-center text-slate-400">
                                                لا توجد سجلات حضور مسجلة لهذا الشهر حتى الآن.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- TAB 3: LEAVES & BALANCES -->
            @if($activeTab === 'leaves')
                <div class="space-y-6">
                    
                    <!-- Balances Cards Grid -->
                    <div>
                        <h2 class="text-base font-black text-slate-900 mb-3">أرصدة الإجازات للعام الحالي ({{ \Carbon\Carbon::today()->year }})</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($leaveBalancesData as $bal)
                                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-sm font-black text-slate-800">{{ $bal['name'] }}</h3>
                                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-brand-50 text-brand-700">
                                            المتبقي: {{ $bal['remaining'] }} يوم
                                        </span>
                                    </div>

                                    <div class="flex items-baseline justify-between text-xs text-slate-500 mb-1.5 font-bold">
                                        <span>المستهلك: {{ $bal['used'] }} يوم</span>
                                        <span>الإجمالي: {{ $bal['total'] }} يوم</span>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                        <div class="bg-brand-600 h-2.5 rounded-full transition-all" style="width: {{ $bal['percent'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Leave Request Form -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </span>
                            <div>
                                <h2 class="text-base font-black text-slate-900">تقديم طلب إجازة جديد</h2>
                                <p class="text-xs text-slate-500 mt-0.5">يرجى تحديد النوع والتواريخ وسبب الإجازة لإرسالها للاعتماد</p>
                            </div>
                        </div>

                        <form wire:submit.prevent="submitLeaveRequest" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <!-- Leave Type -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">نوع الإجازة</label>
                                    <select wire:model="leave_type_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-slate-50/50">
                                        @foreach($leaveTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->default_days_per_year ?? 21 }} يوم/سنة)</option>
                                        @endforeach
                                    </select>
                                    @error('leave_type_id') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Start Date -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">تاريخ البداية</label>
                                    <input type="date" wire:model="start_date" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-slate-50/50">
                                    @error('start_date') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- End Date -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">تاريخ النهاية</label>
                                    <input type="date" wire:model="end_date" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-slate-50/50">
                                    @error('end_date') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Reason -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">سبب الإجازة وملاحظات الموظف</label>
                                <textarea wire:model="leave_reason" rows="3" placeholder="اكتب سبب طلب الإجازة هنا..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-slate-50/50"></textarea>
                                @error('leave_reason') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center justify-between pt-2">
                                <div class="text-xs text-slate-500 font-bold">
                                    @if($start_date && $end_date && $start_date <= $end_date)
                                        المدة المحسوبة: <span class="text-brand-700 font-black">{{ \Carbon\Carbon::parse($start_date)->diffInDays(\Carbon\Carbon::parse($end_date)) + 1 }} أيام</span>
                                    @endif
                                </div>

                                <button type="submit" 
                                        wire:loading.attr="disabled"
                                        class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-brand-500/20 transition-all flex items-center gap-2">
                                    <svg wire:loading.remove wire:target="submitLeaveRequest" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                                    <span wire:loading.remove wire:target="submitLeaveRequest">إرسال طلب الإجازة</span>
                                    <span wire:loading wire:target="submitLeaveRequest">جارِ الإرسال...</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Leave Requests History -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
                        <h2 class="text-base font-black text-slate-900 mb-4">سجل طلبات الإجازات السابقة</h2>

                        <div class="overflow-x-auto">
                            <table class="w-full text-right text-xs sm:text-sm">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-bold">
                                        <th class="py-3 px-4">نوع الإجازة</th>
                                        <th class="py-3 px-4">من تاريخ</th>
                                        <th class="py-3 px-4">إلى تاريخ</th>
                                        <th class="py-3 px-4">المدة</th>
                                        <th class="py-3 px-4">السبب</th>
                                        <th class="py-3 px-4 text-center">الحالة</th>
                                        <th class="py-3 px-4">ملاحظات الإدارة</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($leaves as $leave)
                                        @php
                                            $days = \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
                                        @endphp
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="py-3.5 px-4 font-bold text-slate-800">{{ $leave->leaveType->name ?? 'إجازة' }}</td>
                                            <td class="py-3.5 px-4 font-semibold text-slate-700">{{ $leave->start_date }}</td>
                                            <td class="py-3.5 px-4 font-semibold text-slate-700">{{ $leave->end_date }}</td>
                                            <td class="py-3.5 px-4 font-bold text-brand-700">{{ $days }} أيام</td>
                                            <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate">{{ $leave->reason }}</td>
                                            <td class="py-3.5 px-4 text-center">
                                                @if($leave->status === 'approved')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                        معتمدة
                                                    </span>
                                                @elseif($leave->status === 'rejected')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                        مرفوضة
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                        قيد المراجعة
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-4 text-xs text-slate-500">{{ $leave->admin_notes ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="py-8 text-center text-slate-400">
                                                لا توجد طلبات إجازات سابقة.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- TAB 4: EXCUSES -->
            @if($activeTab === 'excuses')
                <div class="space-y-6">
                    
                    <!-- Excuse Request Form -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="p-2 rounded-xl bg-purple-50 text-purple-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </span>
                            <div>
                                <h2 class="text-base font-black text-slate-900">تقديم طلب استئذان ساعي</h2>
                                <p class="text-xs text-slate-500 mt-0.5">يمكنك الاستئذان خلال ساعات الدوام لأسباب شخصية أو طبية</p>
                            </div>
                        </div>

                        <form wire:submit.prevent="submitExcuseRequest" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <!-- Date -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">تاريخ الاستئذان</label>
                                    <input type="date" wire:model="excuse_date" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-slate-50/50">
                                    @error('excuse_date') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Start Time -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">وقت الخروج</label>
                                    <input type="time" wire:model="excuse_start_time" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-slate-50/50">
                                    @error('excuse_start_time') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- End Time -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">وقت العودة المتوقع</label>
                                    <input type="time" wire:model="excuse_end_time" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-slate-50/50">
                                    @error('excuse_end_time') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Reason -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">سبب الاستئذان</label>
                                <textarea wire:model="excuse_reason" rows="3" placeholder="اكتب سبب الاستئذان بوضوح..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-slate-50/50"></textarea>
                                @error('excuse_reason') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit" 
                                        wire:loading.attr="disabled"
                                        class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-brand-500/20 transition-all flex items-center gap-2">
                                    <svg wire:loading.remove wire:target="submitExcuseRequest" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                                    <span wire:loading.remove wire:target="submitExcuseRequest">إرسال طلب الاستئذان</span>
                                    <span wire:loading wire:target="submitExcuseRequest">جارِ الإرسال...</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Excuses History Table -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
                        <h2 class="text-base font-black text-slate-900 mb-4">سجل طلبات الاستئذان السابقة</h2>

                        <div class="overflow-x-auto">
                            <table class="w-full text-right text-xs sm:text-sm">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-bold">
                                        <th class="py-3 px-4">التاريخ</th>
                                        <th class="py-3 px-4">من</th>
                                        <th class="py-3 px-4">إلى</th>
                                        <th class="py-3 px-4">المدة</th>
                                        <th class="py-3 px-4">السبب</th>
                                        <th class="py-3 px-4 text-center">الحالة</th>
                                        <th class="py-3 px-4">ملاحظات الإدارة</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($excuses as $excuse)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="py-3.5 px-4 font-bold text-slate-800">{{ $excuse->date }}</td>
                                            <td class="py-3.5 px-4 font-mono text-slate-700">{{ $excuse->start_time }}</td>
                                            <td class="py-3.5 px-4 font-mono text-slate-700">{{ $excuse->end_time }}</td>
                                            <td class="py-3.5 px-4 font-bold text-brand-700">{{ $excuse->duration_minutes }} دقيقة</td>
                                            <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate">{{ $excuse->reason }}</td>
                                            <td class="py-3.5 px-4 text-center">
                                                @if($excuse->status === 'approved')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        معتمد
                                                    </span>
                                                @elseif($excuse->status === 'rejected')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                        مرفوض
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                        قيد المراجعة
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-4 text-xs text-slate-500">{{ $excuse->admin_notes ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="py-8 text-center text-slate-400">
                                                لا توجد طلبات استئذان مسجلة.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- TAB 5: PAYROLLS & PAYSLIPS -->
            @if($activeTab === 'payrolls')
                <div class="space-y-6">
                    
                    <!-- Salary Summary Card -->
                    <div class="bg-gradient-to-r from-slate-900 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <span class="text-xs font-bold text-indigo-300 block mb-1">البيانات المالية للموظف</span>
                                <h2 class="text-2xl sm:text-3xl font-black">تفاصيل الراتب والبدلات الرسمية</h2>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/10 text-center sm:text-left">
                                <span class="text-xs text-indigo-200 block font-bold">الراتب الأساسي المعتمد</span>
                                <span class="text-2xl font-black font-mono text-emerald-400 block mt-0.5">
                                    {{ number_format($employee->salary ?? 0, 2) }} {{ $currency }}
                                </span>
                            </div>
                        </div>

                        <!-- Bank & IBAN -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6 pt-6 border-t border-white/10 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="text-indigo-300 font-bold">اسم البنك المعتمد:</span>
                                <span class="text-white font-semibold">{{ $employee->bank_name ?? 'لم يُحدد بعد' }}</span>
                            </div>
                            <div class="flex items-center gap-2" dir="ltr">
                                <span class="text-white font-mono font-bold">{{ $employee->iban ?? 'IBAN Not Set' }}</span>
                                <span class="text-indigo-300 font-bold">:IBAN</span>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Payrolls History List -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-base font-black text-slate-900">سجل مسيرات الرواتب الشهرية</h2>
                            <span class="text-xs font-bold text-slate-400">إجمالي {{ $payrolls->count() }} مسير</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-right text-xs sm:text-sm">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-bold">
                                        <th class="py-3 px-4">الفترة (الشهر / السنة)</th>
                                        <th class="py-3 px-4">الراتب الأساسي</th>
                                        <th class="py-3 px-4">البدلات</th>
                                        <th class="py-3 px-4">الخصومات</th>
                                        <th class="py-3 px-4">صافي الراتب المستحق</th>
                                        <th class="py-3 px-4 text-center">الحالة</th>
                                        <th class="py-3 px-4 text-center">الإجراء</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($payrolls as $payroll)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="py-3.5 px-4 font-black text-slate-800">
                                                {{ $payroll->month_name }} {{ $payroll->year }}
                                            </td>
                                            <td class="py-3.5 px-4 font-mono text-slate-700">{{ number_format($payroll->basic_salary, 2) }} {{ $currency }}</td>
                                            <td class="py-3.5 px-4 font-mono text-emerald-600">+{{ number_format($payroll->allowances ?? 0, 2) }} {{ $currency }}</td>
                                            <td class="py-3.5 px-4 font-mono text-rose-600">-{{ number_format($payroll->deductions ?? 0, 2) }} {{ $currency }}</td>
                                            <td class="py-3.5 px-4 font-mono font-black text-brand-700 text-base">
                                                {{ number_format($payroll->net_salary, 2) }} {{ $currency }}
                                            </td>
                                            <td class="py-3.5 px-4 text-center">
                                                @if($payroll->status === 'paid')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">تم الصرف</span>
                                                @elseif($payroll->status === 'draft')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">مسودة</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">قيد المعالجة</span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-4 text-center">
                                                <button wire:click="openPayslip({{ $payroll->id }})" 
                                                        class="px-3 py-1.5 rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold text-xs transition-colors inline-flex items-center gap-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                    <span>عرض مفردات الراتب</span>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="py-8 text-center text-slate-400">
                                                لا توجد مسيرات رواتب مسجلة لهذا الموظف بعد.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- TAB 6: PROFILE -->
            @if($activeTab === 'profile')
                <div class="space-y-6">
                    
                    <!-- Profile Card -->
                    <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-slate-200/80">
                        <div class="flex flex-col sm:flex-row items-center gap-6 pb-8 border-b border-slate-100 text-center sm:text-right">
                            <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white font-black text-3xl flex items-center justify-center shadow-xl shadow-brand-500/20 overflow-hidden ring-4 ring-slate-50">
                                @if($employee->avatar_url)
                                    <img src="{{ str_starts_with($employee->avatar_url, 'http') || str_starts_with($employee->avatar_url, '/') ? $employee->avatar_url : \Illuminate\Support\Facades\Storage::disk('public')->url($employee->avatar_url) }}" 
                                         alt="{{ $employee->full_name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    {{ mb_substr($employee->first_name, 0, 1) }}
                                @endif
                            </div>

                            <div class="flex-1">
                                <h1 class="text-2xl font-black text-slate-900">{{ $employee->full_name }}</h1>
                                <p class="text-brand-600 font-bold text-sm mt-0.5">{{ $employee->job_title ?? 'موظف' }}</p>
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-3 text-xs font-semibold">
                                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700">
                                        القسم: {{ $employee->department->name ?? 'غير محدد' }}
                                    </span>
                                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        الحالة: {{ $employee->status === 'active' ? 'نشط على رأس العمل' : $employee->status }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pt-8 text-xs sm:text-sm">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-bold block mb-1">البريد الإلكتروني</span>
                                <span class="text-slate-800 font-bold block">{{ $employee->user->email ?? $user->email }}</span>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-bold block mb-1">رقم الجوال</span>
                                <span class="text-slate-800 font-bold font-mono block">{{ $employee->phone ?? '—' }}</span>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-bold block mb-1">رقم الهوية الوطنية / الإقامة</span>
                                <span class="text-slate-800 font-bold font-mono block">{{ $employee->national_id ?? '—' }}</span>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-bold block mb-1">تاريخ التعيين</span>
                                <span class="text-slate-800 font-bold block">{{ $employee->hire_date ?? '—' }}</span>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-bold block mb-1">تاريخ انتهاء العقد</span>
                                <span class="text-slate-800 font-bold block">{{ $employee->contract_end_date ?? 'عقد غير محدد المدة' }}</span>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-bold block mb-1">المدير المباشر</span>
                                <span class="text-slate-800 font-bold block">{{ $employee->manager->full_name ?? '—' }}</span>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-bold block mb-1">البنك المعتمد</span>
                                <span class="text-slate-800 font-bold block">{{ $employee->bank_name ?? '—' }}</span>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 sm:col-span-2">
                                <span class="text-slate-400 font-bold block mb-1">رقم الحساب الدولي (IBAN)</span>
                                <span class="text-slate-800 font-mono font-bold block text-sm" dir="ltr">{{ $employee->iban ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- ========================================== -->
            <!-- 7. GRIEVANCES TAB (الشكاوى والمقترحات الرقمية) -->
            <!-- ========================================== -->
            @if($activeTab === 'grievances')
                <div class="space-y-6">
                    <!-- Header Banner -->
                    <div class="bg-gradient-to-r from-slate-900 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden shadow-xl">
                        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-white backdrop-blur-md mb-3 border border-white/10">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                    <span>نظام لا ورقي موثق بالكامل</span>
                                </span>
                                <h2 class="text-xl sm:text-2xl font-black text-white">صندوق الشكاوى والمقترحات الرقمي</h2>
                                <p class="text-xs sm:text-sm text-slate-300 mt-2 max-w-2xl leading-relaxed">
                                    تواصل مباشرة مع إدارة الموارد البشرية لتقديم أي شكوى، ملاحظة، أو مقترح تطويري. يمكنك تقديم الشكوى بسرية تامة (دون إظهار هويتك) لحماية خصوصيتك التامة.
                                </p>
                            </div>

                            <button wire:click="openGrievanceModal" 
                                    class="px-6 py-3.5 bg-brand-600 hover:bg-brand-500 active:scale-95 text-white font-black text-sm rounded-2xl shadow-lg shadow-brand-500/30 transition-all flex items-center justify-center gap-2 whitespace-nowrap self-start md:self-auto">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                <span>تقديم شكوى أو مقترح جديد</span>
                            </button>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-slate-400 block mb-1">إجمالي طلباتي المقدمة</span>
                                <span class="text-2xl font-black text-slate-900 font-mono">{{ $myGrievances->count() }}</span>
                            </div>
                            <span class="p-3 rounded-2xl bg-slate-50 text-slate-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </span>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-amber-500 block mb-1">قيد المراجعة والفحص</span>
                                <span class="text-2xl font-black text-amber-600 font-mono">{{ $myGrievances->whereIn('status', ['new', 'in_progress'])->count() }}</span>
                            </div>
                            <span class="p-3 rounded-2xl bg-amber-50 text-amber-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </span>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-emerald-500 block mb-1">تم الرد والمعالجة</span>
                                <span class="text-2xl font-black text-emerald-600 font-mono">{{ $myGrievances->where('status', 'resolved')->count() }}</span>
                            </div>
                            <span class="p-3 rounded-2xl bg-emerald-50 text-emerald-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </span>
                        </div>
                    </div>

                    <!-- Grievances List & Filter Bar -->
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                                    <span>سجل الشكاوى والمقترحات</span>
                                    <span class="text-xs font-normal text-slate-400">({{ $allMyGrievances->count() }} طلب)</span>
                                </h3>
                                <p class="text-xs text-slate-400 mt-0.5">يمكنك متابعة مرحلة فحص طلبك ورد الموارد البشرية عليه لحظة بلحظة</p>
                            </div>

                            <!-- Filter Pills -->
                            <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl overflow-x-auto">
                                <button type="button" wire:click="setGrievanceFilter('all')" 
                                        class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ $grievanceFilter === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                                    الكل ({{ $allMyGrievances->count() }})
                                </button>
                                <button type="button" wire:click="setGrievanceFilter('in_progress')" 
                                        class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ $grievanceFilter === 'in_progress' ? 'bg-amber-500 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                                    قيد الفحص ({{ $allMyGrievances->whereIn('status', ['new', 'in_progress'])->count() }})
                                </button>
                                <button type="button" wire:click="setGrievanceFilter('resolved')" 
                                        class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ $grievanceFilter === 'resolved' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                                    تم الحل ({{ $allMyGrievances->where('status', 'resolved')->count() }})
                                </button>
                                <button type="button" wire:click="setGrievanceFilter('suggestion')" 
                                        class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ $grievanceFilter === 'suggestion' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                                    مقترحات ({{ $allMyGrievances->where('type', 'suggestion')->count() }})
                                </button>
                            </div>
                        </div>

                        @if($myGrievances->isEmpty())
                            <div class="text-center py-12">
                                <span class="inline-flex p-4 rounded-3xl bg-slate-50 text-slate-400 mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                </span>
                                <h4 class="text-sm font-bold text-slate-700">لا توجد شكاوى أو مقترحات مطابقة</h4>
                                <p class="text-xs text-slate-400 mt-1">صوتك مسموع دائماً! انقر على زر التقديم أعلاه لرفع أي استفسار أو مقترح لإدارة الموارد البشرية.</p>
                            </div>
                        @else
                            <div class="space-y-5">
                                @foreach($myGrievances as $item)
                                    <div class="p-5 sm:p-6 rounded-3xl border border-slate-100 bg-white hover:border-slate-200 transition-all shadow-sm space-y-4">
                                        <!-- Top Info Bar -->
                                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <button type="button" 
                                                        onclick="navigator.clipboard.writeText('{{ $item->ticket_number }}'); alert('تم نسخ رقم التذكرة: {{ $item->ticket_number }}');"
                                                        title="انقر لنسخ رقم التذكرة"
                                                        class="group flex items-center gap-1.5 font-mono font-bold text-xs px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 transition-colors">
                                                    <span>{{ $item->ticket_number }}</span>
                                                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                                </button>

                                                <span class="text-xs font-bold px-3 py-1 rounded-xl {{ $item->type === 'suggestion' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-indigo-50 text-indigo-700 border border-indigo-100' }}">
                                                    {{ $item->type_label }}
                                                </span>

                                                @if($item->is_anonymous)
                                                    <span class="text-xs font-bold px-3 py-1 rounded-xl bg-slate-900 text-white flex items-center gap-1 shadow-sm">
                                                        <span>🔒 سري تماماً (مشفر)</span>
                                                    </span>
                                                @endif

                                                @if($item->against_manager)
                                                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-xl bg-amber-50 text-amber-800 border border-amber-200">
                                                        موجه للإدارة العليا
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold px-3 py-1 rounded-full {{ match($item->status) { 'resolved' => 'bg-emerald-100 text-emerald-800', 'in_progress' => 'bg-amber-100 text-amber-800', 'rejected' => 'bg-slate-200 text-slate-700', default => 'bg-rose-100 text-rose-800' } }}">
                                                    {{ $item->status_label }}
                                                </span>
                                                <span class="text-[11px] text-slate-400 font-mono">{{ $item->created_at->format('Y-m-d H:i') }}</span>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div>
                                            <h4 class="text-sm sm:text-base font-black text-slate-900">{{ $item->title }}</h4>
                                            <p class="text-xs sm:text-sm text-slate-600 mt-1.5 leading-relaxed bg-slate-50/60 p-4 rounded-2xl border border-slate-100">{{ $item->content }}</p>
                                        </div>

                                        <!-- Visual Timeline Progress Tracker -->
                                        <div class="py-2 px-4 rounded-2xl bg-slate-50/70 border border-slate-100">
                                            <div class="flex items-center justify-between text-xs">
                                                <!-- Step 1 -->
                                                <div class="flex items-center gap-2">
                                                    <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] font-bold">✓</span>
                                                    <div>
                                                        <span class="font-bold text-slate-800 block text-[11px]">تم الاستلام والتوثيق</span>
                                                        <span class="text-[10px] text-slate-400 block font-mono">{{ $item->created_at->format('Y/m/d') }}</span>
                                                    </div>
                                                </div>

                                                <div class="flex-1 mx-3 h-0.5 {{ $item->status !== 'new' ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>

                                                <!-- Step 2 -->
                                                <div class="flex items-center gap-2">
                                                    <span class="w-6 h-6 rounded-full {{ in_array($item->status, ['in_progress', 'resolved', 'rejected']) ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-[10px] font-bold">
                                                        {{ in_array($item->status, ['in_progress', 'resolved', 'rejected']) ? '✓' : '2' }}
                                                    </span>
                                                    <div>
                                                        <span class="font-bold {{ in_array($item->status, ['in_progress', 'resolved', 'rejected']) ? 'text-slate-800' : 'text-slate-400' }} block text-[11px]">قيد الفحص والدراسة</span>
                                                        <span class="text-[10px] text-slate-400 block">فريق الموارد البشرية</span>
                                                    </div>
                                                </div>

                                                <div class="flex-1 mx-3 h-0.5 {{ $item->status === 'resolved' ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>

                                                <!-- Step 3 -->
                                                <div class="flex items-center gap-2">
                                                    <span class="w-6 h-6 rounded-full {{ $item->status === 'resolved' ? 'bg-emerald-600 text-white' : ($item->status === 'rejected' ? 'bg-slate-500 text-white' : 'bg-slate-200 text-slate-500') }} flex items-center justify-center text-[10px] font-bold">
                                                        {{ $item->status === 'resolved' ? '✓' : '3' }}
                                                    </span>
                                                    <div>
                                                        <span class="font-bold {{ in_array($item->status, ['resolved', 'rejected']) ? 'text-slate-800' : 'text-slate-400' }} block text-[11px]">
                                                            {{ $item->status === 'rejected' ? 'تم الإغلاق' : 'تم الحل والمعالجة' }}
                                                        </span>
                                                        <span class="text-[10px] text-slate-400 block font-mono">
                                                            {{ $item->resolved_at ? $item->resolved_at->format('Y/m/d') : 'بانتظار الإجراء' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Official HR Resolution Box -->
                                        @if($item->hr_response)
                                            <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-emerald-50/90 to-teal-50/70 border border-emerald-200 shadow-sm space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <span class="p-1.5 rounded-lg bg-emerald-600 text-white">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                        </span>
                                                        <span class="text-xs font-black text-emerald-950">رد وتوجيه رسمي من إدارة الموارد البشرية</span>
                                                    </div>
                                                    @if($item->resolved_at)
                                                        <span class="text-[11px] text-emerald-700 font-mono">بتاريخ: {{ $item->resolved_at->format('Y-m-d H:i') }}</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs sm:text-sm text-emerald-900 leading-relaxed font-medium pt-1">{{ $item->hr_response }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- ========================================== -->
            <!-- 8. EVALUATIONS TAB (التقييمات والاستبيانات) -->
            <!-- ========================================== -->
            @if($activeTab === 'evaluations')
                <div class="space-y-6 animate-fadeIn">
                    <!-- Header Banner with Performance Metrics -->
                    <div class="bg-gradient-to-r from-teal-950 via-slate-900 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden shadow-xl border border-teal-800/30">
                        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="max-w-xl">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-teal-500/20 text-teal-300 backdrop-blur-md mb-3 border border-teal-400/30">
                                    <svg class="w-4 h-4 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    <span>نظام التقييم الدوري والتغذية الراجعة (360 Feedback)</span>
                                </span>
                                <h2 class="text-xl sm:text-2xl font-black text-white">التقييمات واستبيانات الأداء الوظيفي</h2>
                                <p class="text-xs sm:text-sm text-slate-300 mt-2 leading-relaxed">
                                    هنا يمكنك متابعة نتائج تقييم أدائك الرسمي المعتمد من الإدارة، والمشاركة في تقييم أسلوب إدارة مديرك (بسرية تامة 🔒)، وتعبئة استبيانات بيئة العمل.
                                </p>
                            </div>

                            <!-- Performance Score Pill -->
                            <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-4 text-center shrink-0 min-w-[180px]">
                                <span class="text-[11px] font-bold text-teal-200 block mb-1">متوسط تقييم أدائي العام</span>
                                <div class="text-3xl font-black text-amber-300 font-mono tracking-tight">
                                    {{ $myAvgScore ? number_format((float)$myAvgScore, 2) : '—' }}
                                    <span class="text-sm font-sans font-bold text-slate-300">/ 5.0 ⭐</span>
                                </div>
                                <span class="text-[10px] font-extrabold text-emerald-300 block mt-1">
                                    {{ $myEvaluations->count() > 0 ? 'مستند إلى ' . $myEvaluations->count() . ' تقييم معتمد' : 'لا توجد تقييمات معتمدة بعد' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 1: Available Evaluation Forms to Fill -->
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-7">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-black text-slate-900">النماذج والاستبيانات المتاحة للبدء فيها الآن</h3>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">اختر النموذج المطلوب لتعبئة التقييم واحتساب النتيجة آلياً</p>
                            </div>
                            <span class="text-xs bg-teal-50 border border-teal-200 text-teal-800 font-extrabold px-3 py-1 rounded-xl">
                                {{ $availableForms->count() }} نموذج متاح
                            </span>
                        </div>

                        @if($availableForms->isEmpty())
                            <div class="text-center py-10 bg-slate-50 border border-dashed border-slate-200 rounded-2xl text-slate-400 text-xs font-bold">
                                📋 لا توجد نماذج تقييم مفتوحة في الوقت الحالي. سيتم إشعارك فور إطلاق استبيان أو دورة تقييمية جديدة.
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($availableForms as $form)
                                    <div class="p-5 rounded-2xl border border-slate-200/90 bg-gradient-to-b from-white to-slate-50/60 hover:shadow-md hover:border-teal-300 transition-all flex flex-col justify-between gap-4">
                                        <div>
                                            <div class="flex items-center justify-between gap-2 mb-2">
                                                @php
                                                    $typeBadge = match($form->type) {
                                                        'employee_to_manager' => ['bg' => 'bg-amber-100 text-amber-900 border-amber-200', 'label' => '🔒 تقييم سري للمدير المباشر'],
                                                        'manager_to_employee' => ['bg' => 'bg-teal-100 text-teal-900 border-teal-200', 'label' => '👔 تقييم أداء موظف / وكيل'],
                                                        default => ['bg' => 'bg-sky-100 text-sky-900 border-sky-200', 'label' => '📊 استبيان قياس رضا عام'],
                                                    };
                                                @endphp
                                                <span class="text-[11px] font-black px-2.5 py-1 rounded-lg border {{ $typeBadge['bg'] }}">
                                                    {{ $typeBadge['label'] }}
                                                </span>
                                                <span class="text-xs text-slate-500 font-mono font-bold">{{ count($form->questions ?? []) }} معايير</span>
                                            </div>
                                            <h4 class="text-sm font-black text-slate-900 leading-snug">{{ $form->title }}</h4>
                                            @if($form->description)
                                                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">{{ $form->description }}</p>
                                            @endif
                                        </div>

                                        <button type="button"
                                                wire:click="openEvaluationModal({{ $form->id }})" 
                                                class="w-full py-2.5 px-4 bg-slate-900 hover:bg-teal-700 text-white text-xs font-extrabold rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm cursor-pointer active:scale-[0.99]">
                                            <span>✍️</span>
                                            <span>بدء تعبئة وإرسال التقييم</span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Section 2: Evaluation Results & History (Where Results Live) -->
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-7">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-black text-slate-900">سجل ونتائج التقييمات</h3>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">استعرض نتائج تقييمات أدائك المعتمدة من الإدارة أو التقييمات التي قمت بتقديمها</p>
                            </div>

                            <!-- Sub-tabs Switcher -->
                            <div class="flex bg-slate-100 p-1 rounded-xl">
                                <button type="button" 
                                        wire:click="$set('evaluationsViewTab', 'received')"
                                        class="px-3.5 py-1.5 rounded-lg text-xs font-black transition {{ $evaluationsViewTab === 'received' ? 'bg-white text-teal-800 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                                    تقييمات أدائي المستلمة ({{ $myEvaluations->count() }})
                                </button>
                                <button type="button" 
                                        wire:click="$set('evaluationsViewTab', 'submitted')"
                                        class="px-3.5 py-1.5 rounded-lg text-xs font-black transition {{ $evaluationsViewTab === 'submitted' ? 'bg-white text-teal-800 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                                    التقييمات المرسلة مني ({{ $submittedByMeEvaluations->count() }})
                                </button>
                            </div>
                        </div>

                        <!-- Tab A: Received Appraisals -->
                        @if($evaluationsViewTab === 'received')
                            @if($myEvaluations->isEmpty())
                                <div class="text-center py-12 bg-slate-50 border border-dashed border-slate-200 rounded-2xl">
                                    <span class="text-3xl block mb-2">⭐</span>
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-700">لم يتم اعتماد أي تقييم أداء رسمي لك حتى الآن</h4>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">تتم التقييمات الرسمية دورياً من قِبل مديرك المباشر وإدارة الموارد البشرية، وستظهر نتيجتها وتفاصيلها هنا فور اعتمادها.</p>
                                </div>
                            @else
                                <div class="space-y-4">
                                    @foreach($myEvaluations as $rev)
                                        <div class="p-5 sm:p-6 rounded-2xl border border-slate-200 bg-slate-50/60 hover:bg-white hover:shadow-md transition-all space-y-4">
                                            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200/80 pb-3">
                                                <div>
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="text-xs font-bold text-teal-800 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md">
                                                            {{ $rev->period ?? $rev->created_at->format('Y-m') }}
                                                        </span>
                                                        <span class="text-xs text-slate-400 font-medium">بتاريخ: {{ $rev->created_at->format('Y/m/d') }}</span>
                                                    </div>
                                                    <h4 class="text-sm sm:text-base font-black text-slate-900">{{ $rev->form?->title ?? 'تقييم أداء دوري' }}</h4>
                                                </div>

                                                <div class="flex items-center gap-3">
                                                    <div class="text-left">
                                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-amber-50 border border-amber-300 text-amber-900 font-black text-sm font-mono shadow-xs">
                                                            <span>{{ number_format((float)$rev->score, 2) }}</span>
                                                            <span class="text-amber-500">/ 5.0 ⭐</span>
                                                        </span>
                                                    </div>
                                                    <button type="button"
                                                            wire:click="viewEvaluationDetails({{ $rev->id }})"
                                                            class="text-xs font-extrabold text-teal-700 hover:text-white bg-white hover:bg-teal-600 border border-teal-300 px-3 py-1.5 rounded-xl transition cursor-pointer shadow-xs">
                                                        🔍 عرض تفاصيل المعايير
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                                @if($rev->strengths)
                                                    <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200">
                                                        <span class="font-black text-emerald-950 block mb-1">أبرز نقاط القوة والإنجازات:</span>
                                                        <p class="text-emerald-900 leading-relaxed font-medium">{{ $rev->strengths }}</p>
                                                    </div>
                                                @endif
                                                @if($rev->areas_for_improvement)
                                                    <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200">
                                                        <span class="font-black text-amber-950 block mb-1">مجالات التطوير والتحسين:</span>
                                                        <p class="text-amber-900 leading-relaxed font-medium">{{ $rev->areas_for_improvement }}</p>
                                                    </div>
                                                @endif
                                            </div>

                                            @if($rev->action_plan)
                                                <div class="p-3.5 rounded-xl bg-sky-50 border border-sky-200 text-xs">
                                                    <span class="font-black text-sky-950 block mb-1">خطة العمل والتطوير المعتمدة:</span>
                                                    <p class="text-sky-900 leading-relaxed font-medium">{{ $rev->action_plan }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif

                        <!-- Tab B: Submitted Appraisals by Me -->
                        @if($evaluationsViewTab === 'submitted')
                            @if($submittedByMeEvaluations->isEmpty())
                                <div class="text-center py-12 bg-slate-50 border border-dashed border-slate-200 rounded-2xl">
                                    <span class="text-3xl block mb-2">📝</span>
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-700">لم تقم بإرسال أي تقييمات بعد</h4>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">عند قيامك بتعبئة أي نموذج تقييم لمديرك أو استبيان عام، سيتم توثيقه وحفظه هنا.</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($submittedByMeEvaluations as $subRev)
                                        <div class="p-4 rounded-2xl border border-slate-200 bg-white flex flex-wrap items-center justify-between gap-3 text-xs">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-extrabold text-slate-900">{{ $subRev->form?->title ?? 'تقييم' }}</span>
                                                    @if($subRev->is_anonymous)
                                                        <span class="bg-amber-100 text-amber-900 font-bold px-2 py-0.5 rounded-md text-[10px]">🔒 سري مجهول الهوية</span>
                                                    @elseif($subRev->evaluatedEmployee)
                                                        <span class="bg-teal-50 text-teal-800 font-bold px-2 py-0.5 rounded-md text-[10px]">المُقيَّم: {{ $subRev->evaluatedEmployee->full_name }}</span>
                                                    @endif
                                                </div>
                                                <span class="text-slate-400">تاريخ الإرسال: {{ $subRev->created_at->format('Y/m/d H:i') }}</span>
                                            </div>

                                            <div class="flex items-center gap-3">
                                                @if($subRev->score)
                                                    <span class="font-mono font-black text-amber-900 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-lg">
                                                        {{ number_format((float)$subRev->score, 2) }} / 5.0 ⭐
                                                    </span>
                                                @endif
                                                <span class="px-2.5 py-1 rounded-lg font-bold {{ $subRev->status === 'reviewed' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                                    {{ $subRev->status === 'reviewed' ? 'معتمد رسمياً ✓' : 'تم الإرسال للإدارة' }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif

                    </div>
                </div>
            @endif

        @endif

    </main>

    <!-- PAYSLIP MODAL (مفردات الراتب الرقمية) -->
    @if($showPayslipModal && $selectedPayroll)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-6 sm:p-8 border border-slate-100 payslip-container">
                
                <!-- Modal Header & Actions (No Print) -->
                <div class="flex items-center justify-between pb-6 border-b border-slate-100 no-print">
                    <div class="flex items-center gap-2">
                        <span class="p-2 rounded-xl bg-brand-50 text-brand-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </span>
                        <div>
                            <h3 class="text-base font-black text-slate-900">قسيمة الراتب الرسمية (Payslip)</h3>
                            <span class="text-xs text-slate-400">إشعار صرف راتب رسمي معتمد</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Print Button -->
                        <button onclick="window.print()" 
                                class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                            <span>طباعة / PDF</span>
                        </button>

                        <!-- Close Button -->
                        <button wire:click="closePayslipModal" 
                                class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <!-- Printable Payslip Body -->
                <div class="py-6 space-y-6">
                    
                    <!-- Company & Document Info -->
                    <div class="flex items-center justify-between border-b border-slate-100 pb-5">
                        <div>
                            <h2 class="text-lg font-black text-slate-900">{{ $brandName }}</h2>
                            <p class="text-xs text-slate-500 mt-0.5">قسم الموارد البشرية والشؤون المالية</p>
                        </div>
                        <div class="text-left">
                            <span class="text-xs font-bold text-brand-600 block">قسيمة راتب شهر</span>
                            <span class="text-base font-black text-slate-900 block">{{ $selectedPayroll->month_name }} {{ $selectedPayroll->year }}</span>
                        </div>
                    </div>

                    <!-- Employee Details Block -->
                    <div class="bg-slate-50 rounded-2xl p-4 grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                        <div>
                            <span class="text-slate-400 font-bold block">اسم الموظف:</span>
                            <span class="text-slate-900 font-black block mt-0.5">{{ $selectedPayroll->employee->full_name }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-bold block">المسمى الوظيفي:</span>
                            <span class="text-slate-900 font-bold block mt-0.5">{{ $selectedPayroll->employee->job_title ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-bold block">القسم:</span>
                            <span class="text-slate-900 font-bold block mt-0.5">{{ $selectedPayroll->employee->department->name ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-bold block">البنك:</span>
                            <span class="text-slate-900 font-bold block mt-0.5">{{ $selectedPayroll->employee->bank_name ?? '—' }}</span>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="text-slate-400 font-bold block">الحساب (IBAN):</span>
                            <span class="text-slate-900 font-mono font-bold block mt-0.5" dir="ltr">{{ $selectedPayroll->employee->iban ?? '—' }}</span>
                        </div>
                    </div>

                    <!-- Earnings & Deductions Table -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <!-- Earnings (الاستحقاقات) -->
                        <div class="border border-emerald-100 rounded-2xl p-4 bg-emerald-50/30">
                            <h4 class="text-xs font-black text-emerald-800 pb-2 border-b border-emerald-100 mb-3 flex items-center justify-between">
                                <span>الاستحقاقات (Earnings)</span>
                                <span>المبلغ</span>
                            </h4>
                            <div class="space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-600 font-medium">الراتب الأساسي</span>
                                    <span class="font-mono font-bold text-slate-800">{{ number_format($selectedPayroll->basic_salary, 2) }} {{ $currency }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-600 font-medium">البدلات والحوافز</span>
                                    <span class="font-mono font-bold text-emerald-600">+{{ number_format($selectedPayroll->allowances ?? 0, 2) }} {{ $currency }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Deductions (الاستقطاعات) -->
                        <div class="border border-rose-100 rounded-2xl p-4 bg-rose-50/30">
                            <h4 class="text-xs font-black text-rose-800 pb-2 border-b border-rose-100 mb-3 flex items-center justify-between">
                                <span>الاستقطاعات (Deductions)</span>
                                <span>المبلغ</span>
                            </h4>
                            <div class="space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-600 font-medium">الخصومات والغيابات</span>
                                    <span class="font-mono font-bold text-rose-600">-{{ number_format($selectedPayroll->deductions ?? 0, 2) }} {{ $currency }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Net Salary Banner -->
                    <div class="bg-slate-900 rounded-2xl p-5 text-white flex items-center justify-between">
                        <div>
                            <span class="text-xs text-indigo-200 block font-bold">صافي الراتب المستحق (Net Payable Salary)</span>
                            <span class="text-[11px] text-slate-400 block mt-0.5">شامل كافة الاستحقاقات والخصومات</span>
                        </div>
                        <div class="text-left font-mono font-black text-2xl text-emerald-400">
                            {{ number_format($selectedPayroll->net_salary, 2) }} {{ $currency }}
                        </div>
                    </div>

                    <!-- Footer Signatures in Print -->
                    <div class="pt-8 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 print-only">
                        <div>
                            <span>توقيع الموظف المستلم: ___________________</span>
                        </div>
                        <div>
                            <span>ختم إدارة الموارد البشرية: ___________________</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- NEW GRIEVANCE MODAL (تقديم شكوى أو مقترح جديد) -->
    @if($showGrievanceModal)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 sm:p-8 border border-slate-100">
                <div class="flex items-center justify-between pb-5 border-b border-slate-100 mb-5">
                    <div class="flex items-center gap-2">
                        <span class="p-2 rounded-xl bg-brand-50 text-brand-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        </span>
                        <div>
                            <h3 class="text-base font-black text-slate-900">تقديم شكوى أو مقترح رسمي</h3>
                            <span class="text-xs text-slate-400">نموذج إلكتروني بديل للسجلات الورقية</span>
                        </div>
                    </div>
                    <button wire:click="closeGrievanceModal" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit.prevent="submitGrievance" class="space-y-4">
                    <!-- Anonymous Shield Card -->
                    <div class="p-4 rounded-2xl transition-all border {{ $grievance_is_anonymous ? 'bg-slate-900 border-slate-800 text-white shadow-md' : 'bg-slate-50 border-slate-200/80 text-slate-900' }}">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="p-2.5 rounded-xl {{ $grievance_is_anonymous ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-200 text-slate-600' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                </span>
                                <div>
                                    <span class="text-xs font-black block">تقديم بسرية تامة (إخفاء هويتي) 🔒</span>
                                    <span class="text-[11px] block {{ $grievance_is_anonymous ? 'text-slate-300' : 'text-slate-500' }}">
                                        {{ $grievance_is_anonymous ? '✓ وضع الخصوصية مفعل: لن يتم تسجيل اسمك أو بريدك.' : 'انقر لتفعيل وضع السرية الكاملة وتشفير هويتك.' }}
                                    </span>
                                </div>
                            </div>
                            <input type="checkbox" wire:model.live="grievance_is_anonymous" class="w-5 h-5 text-emerald-500 rounded-lg border-slate-300 focus:ring-emerald-500 cursor-pointer">
                        </div>
                    </div>

                    <!-- Category Visual Cards -->
                    <div>
                        <label class="block text-xs font-black text-slate-800 mb-2">اختر تصنيف الشكوى أو المقترح</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" wire:click="selectGrievanceType('administrative')" 
                                    class="p-3 rounded-2xl border text-right transition-all {{ $grievance_type === 'administrative' ? 'border-brand-500 bg-brand-50/70 ring-2 ring-brand-500/20' : 'border-slate-200 bg-white hover:bg-slate-50' }}">
                                <span class="text-lg block mb-1">🏢</span>
                                <span class="text-xs font-black text-slate-900 block">إدارية / مالية</span>
                                <span class="text-[10px] text-slate-500 block">رواتب، بدلات، إجازات</span>
                            </button>

                            <button type="button" wire:click="selectGrievanceType('workplace')" 
                                    class="p-3 rounded-2xl border text-right transition-all {{ $grievance_type === 'workplace' ? 'border-amber-500 bg-amber-50/70 ring-2 ring-amber-500/20' : 'border-slate-200 bg-white hover:bg-slate-50' }}">
                                <span class="text-lg block mb-1">💻</span>
                                <span class="text-xs font-black text-slate-900 block">بيئة العمل والأدوات</span>
                                <span class="text-[10px] text-slate-500 block">أجهزة، تكييف، مقاعد</span>
                            </button>

                            <button type="button" wire:click="selectGrievanceType('behavioral')" 
                                    class="p-3 rounded-2xl border text-right transition-all {{ $grievance_type === 'behavioral' ? 'border-rose-500 bg-rose-50/70 ring-2 ring-rose-500/20' : 'border-slate-200 bg-white hover:bg-slate-50' }}">
                                <span class="text-lg block mb-1">🤝</span>
                                <span class="text-xs font-black text-slate-900 block">سلوكية وتعامل مهني</span>
                                <span class="text-[10px] text-slate-500 block">معاملة، سوء تفاهم</span>
                            </button>

                            <button type="button" wire:click="selectGrievanceType('suggestion')" 
                                    class="p-3 rounded-2xl border text-right transition-all {{ $grievance_type === 'suggestion' ? 'border-emerald-500 bg-emerald-50/70 ring-2 ring-emerald-500/20' : 'border-slate-200 bg-white hover:bg-slate-50' }}">
                                <span class="text-lg block mb-1">💡</span>
                                <span class="text-xs font-black text-slate-900 block">فكرة / مقترح تطويري</span>
                                <span class="text-[10px] text-slate-500 block">أفكار تحسين العمل</span>
                            </button>
                        </div>
                    </div>

                    <!-- Priority Chips -->
                    <div>
                        <label class="block text-xs font-black text-slate-800 mb-1.5">مستوى الأهمية والسرعة</label>
                        <div class="grid grid-cols-4 gap-2">
                            @foreach(['low' => 'منخفضة', 'normal' => 'عادية', 'high' => 'عالية', 'urgent' => 'عاجلة جداً ⚠️'] as $pVal => $pLabel)
                                <button type="button" wire:click="selectGrievancePriority('{{ $pVal }}')" 
                                        class="py-2 px-1 text-center rounded-xl text-xs font-bold border transition-all {{ $grievance_priority === $pVal ? ($pVal === 'urgent' ? 'bg-rose-600 text-white border-rose-600 shadow-sm' : 'bg-slate-900 text-white border-slate-900 shadow-sm') : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                                    {{ $pLabel }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-800 mb-1">موضوع الطلب / الشكوى</label>
                        <input type="text" wire:model="grievance_title" placeholder="اكتب ملخصاً واضحاً في جملة واحدة..." class="w-full text-xs font-bold rounded-2xl border-slate-200 focus:border-brand-500 focus:ring-brand-500 py-3 px-4">
                        @error('grievance_title') <span class="text-rose-500 text-[11px] font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-800 mb-1">التفاصيل والشرح</label>
                        <textarea wire:model="grievance_content" rows="4" placeholder="اشرح المشكلة أو المقترح بالتفصيل لمساعدتنا في معالجتها بأسرع وقت..." class="w-full text-xs font-medium rounded-2xl border-slate-200 focus:border-brand-500 focus:ring-brand-500 p-4 leading-relaxed"></textarea>
                        @error('grievance_content') <span class="text-rose-500 text-[11px] font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Against Manager Checkbox -->
                    <div class="flex items-start gap-2.5 p-3 rounded-2xl bg-amber-50/70 border border-amber-200 text-xs">
                        <input type="checkbox" wire:model="grievance_against_manager" id="against_mgr" class="w-4 h-4 text-amber-600 rounded border-amber-300 focus:ring-amber-500 mt-0.5 cursor-pointer">
                        <label for="against_mgr" class="font-bold text-amber-950 cursor-pointer leading-relaxed">
                            الشكوى متعلقة بمديري المباشر (توجيهها مباشرة لإدارة الـ HR وحجبها تماماً عن المدير)
                        </label>
                    </div>

                    <!-- Attachment Upload -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">مستند أو صورة داعمة (اختياري)</label>
                        <input type="file" wire:model="grievance_attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="text-xs text-slate-500 file:mr-0 file:ml-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                        <span wire:loading wire:target="grievance_attachment" class="text-xs text-brand-600 block mt-1 animate-pulse">جاري تحميل الملف...</span>
                    </div>

                    <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                        <button type="button" wire:click="closeGrievanceModal" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold">
                            إلغاء
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold shadow-lg shadow-brand-500/25 flex items-center gap-1.5">
                            <span wire:loading.remove>إرسال الشكوى وتوثيقها</span>
                            <span wire:loading>جاري الإرسال...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- EVALUATION / SURVEY MODAL (تعبئة نموذج التقييم والاستبيان) -->
    @if($showEvaluationModal && $selectedForm)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fadeIn">
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-xl w-full p-6 sm:p-8 border border-slate-100 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <div>
                        <span class="text-[11px] font-black px-2.5 py-1 rounded-md bg-teal-50 text-teal-700 border border-teal-200/60">{{ $selectedForm->type_label }}</span>
                        <h3 class="text-base font-black text-slate-900 mt-1">{{ $selectedForm->title }}</h3>
                    </div>
                    <button type="button" wire:click="closeEvaluationModal" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {{-- Evaluated Person Selection (When evaluating someone else) --}}
                @if($selectedForm->type === 'manager_to_employee')
                    <div class="p-4 rounded-2xl bg-teal-50 border border-teal-200 mb-4 text-xs">
                        <label class="block font-black text-teal-950 mb-1.5">
                            اختر الموظف / الوكيل الخاضع للتقييم <span class="text-rose-500">*</span>
                        </label>
                        <select wire:model="evaluatingEmployeeId" class="w-full text-xs font-bold bg-white border border-teal-300 rounded-xl px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="">— اختر الموظف من القائمة —</option>
                            @foreach($allEmployees as $emp)
                                @if(!$employee || $emp->id !== $employee->id)
                                    <option value="{{ $emp->id }}">
                                        {{ $emp->full_name }} ({{ $emp->job_title ?: 'موظف' }}) - {{ $emp->department?->name ?? 'بدون قسم' }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                @elseif($selectedForm->type === 'employee_to_manager')
                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 mb-4 text-xs">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-black text-amber-950 flex items-center gap-1.5">
                                <span>🔒</span>
                                <span>تقييم سري للمدير المباشر (Upward Feedback)</span>
                            </span>
                            <span class="text-[10px] bg-amber-200 text-amber-900 font-extrabold px-2 py-0.5 rounded-md">مجهول الهوية 100%</span>
                        </div>
                        <p class="text-amber-800 text-[11px] leading-relaxed mb-2">
                            هويتك واسمك محجوبان بالكامل. يتم تجميع نتائج التقييم في مؤشرات عامة دون كشف صاحب التقييم.
                        </p>
                        <div>
                            <label class="block font-bold text-amber-950 mb-1">المدير الخاضع للتقييم:</label>
                            <select wire:model="evaluatingEmployeeId" class="w-full text-xs font-bold bg-white border border-amber-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                @if($employee && $employee->manager)
                                    <option value="{{ $employee->manager->id }}">
                                        {{ $employee->manager->full_name }} (مديري المباشر المسجل)
                                    </option>
                                @else
                                    <option value="">— اختر المدير —</option>
                                @endif
                                @foreach($allEmployees as $emp)
                                    @if(!$employee || ($emp->id !== $employee->id && (!$employee->manager || $emp->id !== $employee->manager->id)))
                                        <option value="{{ $emp->id }}">
                                            {{ $emp->full_name }} ({{ $emp->job_title ?: 'إدارة' }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="submitPortalEvaluation" class="space-y-5">
                    @if(is_array($selectedForm->questions))
                        @foreach($selectedForm->questions as $idx => $q)
                            @php 
                                $qKey = 'q_' . $idx; 
                                $currentAnswer = $evaluation_answers[$qKey] ?? null;
                            @endphp
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                                <label class="block text-xs font-black text-slate-800">
                                    {{ $idx + 1 }}. {{ $q['label'] ?? '' }}
                                    @if($q['required'] ?? true) <span class="text-rose-500">*</span> @endif
                                </label>

                                @if(($q['type'] ?? '') === 'rating_5')
                                    <div class="grid grid-cols-5 gap-1.5 sm:gap-2">
                                        @foreach([
                                            5 => ['num' => '5', 'label' => 'ممتاز'],
                                            4 => ['num' => '4', 'label' => 'جيد جداً'],
                                            3 => ['num' => '3', 'label' => 'جيد'],
                                            2 => ['num' => '2', 'label' => 'مقبول'],
                                            1 => ['num' => '1', 'label' => 'ضعيف'],
                                        ] as $starVal => $starData)
                                            <button type="button"
                                                    wire:click="$set('evaluation_answers.{{ $qKey }}', {{ $starVal }})"
                                                    class="py-2.5 px-1 rounded-xl border text-center transition-all cursor-pointer flex flex-col items-center justify-center gap-1 {{ (int)$currentAnswer === $starVal ? 'bg-amber-400 text-slate-950 font-black border-amber-500 shadow-md ring-2 ring-amber-300' : 'bg-white border-slate-200 text-slate-700 hover:border-slate-300 hover:bg-slate-100 font-bold' }}">
                                                <span class="text-base font-black font-mono leading-none">{{ $starData['num'] }}</span>
                                                <span class="text-[10px] leading-none">{{ $starData['label'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                @elseif(($q['type'] ?? '') === 'boolean')
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="button"
                                                wire:click="$set('evaluation_answers.{{ $qKey }}', 1)"
                                                class="py-2.5 px-4 rounded-xl border text-xs font-black transition cursor-pointer flex items-center justify-center gap-1.5 {{ (string)$currentAnswer === '1' ? 'bg-emerald-600 text-white border-emerald-700 shadow-sm' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                                            <span>✓</span>
                                            <span>نعم / محقق بالكامل</span>
                                        </button>
                                        <button type="button"
                                                wire:click="$set('evaluation_answers.{{ $qKey }}', 0)"
                                                class="py-2.5 px-4 rounded-xl border text-xs font-black transition cursor-pointer flex items-center justify-center gap-1.5 {{ (string)$currentAnswer === '0' ? 'bg-rose-600 text-white border-rose-700 shadow-sm' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                                            <span>✕</span>
                                            <span>لا / غير محقق</span>
                                        </button>
                                    </div>
                                @elseif(($q['type'] ?? '') === 'rating_10')
                                    <div class="grid grid-cols-10 gap-1">
                                        @for($i = 1; $i <= 10; $i++)
                                            <button type="button"
                                                    wire:click="$set('evaluation_answers.{{ $qKey }}', {{ $i }})"
                                                    class="py-2 text-center rounded-lg border text-xs font-black font-mono transition cursor-pointer {{ (int)$currentAnswer === $i ? 'bg-teal-600 text-white border-teal-700 shadow-xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                                                {{ $i }}
                                            </button>
                                        @endfor
                                    </div>
                                @else
                                    <textarea wire:model="evaluation_answers.{{ $qKey }}" rows="2" placeholder="اكتب إجابتك أو ملاحظاتك هنا..." class="w-full text-xs font-medium rounded-xl border-slate-300 focus:border-teal-500 focus:ring-teal-500 p-2.5"></textarea>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    <div class="space-y-3 pt-3 border-t border-slate-100">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">أبرز الإيجابيات ونقاط القوة (اختياري)</label>
                            <textarea wire:model="evaluation_strengths" rows="2" placeholder="ما هي النقاط الإيجابية البارزة؟" class="w-full text-xs font-medium rounded-xl border-slate-300 focus:border-teal-500 p-2.5"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">مقترحات وفرص التحسين والتطوير (اختياري)</label>
                            <textarea wire:model="evaluation_improvements" rows="2" placeholder="ما الذي يمكن تطويره أو تحسينه؟" class="w-full text-xs font-medium rounded-xl border-slate-300 focus:border-teal-500 p-2.5"></textarea>
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                        <button type="button" wire:click="closeEvaluationModal" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition cursor-pointer">
                            إلغاء
                        </button>
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white text-xs font-black shadow-md transition cursor-pointer flex items-center gap-2">
                            <span wire:loading.remove wire:target="submitPortalEvaluation">🚀 إرسال التقييم واحتساب النتيجة</span>
                            <span wire:loading wire:target="submitPortalEvaluation" class="inline-flex items-center gap-1.5">
                                <span class="animate-spin">⏳</span>
                                <span>جارٍ الحفظ والاحتساب...</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- EVALUATION DETAIL MODAL (عرض تفاصيل نتيجة التقييم) -->
    @if($showEvaluationDetailModal && $viewingEvaluation)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fadeIn">
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-xl w-full p-6 sm:p-8 border border-slate-100 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <div>
                        <span class="text-[11px] font-extrabold bg-teal-50 text-teal-700 px-2.5 py-1 rounded-md border border-teal-200/60">
                            نتيجة التقييم الرسمية
                        </span>
                        <h3 class="text-base font-black text-slate-900 mt-1">
                            {{ $viewingEvaluation->form?->title ?? 'تقييم أداء' }}
                        </h3>
                        <span class="text-xs text-slate-400 font-medium">فترة التقييم: {{ $viewingEvaluation->period ?? $viewingEvaluation->created_at->format('Y/m') }}</span>
                    </div>
                    <button type="button" wire:click="closeEvaluationDetailModal" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="bg-gradient-to-r from-teal-950 to-slate-900 rounded-2xl p-5 text-white flex items-center justify-between mb-5 shadow-sm">
                    <div>
                        <span class="text-xs text-teal-200 block mb-0.5">الدرجة الإجمالية المحتسبة:</span>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-2xl font-black font-mono text-amber-300">{{ number_format((float)$viewingEvaluation->score, 2) }}</span>
                            <span class="text-xs text-slate-300">من 5.0 ⭐</span>
                        </div>
                    </div>
                    <div class="text-left">
                        @php
                            $scoreVal = (float)$viewingEvaluation->score;
                            $ratingBadge = match(true) {
                                $scoreVal >= 4.5 => ['label' => 'أداء متميز (Excellent)', 'bg' => 'bg-emerald-500/20 text-emerald-300 border-emerald-400/30'],
                                $scoreVal >= 3.5 => ['label' => 'أداء جيد جداً (Very Good)', 'bg' => 'bg-teal-500/20 text-teal-300 border-teal-400/30'],
                                $scoreVal >= 2.5 => ['label' => 'أداء مرضي (Satisfactory)', 'bg' => 'bg-amber-500/20 text-amber-300 border-amber-400/30'],
                                default => ['label' => 'يحتاج تطوير وتحسين', 'bg' => 'bg-rose-500/20 text-rose-300 border-rose-400/30'],
                            };
                        @endphp
                        <span class="inline-block px-3 py-1 rounded-xl text-xs font-black border {{ $ratingBadge['bg'] }}">
                            {{ $ratingBadge['label'] }}
                        </span>
                        <span class="block text-[10px] text-slate-400 mt-1">الحالة: {{ $viewingEvaluation->status === 'reviewed' ? 'معتمد رسمياً ✓' : 'قيد المراجعة' }}</span>
                    </div>
                </div>

                <div class="space-y-3 mb-5">
                    <span class="text-xs font-black text-slate-700 block">تفاصيل درجات المعايير:</span>
                    @if($viewingEvaluation->form && is_array($viewingEvaluation->form->questions))
                        @foreach($viewingEvaluation->form->questions as $idx => $q)
                            @php
                                $qKey = 'q_' . $idx;
                                $answer = $viewingEvaluation->answers[$qKey] ?? null;
                                $type = $q['type'] ?? 'rating_5';
                            @endphp
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3 text-xs">
                                <span class="font-bold text-slate-800 flex-1">{{ $idx + 1 }}. {{ $q['label'] ?? '' }}</span>
                                <div>
                                    @if($type === 'rating_5')
                                        <span class="inline-flex items-center gap-1 font-mono font-black text-amber-900 bg-amber-100 px-2.5 py-1 rounded-lg">
                                            {{ $answer ?? '-' }} / 5 ⭐
                                        </span>
                                    @elseif($type === 'rating_10')
                                        <span class="inline-flex items-center gap-1 font-mono font-black text-teal-900 bg-teal-100 px-2.5 py-1 rounded-lg">
                                            {{ $answer ?? '-' }} / 10
                                        </span>
                                    @elseif($type === 'boolean')
                                        <span class="font-bold {{ $answer ? 'text-emerald-700 bg-emerald-100' : 'text-rose-700 bg-rose-100' }} px-2.5 py-1 rounded-lg">
                                            {{ $answer ? 'محقق ✓' : 'غير محقق ✕' }}
                                        </span>
                                    @else
                                        <span class="text-slate-600 italic">{{ $answer ?: 'لا توجد ملاحظات' }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="space-y-3 text-xs border-t border-slate-100 pt-4">
                    @if($viewingEvaluation->strengths)
                        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200">
                            <strong class="font-bold text-emerald-950 block mb-1">أبرز نقاط القوة والإنجازات:</strong>
                            <p class="text-emerald-900 leading-relaxed font-medium">{{ $viewingEvaluation->strengths }}</p>
                        </div>
                    @endif

                    @if($viewingEvaluation->areas_for_improvement)
                        <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200">
                            <strong class="font-bold text-amber-950 block mb-1">مجالات التحسين والتطوير:</strong>
                            <p class="text-amber-900 leading-relaxed font-medium">{{ $viewingEvaluation->areas_for_improvement }}</p>
                        </div>
                    @endif

                    @if($viewingEvaluation->action_plan)
                        <div class="p-3.5 rounded-xl bg-sky-50 border border-sky-200">
                            <strong class="font-bold text-sky-950 block mb-1">خطة العمل التطويرية:</strong>
                            <p class="text-sky-900 leading-relaxed font-medium">{{ $viewingEvaluation->action_plan }}</p>
                        </div>
                    @endif
                </div>

                <div class="pt-5 border-t border-slate-100 mt-5 flex justify-end">
                    <button type="button" wire:click="closeEvaluationDetailModal" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition cursor-pointer">
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Mobile Bottom Navigation Bar (Visible on mobile only) -->
    <nav class="sm:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200/90 z-40 px-2 py-2 flex items-center justify-around shadow-lg">
        <button wire:click="setTab('overview')" class="flex flex-col items-center gap-1 p-1 text-xs font-bold {{ $activeTab === 'overview' ? 'text-brand-600' : 'text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            <span class="text-[10px]">الرئيسية</span>
        </button>

        <button wire:click="setTab('attendance')" class="flex flex-col items-center gap-1 p-1 text-xs font-bold {{ $activeTab === 'attendance' ? 'text-brand-600' : 'text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="text-[10px]">البصمة</span>
        </button>

        <button wire:click="setTab('leaves')" class="flex flex-col items-center gap-1 p-1 text-xs font-bold {{ $activeTab === 'leaves' ? 'text-brand-600' : 'text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            <span class="text-[10px]">الإجازات</span>
        </button>

        <button wire:click="setTab('grievances')" class="flex flex-col items-center gap-1 p-1 text-xs font-bold {{ $activeTab === 'grievances' ? 'text-brand-600' : 'text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            <span class="text-[10px]">الشكاوى</span>
        </button>

        <button wire:click="setTab('evaluations')" class="flex flex-col items-center gap-1 p-1 text-xs font-bold {{ $activeTab === 'evaluations' ? 'text-brand-600' : 'text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
            <span class="text-[10px]">التقييمات</span>
        </button>

        <button wire:click="setTab('profile')" class="flex flex-col items-center gap-1 p-1 text-xs font-bold {{ $activeTab === 'profile' ? 'text-brand-600' : 'text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            <span class="text-[10px]">حسابي</span>
        </button>
    </nav>
</div>
