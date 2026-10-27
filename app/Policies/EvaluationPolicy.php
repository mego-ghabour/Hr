<?php

namespace App\Policies;

use App\Models\Evaluation;
use App\Models\User;

class EvaluationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Evaluation $model): bool
    {
        // الأدمن أو مسؤول HR يرى كل التقييمات
        if ($user->isAdministrative()) {
            return true;
        }

        // المقيم يرى تقييمه
        if ($model->evaluator_id === $user->id) {
            return true;
        }

        // الموظف الخاضع للتقييم يرى التقييم إذا لم يكن مسودة
        if ($user->employee && $model->evaluated_employee_id === $user->employee->id && $model->status !== 'draft') {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Evaluation $model): bool
    {
        if ($user->isAdministrative()) {
            return true;
        }

        // فقط صاحب التقييم يمكنه تعديله إذا كان لا يزال مسودة أو مقدم مؤخراً
        return $model->evaluator_id === $user->id && $model->status === 'draft';
    }

    public function delete(User $user, Evaluation $model): bool
    {
        return $user->isAdministrative();
    }
}
