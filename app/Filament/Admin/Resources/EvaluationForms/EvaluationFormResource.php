<?php

namespace App\Filament\Admin\Resources\EvaluationForms;

use App\Filament\Admin\Resources\EvaluationForms\Pages\CreateEvaluationForm;
use App\Filament\Admin\Resources\EvaluationForms\Pages\EditEvaluationForm;
use App\Filament\Admin\Resources\EvaluationForms\Pages\ListEvaluationForms;
use App\Filament\Admin\Resources\EvaluationForms\Schemas\EvaluationFormSchema;
use App\Filament\Admin\Resources\EvaluationForms\Tables\EvaluationFormsTable;
use App\Models\EvaluationForm;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EvaluationFormResource extends Resource
{
    protected static ?string $model = EvaluationForm::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    public static function getNavigationGroup(): ?string
    {
        return 'إدارة التقييمات والشكاوى';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getModelLabel(): string
    {
        return 'نموذج تقييم';
    }

    public static function getPluralModelLabel(): string
    {
        return 'نماذج التقييم والاستبيانات';
    }

    public static function form(Schema $schema): Schema
    {
        return EvaluationFormSchema::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EvaluationFormsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvaluationForms::route('/'),
            'create' => CreateEvaluationForm::route('/create'),
            'edit' => EditEvaluationForm::route('/{record}/edit'),
        ];
    }
}
