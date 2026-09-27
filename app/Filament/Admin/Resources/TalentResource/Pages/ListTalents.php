<?php

namespace App\Filament\Admin\Resources\TalentResource\Pages;

use App\Filament\Admin\Resources\TalentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTalents extends ListRecords
{
    protected static string $resource = TalentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('kanban_board')
                ->label('عرض كلوحة كانبان')
                ->icon('heroicon-o-view-columns')
                ->color('success')
                ->url(TalentResource::getUrl('kanban')),

            Actions\CreateAction::make()
                ->label('إضافة مرشح جديد')
                ->icon('heroicon-o-user-plus'),

            Actions\Action::make('openPublicForm')
                ->label('رابط استمارة التقديم الخارجي')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('info')
                ->url(url('/apply'), shouldOpenInNewTab: true),
        ];
    }
}
