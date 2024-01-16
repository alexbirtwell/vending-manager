<?php

namespace App\Filament\Resources\MachineNoteResource\Pages;

use App\Filament\Resources\MachineNoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMachineNotes extends ListRecords
{
    protected static string $resource = MachineNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
