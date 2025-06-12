<?php

namespace App\Filament\Widgets;

use App\Models\IncomeLog;
use App\Models\MachineExpense;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinancialOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $currentMonthIncome = IncomeLog::where('date', '>=', now()->startOfMonth())->sum('amount');
        $currentMonthExpenses = MachineExpense::where('date', '>=', now()->startOfMonth())->sum('amount');
        $previousMonthIncome = IncomeLog::where('date', '>=', now()->subMonth()->startOfMonth())->where('date', '<', now()->startOfMonth())->sum('amount');
        $previousMonthExpenses = MachineExpense::where('date', '>=', now()->subMonth()->startOfMonth())->where('date', '<', now()->startOfMonth())->sum('amount');

        $currentMonthProfit = $currentMonthIncome - $currentMonthExpenses;
        $previousMonthProfit = $previousMonthIncome - $previousMonthExpenses;
        $profitMargin = $currentMonthIncome > 0 ? ($currentMonthProfit / $currentMonthIncome) * 100 : 0;
        $trendIndicator = $currentMonthProfit > $previousMonthProfit ? 'up' : 'down';

        return [
            Stat::make('Monthly Income', config('business.currency.symbol') . number_format($currentMonthIncome, config('business.currency.decimals')))
                ->description('Previous: ' . config('business.currency.symbol') . number_format($previousMonthIncome, config('business.currency.decimals'))),
            Stat::make('Monthly Expenses', config('business.currency.symbol') . number_format($currentMonthExpenses, config('business.currency.decimals')))
                ->description('Previous: ' . config('business.currency.symbol') . number_format($previousMonthExpenses, config('business.currency.decimals'))),
            Stat::make('Profit Margin', number_format($profitMargin, 2) . '%')
                ->description('Trend: ' . $trendIndicator),
        ];
    }
} 