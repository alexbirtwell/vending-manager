<?php

namespace App\Filament\Resources\SiteResource\Pages;

use App\Filament\Resources\SiteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSite extends EditRecord
{
    protected static string $resource = SiteResource::class;

    protected function getActions(): array
    {
        session()->put('site_id', $this->record->id);
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
