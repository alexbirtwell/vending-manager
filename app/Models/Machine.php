<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Machine extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public $timestamps = true;

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function getNameAttribute(): string
    {
        if (!empty($this->brand) && !empty($this->model)) {
            return $this->brand . ' - ' . $this->model;
        }

        return Str::title($this->machine_type . ' machine (' . $this->machine_number . ')');
    }

    protected static function booted(): void
    {
        static::creating(static function (Machine $machine): void {
            $uuid ??= Str::substr(Str::uuid(), -5);
            while(Machine::where('machine_uuid', $uuid)->count()) {
                 $uuid ??= Str::substr(Str::uuid(), -5);
            }
            $machine->uuid = $uuid;
        });
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('uuid', $value)->orWhere('id', $value)->ddQuery()->firstOrFail();
    }



    public function site(): belongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function serviceLogs(): hasMany
    {
        return $this->hasMany(ServiceLog::class);
    }

    public function expenses(): hasMany
    {
        return $this->hasMany(MachineExpense::class);
    }

    public function income(): hasMany
    {
        return $this->hasMany(IncomeLog::class);
    }

    public function notes(): hasMany
    {
        return $this->hasMany(MachineNote::class);
    }

    public function getSummaryAttribute(): string
    {
        return $this->brand . ' - ' . $this->model . ' (' . $this->machine_type . ' machine ' . __('accepting') . ' '. $this->payment_mechanic .  ')';
    }

    public function getActivitySubjectDescription()
    {
        return $this->summary;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }
}
