<?php

namespace App\Filament\Resources;

use Illuminate\Database\Eloquent\Model;

class UserResource extends \Phpsa\FilamentAuthentication\Resources\UserResource
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
}
