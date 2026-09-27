<?php

namespace App\Filament\Admin\Resources\TalentResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';
    protected static ?string $title = 'المستندات';

    public static function getModelLabel(): string { return 'مستند'; }
    public static function getPluralModelLabel(): string { return 'المستندات'; }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('type')
                ->label('نوع المستند')
                ->options([
                    'cv' => 'سيرة ذاتية (CV)',
                    'cover_letter' => 'رسالة تغطية',
                    'certificate' => 'شهادة',
                    'portfolio' => 'معرض أعمال',
                    'other' => 'أخرى',
                ])
                ->required(),
            FileUpload::make('file_path')
                ->label('الملف')
                ->disk('public')
                ->directory('talent-documents')
                ->storeFileNamesIn('file_name')
                ->acceptedFileTypes([
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'image/png',
                    'image/jpeg',
                ])
                ->maxSize(10240)
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file_name')
            ->columns([
                Tables\Columns\TextColumn::make('file_name')
                    ->label('اسم الملف')
                    ->icon('heroicon-o-document')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->color(fn ($state) => match($state?->value ?? $state) {
                        'cv' => 'primary',
                        'cover_letter' => 'info',
                        'certificate' => 'success',
                        'portfolio' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state?->value ?? $state) {
                        'cv' => 'سيرة ذاتية',
                        'cover_letter' => 'رسالة تغطية',
                        'certificate' => 'شهادة',
                        'portfolio' => 'معرض أعمال',
                        'other' => 'أخرى',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('size')
                    ->label('الحجم')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state / 1024, 1) . ' KB' : '-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الرفع')
                    ->date('d/m/Y')
                    ->since(),
            ])
            ->filters([])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label('رفع مستند جديد')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->mutateFormDataUsing(function (array $data): array {
                        // Auto-fill size and mime_type
                        if (isset($data['file_path']) && is_string($data['file_path'])) {
                            $fullPath = Storage::disk('public')->path($data['file_path']);
                            if (file_exists($fullPath)) {
                                $data['size'] = filesize($fullPath);
                                $data['mime_type'] = mime_content_type($fullPath);
                            }
                        }
                        return $data;
                    }),
            ])
            ->actions([
                // زر فتح/تحميل الملف
                \Filament\Actions\Action::make('download')
                    ->label('فتح')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('primary')
                    ->url(function ($record) {
                        $path = $record->file_path;
                        if (!$path) return null;

                        // Try public disk first
                        if (Storage::disk('public')->exists($path)) {
                            return '/storage/' . $path;
                        }
                        // Fallback to local disk
                        if (Storage::disk('local')->exists($path)) {
                            return route('document.download', $record->id);
                        }
                        return null;
                    })
                    ->openUrlInNewTab(),

                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('لا توجد مستندات')
            ->emptyStateDescription('قم بتحميل السيرة الذاتية أو أي مستندات متعلقة بالمرشح')
            ->emptyStateIcon('heroicon-o-document');
    }
}
