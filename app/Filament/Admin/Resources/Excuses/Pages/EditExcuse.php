<?php

namespace App\Filament\Admin\Resources\Excuses\Pages;

use App\Filament\Admin\Resources\Excuses\ExcuseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditExcuse extends EditRecord
{
    protected static string $resource = ExcuseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
