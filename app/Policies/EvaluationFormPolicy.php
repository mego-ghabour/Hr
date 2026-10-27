<?php

namespace App\Policies;

use App\Models\EvaluationForm;
use App\Models\User;

class EvaluationFormPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdministrative();
    }

    public function view(User $user, EvaluationForm $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdministrative() || $user->hasRole(['مدير الموارد البشرية', 'HR Manager', 'Super Admin', 'admin']);
    }

    public function update(User $user, EvaluationForm $model): bool
    {
        return $user->isAdministrative() || $user->hasRole(['مدير الموارد البشرية', 'HR Manager', 'Super Admin', 'admin']);
    }

    public function delete(User $user, EvaluationForm $model): bool
    {
        return $user->isAdministrative() || $user->hasRole(['مدير الموارد البشرية', 'HR Manager', 'Super Admin', 'admin']);
    }
}
