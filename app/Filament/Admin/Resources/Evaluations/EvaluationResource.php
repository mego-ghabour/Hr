<?php

namespace App\Filament\Admin\Resources\Evaluations;

use App\Filament\Admin\Resources\Evaluations\Pages\CreateEvaluation;
use App\Filament\Admin\Resources\Evaluations\Pages\EditEvaluation;
use App\Filament\Admin\Resources\Evaluations\Pages\ListEvaluations;
use App\Filament\Admin\Resources\Evaluations\Schemas\EvaluationSchema;
use App\Filament\Admin\Resources\Evaluations\Tables\EvaluationsTable;
use App\Models\Evaluation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EvaluationResource extends Resource
{
    protected static ?string $model = Evaluation::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    public static function getNavigationGroup(): ?string
    {
        return 'إدارة التقييمات والشكاوى';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getModelLabel(): string
    {
        return 'تقييم أداء';
    }

    public static function getPluralModelLabel(): string
    {
        return 'سجل تقييمات الأداء';
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('evaluations')) {
                $pendingCount = \App\Models\Evaluation::where('status', 'submitted')->count();
                return $pendingCount > 0 ? (string)$pendingCount : null;
            }
        } catch (\Throwable) {
            return null;
        }

        return null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return EvaluationSchema::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EvaluationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvaluations::route('/'),
            'create' => CreateEvaluation::route('/create'),
            'edit' => EditEvaluation::route('/{record}/edit'),
        ];
    }
}
