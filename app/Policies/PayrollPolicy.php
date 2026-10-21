<?php

namespace App\Policies;

use App\Models\Payroll;
use App\Models\User;

class PayrollPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_payroll');
    }

    public function view(User $user, Payroll $model): bool
    {
        return $user->can('view_payroll');
    }

    public function create(User $user): bool
    {
        return $user->can('create_payroll');
    }

    public function update(User $user, Payroll $model): bool
    {
        return $user->can('update_payroll');
    }

    public function delete(User $user, Payroll $model): bool
    {
        return $user->can('delete_payroll');
    }
}
