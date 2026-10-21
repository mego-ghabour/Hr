@extends('layouts.portal')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 relative overflow-hidden">
    
    <!-- Decorative Glowing Orbs -->
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo and Branding -->
        <div class="text-center mb-8">
            @php
                $brandName = \App\Models\Setting::get('brand_name', 'نظام الموارد البشرية');
                $logo = \App\Models\Setting::get('logo');
            @endphp

            @if($logo)
                <div class="inline-flex items-center justify-center p-3 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 shadow-2xl mb-4">
                    <img src="{{ str_starts_with($logo, 'http') || str_starts_with($logo, '/') ? $logo : \Illuminate\Support\Facades\Storage::disk('public')->url($logo) }}" 
                         alt="{{ $brandName }}" 
                         class="h-14 w-auto object-contain">
                </div>
            @else
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-tr from-brand-600 to-indigo-500 text-white rounded-2xl shadow-xl shadow-indigo-500/30 mb-4 border border-white/10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            @endif

            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                {{ $brandName }}
            </h1>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 mt-2.5 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-200 border border-indigo-400/30">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                بوابة الخدمة الذاتية للموظفين (ESS)
            </div>
            <p class="text-slate-300 text-sm mt-3">
                سجّل دخولك لمتابعة الحضور، الإجازات، الاستئذانات ومفردات الراتب
            </p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-6 sm:p-8 border border-white/40 shadow-indigo-950/50">
            
            @if(session('success'))
                <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                    <ul class="space-y-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('portal.login.submit') }}" method="POST" class="space-y-5" x-data="{ showPassword: false }">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-1.5">
                        البريد الإلكتروني المهني
                    </label>
                    <div class="relative">
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               placeholder="employee@company.com" 
                               class="w-full px-4 py-3 pr-11 rounded-xl border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all text-sm bg-slate-50/50">
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5">
                        كلمة المرور
                    </label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" 
                               id="password" 
                               name="password" 
                               required 
                               placeholder="••••••••" 
                               class="w-full px-4 py-3 pr-11 pl-11 rounded-xl border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all text-sm bg-slate-50/50">
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <button type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                        <span class="text-slate-600 text-xs sm:text-sm">تذكرني على هذا الجهاز</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/25 transition-all transform active:scale-[0.99] flex items-center justify-center gap-2">
                    <span>تسجيل الدخول إلى البوابة</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>

            <!-- Admin Panel Shortcut -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <a href="/admin/login" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>الدخول كمسؤول أو مدير نظام (Filament Admin)</span>
                </a>
            </div>
        </div>

        <!-- Privacy & Protection Note -->
        <p class="text-center text-xs text-slate-400 mt-6">
            بياناتك مشفرة ومحمية وفق أعلى معايير أمن المعلومات والخصوصية.
        </p>
    </div>
</div>
@endsection
