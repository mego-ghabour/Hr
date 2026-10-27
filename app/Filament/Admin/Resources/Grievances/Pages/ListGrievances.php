<?php

namespace App\Filament\Admin\Resources\Grievances\Pages;

use App\Filament\Admin\Resources\Grievances\GrievanceResource;
use App\Models\Grievance;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListGrievances extends ListRecords
{
    protected static string $resource = GrievanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('share_link')
                ->label('مشاركة ونسخ الرابط للموظفين')
                ->icon('heroicon-o-share')
                ->color('info')
                ->modalHeading('مشاركة رابط صندوق الشكاوى مع الموظفين')
                ->modalDescription('انسخ الرابط المباشر، أو شاركه فوراً عبر واتساب / الإيميل، أو اطبع رمز الـ QR Code.')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('إغلاق')
                ->form([
                    ViewField::make('share_box')
                        ->view('filament.actions.share-grievance-link'),
                ]),

            CreateAction::make()
                ->label('تسجيل شكوى جديدة')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTabs(): array
    {
        $newCount = 0;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('grievances')) {
                $newCount = Grievance::where('status', 'new')->count();
            }
        } catch (\Throwable) {
            $newCount = 0;
        }

        return [
            'all' => Tab::make('الكل'),

            'new' => Tab::make('جديدة')
                ->badge($newCount > 0 ? (string)$newCount : null)
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'new')),

            'in_progress' => Tab::make('قيد الدراسة')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'in_progress')),

            'resolved' => Tab::make('تم الحل')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'resolved')),

            'anonymous' => Tab::make('سرية 🔒')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_anonymous', true)),
        ];
    }
}
