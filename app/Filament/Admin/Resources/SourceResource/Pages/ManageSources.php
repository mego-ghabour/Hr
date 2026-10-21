<?php

namespace App\Filament\Admin\Resources\SourceResource\Pages;

use App\Filament\Admin\Resources\SourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSources extends ManageRecords
{
    protected static string $resource = SourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function mount(): void
    {
        $this->redirect(\App\Filament\Admin\Pages\Settings::getUrl(['tab' => 'sources']));
    }
}












