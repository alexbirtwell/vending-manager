<?php

namespace App\Filament\Resources\MachineResource\RelationManagers;

use App\Filament\Resources\MachineResource;
use App\Filament\Resources\UserResource;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceLogRelationManager extends RelationManager
{
    protected static string $relationship = 'serviceLogs';

    protected static ?string $recordTitleAttribute = 'description';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
