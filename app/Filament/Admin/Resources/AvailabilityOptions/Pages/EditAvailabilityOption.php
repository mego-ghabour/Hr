<?php

namespace App\Filament\Admin\Resources\AvailabilityOptions\Pages;

use App\Filament\Admin\Resources\AvailabilityOptions\AvailabilityOptionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAvailabilityOption extends EditRecord
{
    protected static string $resource = AvailabilityOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
