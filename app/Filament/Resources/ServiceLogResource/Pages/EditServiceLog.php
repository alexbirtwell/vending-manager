<?php

namespace App\Filament\Resources\ServiceLogResource\Pages;

use App\Filament\Resources\ServiceLogResource;
use App\Models\ServiceLog;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use RalphJSmit\Filament\Activitylog\Actions\TimelineAction;

class EditServiceLog extends EditRecord
{
    protected static string $resource = ServiceLogResource::class;

    protected function getHeaderActions(): array
    {
        $hideComplete = $this->record->date_completed !== null;
        return [
            TimelineAction::make()
                ->label('History'),
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
        Notification::make('success')
            ->body('Service Log marked as complete.')
            ->success()
            ->persistent()
            ->send();

        $this->redirect(ServiceLogResource::getUrl('index'));
    }
}
