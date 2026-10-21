<?php

namespace App\Filament\Admin\Resources\ActivityLogs;

use App\Filament\Admin\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Filament\Admin\Resources\ActivityLogs\Pages\ViewActivityLog;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $recordTitleAttribute = 'description';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    public static function getNavigationLabel(): string
    {
        return 'سجل تدقيق ومراقبة العمليات (Audit Trail)';
    }

    public static function getModelLabel(): string
    {
        return 'سجل تدقيق';
    }

    public static function getPluralModelLabel(): string
    {
        return 'سجلات تدقيق ومراقبة العمليات';
    }

    protected static \UnitEnum|string|null $navigationGroup = 'إدارة النظام والصلاحيات';

    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['causer', 'subject']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(15)
            ->columns([
                TextColumn::make('causer.name')
                    ->label('المستخدم المسؤول')
                    ->placeholder('النظام آلياً / زائر')
                    ->weight('bold')
                    ->icon('heroicon-m-user')
                    ->searchable(),

                TextColumn::make('event')
                    ->label('نوع الإجراء')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'created' => 'إنشاء جديد',
                        'updated' => 'تعديل بيانات',
                        'deleted' => 'حذف سجل',
                        default => $state ?? 'إجراء',
                    })
                    ->color(fn ($state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'primary',
                    }),

                TextColumn::make('subject_type')
                    ->label('القسم / الموديل')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'App\Models\Employee' => 'شؤون الموظفين',
                        'App\Models\Payroll' => 'مسيرات الرواتب',
                        'App\Models\Leave' => 'الإجازات',
                        'App\Models\Excuse' => 'الاستئذانات',
                        'App\Models\Talent' => 'استقطاب المواهب (مرشح)',
                        'App\Models\User' => 'حسابات المستخدمين',
                        'App\Models\Setting' => 'إعدادات النظام',
                        default => class_basename($state ?? ''),
                    })
                    ->badge()
                    ->color('gray'),

                TextColumn::make('description')
                    ->label('وصف العملية')
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('التاريخ والوقت')
                    ->dateTime('d M Y - h:i A')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('event')
                    ->label('تصفية حسب نوع الإجراء')
                    ->options([
                        'created' => 'إنشاء جديد',
                        'updated' => 'تعديل بيانات',
                        'deleted' => 'حذف سجل',
                    ]),

                SelectFilter::make('subject_type')
                    ->label('تصفية حسب القسم')
                    ->options([
                        'App\Models\Employee' => 'شؤون الموظفين',
                        'App\Models\Payroll' => 'مسيرات الرواتب',
                        'App\Models\Leave' => 'الإجازات',
                        'App\Models\User' => 'المستخدمين',
                        'App\Models\Talent' => 'المرشحين والتوظيف',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            InfolistSection::make('تفاصيل العملية')
                ->schema([
                    TextEntry::make('causer.name')
                        ->label('منفذ العملية')
                        ->placeholder('النظام التلقائي'),
                    TextEntry::make('event')
                        ->label('نوع الإجراء')
                        ->badge()
                        ->formatStateUsing(fn ($state) => match ($state) {
                            'created' => 'إنشاء جديد',
                            'updated' => 'تعديل بيانات',
                            'deleted' => 'حذف سجل',
                            default => $state,
                        })
                        ->color(fn ($state): string => match ($state) {
                            'created' => 'success',
                            'updated' => 'warning',
                            'deleted' => 'danger',
                            default => 'primary',
                        }),
                    TextEntry::make('subject_type')
                        ->label('النموذج المتأثر')
                        ->formatStateUsing(fn ($state) => class_basename($state ?? '—')),
                    TextEntry::make('created_at')
                        ->label('توقيت العملية')
                        ->dateTime('Y-m-d h:i:s A'),
                ])->columns(2),

            InfolistSection::make('التغييرات المسجلة (القيم السابقة والجديدة)')
                ->schema([
                    KeyValueEntry::make('properties.old')
                        ->label('القيم السابقة (قبل التعديل)')
                        ->placeholder('لا توجد قيم سابقة مسجلة'),
                    KeyValueEntry::make('properties.attributes')
                        ->label('القيم الحالية / الجديدة')
                        ->placeholder('لا توجد قيم جديدة مسجلة'),
                ])->columns(2),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
            'view' => ViewActivityLog::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
