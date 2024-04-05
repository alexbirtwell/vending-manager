<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Password;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = bcrypt(\Str::password(16));
        return $data;
    }

    protected function afterCreate(): void
    {
        if ($email = $this->form->getState()['email']) {
            Password::sendResetLink(['email' => $email]);
            Notification::make('reset')
                ->success()
                ->body('Password instructions sent.')
                ->send();

        }
    }
}
