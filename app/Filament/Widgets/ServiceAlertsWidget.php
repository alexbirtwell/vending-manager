<?php

namespace App\Filament\Widgets;

use App\Models\ServiceLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ServiceAlertsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $upcomingService = ServiceLog::where('date_expected', '>=', now())
            ->where('date_expected', '<=', now()->addDays(7))
            ->count();

        $overdueService = ServiceLog::where('date_expected', '<', now())
            ->open()
            ->count();

        $resolvedService = ServiceLog::whereNotNull('date_completed')
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();

        return [
            Stat::make('Upcoming Service Due', $upcomingService)
                ->description('Next 7 days'),
            Stat::make('Overdue Service', $overdueService)
                ->description('Pending'),
            Stat::make('Service History', $resolvedService)
                ->description('Resolved in last 30 days'),
        ];
    }
} 