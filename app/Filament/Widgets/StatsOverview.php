<?php

namespace App\Filament\Widgets;

use App\Models\IncomeLog;
use App\Models\Machine;
use App\Models\MachineExpense;
use App\Models\Site;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
         return [
            Stat::make('Machine Count', Machine::count()),
            Stat::make('Site Count', Site::count()),
            Stat::make('Income This Month', config('business.currency.symbol') . number_format(IncomeLog::where('date', '>=', now()->startOfMonth())->sum('amount'),config('business.currency.decimals')))
             ->description('All time: '. config('business.currency.symbol') . number_format(IncomeLog::sum('amount'),config('business.currency.decimals'))),
            Stat::make('Expenses This Month', config('business.currency.symbol') . number_format(MachineExpense::where('date', '>=', now()->startOfMonth())->sum('amount'),config('business.currency.decimals')))
             ->description('All time: '. config('business.currency.symbol') . number_format(MachineExpense::sum('amount'),config('business.currency.decimals')))

        ];
    }
}
