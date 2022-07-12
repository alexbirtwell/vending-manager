<?php

namespace App\Observers;

use App\Models\ServiceNote;
use Illuminate\Http\Request;

class ServiceNoteObserver
{
    //

    public function created(ServiceNote $serviceNote)
    {
        $serviceNote->createdListener();
    }
}
