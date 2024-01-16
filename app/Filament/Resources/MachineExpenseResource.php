<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MachineExpenseResource\Pages;
use App\Filament\Resources\MachineExpenseResource\RelationManagers;
use App\Models\Machine;
use App\Models\MachineExpense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class MachineExpenseResource extends Resource
{
    protected static ?string $model = MachineExpense::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('machine_id')
                        ->label('Machine')
                        ->options(Machine::all()->pluck('machine_number','id'))
                        ->searchable()
                        ->required(),
                Forms\Components\Select::make('type')
                    ->options(['Stock' => 'Stock', 'Parts' => 'Parts', 'Labour' => 'Labour', 'Other' => 'Other'])
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->default(now()),
                Forms\Components\Textarea::make('description')
                    ->maxLength(16777215),
                Forms\Components\Select::make('repeat')
                    ->label('Recurring Expense')
                    ->options([null => 'No', 'Daily' => 'Daily', 'Weekly' => 'Weekly', 'Fortnightly' => 'Fortnightly', 'Monthly' => 'Monthly', 'Yearly' => 'Yearly'])
                    ->required()
                ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('machine.machine_number')->url(fn ($record) => MachineResource::getUrl('view', $record->machine_id)),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('amount')->formatStateUsing(fn ($record) => currency_format($record->amount)),
                Tables\Columns\TextColumn::make('date')
                    ->date(),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('repeat')->label('Recurring')->formatStateUsing(fn ($record) => $record->repeat ? $record->repeat : 'No'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMachineExpenses::route('/'),
            'create' => Pages\CreateMachineExpense::route('/create'),
            'edit' => Pages\EditMachineExpense::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasPermissionTo('Create Machine Expense');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->hasPermissionTo('Delete Machine Expense');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasPermissionTo('Edit Machine Expense');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasPermissionTo('View Machine Expense');
    }
}
