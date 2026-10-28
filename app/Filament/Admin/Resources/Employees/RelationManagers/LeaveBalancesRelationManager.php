<?php

namespace App\Filament\Admin\Resources\Employees\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LeaveBalancesRelationManager extends RelationManager
{
    protected static string $relationship = 'leaveBalances';

    protected static ?string $title = 'أرصدة الإجازات';

    protected static ?string $modelLabel = 'رصيد إجازة';

    protected static ?string $pluralModelLabel = 'أرصدة الإجازات';

    public static function getModelLabel(): ?string
    {
        return 'رصيد إجازة';
    }

    public static function getPluralModelLabel(): ?string
    {
        return 'أرصدة الإجازات';
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'أرصدة الإجازات';
    }
    
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('leave_type_id')
                    ->label('نوع الإجازة')
                    ->relationship('leaveType', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('اسم نوع الإجازة')
                            ->placeholder('مثال: إجازة زواج، إجازة أمومة...')
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('default_days_per_year')
                            ->label('الرصيد الافتراضي (أيام بالسنة)')
                            ->numeric()
                            ->default(21)
                            ->required(),
                        \Filament\Forms\Components\Toggle::make('requires_approval')
                            ->label('تتطلب موافقة المدير؟')
                            ->default(true),
                    ])
                    ->required(),
                \Filament\Forms\Components\TextInput::make('year')
                    ->label('السنة')
                    ->numeric()
                    ->default(now()->year)
                    ->required(),
                \Filament\Forms\Components\TextInput::make('total_days')
                    ->label('إجمالي الرصيد (أيام)')
                    ->numeric()
                    ->required(),
                \Filament\Forms\Components\TextInput::make('used_days')
                    ->label('الرصيد المستخدم (أيام)')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('leaveType.name')
            ->columns([
                TextColumn::make('leaveType.name')->label('نوع الإجازة')->sortable(),
                TextColumn::make('year')->label('السنة')->sortable(),
                TextColumn::make('total_days')->label('الإجمالي'),
                TextColumn::make('used_days')->label('المستخدم'),
                TextColumn::make('remaining')
                    ->label('المتبقي')
                    ->getStateUsing(fn ($record) => $record->total_days - $record->used_days)
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label('إضافة رصيد إجازة'),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('لا توجد أرصدة إجازات')
            ->emptyStateDescription('ابدأ بإضافة رصيد إجازة للموظف');
    }
}
