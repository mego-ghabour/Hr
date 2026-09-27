<?php

namespace App\Policies;

use App\Models\TalentStatus;
use App\Models\User;

class TalentStatusPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_status');
    }

    public function view(User $user, TalentStatus $model): bool
    {
        return $user->hasPermissionTo('view_status');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_status');
    }

    public function update(User $user, TalentStatus $model): bool
    {
        return $user->hasPermissionTo('update_status');
    }

    public function delete(User $user, TalentStatus $model): bool
    {
        return $user->hasPermissionTo('delete_status');
    }
}