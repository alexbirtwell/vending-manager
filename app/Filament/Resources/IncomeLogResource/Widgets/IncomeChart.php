<?php

namespace App\Filament\Resources\IncomeLogResource\Widgets;

use App\Models\IncomeLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class IncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Income Chart';
    protected int | string | array $columnSpan = 'full'; // '1/3', '2/3', 'full

    protected function getData(): array
    {
        $query = IncomeLog::select(DB::raw('SUM(amount) as total_amount, YEAR(created_at) as year, MONTH(created_at) as month'))
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->get();
        return [
            'datasets' => [
                [
                    'label' => 'Income Per Month',
                    'data' => $query->pluck('total_amount'),
                ],
            ],
            'labels' => $query->map(function ($row) {
                return Carbon::create()->month($row->month)->year($row->year)->format('M y');
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
