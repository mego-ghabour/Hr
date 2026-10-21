<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_announcement');
    }

    public function view(User $user, Announcement $model): bool
    {
        return $user->can('view_announcement');
    }

    public function create(User $user): bool
    {
        return $user->can('create_announcement');
    }

    public function update(User $user, Announcement $model): bool
    {
        return $user->can('update_announcement');
    }

    public function delete(User $user, Announcement $model): bool
    {
        return $user->can('delete_announcement');
    }
}
