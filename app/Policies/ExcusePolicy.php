<?php

namespace App\Policies;

use App\Models\Excuse;
use App\Models\User;

class ExcusePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_excuse');
    }

    public function view(User $user, Excuse $model): bool
    {
        return $user->can('view_excuse');
    }

    public function create(User $user): bool
    {
        return $user->can('create_excuse');
    }

    public function update(User $user, Excuse $model): bool
    {
        return $user->can('update_excuse');
    }

    public function delete(User $user, Excuse $model): bool
    {
        return $user->can('delete_excuse');
    }
}
