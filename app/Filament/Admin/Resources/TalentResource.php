<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TalentResource\Pages;
use App\Models\Talent;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Forms\Components\DatePicker;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\TrashedFilter;
use \Filament\Actions\ViewAction;
use \Filament\Actions\EditAction;
use \Filament\Actions\DeleteAction;
use \Filament\Actions\ForceDeleteAction;
use \Filament\Actions\RestoreAction;
use \Filament\Actions\DeleteBulkAction;
use \Filament\Actions\BulkActionGroup;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\TalentResource\RelationManagers;
use App\Filament\Actions\CommunicationActions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TalentResource extends Resource
{
    protected static ?string $model = Talent::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|\UnitEnum|null $navigationGroup = 'إدارة المرشحين';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'talent_id',
            'full_name',
            'email',
            'phone',
            'current_job_title',
            'current_company',
            'general_notes',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'البريد الإلكتروني' => $record->email ?? 'غير متوفر',
            'المسمى الوظيفي' => $record->current_job_title ?? 'غير متوفر',
            'الهاتف' => $record->phone ?? 'غير متوفر',
        ];
    }

    public static function getModelLabel(): string
    {
        return 'مرشح';
    }

    public static function getPluralModelLabel(): string
    {
        return 'المرشحين';
    }

    public static function getNavigationLabel(): string
    {
        return 'المرشحين';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Tabs::make('TalentTabs')
                    ->columnSpanFull()
                    ->tabs([
                        \Filament\Schemas\Components\Tabs\Tab::make('البيانات الأساسية')
                            ->icon('heroicon-o-user')
                            ->components([
                                Section::make('المعلومات الشخصية')
                    ->columns(2)
                    ->components([
                        TextInput::make('full_name')
                            ->label('الاسم الكامل')
                            ->placeholder('مثال: أحمد محمد')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('الإيميل')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'الإيميل ده مسجل عندنا قبل كده، جرب إيميل تاني.',
                            ])
                            ->placeholder('ahmed@company.com')
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('رقم الموبايل')
                            ->tel()
                            ->unique(ignoreRecord: true)
                            ->regex('/^\+?[0-9][0-9\s\-\(\)]{7,20}$/')
                            ->validationMessages([
                                'unique' => 'رقم الموبايل ده مسجل عندنا قبل كده.',
                                'regex' => 'صيغة رقم الموبايل غير صحيحة.',
                            ])
                            ->placeholder('+20 1xx xxxx xxxx')
                            ->maxLength(255),
                        TextInput::make('linkedin_url')
                            ->label('لينكد إن')
                            ->url()
                            ->regex('/^https:\/\/(www\.)?[a-zA-Z0-9-]*\.?linkedin\.com\/.*$/i')
                            ->validationMessages([
                                'regex' => 'يجب أن يكون رابط لينكدإن صحيح (يبدأ بـ https://linkedin.com)',
                            ])
                            ->placeholder('https://linkedin.com/in/..')
                            ->suffixIcon('heroicon-o-arrow-top-right-on-square')
                            ->maxLength(255),
                        TextInput::make('portfolio_url')
                            ->label('بورتفوليو / موقع شخصي')
                            ->url()
                            ->placeholder('https://..')
                            ->maxLength(255),
                    ]),

                Section::make('التفاصيل المهنية')
                    ->columns(2)
                    ->components([
                        TextInput::make('current_job_title')
                            ->label('المسمى الوظيفي الحالي')
                            ->placeholder('مثال: Senior Software Engineer')
                            ->maxLength(255),
                        TextInput::make('current_company')
                            ->label('الشركة اللي بيشتغل فيها دلوقتي')
                            ->placeholder('اسم الشركة')
                            ->maxLength(255),
                        TextInput::make('years_of_experience')
                            ->label('سنين الخبرة')
                            ->numeric()
                            ->suffix('سنة')
                            ->minValue(0)
                            ->maxValue(50),
                        TextInput::make('expected_salary')
                            ->label('الراتب المتوقع (بالشهر)')
                            ->numeric()
                            ->prefix('ج.م')
                            ->helperText('اكتب الراتب شهرياً'),
                        Select::make('seniority_level')
                            ->label('مستوى الخبرة')
                            ->options([
                                'intern'    => 'متدرب',
                                'junior'    => 'مبتدئ (0-2 سنة)',
                                'mid'       => 'متوسط (2-5 سنين)',
                                'senior'    => 'سينيور (5+ سنين)',
                                'lead'      => 'ليد',
                                'manager'   => 'مدير',
                                'director'  => 'دايركتور',
                                'vp'        => 'نائب رئيس',
                                'c_level'   => 'C-Level',
                            ])
                            ->searchable(),
                        Select::make('work_preference')
                            ->label('طريقة الشغل')
                            ->options([
                                'remote'   => 'عن بعد (ريموت)',
                                'onsite'   => 'في المقر',
                                'hybrid'   => 'هايبريد',
                                'flexible' => 'مرن',
                            ]),
                        Select::make('availability_option_id')
                            ->label('متاح للبدء امتى؟')
                            ->relationship('availabilityOption', 'name')
                            ->preload()
                            ->searchable(),
                    ]),
                            ]),
                            
                        \Filament\Schemas\Components\Tabs\Tab::make('تحديد الوظيفة')
                            ->icon('heroicon-o-briefcase')
                            ->components([
                                Section::make('التصنيف والتفاصيل')
                    ->columns(2)
                    ->components([
                        Select::make('department_id')
                            ->label('القسم')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('location_id')
                            ->label('الموقع')
                            ->relationship('location', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('source_id')
                            ->label('مصدر المرشح')
                            ->relationship('source', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('المصدر الذي أتى منه المرشح'),
                        Select::make('status_id')
                            ->label('الحالة المبدئية')
                            ->relationship('status', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('job_posting_id')
                            ->label('الوظيفة المتقدم عليها')
                            ->relationship('jobPosting', 'title')
                            ->searchable()
                            ->preload(),
                    ]),
                            ]),
                            
                        \Filament\Schemas\Components\Tabs\Tab::make('المرفقات')
                            ->icon('heroicon-o-document-text')
                            ->components([
                                Section::make('المستندات والسيرة الذاتية')
                    ->components([
                        FileUpload::make('cv')
                            ->label('رفع السيرة الذاتية (CV)')
                            ->disk('public')
                            ->directory('talent-documents')
                            ->rules(['file', 'extensions:pdf,doc,docx'])
                            ->validationMessages([
                                'extensions' => 'يجب أن يكون الملف بصيغة PDF أو Word (امتداد .pdf, .doc, .docx).',
                            ])
                            ->maxSize(10240)
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),
                            ]),
                            
                        \Filament\Schemas\Components\Tabs\Tab::make('الأسئلة المخصصة')
                            ->icon('heroicon-o-chat-bubble-bottom-center-text')
                            ->components([
                                \Filament\Schemas\Components\Section::make('إجابات الأسئلة المخصصة (مضافة للوظيفة)')
                    ->components([
                        \Filament\Forms\Components\Placeholder::make('custom_answers_display')
                            ->label('')
                            ->content(function ($record) {
                                if (!$record || empty($record->custom_answers)) return new \Illuminate\Support\HtmlString('<span class="text-gray-500">لا توجد إجابات مخصصة.</span>');
                                
                                $html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';
                                foreach ($record->custom_answers as $key => $answer) {
                                    $val = $answer['value'] ?? '';
                                    if (($answer['type'] ?? '') === 'file') {
                                        $url = \Illuminate\Support\Facades\Storage::disk('public')->url($answer['path'] ?? '');
                                        $val = '<a href="'.$url.'" target="_blank" class="text-primary-600 underline flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg> تحميل المرفق</a>';
                                    } elseif (($answer['type'] ?? '') === 'checkbox') {
                                        $val = '<span class="font-semibold">' . htmlspecialchars($val) . '</span>';
                                    } else {
                                        $val = nl2br(htmlspecialchars($val));
                                    }
                                    
                                    $html .= '<div class="bg-gray-50 p-4 rounded-lg border border-gray-100">';
                                    $html .= '<span class="block text-sm font-bold text-gray-700 mb-2">' . htmlspecialchars($answer['question'] ?? $key) . '</span>';
                                    $html .= '<div class="text-gray-900">' . $val . '</div>';
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                return new \Illuminate\Support\HtmlString($html);
                            })
                    ])
                    ->visible(fn ($record) => $record && !empty($record->custom_answers)),

                Section::make('ملاحظات عامة')
                    ->components([
                        Textarea::make('general_notes')
                            ->label('ملاحظات')
                            ->placeholder('اكتب هنا أي ملاحظات مهمة عن المرشح...')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('talent_id')
                    ->label('#')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->toggleable(),
                TextColumn::make('full_name')
                    ->label('اسم المرشح')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn($record) => $record->current_job_title)
                    ->toggleable(),
                TextColumn::make('email')
                    ->label('الإيميل')
                    ->searchable()
                    ->toggleable()
                    ->icon('heroicon-o-envelope')
                    ->copyable(),
                TextColumn::make('phone')
                    ->label('الموبايل')
                    ->toggleable()
                    ->icon('heroicon-o-phone')
                    ->copyable(),
                TextColumn::make('jobPosting.title')
                    ->label('الوظيفة المتقدم لها')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('department.name')
                    ->label('القسم')
                    ->badge()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status.name')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn ($record) => $record->status?->color ?? 'primary')
                    ->toggleable(),
                TextColumn::make('seniority_level')
                    ->label('مستوى الخبرة')
                    ->badge()
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
                        default    => $state
                    })
                    ->toggleable(),
                TextColumn::make('expected_salary')
                    ->label('الراتب المتوقع')
                    ->money('EGP')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('date_added')
                    ->label('تاريخ الإضافة')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('is_potential_duplicate')
                    ->label('مكرر؟')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('مفيش مرشحين لسه!')
            ->emptyStateDescription('ابدأ بإضافة أول مرشح من الزر فوق.')
            ->emptyStateIcon('heroicon-o-users')
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([
                SelectFilter::make('job_posting_id')
                    ->label('الوظيفة المتقدم عليها')
                    ->relationship('jobPosting', 'title')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status_id')
                    ->label('الحالة')
                    ->relationship('status', 'name'),
                SelectFilter::make('department_id')
                    ->label('القسم')
                    ->relationship('department', 'name'),
                SelectFilter::make('seniority_level')
                    ->label('مستوى الخبرة')
                    ->options([
                        'intern'   => 'متدرب',
                        'junior'   => 'مبتدئ (0-2 سنة)',
                        'mid'      => 'متوسط (2-5 سنين)',
                        'senior'   => 'سينيور (5+ سنين)',
                        'lead'     => 'ليد',
                        'manager'  => 'مدير',
                        'director' => 'دايركتور',
                        'vp'       => 'نائب رئيس',
                        'c_level'  => 'C-Level',
                    ]),
                SelectFilter::make('work_preference')
                    ->label('طريقة الشغل')
                    ->options([
                        'remote'   => 'عن بعد (ريموت)',
                        'onsite'   => 'في المقر',
                        'hybrid'   => 'هايبريد',
                        'flexible' => 'مرن',
                    ]),
                SelectFilter::make('availability_option_id')
                    ->label('متاح للبدء')
                    ->relationship('availabilityOption', 'name'),
                SelectFilter::make('source_id')
                    ->label('المصدر')
                    ->relationship('source', 'name'),
                Filter::make('date_added')
                    ->form([
                        DatePicker::make('added_from')->label('تمت الإضافة من تاريخ'),
                        DatePicker::make('added_until')->label('تمت الإضافة إلى تاريخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['added_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_added', '>=', $date),
                            )
                            ->when(
                                $data['added_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_added', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['added_from'] ?? null) {
                            $indicators[] = Indicator::make('تمت الإضافة من: ' . Carbon::parse($data['added_from'])->toFormattedDateString())
                                ->removeField('added_from');
                        }
                        if ($data['added_until'] ?? null) {
                            $indicators[] = Indicator::make('تمت الإضافة إلى: ' . Carbon::parse($data['added_until'])->toFormattedDateString())
                                ->removeField('added_until');
                        }
                        return $indicators;
                    }),
                TrashedFilter::make()
                    ->label('المحذوفين'),
            ])
            ->actions([
                CommunicationActions::makeWhatsAppAction(),
                CommunicationActions::makeEmailAction(),
                ViewAction::make()->slideOver(),
                EditAction::make()->slideOver(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\NotesRelationManager::class,
            RelationManagers\ReviewsRelationManager::class,
            RelationManagers\FollowUpsRelationManager::class,
            RelationManagers\DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTalents::route('/'),
            'kanban' => Pages\TalentKanbanBoard::route('/kanban'),
            'create' => Pages\CreateTalent::route('/create'),
            'view' => Pages\ViewTalent::route('/{record}'),
            'edit' => Pages\EditTalent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['department', 'status', 'assignedRecruiter'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

