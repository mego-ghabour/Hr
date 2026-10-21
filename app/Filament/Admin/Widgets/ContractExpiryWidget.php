<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Employee;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class ContractExpiryWidget extends Widget
{
    protected static bool $isLazy = false;
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    protected string $view = 'filament.admin.widgets.contract-expiry-widget';

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['Super Admin', 'admin', 'manager']) || $user->can('view_any_employee'));
    }

    protected function getViewData(): array
    {
        $alertDays = (int) \App\Models\Setting::get('alert_contract_expiry_days', 60);
        $thresholdDate = Carbon::today()->addDays($alertDays);

        $expiringEmployees = Employee::with('department')
            ->where('status', 'active')
            ->whereNotNull('contract_end_date')
            ->where('contract_end_date', '<=', $thresholdDate)
            ->orderBy('contract_end_date', 'asc')
            ->take(6)
            ->get()
            ->map(function ($emp) {
                $endDate = Carbon::parse($emp->contract_end_date)->startOfDay();
                $today = Carbon::today()->startOfDay();
                $days = (int) $today->diffInDays($endDate, false);

                return [
                    'id' => $emp->id,
                    'name' => "{$emp->first_name} {$emp->last_name}",
                    'job_title' => $emp->job_title ?? 'موظف',
                    'department' => $emp->department?->name ?? 'غير محدد',
                    'contract_end_date' => $emp->contract_end_date,
                    'days_remaining' => $days,
                    'is_expired' => $days < 0,
                ];
            });

        return [
            'expiringEmployees' => $expiringEmployees,
        ];
    }
}
