<?php

namespace App\Filament\Widgets;

use App\Models\Machine;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MachinePerformanceWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $topMachines = Machine::withSum('income', 'amount')
            ->orderByDesc('income_sum_amount')
            ->take(3)
            ->get();

        $lowMachines = Machine::withSum('income', 'amount')
            ->orderBy('income_sum_amount')
            ->take(3)
            ->get();

        $totalMachines = Machine::count();
        $operationalMachines = $totalMachines; // Assume all machines are operational for now
        $uptimePercentage = $totalMachines > 0 ? ($operationalMachines / $totalMachines) * 100 : 0;

        return [
            Stat::make('Top Performing Machines', $topMachines->pluck('name')->join(', '))
                ->description('Income: ' . config('business.currency.symbol') . number_format($topMachines->sum('income_sum_amount'), config('business.currency.decimals')) . ' | Sites: ' . $topMachines->pluck('site.name')->join(', ')),
            Stat::make('Low Performing Machines', $lowMachines->pluck('name')->join(', '))
                ->description('Income: ' . config('business.currency.symbol') . number_format($lowMachines->sum('income_sum_amount'), config('business.currency.decimals')) . ' | Sites: ' . $lowMachines->pluck('site.name')->join(', ')),
            Stat::make('Machine Uptime', number_format($uptimePercentage, 2) . '%')
                ->description('Operational: ' . $operationalMachines . ' / ' . $totalMachines),
        ];
    }
} 