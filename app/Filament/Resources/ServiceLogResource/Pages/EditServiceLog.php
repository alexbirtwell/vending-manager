<?php

namespace App\Filament\Resources\ServiceLogResource\Pages;

use App\Filament\Resources\ServiceLogResource;
use App\Models\ServiceLog;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class EditServiceLog extends EditRecord
{
    protected static string $resource = ServiceLogResource::class;

    protected function getHeaderActions(): array
    {
        $hideComplete = $this->record->date_completed !== null;
        return [
            Actions\DeleteAction::make(),
            Actions\EditAction::make('Complete')
                ->label('Mark as Complete')
                ->color('success')
                ->icon('heroicon-s-check')
                ->action('completeServiceLog')
                ->hidden($hideComplete)
                ->requiresConfirmation()

        ];
    }

    public function completeServiceLog(): void
    {
        $record = $this->getRecord();
        $record->date_completed = now();
        $record->save();
        $this->notify('success', 'Service Log Completed!');
        $this->redirect(ServiceLogResource::getUrl('index'));
    }
}
