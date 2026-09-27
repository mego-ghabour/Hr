<?php

namespace App\Filament\Admin\Resources\Employees\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'مستندات الموظف';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')
                    ->label('اسم المستند')
                    ->placeholder('مثال: الهوية الوطنية، عقد العمل، شهادة التخرج')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\DatePicker::make('expiry_date')
                    ->label('تاريخ الانتهاء')
                    ->helperText('اتركه فارغاً إذا كان المستند ليس له تاريخ انتهاء.'),
                \Filament\Forms\Components\FileUpload::make('file_path')
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
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
