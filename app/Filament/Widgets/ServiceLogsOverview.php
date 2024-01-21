<?php

namespace App\Filament\Widgets;

use App\Models\IncomeLog;
use App\Models\Machine;
use App\Models\MachineExpense;
use App\Models\ServiceLog;
use App\Models\Site;
use Carbon\CarbonInterval;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ServiceLogsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $colors = [
            43200 => 'green-500',
            86400 => 'warning-500',
            172800 => 'danger-500',
        ];

        $secondsToCloseMonth = ServiceLog::select(DB::raw('AVG(TIMESTAMPDIFF(SECOND, created_at, date_completed)) AS average_close_time'))
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->value('average_close_time');
        $secondsToCloseTotal = ServiceLog::select(DB::raw('AVG(TIMESTAMPDIFF(SECOND, created_at, date_completed)) AS average_close_time'))
                ->value('average_close_time');

        $secondsToCloseMonthColor = match(true) {
            $secondsToCloseMonth < 43200 => 'success',
            $secondsToCloseMonth < 86400 => 'warning',
            $secondsToCloseMonth < 172800 => 'danger',
            default => 'danger',
        };
        $secondsToCloseTotalColor = match(true) {
            $secondsToCloseTotal < 43200 => 'success',
            $secondsToCloseTotal < 86400 => 'warning',
            $secondsToCloseTotal < 172800 => 'danger',
            default => 'danger',
        };

         return [
            Stat::make('Open service logs', ServiceLog::open()->count()),
            Stat::make('Closed this month', ServiceLog::where('date_completed', '>=', now()->startOfMonth())->count() ),
            Stat::make('Average time to close', CarbonInterval::seconds($secondsToCloseMonth)->cascade()->seconds(0)->forHumans())
                ->description('This month.')
                ->color($secondsToCloseMonthColor),
             Stat::make('Average time to close', CarbonInterval::seconds($secondsToCloseTotal)->cascade()->seconds(0)->forHumans())
             ->description('All time.')
             ->color($secondsToCloseTotalColor),
        ];
    }
}
