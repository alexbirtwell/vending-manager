<?php

namespace App\Filament\Resources\IncomeLogResource\Pages;

use App\Filament\Resources\IncomeLogResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use RalphJSmit\Filament\Activitylog\Actions\TimelineAction;

class EditIncomeLog extends EditRecord
{
    protected static string $resource = IncomeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            TimelineAction::make()
                ->label('History'),
            Actions\DeleteAction::make(),
        ];
    }
}
