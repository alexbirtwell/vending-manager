<?php

namespace App\Filament\Resources\SiteResource\Widgets;

use App\Models\Site;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopSites extends BaseWidget
{
    protected static ?string $heading = 'Top Sites';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Site::query()
                    ->withCount('machines')
                    ->orderBy('machines_count', 'desc')
                ->limit(5)
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('machines_count')->label('Machines'),
            ])
            ->description('The top 5 sites with the most machines installed.');
    }
}
