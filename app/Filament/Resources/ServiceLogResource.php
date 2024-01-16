<?php

namespace App\Filament\Resources;

use AlexJustesen\FilamentSpatieLaravelActivitylog\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\ServiceLogResource\Pages;
use App\Filament\Resources\ServiceLogResource\RelationManagers;
use App\Models\Machine;
use App\Models\ServiceLog;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ServiceLogResource extends Resource
{
    protected static ?string $model = ServiceLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Section::make('Details')
                ->schema([
                    Forms\Components\DateTimePicker::make('date_completed')
                        ->label('Date Completed')
                        ->hidden(fn (?Model $record) => !$record?->date_completed),
                    Forms\Components\Select::make('machine_id')
                        ->label('Affected Machine')
                        ->options(Machine::all()->pluck('machine_number','id'))
                        ->searchable()
                        ->required(),
                    Forms\Components\DateTimePicker::make('date_reported'),
                    Forms\Components\Textarea::make('description')
                        ->label('Service Description')
                        ->maxLength(16777215),
                     Forms\Components\DatePicker::make('date_expected')
                        ->label('Date Expected')
                        ->default(fn (?Model $record) => $record?->machine?->site?->service_days ? now()->addDays($record->machine?->site?->service_days) : now()->addDays(config('business.default_service_days')))
                        ->required()
                        ->minDate(now()->format('Y-m-d'))
                        ->helperText('The date the service is expected to be completed.'),
                    Forms\Components\Select::make('logged_user')
                        ->options(User::all()->pluck('name','id'))
                        ->searchable()
                        ->required()
                        ->default(auth()->user()?->id ?? 0)
                        ->helperText('The user who inputted the service request.')
                ]),
                Section::make('Assignment')
                ->schema([
                   Forms\Components\Select::make('assigned_user')
                        ->options(User::all()->pluck('name','id'))
                        ->searchable()
                        ->required()
                        ->helperText('This user will response to the service request.'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('machine.site.name')
                    ->label('Site')
                    ->sortable()
                    ->url(fn ($record) => SiteResource::getUrl('view', ['record' => $record->machine->site_id])),

                Tables\Columns\TextColumn::make('machine.machine_number')
                    ->sortable()
                    ->url(fn ($record) => MachineResource::getUrl('view', ['record' => $record->machine_id])),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->sortable()
                    ->url(fn ($record) => UserResource::getUrl('view', ['record' => $record?->assigned_user])),
                Tables\Columns\TextColumn::make('date_reported')
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('date_expected')
                    ->sortable()
                    ->date(),
                Tables\Columns\TextColumn::make('date_completed')
                    ->formatStateUsing(fn ($value) => $value ? $value->format('d/m/Y') : '-')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                Filter::make('open')
                    ->query(fn (Builder $query): Builder => $query->open())->default(),
                Filter::make('due')
                    ->query(fn (Builder $query): Builder => $query->open()->where('date_expected', '<=', today()->addDay())),
                Filter::make('due_today')
                    ->query(fn (Builder $query): Builder => $query->open()->dueToday()),
                Filter::make('overdue')
                    ->query(fn (Builder $query): Builder => $query->open()->overdue()),
                Filter::make('completed')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('date_completed')->orderByDesc('date_completed')),

            ])
            ->defaultSort('date_expected', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check')
                    ->action(fn (Model $record) => $record->update(['date_completed' => now()]))
                    ->requiresConfirmation()
                    ->hidden(fn (Model $record) => $record->date_completed),
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
            ActivitiesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceLogs::route('/'),
            'create' => Pages\CreateServiceLog::route('/create'),
            'edit' => Pages\EditServiceLog::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasPermissionTo('Create Service Log');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->hasPermissionTo('Delete Service Log');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasPermissionTo('Edit Service Log');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasPermissionTo('View Service Log');
    }
}
