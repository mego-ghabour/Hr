<?php

namespace App\Policies;

use App\Models\Duplicate;
use App\Models\User;

class DuplicatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_duplicate');
    }

    public function view(User $user, Duplicate $model): bool
    {
        return $user->hasPermissionTo('view_duplicate');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_duplicate');
    }

    public function update(User $user, Duplicate $model): bool
    {
        return $user->hasPermissionTo('update_duplicate');
    }

    public function delete(User $user, Duplicate $model): bool
    {
        return $user->hasPermissionTo('delete_duplicate');
    }
}