<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use App\Models\Talent;
use App\Models\TalentFollowUp;
use App\Models\Duplicate;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class HrOverviewWidget extends Widget implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithPageFilters, InteractsWithForms {
        InteractsWithForms::__get insteadof InteractsWithPageFilters;
    }

    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected string $view = 'filament.admin.widgets.hr-overview-widget';

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['Super Admin', 'admin', 'المدير العام', 'مسؤول النظام', 'مدير الموارد البشرية', 'مدير قسم']) || $user->can('view_any_employee') || $user->can('view_any_talent'));
    }

    protected function getViewData(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        $departmentId = $this->filters['department_id'] ?? null;

        $talentQuery = Talent::query();
        $employeeQuery = Employee::query();
        $leaveQuery = Leave::query();

        if ($startDate) {
            $talentQuery->whereDate('created_at', '>=', $startDate);
            $employeeQuery->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $talentQuery->whereDate('created_at', '<=', $endDate);
            $employeeQuery->whereDate('created_at', '<=', $endDate);
        }

        if ($departmentId) {
            $talentQuery->where('department_id', $departmentId);
            $employeeQuery->where('department_id', $departmentId);
        }

        // Stats for the cards (Nathret Al Youm - Today's Overview)
        $totalEmployees = Employee::where('status', 'active')->count();
        $totalTalents = $talentQuery->count();
        $pendingLeaves = Leave::where('status', 'pending')->count();
        $overdueFollowUps = TalentFollowUp::where('status', 'pending')->where('follow_up_date', '<', now())->count();
        
        $todayAttendanceRatio = 0; // Simplified for UI demonstration

        // Department movement table data
        $departments = \App\Models\Department::withCount(['talents as pending_talents_count' => function ($query) {
            // Simplified status check
            $query->whereHas('status', function($q) {
                $q->where('name', '!=', 'مرفوض');
            });
        }])->get();

        $departmentStats = $departments->map(function ($dept) {
            $activeEmp = Employee::where('department_id', $dept->id)->where('status', 'active')->count();
            return [
                'name' => $dept->name,
                'manager' => 'مدير القسم', // Placeholder
                'pending_candidates' => $dept->pending_talents_count,
                'active_employees' => $activeEmp,
                'status' => 'طبيعي'
            ];
        });

        return [
            'totalEmployees' => $totalEmployees,
            'totalTalents' => $totalTalents,
            'pendingLeaves' => $pendingLeaves,
            'overdueFollowUps' => $overdueFollowUps,
            'departmentStats' => $departmentStats,
        ];
    }

    public function addEmployeeAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\CreateAction::make('addEmployee')
            ->label('إضافة موظف')
            ->model(Employee::class)
            ->form(\App\Filament\Admin\Resources\Employees\Schemas\EmployeeForm::schema())
            ->slideOver();
    }
}
