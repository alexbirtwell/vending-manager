<?php

namespace App\Filament\Resources\SiteResource\RelationManagers;

use App\Filament\Resources\MachineResource;
use App\Filament\Resources\UserResource;
use App\Models\Machine;
use App\Filament\Resources\ServiceLogResource;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'serviceLogs';

    protected static ?string $recordTitleAttribute = 'description';

    public static function form(Form $form): Form
    {
        $site_id = session()->has('site_id') ? session()->get('site_id') : null;
        $options = Machine::where('site_id',$site_id )->get()->pluck('machine_number','id');
        return $form
            ->schema([
                Forms\Components\Select::make('machine_id')
                    ->label('Machine')
                    ->options($options)
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('machine.machine_number')
                    ->sortable()
                    ->url(fn ($record) => MachineResource::getUrl('view', $record->machine_id)),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->sortable()
                    ->url(fn ($record) => UserResource::getUrl('view', $record->assigned_user)),
                Tables\Columns\TextColumn::make('date_reported')
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('date_expected')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->url(fn (Model $record) => ServiceLogResource::getUrl('edit', $record->id)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
