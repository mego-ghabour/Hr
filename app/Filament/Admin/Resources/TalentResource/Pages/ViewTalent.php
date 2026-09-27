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
            CommunicationActions::makeWhatsAppAction(),
            CommunicationActions::makeEmailAction(),
            Actions\EditAction::make(),
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
            ->components([
                // ── البطاقة العلوية (Profile Header) ──
                Section::make()
                    ->components([
                        Grid::make(['default' => 1, 'md' => 2])
                            ->components([
                                // الجزء الأيمن: الاسم والوظيفة
                                Grid::make(1)
                                    ->components([
                                        TextEntry::make('full_name')
                                            ->label('اسم المرشح')
                                            ->size('xl')
                                            ->weight('bold')
                                            ->icon('heroicon-o-user-circle'),
                                        TextEntry::make('current_job_title')
                                            ->label('المسمى الوظيفي')
                                            ->icon('heroicon-o-briefcase')
                                            ->placeholder('غير محدد')
                                            ->color('gray'),
                                        TextEntry::make('current_company')
                                            ->label('الشركة الحالية')
                                            ->icon('heroicon-o-building-office')
                                            ->placeholder('غير محدد')
                                            ->color('gray'),
                                    ]),

                                // الجزء الأيسر: الحالة ومعلومات التواصل
                                Grid::make(1)
                                    ->components([
                                        TextEntry::make('status.name')
                                            ->label('الحالة')
                                            ->badge()
                                            ->size('lg')
                                            ->color(fn ($record) => $record->status?->color ?? 'primary'),
                                        TextEntry::make('email')
                                            ->label('البريد الإلكتروني')
                                            ->icon('heroicon-o-envelope')
                                            ->copyable()
                                            ->copyMessage('تم نسخ البريد ✅')
                                            ->color('primary'),
                                        TextEntry::make('phone')
                                            ->label('رقم الهاتف')
                                            ->icon('heroicon-o-phone')
                                            ->copyable()
                                            ->copyMessage('تم نسخ الرقم ✅')
                                            ->color('primary'),
                                        TextEntry::make('talent_id')
                                            ->label('رقم المرشح')
                                            ->icon('heroicon-o-hashtag')
                                            ->fontFamily('mono')
                                            ->color('gray')
                                            ->copyable(),
                                    ]),
                            ]),
                    ]),

                // ── تفاصيل المرشح (Main Content) ──
                Grid::make(3)
                    ->components([
                        // ═══ العمود الرئيسي (2/3) ═══
                        Grid::make(1)
                            ->columnSpan(['default' => 3, 'lg' => 2])
                            ->components([

                                // قسم: الخبرة والمهارات
                                Section::make('التفاصيل المهنية')
                                    ->icon('heroicon-o-academic-cap')
                                    ->collapsible()
                                    ->columns(2)
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
                                        TextEntry::make('expected_salary')
                                            ->label('الراتب المتوقع')
                                            ->money('EGP')
                                            ->icon('heroicon-o-banknotes')
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
                                        TextEntry::make('availabilityOption.name')
                                            ->label('الإتاحة للبدء')
                                            ->badge()
                                            ->color('warning')
                                            ->default('غير محدد'),
                                    ]),

                                // قسم: الروابط الخارجية
                                Section::make('الروابط الخارجية')
                                    ->icon('heroicon-o-link')
                                    ->collapsible()
                                    ->collapsed(false)
                                    ->columns(2)
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
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // ═══ العمود الجانبي (1/3) ═══
                        Section::make('التصنيف والمتابعة')
                            ->icon('heroicon-o-tag')
                            ->columnSpan(['default' => 3, 'lg' => 1])
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
                                TextEntry::make('assignedRecruiter.name')
                                    ->label('المسؤول عن التواصل')
                                    ->icon('heroicon-o-user-circle')
                                    ->placeholder('لم يتم تعيينه'),
                                TextEntry::make('tags.name')
                                    ->label('الوسوم')
                                    ->icon('heroicon-o-tag')
                                    ->badge()
                                    ->color('info')
                                    ->separator(', ')
                                    ->placeholder('لا توجد وسوم'),
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
                                IconEntry::make('is_potential_duplicate')
                                    ->label('ملف مكرر؟')
                                    ->boolean()
                                    ->trueColor('danger')
                                    ->falseColor('success')
                                    ->trueIcon('heroicon-o-exclamation-triangle')
                                    ->falseIcon('heroicon-o-check-circle'),
                            ]),
                    ]),
            ]);
    }
}
