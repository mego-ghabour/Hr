<?php

namespace App\Filament\Admin\Resources\TalentStatusResource\Pages;

use App\Filament\Admin\Resources\TalentStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTalentStatuses extends ManageRecords
{
    protected static string $resource = TalentStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}












