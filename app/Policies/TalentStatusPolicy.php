<?php

namespace App\Policies;

use App\Models\TalentStatus;
use App\Models\User;

class TalentStatusPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_status');
    }

    public function view(User $user, TalentStatus $model): bool
    {
        return $user->can('view_status');
    }

    public function create(User $user): bool
    {
        return $user->can('create_status');
    }

    public function update(User $user, TalentStatus $model): bool
    {
        return $user->can('update_status');
    }

    public function delete(User $user, TalentStatus $model): bool
    {
        return $user->can('delete_status');
    }
}
