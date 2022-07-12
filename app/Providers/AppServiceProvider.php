<?php

namespace App\Providers;

use App\Models\MachineNote;
use App\Models\ServiceLog;
use App\Models\ServiceNote;
use App\Models\SiteNote;
use App\Observers\MachineNoteObserver;
use App\Observers\ServiceLogObserver;
use App\Observers\ServiceNoteObserver;
use App\Observers\SiteNoteObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
         Schema::defaultStringLength(191);
         ServiceLog::observe(ServiceLogObserver::class);
         ServiceNote::observe(ServiceNoteObserver::class);
         SiteNote::observe(SiteNoteObserver::class);
         MachineNote::observe(MachineNoteObserver::class);
    }
}
