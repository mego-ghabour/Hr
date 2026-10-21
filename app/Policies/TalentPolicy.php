<?php

namespace App\Policies;

use App\Models\Talent;
use App\Models\User;

class TalentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_talent');
    }

    public function view(User $user, Talent $model): bool
    {
        return $user->can('view_talent');
    }

    public function create(User $user): bool
    {
        return $user->can('create_talent');
    }

    public function update(User $user, Talent $model): bool
    {
        return $user->can('update_talent');
    }

    public function delete(User $user, Talent $model): bool
    {
        return $user->can('delete_talent');
    }
}
