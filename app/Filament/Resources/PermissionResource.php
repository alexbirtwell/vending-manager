<?php

namespace App\Filament\Resources;


use Illuminate\Database\Eloquent\Model;

class PermissionResource extends \Phpsa\FilamentAuthentication\Resources\PermissionResource
{
    public static function canCreate(): bool
    {
        return auth()->user()->hasPermissionTo('Create Permission');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->hasPermissionTo('Delete Permission');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasPermissionTo('Edit Permission');
    }

    public static function canViewAny(): bool
    {
        return self::canCreate() || self::canDeleteAny();
    }
}
