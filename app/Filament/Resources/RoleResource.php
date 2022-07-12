<?php

namespace App\Filament\Resources;

use Illuminate\Database\Eloquent\Model;

class RoleResource extends \Phpsa\FilamentAuthentication\Resources\RoleResource
{
     public static function canCreate(): bool
    {
        return auth()->user()->hasPermissionTo('Create Role');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->hasPermissionTo('Delete Role');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasPermissionTo('Edit Role');
    }

    public static function canViewAny(): bool
    {
        return self::canCreate() || self::canDeleteAny();
    }
}
