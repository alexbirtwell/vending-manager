<?php

namespace App\Models;

use App\Notifications\ServiceLogCompleted;
use App\Notifications\ServiceLogCompletedCustomer;
use App\Notifications\ServiceLogCreated;
use App\Notifications\ServiceLogCreatedCustomer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Filament\Resources\ServiceLogResource;

class ServiceLog extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    protected $dates = ['date_completed', 'date_reported', 'date_expected'];
    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class,'assigned_user');
    }

    public function logger(): BelongsTo
    {
        return $this->belongsTo(User::class,'logged_user');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ServiceNote::class, 'service_id');
    }

     public function getSiteAttribute(): Site
    {
        return $this->machine->site;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereNull('date_completed');
    }

    public function scopeDueToday(Builder $query): Builder
    {
        return $query->open()->whereDate('date_expected', '<=', now()->format('Y-m-d'));
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->open()->whereDate('date_expected', '<', now()->format('Y-m-d'));
    }

    public function getFullAddressAttribute(): string
    {
        $site = $this->site;
        $address = $site->address_line_1;
        if ($site->address_line_2) {
            $address .= ', ' . $site->address_line_2;
        }
        if ($site->address_city) {
            $address .= ', ' . $site->address_city;
        }
        if ($site->address_region) {
            $address .= ', ' . $site->address_region;
        }
         if ($site->address_postal_code) {
            $address .= ', ' . $site->address_postal_code;
        }

         return $address;
    }

    public function getTravelTimeAttribute(): string
    {
        $site = $this->site;
        if($site->travel_time){
            return __('Expected Travel Time') . ': ' . $site->travel_time . " (" .__('Minutes') . ")";
        }
    }

    public function getUrlAttribute(): string
    {
        return ServiceLogResource::getUrl('edit', $this->id);
    }

    public function getLatestNoteAttribute(): string
    {
        return $this->notes()?->latest()?->first()?->note ?? "Non Added";
    }

    public function getActivitySubjectDescription()
    {
        return $this->id . " - " . $this->description;
    }

    public function completeListener(): void
    {
        //notifications
        $this->assignee->notify(new ServiceLogCompleted($this));
        $this->logger->notify(new ServiceLogCompleted($this));
        if ($this->site->main_contact_email) {
            Notification::route('mail', $this->site->main_contact_email)->notify(new ServiceLogCompletedCustomer($this));
        }
    }

    public function createdListener(): void
    {
        //notifications
        $this->assignee->notify(new ServiceLogCreated($this));
        $this->logger->notify(new ServiceLogCreated($this));
        if ($this->site->main_contact_email) {
            Notification::route('mail', $this->site->main_contact_email)->notify(new ServiceLogCreatedCustomer($this));
        }
    }

}
