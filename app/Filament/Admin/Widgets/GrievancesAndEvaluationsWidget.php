<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Evaluation;
use App\Models\EvaluationForm;
use App\Models\Grievance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;

class GrievancesAndEvaluationsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['Super Admin', 'admin', 'المدير العام', 'مسؤول النظام', 'مدير الموارد البشرية', 'HR Manager', 'مدير قسم']) || $user->can('view_any_grievance') || $user->can('view_any_evaluation'));
    }

    protected function getStats(): array
    {
        if (! Schema::hasTable('grievances') || ! Schema::hasTable('evaluations')) {
            return [];
        }

        try {
            $totalGrievances = Grievance::count();
            $newGrievances = Grievance::where('status', 'new')->count();
            $resolvedGrievances = Grievance::where('status', 'resolved')->count();
            $resolutionRate = $totalGrievances > 0 ? round(($resolvedGrievances / $totalGrievances) * 100) : 100;

            $totalEvaluations = Evaluation::where('status', '!=', 'draft')->count();
            $avgScore = Evaluation::whereNotNull('score')->avg('score');
            $pendingEvaluations = Evaluation::where('status', 'submitted')->count();
            $activeForms = EvaluationForm::where('is_active', true)->count();
        } catch (\Throwable) {
            return [];
        }

        return [
            Stat::make('شكاوى جديدة بانتظار التحقيق', $newGrievances)
                ->description($newGrievances > 0 ? 'تتطلب تدخلاً ومراجعة إدارية' : 'كافة الشكاوى تحت السيطرة ✓')
                ->descriptionIcon($newGrievances > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($newGrievances > 0 ? 'danger' : 'success')
                ->url(url('/admin/grievances')),

            Stat::make('نسبة معالجة الشكاوى والمقترحات', "{$resolutionRate}%")
                ->description("تم حل {$resolvedGrievances} من إجمالي {$totalGrievances}")
                ->descriptionIcon('heroicon-m-shield-check')
                ->color($resolutionRate >= 75 ? 'success' : 'warning')
                ->url(url('/admin/grievances')),

            Stat::make('متوسط تقييمات الأداء', $avgScore ? number_format((float)$avgScore, 2) . ' / 5.0 ⭐' : 'لا يوجد بعد')
                ->description("إجمالي {$totalEvaluations} تقييم مسجل بالنظام")
                ->descriptionIcon('heroicon-m-star')
                ->color($avgScore >= 3.5 ? 'success' : ($avgScore > 0 ? 'warning' : 'gray'))
                ->url(url('/admin/evaluations')),

            Stat::make('تقييمات بانتظار الاعتماد', $pendingEvaluations)
                ->description("موزعة على {$activeForms} نماذج تقييم نشطة")
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color($pendingEvaluations > 0 ? 'warning' : 'info')
                ->url(url('/admin/evaluations')),
        ];
    }
}
