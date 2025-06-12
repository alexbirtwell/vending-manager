<?php

namespace App\Filament\Widgets;

use App\Models\IncomeLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class IncomeTrendWidget extends ChartWidget
{
    protected static ?string $heading = 'Income Trends';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = IncomeLog::selectRaw('date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Daily Income',
                    'data' => $data->pluck('total')->toArray(),
                    'borderColor' => '#10B981',
                ],
            ],
            'labels' => $data->pluck('date')->map(fn ($date) => Carbon::parse($date)->format('M d'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
} 