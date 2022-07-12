<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MachineExpenseResource\Pages;
use App\Filament\Resources\MachineExpenseResource\RelationManagers;
use App\Models\MachineExpense;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MachineExpenseResource extends Resource
{
    protected static ?string $model = MachineExpense::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('machine_id')
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(16777215),
                Forms\Components\TextInput::make('repeat')
                    ->maxLength(255),
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
