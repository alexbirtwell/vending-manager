<?php

namespace App\Observers;

use App\Models\Machine;
use App\Models\ServiceLog;
use Illuminate\Http\Request;

class ServiceLogObserver
{
    //
    public function creating(ServiceLog $serviceLog)
    {
        if (!$serviceLog->assigned_user) {
             $serviceLog->assigned_user = Machine::find($serviceLog->machine_id)?->site?->default_assignee;
        }

        if (!$serviceLog->logged_user) {
            $serviceLog->logged_user = auth()->user()->id;
        }

         if (!$serviceLog->date_expected) {
             $service_days = $serviceLog?->machine?->site->service_days ?? config('business.default_service_days');
            $serviceLog->date_expected = now()->addDays($service_days);
        }
    }

    public function created(ServiceLog $serviceLog)
    {
        $serviceLog->createdListener();
    }

    public function updating(ServiceLog $serviceLog)
    {
        if (!$serviceLog->getOriginal('date_completed') && $serviceLog->date_completed) {
            $serviceLog->completeListener();
        }
    }
}
