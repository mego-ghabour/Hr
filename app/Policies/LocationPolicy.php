<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;

class LocationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_location');
    }

    public function view(User $user, Location $model): bool
    {
        return $user->hasPermissionTo('view_location');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_location');
    }

    public function update(User $user, Location $model): bool
    {
        return $user->hasPermissionTo('update_location');
    }

    public function delete(User $user, Location $model): bool
    {
        return $user->hasPermissionTo('delete_location');
    }
}