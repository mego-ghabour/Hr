<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Talent;
use App\Filament\Admin\Resources\TalentResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTalentsWidget extends BaseWidget
{
    protected int | string | array $columnStart = '';

    public static function canView(): bool
    {
        return auth()->user()->hasPermissionTo('view_any_talent');
    }
    use \Filament\Widgets\Concerns\InteractsWithPageFilters;

    protected static bool $isLazy = false;
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'آخر المرشحين المضافين';

    public function table(Table $table): Table
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        $departmentId = $this->filters['department_id'] ?? null;

        return $table
            ->query(
                Talent::query()
                    ->with(['department', 'status'])
                    ->when($startDate, fn($query) => $query->whereDate('date_added', '>=', $startDate))
                    ->when($endDate, fn($query) => $query->whereDate('date_added', '<=', $endDate))
                    ->when($departmentId, fn($query) => $query->where('department_id', $departmentId))
                    ->latest('date_added')
                    ->limit(8)
            )
            ->columns([
                Tables\Columns\TextColumn::make('talent_id')
                    ->label('#')
                    ->fontFamily('mono')
                    ->size('sm')
                    ->color('gray')
                    ->width('90px'),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('المرشح')
                    ->weight('bold')
                    ->description(fn($record) => $record->current_job_title ?? $record->current_company)
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->copyMessage('تم نسخ البريد')
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('department.name')
                    ->label('القسم')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('status.name')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn($record) => $record->status?->color ?? 'gray'),

                Tables\Columns\TextColumn::make('date_added')
                    ->label('تاريخ الإضافة')
                    ->date('d/m/Y')
                    ->since()
                    ->sortable()
                    ->description(fn($record) => $record->date_added?->format('d/m/Y')),
            ])
            ->actions([
                \Filament\Actions\Action::make('view')
                    ->label('عرض')
                    ->icon('heroicon-m-eye')
                    ->color('primary')
                    ->url(fn(Talent $record) => TalentResource::getUrl('view', ['record' => $record])),
            ])
            ->paginated(false)
            ->contentGrid([
                'md' => 2,
                'xl' => 2,
            ])
            ->emptyStateHeading('لا يوجد مرشحون بعد')
            ->emptyStateIcon('heroicon-o-users');
    }
}
