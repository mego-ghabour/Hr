<?php

namespace App\Filament\Admin\Resources\DuplicateResource\Pages;

use App\Filament\Admin\Resources\DuplicateResource;
use Filament\Resources\Pages\ViewRecord;

use Filament\Actions\Action;
use App\Enums\DuplicateStatus;

class ViewDuplicate extends ViewRecord
{
    protected static string $resource = DuplicateResource::class;

    protected string $view = 'filament.admin.resources.duplicate-resource.pages.view-duplicate';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('merge')
                ->label('تم دمج البيانات')
                ->color('success')
                ->icon('heroicon-o-arrows-pointing-in')
                ->requiresConfirmation()
                ->action(function () {
                    $this->getRecord()->update(['status' => DuplicateStatus::MERGED]);
                    \Filament\Notifications\Notification::make()->title('تم تعيين الحالة: مدمج')->success()->send();
                })
                ->hidden(fn () => $this->getRecord()->status === DuplicateStatus::MERGED),
                
            Action::make('reject')
                ->label('رفض المكرر (حذف الجديد)')
                ->color('gray')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->action(function () {
                    $this->getRecord()->update(['status' => DuplicateStatus::REJECTED]);
                    // You could also delete the duplicate talent here if needed
                    \Filament\Notifications\Notification::make()->title('تم تعيين الحالة: مرفوض')->success()->send();
                })
                ->hidden(fn () => $this->getRecord()->status === DuplicateStatus::REJECTED),

            Action::make('confirm')
                ->label('تأكيد (شخصان مختلفان)')
                ->color('info')
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation()
                ->action(function () {
                    $this->getRecord()->update(['status' => DuplicateStatus::CONFIRMED]);
                    \Filament\Notifications\Notification::make()->title('تم تعيين الحالة: مؤكد اختلافهما')->success()->send();
                })
                ->hidden(fn () => $this->getRecord()->status === DuplicateStatus::CONFIRMED),
        ];
    }
}












