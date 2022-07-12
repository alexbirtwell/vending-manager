<?php

namespace App\Observers;

use App\Models\ServiceNote;
use App\Models\SiteNote;
use Illuminate\Http\Request;

class SiteNoteObserver
{
    //

    public function creating(SiteNote $siteNote)
    {
        $siteNote->user_id = auth()->user()?->id ?? null;
    }
}
