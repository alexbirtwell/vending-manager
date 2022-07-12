<?php

namespace App\Providers;

use App\Models\ServiceLog;
use App\Models\ServiceNote;
use App\Observers\ServiceLogObserver;
use App\Observers\ServiceNoteObserver;
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
         ServiceLog::observe(ServiceLogObserver::class);
         ServiceNote::observe(ServiceNoteObserver::class);
    }
}
