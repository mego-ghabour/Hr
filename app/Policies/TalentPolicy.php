<?php

namespace App\Policies;

use App\Models\Talent;
use App\Models\User;

class TalentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_talent');
    }

    public function view(User $user, Talent $model): bool
    {
        return $user->hasPermissionTo('view_talent');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_talent');
    }

    public function update(User $user, Talent $model): bool
    {
        return $user->hasPermissionTo('update_talent');
    }

    public function delete(User $user, Talent $model): bool
    {
        return $user->hasPermissionTo('delete_talent');
    }
}