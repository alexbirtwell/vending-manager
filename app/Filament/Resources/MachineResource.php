<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MachineResource\Pages;
use App\Filament\Resources\MachineResource\RelationManagers;
use App\Models\Machine;
use App\Models\Site;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class MachineResource extends Resource
{
    protected static ?string $model = Machine::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('uuid')
                    ->label('Unique Code')
                    ->hiddenOn('create')
                    ->required()
                    ->length(5)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('machine_number')->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('site_id')
                        ->label('Site')
                        ->options(Site::all()->pluck('name','id'))
                        ->searchable()
                        ->required()
                        ->helperText('The site the machine is installed at.'),
                Forms\Components\TextInput::make('brand')
                    ->maxLength(255),
                Forms\Components\TextInput::make('model')
                    ->maxLength(255),
                Forms\Components\Select::make('payment_mechanic')
                    ->options(['Cash' => 'Cash', 'Card' => 'Card', 'Cash & Card' => 'Cash & Card', 'Billed' => 'Billed'])
                ->required()
                ,
                 Forms\Components\Select::make('machine_type')
                    ->options(['Snack' => 'Snack', 'Hot Drinks' => 'Hot Drinks', 'Cold Drinks' => 'Cold Drinks', 'Snack & Drinks' => 'Snack & Drinks'])
                ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->label('Code')
                    ->copyable()
                    ->copyMessage('UUID copied to clipboard')
                    ->copyMessageDuration(1500)
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('machine_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('site.name')->url(fn ($record) => SiteResource::getUrl('view', ['record' => $record->site_id])),
                Tables\Columns\TextColumn::make('brand'),
                Tables\Columns\TextColumn::make('model'),
                Tables\Columns\TextColumn::make('payment_mechanic'),
                Tables\Columns\TextColumn::make('machine_type'),
                Tables\Columns\TextColumn::make('income_sum_amount')
                    ->sum('income', 'amount')
                    ->numeric(2)
                    ->prefix(config('business.currency.symbol'))
                    ->label('Income'),
                Tables\Columns\TextColumn::make('expenses_sum_amount')
                    ->sum('expenses', 'amount')
                    ->numeric(2)
                    ->prefix(config('business.currency.symbol'))
                    ->label('Expenses'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make()
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

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMachines::route('/'),
            'create' => Pages\CreateMachine::route('/create'),
            'view' => Pages\ViewMachine::route('/{record}/view'),
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
