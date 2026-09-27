<?php

namespace App\Filament\Admin\Widgets;

use App\Models\TalentStatus;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;

class ConversionRateChartWidget extends ChartWidget
{
    protected int | string | array $columnStart = '';

    public static function canView(): bool
    {
        return auth()->user()->hasPermissionTo('view_any_talent');
    }

    use InteractsWithPageFilters;

    protected ?string $heading = 'معدل التحويل وحالة المرشحين';
    protected static ?int $sort = 5;
    protected static bool $isLazy = false;
    protected int | string | array $columnSpan = 1;
    protected ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        $departmentId = $this->filters['department_id'] ?? null;

        $statuses = TalentStatus::withCount(['talents' => function ($query) use ($startDate, $endDate, $departmentId) {
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
        ->orderBy('order_column')
        ->get();

        // Extracting data
        $labels = $statuses->pluck('name')->toArray();
        $data = $statuses->pluck('talents_count')->toArray();
        $colors = $statuses->pluck('color')->map(function ($color) {
            // Mapping filament colors to hex for chart.js if they are named, otherwise generating random or using defaults.
            $colorMap = [
                'primary' => '#3b82f6',
                'success' => '#22c55e',
                'warning' => '#eab308',
                'danger' => '#ef4444',
                'info' => '#0ea5e9',
                'gray' => '#6b7280',
            ];
            return $colorMap[$color] ?? $color ?? '#3b82f6';
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'المرشحين',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => $labels,
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
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'cutout' => '70%',
        ];
    }
}
