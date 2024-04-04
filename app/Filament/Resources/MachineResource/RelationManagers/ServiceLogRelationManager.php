<?php

namespace App\Filament\Resources\MachineResource\RelationManagers;

use App\Filament\Resources\MachineResource;
use App\Filament\Resources\UserResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ServiceLogRelationManager extends RelationManager
{
    protected static string $relationship = 'serviceLogs';

    protected static ?string $recordTitleAttribute = 'description';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('machine.machine_number')
                    ->sortable()
                    ->url(fn ($record) => MachineResource::getUrl('view', ['record' => $record->machine_id])),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->sortable()
                    ->url(fn ($record) => UserResource::getUrl('edit', ['record' => $record?->assigned_user->id])),
                Tables\Columns\TextColumn::make('date_reported')
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('date_expected')
                    ->sortable()
                    ->date()
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
                ExportBulkAction::make()
            ]);
    }
}
