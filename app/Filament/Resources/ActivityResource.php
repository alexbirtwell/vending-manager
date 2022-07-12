<?php
namespace App\Filament\Resources;

class ActivityResource extends \AlexJustesen\FilamentSpatieLaravelActivitylog\Resources\ActivityResource
{
    public static function canViewAny(): bool
    {
        return auth()->user()->hasPermissionTo('View Activity');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->hasPermissionTo('Delete Activity');
    }
}
