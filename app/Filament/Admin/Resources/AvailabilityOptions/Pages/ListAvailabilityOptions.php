<?php

namespace App\Filament\Admin\Resources\AvailabilityOptions\Pages;

use App\Filament\Admin\Resources\AvailabilityOptions\AvailabilityOptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAvailabilityOptions extends ListRecords
{
    protected static string $resource = AvailabilityOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
