<?php

namespace App\Policies;

use App\Models\AvailabilityOption;
use App\Models\User;

class AvailabilityOptionPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, AvailabilityOption $model): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, AvailabilityOption $model): bool
    {
        return false;
    }

    public function delete(User $user, AvailabilityOption $model): bool
    {
        return false;
    }
}
