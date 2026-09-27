<?php

namespace App\Filament\Admin\Resources\TalentResource\Pages;

use App\Filament\Admin\Resources\TalentResource;
use App\Enums\DocumentType;
use Filament\Resources\Pages\CreateRecord;

class CreateTalent extends CreateRecord
{
    protected static string $resource = TalentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'تم إضافة المرشح بنجاح! 🎉';
    }

    protected function afterCreate(): void
    {
        $cvPath = $this->data['cv'] ?? null;

        // Filament FileUpload sometimes returns an array even for single files
        if (is_array($cvPath)) {
            $cvPath = array_values($cvPath)[0] ?? null;
        }

        if ($cvPath && is_string($cvPath)) {
            $originalName = basename($cvPath);
            $fullPath = storage_path('app/public/' . $cvPath);
            $size = file_exists($fullPath) ? filesize($fullPath) : null;
            $mimeType = file_exists($fullPath) ? mime_content_type($fullPath) : null;

            $this->getRecord()->documents()->create([
                'type'      => DocumentType::CV,
                'file_name' => $originalName,
                'file_path' => $cvPath,
                'mime_type' => $mimeType,
                'size'      => $size,
            ]);
        }

        // إرسال إشعار
        $admins = \App\Models\User::all();
        \Filament\Notifications\Notification::make()
            ->title('تمت الإضافة يدوياً!')
            ->body("**{$this->getRecord()->full_name}** تمت إضافته للمرشحين.")
            ->icon('heroicon-o-user-plus')
            ->iconColor('success')
            ->color('success')
            ->actions([
                \Filament\Actions\Action::make('view')
                    ->label('عرض الملف')
                    ->button()
                    ->url(\App\Filament\Admin\Resources\TalentResource::getUrl('view', ['record' => $this->getRecord()->id]))
                    ->markAsRead(),
            ])
            ->sendToDatabase($admins);
    }
}
