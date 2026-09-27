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
        return auth()->user()->hasPermissionTo('view_any_talent');
    }

    use InteractsWithPageFilters;

    protected ?string $heading = 'أداء مصادر التوظيف';
    protected static ?int $sort = 4;
    protected static bool $isLazy = false;
    protected int | string | array $columnSpan = 2;
    protected ?string $maxHeight = '320px';

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
                    'backgroundColor' => '#3b82f6', // blue-500
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $sources->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
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
