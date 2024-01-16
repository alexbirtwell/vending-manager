<?php

namespace App\Filament\Resources\SiteNoteResource\Pages;

use App\Filament\Resources\SiteNoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSiteNotes extends ListRecords
{
    protected static string $resource = SiteNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
