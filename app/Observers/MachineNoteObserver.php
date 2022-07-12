<?php

namespace App\Observers;

use App\Models\MachineNote;
use App\Models\ServiceNote;
use App\Models\SiteNote;
use Illuminate\Http\Request;

class MachineNoteObserver
{
    //

    public function creating(MachineNote $machineNote)
    {
        $machineNote->user_id = auth()->user()?->id ?? null;
    }
}
