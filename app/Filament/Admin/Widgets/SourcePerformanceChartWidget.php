<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Source;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;

class SourcePerformanceChartWidget extends ChartWidget
{
    protected int | string | array $columnStart = '';

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['Super Admin', 'admin']) || $user->can('view_any_talent'));
    }

    use InteractsWithPageFilters;

    protected ?string $heading = 'أداء مصادر التوظيف';
    protected static ?int $sort = 6;
    protected static bool $isLazy = false;
    protected int | string | array $columnSpan = 1;
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        $departmentId = $this->filters['department_id'] ?? null;

        // Get sources with their talent counts
        $sources = Source::withCount(['talents' => function ($query) use ($startDate, $endDate, $departmentId) {
            if ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            }
            if ($departmentId) {
                $query->where('department_id', $departmentId);
            }
        }])
        ->orderByDesc('talents_count')
        ->get();

        return [
            'datasets' => [
                [
                    'label' => 'عدد المتقدمين',
                    'data' => $sources->pluck('talents_count')->toArray(),
                    'backgroundColor' => ['#0d9488', '#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981'], 'borderWidth' => 0,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $sources->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => true, 'position' => 'bottom'],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => ['stepSize' => 1, 'precision' => 0],
                ],
                'x' => [
                    'grid' => ['display' => false],
                ],
            ],
        ];
    }
}

