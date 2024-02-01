<?php

namespace App\Filament\Resources\MachineResource\Pages;

use App\Filament\Resources\MachineResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use RalphJSmit\Filament\Activitylog\Actions\TimelineAction;

class EditMachine extends EditRecord
{
    protected static string $resource = MachineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            TimelineAction::make()
                ->label('History'),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
