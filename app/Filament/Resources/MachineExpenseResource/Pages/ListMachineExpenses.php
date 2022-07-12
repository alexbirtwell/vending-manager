<?php

namespace App\Filament\Resources\MachineExpenseResource\Pages;

use App\Filament\Resources\MachineExpenseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMachineExpenses extends ListRecords
{
    protected static string $resource = MachineExpenseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
