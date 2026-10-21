<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_employee');
    }

    public function view(User $user, Employee $model): bool
    {
        return $user->can('view_employee');
    }

    public function create(User $user): bool
    {
        return $user->can('create_employee');
    }

    public function update(User $user, Employee $model): bool
    {
        return $user->can('update_employee');
    }

    public function delete(User $user, Employee $model): bool
    {
        return $user->can('delete_employee');
    }
}
