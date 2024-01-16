<?php

namespace App\Filament\Resources\MachineExpenseResource\Pages;

use App\Filament\Resources\MachineExpenseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMachineExpense extends EditRecord
{
    protected static string $resource = MachineExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
