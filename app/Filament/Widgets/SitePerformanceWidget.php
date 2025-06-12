<?php

namespace App\Filament\Widgets;

use App\Models\Site;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SitePerformanceWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $topSites = Site::withSum('incomeLogs', 'amount')
            ->orderByDesc('income_logs_sum_amount')
            ->take(3)
            ->get();

        $lowSites = Site::withSum('incomeLogs', 'amount')
            ->orderBy('income_logs_sum_amount')
            ->take(3)
            ->get();

        return [
            Stat::make('Top Performing Sites', $topSites->pluck('name')->join(', '))
                ->description('Income: ' . config('business.currency.symbol') . number_format($topSites->sum('income_logs_sum_amount'), config('business.currency.decimals'))),
            Stat::make('Low Performing Sites', $lowSites->pluck('name')->join(', '))
                ->description('Income: ' . config('business.currency.symbol') . number_format($lowSites->sum('income_logs_sum_amount'), config('business.currency.decimals'))),
        ];
    }
} 