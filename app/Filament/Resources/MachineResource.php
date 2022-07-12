<?php

namespace App\Filament\Resources;

use AlexJustesen\FilamentSpatieLaravelActivitylog\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\MachineResource\Pages;
use App\Filament\Resources\MachineResource\RelationManagers;
use App\Models\Machine;
use App\Models\Site;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MachineResource extends Resource
{
    protected static ?string $model = Machine::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('machine_number'),
                Forms\Components\Select::make('site_id')
                        ->label('Site')
                        ->options(Site::all()->pluck('name','id'))
                        ->searchable()
                        ->required()
                        ->helperText('This user will be assigned to all new service requests.'),
                Forms\Components\TextInput::make('brand')
                    ->maxLength(255),
                Forms\Components\TextInput::make('model')
                    ->maxLength(255),
                Forms\Components\Select::make('payment_mechanic')
                    ->options(['Cash' => 'Cash', 'Card' => 'Card', 'Cash & Card' => 'Cash & Card'])
                ->required()
                ,
                 Forms\Components\Select::make('machine_type')
                    ->options(['Snack' => 'Snack', 'Drinks' => 'Drinks', 'Snack & Drinks' => 'Snack & Drinks'])
                ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('machine_number'),
                Tables\Columns\TextColumn::make('site.name')->url(fn ($record) => SiteResource::getUrl('view', $record->site_id)),
                Tables\Columns\TextColumn::make('brand'),
                Tables\Columns\TextColumn::make('model'),
                Tables\Columns\TextColumn::make('payment_mechanic'),
                Tables\Columns\TextColumn::make('machine_type'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            RelationManagers\ServiceLogRelationManager::class,
            RelationManagers\ExpensesRelationManager::class,
            RelationManagers\IncomeRelationManager::class,
            RelationManagers\NotesRelationManager::class,
            ActivitiesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMachines::route('/'),
            'create' => Pages\CreateMachine::route('/create'),
            'view' => Pages\ViewMachine::route('/{record}'),
            'edit' => Pages\EditMachine::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasPermissionTo('Create Machine');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->hasPermissionTo('Delete Machine');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasPermissionTo('Edit Machine');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasPermissionTo('View Machine');
    }
}
