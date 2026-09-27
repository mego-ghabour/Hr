<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    public function viewAny(User $user): bool
    {
        \Illuminate\Support\Facades\Log::info("DepartmentPolicy::viewAny called for: " . $user->email . " - Result: " . ($user->hasPermissionTo('view_any_department') ? 'true' : 'false'));
        return $user->hasPermissionTo('view_any_department');
    }

    public function view(User $user, Department $model): bool
    {
        return $user->hasPermissionTo('view_department');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_department');
    }

    public function update(User $user, Department $model): bool
    {
        return $user->hasPermissionTo('update_department');
    }

    public function delete(User $user, Department $model): bool
    {
        return $user->hasPermissionTo('delete_department');
    }
}