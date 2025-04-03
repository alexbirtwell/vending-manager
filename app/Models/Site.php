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

    public $timestamps = true;

    protected $guarded = [];

    public function country(): belongsTo
    {
        return $this->belongsTo(Country::class, 'address_country_id');
    }

    public function defaultAssignee(): belongsTo
    {
        return $this->belongsTo(User::class, 'default_assignee');
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
    public function incomeLogs(): HasManyThrough
    {
        return $this->hasManyThrough(IncomeLog::class, Machine::class, 'site_id', 'machine_id');
    }

    public function getAddress(): string
    {
      return $this->address_line_1 . ', ' . $this->address_line_2 . ', ' . $this->address_city . ', ' . $this->address_region . ', ' . $this->address_postal_code . ', ' . ($this->country?->name ?? "UK");
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logAll()->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }
}
