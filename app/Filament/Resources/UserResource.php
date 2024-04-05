<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Password;
use Phpsa\FilamentAuthentication\Resources\UserResource\RelationManager\RoleRelationManager;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;

class UserResource extends Resource
{
    public static function canCreate(): bool
    {
        return auth()->user()->hasPermissionTo('Create User');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->hasPermissionTo('Delete User');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->id == $record->id || auth()->user()->hasPermissionTo('Edit User');
    }

    public static function canViewAny(): bool
    {
        return self::canCreate() || self::canDeleteAny();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->autofocus()
                    ->required()
                    ->placeholder('John Doe'),
                TextInput::make('email')
                    ->dehydrated(fn ($record) => !$record)
                    ->disabled(fn ($record) => $record),
                TextInput::make('password')
                    ->password()
                    ->label('Change Password')
                    ->dehydrated(fn ($state) => $state && strlen($state))
                    ->hidden(fn (?User $record) => ! $record)
                    ->helperText('Leave blank to ignore. You should use the send reset password link above instead of this.'),
                TextInput::make('confirm_password')
                    ->label('Confirm Password')
                    ->required(fn (callable $get) => $get('password') !== null)
                    ->rules('same:password')
                    ->dehydrated(false)
                    ->hidden(fn (?User $record) => $record === null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TagsColumn::make('roles')
                    ->getStateUsing(fn (User $record) => $record->roles->pluck('name')->toArray()),
                TextColumn::make('last_logged_in')
                    ->getStateUsing(fn (User $record) => $record->last_logged_in ? $record->last_logged_in->diffForHumans() : 'Never'),

            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    Impersonate::make()
                        ->visible(fn (?User $record) => auth()->user()?->hasRole('Admin')),
                    Action::make('send_reset_link')
                        ->label('Send Password Reset Link')
                        ->action(action: function (?User $record) {
                            Password::sendResetLink(['email' => $record?->email]);
                            Notification::make('reset')
                                ->success()
                                ->body('Password reset link sent.')
                                ->send();
                        })
                        ->icon('heroicon-o-paper-airplane')
                        ->hidden(fn (?User $record) => ! $record),
                ]),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //RelationManagers\LinkableRelationManager::class
            RoleRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['roles']); // TODO: Change the autogenerated stub
    }
}
