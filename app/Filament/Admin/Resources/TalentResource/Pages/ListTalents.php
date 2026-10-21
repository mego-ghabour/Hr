<?php

namespace App\Filament\Admin\Resources\TalentResource\Pages;

use App\Filament\Admin\Resources\TalentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTalents extends ListRecords
{
    protected static string $resource = TalentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('kanban_board')
                ->label('عرض كلوحة كانبان')
                ->icon('heroicon-o-view-columns')
                ->color('success')
                ->url(TalentResource::getUrl('kanban')),

            Actions\CreateAction::make()
                ->label('إضافة مرشح جديد')
                ->icon('heroicon-o-user-plus'),

            Actions\Action::make('export_csv')
                ->label('تصدير إكسيل / CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    return response()->streamDownload(function () {
                        $handle = fopen('php://output', 'w');
                        fputs($handle, "\xEF\xBB\xBF");
                        fputcsv($handle, [
                            'المعرف',
                            'الاسم الكامل',
                            'البريد الإلكتروني',
                            'الهاتف',
                            'الوظيفة المتقدم لها',
                            'القسم',
                            'الراتب المتوقع',
                            'سنوات الخبرة',
                            'المستوى المهني',
                            'طريقة العمل',
                            'الحالة',
                            'تاريخ الإضافة'
                        ]);
                        
                        \App\Models\Talent::with(['jobPosting', 'department', 'status'])->chunk(200, function ($talents) use ($handle) {
                            foreach ($talents as $t) {
                                fputcsv($handle, [
                                    $t->talent_id,
                                    $t->full_name,
                                    $t->email,
                                    $t->phone,
                                    $t->jobPosting?->title ?? '-',
                                    $t->department?->name ?? '-',
                                    $t->expected_salary ?? '-',
                                    $t->years_of_experience ?? '-',
                                    $t->seniority_level?->value ?? '-',
                                    $t->work_preference?->value ?? '-',
                                    $t->status?->name ?? '-',
                                    $t->date_added?->format('Y-m-d') ?? $t->created_at->format('Y-m-d'),
                                ]);
                            }
                        });
                        fclose($handle);
                    }, 'talents-all-' . date('Y-m-d') . '.csv');
                }),

            Actions\Action::make('openPublicForm')
                ->label('رابط استمارة التقديم الخارجي')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('info')
                ->url(url('/apply'), shouldOpenInNewTab: true),
        ];
    }
}
