<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Password;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('send_reset_link')
                        ->label('Send Password Reset Link')
                        ->action(action: function (?User $record) {
                            Password::sendResetLink(['email' => $record?->email]);
                            Notification::make('reset')
                                ->success()
                                ->body('Password reset link sent.')
                                ->send();
                        })
                        ->icon('heroicon-o-paper-airplane'),
            Actions\DeleteAction::make(),
        ];
    }
}
