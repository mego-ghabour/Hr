<?php

namespace App\Policies;

use App\Models\JobPosting;
use App\Models\User;

class JobPostingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_job_posting');
    }

    public function view(User $user, JobPosting $model): bool
    {
        return $user->can('view_job_posting');
    }

    public function create(User $user): bool
    {
        return $user->can('create_job_posting');
    }

    public function update(User $user, JobPosting $model): bool
    {
        return $user->can('update_job_posting');
    }

    public function delete(User $user, JobPosting $model): bool
    {
        return $user->can('delete_job_posting');
    }
}
