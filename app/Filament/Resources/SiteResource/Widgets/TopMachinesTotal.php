<?php

namespace App\Filament\Resources\SiteResource\Widgets;

use App\Models\Machine;
use App\Models\Site;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopMachinesTotal extends BaseWidget
{
    protected static ?string $heading = 'Top Machines Total';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Machine::query()
                    ->withSum('income', 'amount')
                    ->orderBy('income_sum_amount', 'desc')
                ->limit(5)
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('income_sum_amount')
                    ->label('Total')
                    ->numeric(2)
                    ->prefix(config('business.currency.symbol')),
            ])
            ->description('The top 5 performing machines all time.');
    }
}
