<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Filament\Auth\Http\Responses\Contracts\LoginResponse::class,
            \App\Http\Responses\FilamentLoginResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\Talent::observe(\App\Observers\TalentObserver::class);

        \Illuminate\Support\Facades\Gate::policy(\Spatie\Permission\Models\Role::class, \App\Policies\RolePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Department::class, \App\Policies\DepartmentPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Location::class, \App\Policies\LocationPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Source::class, \App\Policies\SourcePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\TalentStatus::class, \App\Policies\TalentStatusPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\AvailabilityOption::class, \App\Policies\AvailabilityOptionPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Duplicate::class, \App\Policies\DuplicatePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Talent::class, \App\Policies\TalentPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Employee::class, \App\Policies\EmployeePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Attendance::class, \App\Policies\AttendancePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Leave::class, \App\Policies\LeavePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\LeaveType::class, \App\Policies\LeaveTypePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Excuse::class, \App\Policies\ExcusePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\JobPosting::class, \App\Policies\JobPostingPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Announcement::class, \App\Policies\AnnouncementPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Payroll::class, \App\Policies\PayrollPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\EvaluationForm::class, \App\Policies\EvaluationFormPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Evaluation::class, \App\Policies\EvaluationPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Grievance::class, \App\Policies\GrievancePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\Spatie\Activitylog\Models\Activity::class, \App\Policies\ActivityPolicy::class);

        // إعطاء صلاحيات كاملة للـ Super Admin والـ admin لمنع أي تعليق في الصلاحيات
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return ($user->hasRole(['Super Admin', 'admin', 'المدير العام', 'مسؤول النظام'])) ? true : null;
        });

        // مزامنة التوقيت والعملة الافتراضية مع إعدادات النظام ديناميكياً
        try {
            $timezone = \App\Models\Setting::timezone();
            if (!empty($timezone)) {
                date_default_timezone_set($timezone);
                config(['app.timezone' => $timezone]);
            }
        } catch (\Throwable) {
            // تجاهل الخطأ في حالة عدم اكتمال تثبيت قاعدة البيانات
        }

        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            try {
                $view->with('currentCurrency', \App\Models\Setting::currency());
                $view->with('currentCurrencySymbol', \App\Models\Setting::currencySymbol());
                $view->with('currentTimezone', \App\Models\Setting::timezone());
                $view->with('currentDateFormat', \App\Models\Setting::dateFormat());
            } catch (\Throwable) {
                $view->with('currentCurrency', 'EGP');
                $view->with('currentCurrencySymbol', 'ج.م');
                $view->with('currentTimezone', 'Africa/Cairo');
                $view->with('currentDateFormat', 'Y-m-d');
            }
        });
    }
}
