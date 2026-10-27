<?php

namespace App\Policies;

use App\Models\Grievance;
use App\Models\User;

class GrievancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdministrative();
    }

    public function view(User $user, Grievance $model): bool
    {
        if ($user->isAdministrative()) {
            return true;
        }

        return $model->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Grievance $model): bool
    {
        return $user->isAdministrative();
    }

    public function delete(User $user, Grievance $model): bool
    {
        return $user->isAdministrative();
    }
}
