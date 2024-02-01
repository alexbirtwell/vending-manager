<?php

namespace App\Filament\Resources\SiteResource\Pages;

use App\Filament\Resources\SiteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use RalphJSmit\Filament\Activitylog\Actions\TimelineAction;

class EditSite extends EditRecord
{
    protected static string $resource = SiteResource::class;

    protected function getHeaderActions(): array
    {
        session()->put('site_id', $this->record->id);
        return [
            TimelineAction::make()
                ->label('History'),
            Actions\DeleteAction::make(),
        ];
    }
}
