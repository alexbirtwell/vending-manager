<?php

namespace App\Filament\Resources\ServiceNoteResource\Pages;

use App\Filament\Resources\ServiceNoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceNote extends CreateRecord
{
    protected static string $resource = ServiceNoteResource::class;
}
