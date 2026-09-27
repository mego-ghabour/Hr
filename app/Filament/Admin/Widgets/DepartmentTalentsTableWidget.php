<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Department;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class DepartmentTalentsTableWidget extends BaseWidget
{
    protected int | string | array $columnStart = '';

    public static function canView(): bool
    {
        return auth()->user()->hasPermissionTo('view_any_talent');
    }
    use InteractsWithPageFilters;

    protected static bool $isLazy = false;
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $departmentId = $this->filters['department_id'] ?? null;

        return $table
            ->query(
                Department::query()->withCount(['talents' => function (Builder $query) use ($startDate, $endDate) {
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
            )
            ->columns([
                TextColumn::make('name')
                    ->label('القسم')
                    ->icon('heroicon-o-building-office-2')
                    ->weight('semibold')
                    ->searchable(),

                TextColumn::make('talents_count')
                    ->label('العدد')
                    ->badge()
                    ->color(fn (int $state) => match(true) {
                        $state === 0 => 'gray',
                        $state < 5   => 'info',
                        $state < 15  => 'warning',
                        default      => 'success',
                    })
                    ->sortable()
                    ->alignEnd(),
            ])
            ->heading('الأقسام حسب المرشحين')
            ->description('ترتيب الأقسام من الأعلى للأدنى')
            ->defaultSort('talents_count', 'desc')
            ->paginated([5, 10, 'all'])
            ->defaultPaginationPageOption(5)
            ->striped()
            ->emptyStateHeading('لا توجد أقسام')
            ->emptyStateIcon('heroicon-o-building-office-2');
    }
}
