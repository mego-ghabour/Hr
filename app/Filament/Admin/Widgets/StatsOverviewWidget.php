<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Talent;
use App\Models\TalentFollowUp;
use App\Models\Duplicate;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

use Filament\Widgets\Concerns\InteractsWithPageFilters;

class StatsOverviewWidget extends BaseWidget
{
    protected int | string | array $columnStart = '';

    public static function canView(): bool
    {
        return auth()->user()->hasPermissionTo('view_any_talent');
    }
    use InteractsWithPageFilters;

    protected static bool $isLazy = false;
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $talentQuery = Talent::query();
        $followUpQuery = TalentFollowUp::query();
        $duplicateQuery = Duplicate::query();

        if ($startDate) {
            $talentQuery->whereDate('created_at', '>=', $startDate);
            $followUpQuery->whereDate('created_at', '>=', $startDate);
            $duplicateQuery->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $talentQuery->whereDate('created_at', '<=', $endDate);
            $followUpQuery->whereDate('created_at', '<=', $endDate);
            $duplicateQuery->whereDate('created_at', '<=', $endDate);
        }

        $departmentId = $this->filters['department_id'] ?? null;
        if ($departmentId) {
            $talentQuery->where('department_id', $departmentId);
        }

        $totalTalents     = $talentQuery->count();
        $newThisMonth     = (clone $talentQuery)->whereMonth('created_at', Carbon::now()->month)
                                  ->whereYear('created_at', Carbon::now()->year)
                                  ->count();
        $pendingFollowUps = (clone $followUpQuery)->where('status', 'pending')->count();
        $overdueFollowUps = (clone $followUpQuery)->where('status', 'pending')
                                           ->where('follow_up_date', '<', now())
                                           ->count();
        $potentialDuplicates = $duplicateQuery->where('status', 'pending')->count();

        // Build sparkline from last 7 days
        $spark = [];
        for ($i = 6; $i >= 0; $i--) {
            $spark[] = Talent::whereDate('created_at', Carbon::now()->subDays($i))->count();
        }

        return [
            Stat::make('إجمالي المرشحين', number_format($totalTalents))
                ->description('المرشحون المسجلون في النظام')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart($spark),

            Stat::make('مضافون هذا الشهر', number_format($newThisMonth))
                ->description('في ' . Carbon::now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([1, 3, 2, 5, 4, 7, $newThisMonth]),

            Stat::make('متابعات معلقة', number_format($pendingFollowUps))
                ->description($overdueFollowUps > 0
                    ? $overdueFollowUps . ' متابعة متأخرة ⚠️'
                    : 'لا توجد تأخيرات ✅')
                ->descriptionIcon('heroicon-m-clock')
                ->color($overdueFollowUps > 0 ? 'danger' : 'warning'),

            Stat::make('ملفات مكررة محتملة', number_format($potentialDuplicates))
                ->description('تحتاج مراجعة يدوية')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color($potentialDuplicates > 0 ? 'danger' : 'success'),
        ];
    }
}
