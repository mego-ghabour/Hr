<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\HtmlString;

class CommunicationSettings extends Page implements HasForms
{
    use InteractsWithForms;

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('Super Admin');
    }

    protected string $view = 'filament.admin.pages.communication-settings';

    protected static ?string $cluster = \App\Filament\Clusters\Settings\SettingsCluster::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static string|\UnitEnum|null $navigationGroup = 'إعدادات النظام';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return 'قوالب المراسلة';
    }

    public function getTitle(): string
    {
        return 'إعدادات قوالب المراسلة';
    }

    public function getSubheading(): ?string
    {
        return 'خصّص رسائل الواتساب والإيميل التي يتم إرسالها للمرشحين. أي تعديل هنا ينعكس فوراً على أزرار الإرسال في صفحة المرشح.';
    }

    public ?array $templatesData = [];

    public function mount(): void
    {
        $custom = Setting::get('custom_templates');

        $this->templatesForm->fill([
            'wa_template_interview' => Setting::get('wa_template_interview', "مرحباً {name}،\nنود إعلامك بأنه تم ترشيحك لمقابلة عمل في ({brand}). متى تكون متاحاً؟"),
            'wa_template_docs'      => Setting::get('wa_template_docs', "مرحباً {name}،\nيرجى تزويدنا بالمستندات الناقصة لاستكمال ملفك في ({brand})."),
            
            'email_template_interview_subject' => Setting::get('email_template_interview_subject', "دعوة لمقابلة عمل - {brand}"),
            'email_template_interview_body'    => Setting::get('email_template_interview_body', "عزيزي/عزيزتي {name}،\n\nتحية طيبة وبعد،\n\nيسعدنا إعلامك بأنه تم ترشيحك لإجراء مقابلة عمل.\n\nمع خالص التحيات،\n{brand}"),
            
            'email_template_offer_subject'     => Setting::get('email_template_offer_subject', "عرض وظيفي - {brand}"),
            'email_template_offer_body'        => Setting::get('email_template_offer_body', "عزيزي/عزيزتي {name}،\n\nيسعدنا تقديم عرض وظيفي لك للانضمام إلى فريقنا.\n\nمع خالص التحيات،\n{brand}"),
            
            'email_template_reject_subject'    => Setting::get('email_template_reject_subject', "تحديث بخصوص التقديم - {brand}"),
            'email_template_reject_body'       => Setting::get('email_template_reject_body', "عزيزي/عزيزتي {name}،\n\nنشكرك على اهتمامك بالانضمام إلينا. نود إعلامك بأنه لم يقع الاختيار عليك في هذه المرحلة.\n\nنتمنى لك كل التوفيق.\n{brand}"),
            
            'custom_templates' => $custom ? json_decode($custom, true) : [],
        ]);
    }

    public function templatesForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('templatesTabs')
                    ->tabs([

                        // ═══════════════ تبويب الواتساب ═══════════════
                        Tab::make('واتساب')
                            ->icon('heroicon-o-device-phone-mobile')
                            ->badge(fn () => '2 قوالب')
                            ->badgeColor('success')
                            ->schema([
                                Placeholder::make('wa_help')
                                    ->hiddenLabel()
                                    ->content(new HtmlString('
                                        <div class="p-3 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-sm text-emerald-800 dark:text-emerald-200">
                                            💡 <strong>المتغيرات المتاحة:</strong>
                                            <code class="mx-1 px-1.5 py-0.5 rounded bg-emerald-100 dark:bg-emerald-900 font-mono text-xs">{name}</code> اسم المرشح •
                                            <code class="mx-1 px-1.5 py-0.5 rounded bg-emerald-100 dark:bg-emerald-900 font-mono text-xs">{brand}</code> اسم الشركة
                                        </div>
                                    '))
                                    ->columnSpanFull(),

                                Section::make('دعوة لمقابلة')
                                    ->icon('heroicon-o-calendar')
                                    ->collapsible()
                                    ->compact()
                                    ->schema([
                                        Textarea::make('wa_template_interview')
                                            ->hiddenLabel()
                                            ->rows(3)
                                            ->required()
                                            ->placeholder('اكتب نص رسالة الواتساب للدعوة للمقابلة...'),
                                    ]),

                                Section::make('طلب مستندات')
                                    ->icon('heroicon-o-document-arrow-up')
                                    ->collapsible()
                                    ->compact()
                                    ->schema([
                                        Textarea::make('wa_template_docs')
                                            ->hiddenLabel()
                                            ->rows(3)
                                            ->required()
                                            ->placeholder('اكتب نص رسالة الواتساب لطلب المستندات...'),
                                    ]),
                            ]),

                        // ═══════════════ تبويب الإيميل ═══════════════
                        Tab::make('البريد الإلكتروني')
                            ->icon('heroicon-o-envelope')
                            ->badge(fn () => '3 قوالب')
                            ->badgeColor('primary')
                            ->schema([
                                Placeholder::make('email_help')
                                    ->hiddenLabel()
                                    ->content(new HtmlString('
                                        <div class="p-3 rounded-lg bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 text-sm text-blue-800 dark:text-blue-200">
                                            💡 <strong>المتغيرات المتاحة:</strong>
                                            <code class="mx-1 px-1.5 py-0.5 rounded bg-blue-100 dark:bg-blue-900 font-mono text-xs">{name}</code> اسم المرشح •
                                            <code class="mx-1 px-1.5 py-0.5 rounded bg-blue-100 dark:bg-blue-900 font-mono text-xs">{brand}</code> اسم الشركة
                                        </div>
                                    '))
                                    ->columnSpanFull(),

                                Section::make('دعوة لمقابلة عمل')
                                    ->icon('heroicon-o-calendar')
                                    ->collapsible()
                                    ->compact()
                                    ->schema([
                                        TextInput::make('email_template_interview_subject')
                                            ->label('العنوان (Subject)')
                                            ->required()
                                            ->prefixIcon('heroicon-o-tag'),
                                        Textarea::make('email_template_interview_body')
                                            ->label('نص الرسالة')
                                            ->rows(4)
                                            ->required(),
                                    ]),

                                Section::make('عرض وظيفي (Job Offer)')
                                    ->icon('heroicon-o-gift')
                                    ->collapsible()
                                    ->collapsed()
                                    ->compact()
                                    ->schema([
                                        TextInput::make('email_template_offer_subject')
                                            ->label('العنوان (Subject)')
                                            ->required()
                                            ->prefixIcon('heroicon-o-tag'),
                                        Textarea::make('email_template_offer_body')
                                            ->label('نص الرسالة')
                                            ->rows(4)
                                            ->required(),
                                    ]),

                                Section::make('رسالة اعتذار / رفض')
                                    ->icon('heroicon-o-x-circle')
                                    ->collapsible()
                                    ->collapsed()
                                    ->compact()
                                    ->schema([
                                        TextInput::make('email_template_reject_subject')
                                            ->label('العنوان (Subject)')
                                            ->required()
                                            ->prefixIcon('heroicon-o-tag'),
                                        Textarea::make('email_template_reject_body')
                                            ->label('نص الرسالة')
                                            ->rows(4)
                                            ->required(),
                                    ]),
                            ]),

                        // ═══════════════ تبويب القوالب المخصصة ═══════════════
                        Tab::make('قوالب مخصصة')
                            ->icon('heroicon-o-sparkles')
                            ->badge(fn (Get $get) => count($get('custom_templates') ?? []) ?: null)
                            ->badgeColor('warning')
                            ->schema([
                                Placeholder::make('custom_help')
                                    ->hiddenLabel()
                                    ->content(new HtmlString('
                                        <div class="p-3 rounded-lg bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 text-sm text-amber-800 dark:text-amber-200">
                                            ✨ أنشئ قوالبك الخاصة! القوالب التي تضيفها هنا ستظهر <strong>تلقائياً</strong> في قائمة الإرسال عند التواصل مع أي مرشح.
                                        </div>
                                    '))
                                    ->columnSpanFull(),

                                Repeater::make('custom_templates')
                                    ->hiddenLabel()
                                    ->addActionLabel('➕ إضافة قالب جديد')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('اسم القالب')
                                            ->required()
                                            ->placeholder('مثال: تذكير بموعد المقابلة')
                                            ->prefixIcon('heroicon-o-tag')
                                            ->columnSpanFull(),
                                        Select::make('type')
                                            ->label('نوع الرسالة')
                                            ->options([
                                                'whatsapp' => '📱 واتساب',
                                                'email'    => '📧 إيميل',
                                            ])
                                            ->required()
                                            ->reactive(),
                                        TextInput::make('subject')
                                            ->label('عنوان الإيميل (Subject)')
                                            ->required()
                                            ->visible(fn (Get $get) => $get('type') === 'email')
                                            ->prefixIcon('heroicon-o-tag'),
                                        Textarea::make('body')
                                            ->label('نص الرسالة')
                                            ->rows(3)
                                            ->required()
                                            ->placeholder('اكتب الرسالة هنا... يمكنك استخدام {name} و {brand}')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->itemLabel(fn (array $state): ?string => 
                                        ($state['name'] ?? null) 
                                            ? (($state['type'] ?? '') === 'whatsapp' ? '📱 ' : '📧 ') . $state['name'] 
                                            : null
                                    )
                                    ->collapsible()
                                    ->cloneable()
                                    ->reorderableWithButtons()
                                    ->defaultItems(0),
                            ]),
                    ])
                    ->persistTabInQueryString('tab'),
            ])
            ->statePath('templatesData');
    }

    protected function getForms(): array
    {
        return [
            'templatesForm',
        ];
    }

    public function saveTemplates(): void
    {
        $data = $this->templatesForm->getState();

        if (isset($data['custom_templates'])) {
            $data['custom_templates'] = json_encode($data['custom_templates'], JSON_UNESCAPED_UNICODE);
        }

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()
            ->title('تم حفظ قوالب المراسلة بنجاح ✅')
            ->success()
            ->send();
    }
}
