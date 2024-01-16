<?php

namespace App\Filament\Resources\SiteResource\Pages;

use App\Filament\Resources\MachineResource;
use App\Filament\Resources\SiteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSite extends ViewRecord
{
    protected static string $resource = SiteResource::class;


    protected function getHeaderActions(): array
    {
        session()->put('site_id', $this->record->id);
        return [
            Actions\EditAction::make(),
        ];
    }
}
