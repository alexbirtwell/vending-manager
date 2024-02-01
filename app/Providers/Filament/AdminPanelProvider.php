<?php

namespace App\Providers\Filament;

use App\Filament\Resources\IncomeLogResource\Widgets\IncomeChart;
use App\Filament\Resources\SiteResource\Widgets\MyOpenServiceLogs;
use App\Filament\Resources\SiteResource\Widgets\OpenServiceLogs;
use App\Filament\Resources\SiteResource\Widgets\TopMachinesMonth;
use App\Filament\Resources\SiteResource\Widgets\TopMachinesTotal;
use App\Filament\Resources\SiteResource\Widgets\TopSites;
use App\Filament\Widgets\ServiceLogsOverview;
use App\Filament\Widgets\StatsOverview;
use App\Models\IncomeLog;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                MyOpenServiceLogs::class,
                StatsOverview::class,
                ServiceLogsOverview::class,
                TopSites::class,
                OpenServiceLogs::class,
                TopMachinesMonth::class,
                TopMachinesTotal::class,
                IncomeChart::class

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
