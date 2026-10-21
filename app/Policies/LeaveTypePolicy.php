<?php

namespace App\Policies;

use App\Models\LeaveType;
use App\Models\User;

class LeaveTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_leave_type');
    }

    public function view(User $user, LeaveType $model): bool
    {
        return $user->can('view_leave_type');
    }

    public function create(User $user): bool
    {
        return $user->can('create_leave_type');
    }

    public function update(User $user, LeaveType $model): bool
    {
        return $user->can('update_leave_type');
    }

    public function delete(User $user, LeaveType $model): bool
    {
        return $user->can('delete_leave_type');
    }
}
