<?php

namespace App\Filament\Resources\IncomeLogResource\Pages;

use App\Filament\Resources\IncomeLogResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIncomeLog extends EditRecord
{
    protected static string $resource = IncomeLogResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
