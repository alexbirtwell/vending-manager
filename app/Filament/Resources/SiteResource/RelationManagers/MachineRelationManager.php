<?php

namespace App\Filament\Resources\SiteResource\RelationManagers;

use App\Filament\Resources\MachineResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MachineRelationManager extends RelationManager
{
    protected static string $relationship = 'machines';

    protected static ?string $recordTitleAttribute = 'machine_number';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('machine_number')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('machine_number'),
                Tables\Columns\TextColumn::make('brand'),
                Tables\Columns\TextColumn::make('model'),
                Tables\Columns\TextColumn::make('payment_mechanic'),
                Tables\Columns\TextColumn::make('machine_type')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->url(fn (Model $record) => MachineResource::getUrl('view', $record->id))
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
