<?php

namespace App\Filament\Admin\Resources\TalentResource\Pages;

use App\Filament\Admin\Resources\TalentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;

use App\Filament\Actions\CommunicationActions;

class ViewTalent extends ViewRecord
{
    protected static string $resource = TalentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \App\Filament\Actions\TalentActions::makeRecordInterviewCaseAction(),

            \App\Filament\Actions\TalentActions::makeHireAsEmployeeAction()
                ->label('تعيين كموظف')
                ->icon('heroicon-m-user-plus')
                ->color('success'),

            CommunicationActions::makeWhatsAppAction()
                ->label('واتساب')
                ->icon('heroicon-m-chat-bubble-left-right')
                ->color('success')
                ->outlined(),

            CommunicationActions::makeEmailAction()
                ->label('إرسال إيميل')
                ->icon('heroicon-m-envelope')
                ->color('info')
                ->outlined(),

            Actions\EditAction::make()
                ->label('تعديل البيانات')
                ->icon('heroicon-m-pencil-square')
                ->color('gray')
                ->outlined(),
        ];
    }

    // Prevent loading the form schema (which uses TextInput) in view mode
    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                // ── البطاقة العلوية (Profile Header) ──
                Section::make()
                    ->columns(['default' => 1, 'sm' => 2, 'lg' => 4])
                    ->components([
                        TextEntry::make('full_name')
                            ->label('اسم المرشح')
                            ->size('xl')
                            ->weight('bold')
                            ->icon('heroicon-o-user-circle')
                            ->columnSpan(['default' => 1, 'sm' => 2, 'lg' => 2]),
                        TextEntry::make('talent_id')
                            ->label('رقم المرشح')
                            ->icon('heroicon-o-hashtag')
                            ->fontFamily('mono')
                            ->color('gray')
                            ->copyable()
                            ->columnSpan(1),
                        TextEntry::make('status.name')
                            ->label('الحالة')
                            ->badge()
                            ->size('lg')
                            ->color(fn ($record) => $record->status?->color ?? 'primary')
                            ->columnSpan(1),

                        TextEntry::make('current_job_title')
                            ->label('المسمى الوظيفي')
                            ->icon('heroicon-o-briefcase')
                            ->placeholder('غير محدد')
                            ->color('gray')
                            ->columnSpan(1),
                        TextEntry::make('current_company')
                            ->label('الشركة الحالية')
                            ->icon('heroicon-o-building-office')
                            ->placeholder('غير محدد')
                            ->color('gray')
                            ->columnSpan(1),
                        TextEntry::make('email')
                            ->label('البريد الإلكتروني')
                            ->icon('heroicon-o-envelope')
                            ->copyable()
                            ->copyMessage('تم نسخ البريد ✅')
                            ->color('primary')
                            ->columnSpan(1),
                        TextEntry::make('phone')
                            ->label('رقم الهاتف')
                            ->icon('heroicon-o-phone')
                            ->copyable()
                            ->copyMessage('تم نسخ الرقم ✅')
                            ->color('primary')
                            ->columnSpan(1),
                    ]),

                // ── تنبيه: سجل مقابلة/كيس سابق لنفس المرشح (عند وجود تكرار) ──
                Section::make('تنبيه: يوجد سجل مقابلة أو قرار سابق لهذا المرشح')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->iconColor('warning')
                    ->visible(function (\App\Models\Talent $record): bool {
                        return $record->findPreviousCaseRecord() !== null;
                    })
                    ->components([
                        TextEntry::make('previous_case_notice')
                            ->hiddenLabel()
                            ->state(function (\App\Models\Talent $record): string {
                                $prev = $record->findPreviousCaseRecord();
                                if (! $prev) {
                                    return '';
                                }

                                $parts = [
                                    'الملف السابق: ' . $prev->talent_id . ($prev->jobPosting ? ' (' . $prev->jobPosting->title . ')' : ''),
                                    $prev->decision_outcome ? 'القرار السابق: ' . $prev->decision_outcome->getLabel() : null,
                                    $prev->decision_reason ? 'السبب: ' . $prev->decision_reason->getLabel() : null,
                                    $prev->reapply_eligibility ? 'إعادة التقديم: ' . $prev->reapply_eligibility->getLabel() : null,
                                    $prev->decision_summary ? 'الملخص: ' . $prev->decision_summary : null,
                                ];

                                return implode(' | ', array_filter($parts));
                            })
                            ->color('warning'),
                    ]),

                // ── ملخص قرار المقابلة والكيس الكامل ──
                Section::make('قرار المقابلة وملخص الكيس الكامل')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->iconColor(fn (\App\Models\Talent $record) => $record->decision_outcome?->getColor() ?? 'gray')
                    ->visible(fn (\App\Models\Talent $record): bool => $record->hasCaseDecision())
                    ->columns(['default' => 1, 'sm' => 2, 'lg' => 3])
                    ->components([
                        TextEntry::make('decision_outcome')
                            ->label('القرار النهائي')
                            ->badge()
                            ->size('lg')
                            ->placeholder('غير محدد'),
                        TextEntry::make('decision_reason')
                            ->label('السبب الرئيسي')
                            ->icon('heroicon-o-information-circle')
                            ->placeholder('غير محدد'),
                        TextEntry::make('reapply_eligibility')
                            ->label('إمكانية إعادة التقديم')
                            ->badge()
                            ->placeholder('غير محدد'),
                        TextEntry::make('recommendedJobPosting.title')
                            ->label('الوظيفة البديلة المقترحة')
                            ->icon('heroicon-o-briefcase')
                            ->badge()
                            ->color('info')
                            ->visible(fn (\App\Models\Talent $record): bool => filled($record->recommended_job_posting_id)),
                        TextEntry::make('interview_salary_expectation')
                            ->label('الراتب المطلوب في المقابلة')
                            ->formatStateUsing(fn ($state) => $state !== null && $state !== '' ? number_format((float) $state, 2) . ' ' . \App\Models\Setting::currencySymbol() : null)
                            ->icon('heroicon-o-banknotes')
                            ->visible(fn (\App\Models\Talent $record): bool => filled($record->interview_salary_expectation)),
                        TextEntry::make('decisionBy.name')
                            ->label('تم التوثيق بواسطة')
                            ->icon('heroicon-o-user')
                            ->placeholder('غير محدد'),
                        TextEntry::make('decision_at')
                            ->label('تاريخ القرار')
                            ->dateTime('d/m/Y h:i A')
                            ->icon('heroicon-o-calendar')
                            ->placeholder('غير محدد'),
                        TextEntry::make('decision_summary')
                            ->label('توضيح الكيس بالكامل')
                            ->placeholder('لا يوجد ملخص تفصيلي مسجل.')
                            ->prose()
                            ->columnSpanFull(),
                    ]),

                // ── تفاصيل المرشح (Main Content) ──
                Grid::make(['default' => 1, 'lg' => 3])
                    ->components([
                        // ═══ العمود الرئيسي (2/3) ═══
                        Grid::make(1)
                            ->columnSpan(['default' => 1, 'lg' => 2])
                            ->components([

                                // قسم: الخبرة والمهارات
                                Section::make('التفاصيل المهنية')
                                    ->icon('heroicon-o-academic-cap')
                                    ->collapsible()
                                    ->columns(['default' => 1, 'sm' => 2, 'xl' => 3])
                                    ->components([
                                        TextEntry::make('seniority_level')
                                            ->label('مستوى الخبرة')
                                            ->badge()
                                            ->color('info')
                                            ->formatStateUsing(fn ($state) => match($state?->value ?? $state) {
                                                'intern'   => 'متدرب',
                                                'junior'   => 'مبتدئ',
                                                'mid'      => 'متوسط',
                                                'senior'   => 'سينيور',
                                                'lead'     => 'ليد',
                                                'manager'  => 'مدير',
                                                'director' => 'دايركتور',
                                                'vp'       => 'نائب رئيس',
                                                'c_level'  => 'C-Level',
                                                default    => $state ?? 'غير محدد'
                                            }),
                                        TextEntry::make('years_of_experience')
                                            ->label('سنوات الخبرة')
                                            ->suffix(' سنة')
                                            ->icon('heroicon-o-clock')
                                            ->placeholder('غير محدد'),
                                        TextEntry::make('work_preference')
                                            ->label('طريقة العمل')
                                            ->badge()
                                            ->color('success')
                                            ->formatStateUsing(fn ($state) => match($state?->value ?? $state) {
                                                'remote'   => 'عن بُعد (ريموت)',
                                                'onsite'   => 'في المقر',
                                                'hybrid'   => 'هايبريد',
                                                'flexible' => 'مرن',
                                                default    => $state ?? 'غير محدد'
                                            }),
                                        TextEntry::make('expected_salary')
                                            ->label('الراتب المتوقع')
                                            ->formatStateUsing(fn ($state) => $state !== null && $state !== '' ? number_format((float) $state, 2) . ' ' . \App\Models\Setting::currencySymbol() : 'غير محدد')
                                            ->icon('heroicon-o-banknotes')
                                            ->placeholder('غير محدد'),
                                        TextEntry::make('availabilityOption.name')
                                            ->label('الإتاحة للبدء')
                                            ->badge()
                                            ->color('warning')
                                            ->default('غير محدد')
                                            ->columnSpan(['default' => 1, 'sm' => 1, 'xl' => 2]),
                                    ]),

                                // قسم: الروابط الخارجية
                                Section::make('الروابط الخارجية')
                                    ->icon('heroicon-o-link')
                                    ->collapsible()
                                    ->collapsed(false)
                                    ->columns(['default' => 1, 'sm' => 2])
                                    ->components([
                                        TextEntry::make('linkedin_url')
                                            ->label('لينكد إن')
                                            ->icon('heroicon-o-link')
                                            ->url(fn($state) => $state)
                                            ->openUrlInNewTab()
                                            ->formatStateUsing(fn ($state) => $state ? 'فتح الملف الشخصي ↗' : null)
                                            ->color('primary')
                                            ->placeholder('لم يتم إضافته'),
                                        TextEntry::make('portfolio_url')
                                            ->label('البورتفوليو / الموقع')
                                            ->icon('heroicon-o-globe-alt')
                                            ->url(fn($state) => $state)
                                            ->openUrlInNewTab()
                                            ->formatStateUsing(fn ($state) => $state ? 'فتح البورتفوليو ↗' : null)
                                            ->color('primary')
                                            ->placeholder('لم يتم إضافته'),
                                    ])
                                    ->visible(fn ($record) => $record->linkedin_url || $record->portfolio_url),

                                // قسم: ملاحظات عامة
                                Section::make('ملاحظات عامة')
                                    ->icon('heroicon-o-document-text')
                                    ->collapsible()
                                    ->components([
                                        TextEntry::make('general_notes')
                                            ->hiddenLabel()
                                            ->placeholder('لا توجد ملاحظات مسجلة للمرشح.')
                                            ->prose()
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // ═══ العمود الجانبي (1/3) ═══
                        Section::make('التصنيف والمتابعة')
                            ->icon('heroicon-o-tag')
                            ->columns(['default' => 1, 'sm' => 2])
                            ->columnSpan(['default' => 1, 'lg' => 1])
                            ->components([
                                TextEntry::make('department.name')
                                    ->label('القسم')
                                    ->icon('heroicon-o-building-office-2')
                                    ->badge()
                                    ->color('primary')
                                    ->placeholder('غير محدد'),
                                TextEntry::make('location.name')
                                    ->label('الموقع')
                                    ->icon('heroicon-o-map-pin')
                                    ->placeholder('غير محدد'),
                                TextEntry::make('source.name')
                                    ->label('مصدر التقديم')
                                    ->icon('heroicon-o-arrow-down-tray')
                                    ->badge()
                                    ->color('gray')
                                    ->placeholder('غير محدد'),
                                IconEntry::make('is_potential_duplicate')
                                    ->label('ملف مكرر؟')
                                    ->boolean()
                                    ->trueColor('danger')
                                    ->falseColor('success')
                                    ->trueIcon('heroicon-o-exclamation-triangle')
                                    ->falseIcon('heroicon-o-check-circle'),
                                TextEntry::make('date_added')
                                    ->label('تاريخ الإضافة')
                                    ->date('d/m/Y')
                                    ->icon('heroicon-o-calendar')
                                    ->since()
                                    ->placeholder('غير محدد'),
                                TextEntry::make('last_profile_review_at')
                                    ->label('آخر مراجعة')
                                    ->date('d/m/Y')
                                    ->icon('heroicon-o-eye')
                                    ->since()
                                    ->placeholder('لم يتم المراجعة بعد'),
                                TextEntry::make('assignedRecruiter.name')
                                    ->label('المسؤول عن التواصل')
                                    ->icon('heroicon-o-user-circle')
                                    ->placeholder('لم يتم تعيينه')
                                    ->columnSpanFull(),
                                TextEntry::make('tags.name')
                                    ->label('الوسوم')
                                    ->icon('heroicon-o-tag')
                                    ->badge()
                                    ->color('info')
                                    ->separator(', ')
                                    ->placeholder('لا توجد وسوم')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
