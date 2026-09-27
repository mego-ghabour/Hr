<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Department;
use Filament\Widgets\ChartWidget;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class DepartmentTalentsChartWidget extends ChartWidget
{
    protected int | string | array $columnStart = '';

    public static function canView(): bool
    {
        return auth()->user()->hasPermissionTo('view_any_talent');
    }
    use InteractsWithPageFilters;

    protected static bool $isLazy = false;
    protected ?string $heading = 'توزيع المرشحين حسب الأقسام';
    protected ?string $description = 'عدد المرشحين المسجلين في كل قسم';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 2;

    protected ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $departmentId = $this->filters['department_id'] ?? null;

        $departments = Department::withCount(['talents' => function (Builder $query) use ($startDate, $endDate) {
            if ($startDate) {
                $query->whereDate('date_added', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('date_added', '<=', $endDate);
            }
        }])
            ->when($departmentId, function ($query, $departmentId) {
                $query->where('id', $departmentId);
            })
            ->orderBy('talents_count', 'desc')
            ->limit(10)
            ->get();

        $colors = [
            '#1e4d6e', '#2d6a96', '#3d87be', '#5ba3d9', '#85bbea',
            '#1a6e5c', '#2a9375', '#3dbb96', '#62d4b4', '#95e4ce',
        ];

        return [
            'datasets' => [
                [
                    'label'           => 'عدد المرشحين',
                    'data'            => $departments->pluck('talents_count')->toArray(),
                    'backgroundColor' => array_slice($colors, 0, count($departments)),
                    'borderColor'     => array_slice($colors, 0, count($departments)),
                    'borderRadius'    => 8,
                    'borderSkipped'   => false,
                ],
            ],
            'labels' => $departments->pluck('name')->toArray(),
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
                'tooltip' => [
                    'callbacks' => [],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => ['stepSize' => 1, 'precision' => 0],
                    'grid'        => ['color' => 'rgba(0,0,0,0.05)'],
                ],
                'x' => [
                    'grid' => ['display' => false],
                ],
            ],
            'animation' => [
                'duration' => 800,
                'easing'   => 'easeInOutQuart',
            ],
        ];
    }
}
