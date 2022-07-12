<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Site extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function country(): belongsTo
    {
        return $this->belongsTo(Country::class, 'address_country_id');
    }

    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class, 'site_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(SiteNote::class, 'site_id');
    }

    public function serviceLogs(): HasManyThrough
    {
        return $this->hasManyThrough(ServiceLog::class, Machine::class, 'site_id', 'machine_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logAll()->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }
}
