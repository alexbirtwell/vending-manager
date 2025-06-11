<?php

namespace App\Providers;

use App\Http\Integrations\SendLayer\SendLayerConnector;
use App\Http\Livewire\Forms\PublicServiceForm;
use App\Models\IncomeLog;
use App\Models\Machine;
use App\Models\MachineNote;
use App\Models\ServiceLog;
use App\Models\ServiceNote;
use App\Models\Site;
use App\Models\SiteNote;
use App\Observers\MachineNoteObserver;
use App\Observers\ServiceLogObserver;
use App\Observers\ServiceNoteObserver;
use App\Observers\SiteNoteObserver;
use App\Services\Mail\SendLayerMailTransport;
use App\View\Components\AppLayout;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Mail;
use RalphJSmit\Filament\Activitylog\Infolists\Components\Timeline;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Create a custom FilamentAuthentication class that fixes the issue with overrideResources
        $this->app->bind(\Phpsa\FilamentAuthentication\FilamentAuthentication::class, function ($app) {
            return new class extends \Phpsa\FilamentAuthentication\FilamentAuthentication {
                public static function make(): \Phpsa\FilamentAuthentication\FilamentAuthentication
                {
                    $instance = new static();
                    $config = config('filament-authentication');

                    // Ensure models is an array
                    $instance->overrideModels($config['models'] ?? []);

                    // Ensure resources is an array
                    $instance->overrideResources($config['resources'] ?? []);

                    // Set other configuration values with defaults
                    $instance->setPreload(
                        $config['preload_roles'] ?? true,
                        $config['preload_permissions'] ?? true
                    );

                    $instance->setImpersonation(
                        $config['impersonate']['enabled'] ?? false,
                        $config['impersonate']['guard'] ?? 'web',
                        $config['impersonate']['redirect'] ?? '/'
                    );

                    $instance->withSoftDeletes($config['soft_deletes'] ?? false);

                    return $instance;
                }
            };
        });
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

        Livewire::component('public-service-form', PublicServiceForm::class);

        Timeline::configureUsing(function (Timeline $timeline) {
           return $timeline
                ->eventDescriptions([
                    'created' => 'created a new :subject.name',
                    'updated' => 'updated :subject.name',
                    'deleted' => 'deleted :subject.name',
                ])
                ->eventDescription('created', function (Activity $activity) {
                    $causer = $activity->causer?->name ?? 'System';
                    return new HtmlString("Created by <strong>{$causer}</strong>");
                })
               ->eventDescription('updated', function (Activity $activity) {
                         $causer = $activity->causer?->name ?? 'System';
                         $changes = $activity->changes;
                         if(!isset($changes['attributes'])) {
                             return new HtmlString("Updated by <strong>{$causer}</strong>");
                         }

                         $html = '<ul>';
                         foreach($changes['attributes'] as $key => $value) {
                             if($key == 'updated_at') {
                                 continue;
                             }
                             $html .= '<li class="text-xs"><strong>' . $key . '</strong> changed from <strong>' . $changes['old'][$key] . '</strong> to <strong>' . $value . '</strong></li>';
                         }
                         $html .= '</ul>';


                         return new HtmlString("Updated by <strong>{$causer}</strong>.{$html}");
                     })
                ->itemIcons([
                    // Applies to all Eloquent models...
                    'updated' => 'heroicon-o-pencil',
                    'created' => 'heroicon-o-plus-circle',
                    'deleted' => 'heroicon-o-trash',
                ])
                ->itemIcon('created', 'heroicon-o-plus-circle', [ServiceLog::class, Machine::class, IncomeLog::class, Site::class]) // Applies only to `Post` and `GlossaryItem` model.
                ->itemIconColors([
                    'created' => 'success',
                    'updated' => 'info',
                    'deleted' => 'danger',
                ]);
        });
//        Livewire::component('app-layout', AppLayout::class);
        Mail::extend('sendlayer', function (array $config) {
            return new SendLayerMailTransport(
                new SendLayerConnector()
            );
        });
    }
}
