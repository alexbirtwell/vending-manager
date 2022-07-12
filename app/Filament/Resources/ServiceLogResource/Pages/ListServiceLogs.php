<?php

namespace App\Filament\Resources\ServiceLogResource\Pages;

use App\Filament\Resources\ServiceLogResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListServiceLogs extends ListRecords
{
    protected static string $resource = ServiceLogResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
