<?php

namespace App\Filament\Admin\Resources\Grievances;

use App\Filament\Admin\Resources\Grievances\Pages\CreateGrievance;
use App\Filament\Admin\Resources\Grievances\Pages\EditGrievance;
use App\Filament\Admin\Resources\Grievances\Pages\ListGrievances;
use App\Filament\Admin\Resources\Grievances\Schemas\GrievanceSchema;
use App\Filament\Admin\Resources\Grievances\Tables\GrievancesTable;
use App\Models\Grievance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class GrievanceResource extends Resource
{
    protected static ?string $model = Grievance::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-exclamation';

    public static function getNavigationGroup(): ?string
    {
        return 'إدارة التقييمات والشكاوى';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getModelLabel(): string
    {
        return 'شكوى أو مقترح';
    }

    public static function getPluralModelLabel(): string
    {
        return 'صندوق الشكاوى والمقترحات';
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('grievances')) {
                $newCount = Grievance::where('status', 'new')->count();
                return $newCount > 0 ? (string)$newCount : null;
            }
        } catch (\Throwable) {
            return null;
        }

        return null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Schema $schema): Schema
    {
        return GrievanceSchema::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GrievancesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGrievances::route('/'),
            'create' => CreateGrievance::route('/create'),
            'edit' => EditGrievance::route('/{record}/edit'),
        ];
    }
}
