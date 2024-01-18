<?php

namespace App\Models;

use App\Notifications\ServiceLogNoteAdded;
use App\Notifications\ServiceLogNoteAddedCustomer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceNote extends Model
{
    use HasFactory, LogsActivity;
      protected $guarded = [];
      public $timestamps = true;

    public function serviceLog(): BelongsTo
    {
        return $this->belongsTo(ServiceLog::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }

    public function createdListener(): void
    {
        $this->serviceLog->assignee->notify(new ServiceLogNoteAdded($this));
        $this->serviceLog->logger->notify(new ServiceLogNoteAdded($this));
        if ($this->serviceLog->site->main_contact_email) {
            Notification::route('mail', $this->serviceLog->site->main_contact_email)->notify(new ServiceLogNoteAddedCustomer($this));
        }
    }
}
