<?php

namespace App\Policies;

use App\Models\Duplicate;
use App\Models\User;

class DuplicatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_duplicate');
    }

    public function view(User $user, Duplicate $model): bool
    {
        return $user->can('view_duplicate');
    }

    public function create(User $user): bool
    {
        return $user->can('create_duplicate');
    }

    public function update(User $user, Duplicate $model): bool
    {
        return $user->can('update_duplicate');
    }

    public function delete(User $user, Duplicate $model): bool
    {
        return $user->can('delete_duplicate');
    }
}
