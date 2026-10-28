<?php

namespace App\Filament\Admin\Resources\Employees\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'مستندات الموظف';

    protected static ?string $modelLabel = 'مستند';

    protected static ?string $pluralModelLabel = 'مستندات الموظف';

    public static function getModelLabel(): ?string
    {
        return 'مستند';
    }

    public static function getPluralModelLabel(): ?string
    {
        return 'مستندات الموظف';
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'مستندات الموظف';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('اسم المستند')
                    ->placeholder('مثال: الهوية الوطنية، عقد العمل، شهادة التخرج')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('expiry_date')
                    ->label('تاريخ الانتهاء')
                    ->helperText('اتركه فارغاً إذا كان المستند ليس له تاريخ انتهاء.'),
                FileUpload::make('file_path')
                    ->label('الملف')
                    ->directory('employee-documents')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('اسم المستند')
                    ->searchable(),
                TextColumn::make('expiry_date')
                    ->label('تاريخ الانتهاء')
                    ->date()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state < now() ? 'danger' : 'success'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('إضافة مستند جديد'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('لا توجد مستندات')
            ->emptyStateDescription('ابدأ برفع مستند جديد للموظف');
    }
}
