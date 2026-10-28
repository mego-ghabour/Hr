<?php

namespace App\Filament\Admin\Resources\Grievances\Pages;

use App\Filament\Admin\Resources\Grievances\GrievanceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGrievance extends EditRecord
{
    protected static string $resource = GrievanceResource::class;

    public function getTitle(): string
    {
        return 'متابعة ومعالجة الشكوى / المقترح';
    }

    public function getHeading(): string
    {
        return 'متابعة ومعالجة الشكوى / المقترح';
    }

    public function getSubheading(): ?string
    {
        $ticket = $this->getRecord()->ticket_number ?? '';
        return $ticket ? "رقم التذكرة: {$ticket}" : null;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
