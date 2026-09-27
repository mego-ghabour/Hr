<?php

namespace App\Filament\Admin\Resources\TalentResource\Pages;

use App\Filament\Admin\Resources\TalentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTalent extends EditRecord
{
    protected static string $resource = TalentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('حذف المرشح'),
            Actions\ForceDeleteAction::make()->label('حذف نهائي'),
            Actions\RestoreAction::make()->label('استعادة'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'تم تحديث بيانات المرشح بنجاح!';
    }

    protected function afterSave(): void
    {
        // إرسال إشعار في لوحة التحكم عند تحديث البيانات
        $admins = \App\Models\User::all();
        \Filament\Notifications\Notification::make()
            ->title('تم تحديث البيانات')
            ->body("**{$this->getRecord()->full_name}** تم تعديل بياناته.")
            ->icon('heroicon-o-pencil-square')
            ->iconColor('info')
            ->color('info')
            ->actions([
                \Filament\Actions\Action::make('view')
                    ->label('عرض التحديث')
                    ->button()
                    ->url(\App\Filament\Admin\Resources\TalentResource::getUrl('view', ['record' => $this->getRecord()->id]))
                    ->markAsRead(),
            ])
            ->sendToDatabase($admins);
    }
}
