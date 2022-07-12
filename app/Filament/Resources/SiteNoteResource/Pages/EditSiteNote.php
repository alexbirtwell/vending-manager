<?php

namespace App\Filament\Resources\SiteNoteResource\Pages;

use App\Filament\Resources\SiteNoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiteNote extends EditRecord
{
    protected static string $resource = SiteNoteResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
