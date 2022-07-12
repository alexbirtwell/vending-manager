<?php

namespace App\Filament\Resources\IncomeLogResource\Pages;

use App\Filament\Resources\IncomeLogResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncomeLogs extends ListRecords
{
    protected static string $resource = IncomeLogResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
