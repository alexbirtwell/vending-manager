<?php

namespace App\Filament\Resources\MachineExpenseResource\Pages;

use App\Filament\Resources\MachineExpenseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMachineExpense extends CreateRecord
{
    protected static string $resource = MachineExpenseResource::class;
}
