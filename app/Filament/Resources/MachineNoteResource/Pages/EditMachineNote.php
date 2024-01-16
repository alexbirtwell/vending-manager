<?php

namespace App\Filament\Resources\MachineNoteResource\Pages;

use App\Filament\Resources\MachineNoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMachineNote extends EditRecord
{
    protected static string $resource = MachineNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
