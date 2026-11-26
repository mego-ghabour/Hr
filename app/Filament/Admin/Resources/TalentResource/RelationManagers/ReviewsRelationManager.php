<?php

namespace App\Filament\Admin\Resources\TalentResource\RelationManagers;

use App\Enums\InterviewAttendance;
use App\Enums\InterviewStage;
use App\Enums\TalentDecisionOutcome;
use App\Enums\TalentDecisionReason;
use App\Models\Setting;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';
    protected static ?string $title = 'المقابلات والتقييمات';

    public static function getModelLabel(): string { return 'مقابلة / تقييم'; }
    public static function getPluralModelLabel(): string { return 'المقابلات والتقييمات'; }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Grid::make(2)->schema([
                Select::make('interview_stage')
                    ->label('مرحلة المقابلة')
                    ->options(InterviewStage::class)
                    ->default(InterviewStage::HR_SCREENING->value)
                    ->native(false),
                Select::make('attendance_status')
                    ->label('حالة الحضور')
                    ->options(InterviewAttendance::class)
                    ->default(InterviewAttendance::ATTENDED->value)
                    ->native(false),
                Select::make('rating')
                    ->label('التقييم')
                    ->options([
                        1 => '1 - ضعيف',
                        2 => '2 - مقبول',
                        3 => '3 - جيد',
                        4 => '4 - جيد جداً',
                        5 => '5 - ممتاز',
                    ])
                    ->native(false),
                Select::make('recommendation')
                    ->label('التوصية / القرار')
                    ->options(TalentDecisionOutcome::class)
                    ->native(false),
                Select::make('decision_reason')
                    ->label('السبب (إن وُجد)')
                    ->options(TalentDecisionReason::class)
                    ->searchable()
                    ->native(false),
                TextInput::make('interview_salary_expectation')
                    ->label('الراتب المطلوب في المقابلة')
                    ->numeric()
                    ->prefix(fn () => Setting::currencySymbol()),
                TextInput::make('notice_period')
                    ->label('فترة الإشعار')
                    ->maxLength(120)
                    ->columnSpanFull(),
                Textarea::make('strengths')
                    ->label('نقاط القوة')
                    ->rows(2)
                    ->maxLength(65535),
                Textarea::make('weaknesses')
                    ->label('نقاط الضعف / التحفظات')
                    ->rows(2)
                    ->maxLength(65535),
                Textarea::make('notes')
                    ->label('ملخص المقابلة والملاحظات')
                    ->rows(3)
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ])->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('interview_stage')
                    ->label('المرحلة')
                    ->badge()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('attendance_status')
                    ->label('الحضور')
                    ->badge()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('recommendation')
                    ->label('القرار / التوصية')
                    ->badge()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('decision_reason')
                    ->label('السبب')
                    ->limit(35)
                    ->placeholder('-')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('التقييم')
                    ->badge()
                    ->placeholder('-')
                    ->color(fn ($state): string => match ((string) $state) {
                        '1', '2' => 'danger',
                        '3' => 'warning',
                        '4', '5' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('reviewer.name')
                    ->label('المقيّم / المحاور'),
                Tables\Columns\TextColumn::make('reviewed_at')
                    ->label('التاريخ')
                    ->dateTime('d/m/Y h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('ملخص المقابلة')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(),
            ])
            ->defaultSort('reviewed_at', 'desc')
            ->filters([])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label('إضافة مقابلة / تقييم')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['reviewer_id'] = Auth::id();
                        $data['reviewed_at'] = now();
                        return $data;
                    }),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}


