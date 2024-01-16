<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomeLogResource\Pages;
use App\Filament\Resources\IncomeLogResource\RelationManagers;
use App\Models\IncomeLog;
use App\Models\Machine;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class IncomeLogResource extends Resource
{
    protected static ?string $model = IncomeLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                        ->label('Collected by')
                        ->options(User::all()->pluck('name','id'))
                        ->searchable()
                        ->required()
                        ->default(auth()->user()?->id ?? 0)
                        ->helperText('The user who collected the funds.'),
                Forms\Components\Select::make('machine_id')
                        ->label('Machine')
                        ->options(Machine::all()->pluck('machine_number','id'))
                        ->searchable()
                        ->required(),
                Forms\Components\TextInput::make('amount')
                    ->helperText('The amount of money collected in '.config('business.currency.code').'.')
                    ->numeric()
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->default(now())
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options(['Cash' => 'Cash', 'Card' => 'Card', 'Cash & Card' => 'Cash & Card'])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('machine.machine_number')->url(fn ($record) => MachineResource::getUrl('view', ['record' => $record->machine_id])),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('date')
                    ->date(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
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
            'index' => Pages\ListIncomeLogs::route('/'),
            'create' => Pages\CreateIncomeLog::route('/create'),
            'edit' => Pages\EditIncomeLog::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasPermissionTo('Create Income Log');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->hasPermissionTo('Delete Income Log');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasPermissionTo('Edit Income Log');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasPermissionTo('View Income Log');
    }
}
