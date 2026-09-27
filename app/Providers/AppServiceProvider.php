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
        //
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

        // إعطاء صلاحيات كاملة للـ Super Admin
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
