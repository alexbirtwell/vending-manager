<?php

namespace App\Filament\Resources\ServiceNoteResource\Pages;

use App\Filament\Resources\ServiceNoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceNotes extends ListRecords
{
    protected static string $resource = ServiceNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
