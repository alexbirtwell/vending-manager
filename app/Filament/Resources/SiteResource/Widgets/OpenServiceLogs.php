<?php

namespace App\Filament\Resources\SiteResource\Widgets;

use App\Filament\Resources\ServiceLogResource;
use App\Models\ServiceLog;
use App\Models\Site;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OpenServiceLogs extends BaseWidget
{
    protected static ?string $heading = 'Open Service Logs';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ServiceLog::query()->with(['machine'])
                    ->open()
                    ->orderBy('created_at', 'asc')
                    ->limit(5)
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->wrap()
                    ->size('xs')
                    ->url(fn (ServiceLog $record) => ServiceLogResource::getUrl('edit', ['record' => $record->id])),
                Tables\Columns\TextColumn::make('where')
                    ->size('xs')
                    ->label('Machine')
                    ->getStateUsing(function ($record) {
                    return $record->machine->machine_number . ' ('.$record->machine->uuid .')' . $record->machine->site->name;
                }),
                Tables\Columns\TextColumn::make('created_at')->label('Opened')->formatStateUsing(function (Carbon $state) {
                    return $state->diffForHumans();
                })
            ])
            ->description('The next 5 service logs');
    }
}
