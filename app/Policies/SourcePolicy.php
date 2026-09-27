<?php

namespace App\Policies;

use App\Models\Source;
use App\Models\User;

class SourcePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_source');
    }

    public function view(User $user, Source $model): bool
    {
        return $user->hasPermissionTo('view_source');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_source');
    }

    public function update(User $user, Source $model): bool
    {
        return $user->hasPermissionTo('update_source');
    }

    public function delete(User $user, Source $model): bool
    {
        return $user->hasPermissionTo('delete_source');
    }
}