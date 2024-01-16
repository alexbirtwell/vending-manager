<?php

namespace App\Filament\Resources\ServiceNoteResource\Pages;

use App\Filament\Resources\ServiceNoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceNote extends EditRecord
{
    protected static string $resource = ServiceNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
