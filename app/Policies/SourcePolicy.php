<?php

namespace App\Policies;

use App\Models\Source;
use App\Models\User;

class SourcePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_source');
    }

    public function view(User $user, Source $model): bool
    {
        return $user->can('view_source');
    }

    public function create(User $user): bool
    {
        return $user->can('create_source');
    }

    public function update(User $user, Source $model): bool
    {
        return $user->can('update_source');
    }

    public function delete(User $user, Source $model): bool
    {
        return $user->can('delete_source');
    }
}
