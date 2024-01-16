<?php

namespace App\Filament\Resources;

use AlexJustesen\FilamentSpatieLaravelActivitylog\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\SiteResource\RelationManagers\ServiceLogsRelationManager;
use App\Filament\Resources\SiteResource\Pages;
use App\Filament\Resources\SiteResource\RelationManagers;
use App\Models\Country;
use App\Models\Site;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                    ->maxLength(255),
                ]),
                Section::make('Address')
                ->schema([
                   Forms\Components\TextInput::make('address_line_1')
                       ->label(config('business.address_labels.address_line_1'))
                       ->maxLength(255)
                       ->required(),
                    Forms\Components\TextInput::make('address_line_2')
                        ->label(config('business.address_labels.address_line_2'))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('address_city')
                       ->label(config('business.address_labels.address_city'))
                       ->maxLength(255)
                       ->required(),
                    Forms\Components\TextInput::make('address_region')
                       ->label(config('business.address_labels.address_region'))
                       ->maxLength(255)
                       ->required(),
                    Forms\Components\TextInput::make('address_postal_code')
                       ->label(config('business.address_labels.address_postal_code'))
                       ->maxLength(12)
                       ->required(),
                    Forms\Components\Select::make('address_country_id')
                        ->label('Country')
                        ->default(config('business.default_country_id'))
                        ->options(Country::all()->pluck('name', 'id'))
                        ->searchable(),

                ]),
                Section::make('Contact')
                ->schema([
                    Forms\Components\TextInput::make('main_contact_name')
                        ->label('Main Contact Name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('main_contact_telephone')
                        ->label('Contact Telephone')
                        ->tel()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('main_contact_email')
                        ->label('Contact Email')
                        ->email()
                        ->maxLength(255),
                ]),
                Section::make('Service Information')
                ->schema([
                    Forms\Components\Textarea::make('description')
                        ->label('Site Description')
                        ->helperText('Attach any access notes or useful directions here.')
                        ->maxLength(16777215),
                    Forms\Components\TextInput::make('distance')
                        ->label('Distance from Base (miles)')
                        ->helperText('Distance from Base in miles.')
                        ->numeric(),
                    Forms\Components\TextInput::make('travel_time_minutes')
                        ->label('Average Travel Time (minutes)')
                        ->helperText('Average Travel Time in minutes.')
                        ->numeric(),
                    Forms\Components\TextInput::make('service_days')
                        ->default(config('business.default_service_days'))
                        ->numeric()
                        ->helperText('The number of business days a service request should be actioned'),
                    Forms\Components\Select::make('default_assignee')
                        ->options(User::all()->pluck('name','id'))
                        ->searchable()
                        ->required()
                        ->helperText('This user will be assigned to all new service requests.'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('address_line_1'),
                Tables\Columns\TextColumn::make('address_line_2'),
                Tables\Columns\TextColumn::make('address_city'),
                Tables\Columns\TextColumn::make('address_region'),
                Tables\Columns\TextColumn::make('address_postal_code'),
                Tables\Columns\TextColumn::make('address_country_id'),
                Tables\Columns\TextColumn::make('main_contact_name'),
                Tables\Columns\TextColumn::make('main_contact_telephone'),
                Tables\Columns\TextColumn::make('main_contact_email'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('coordinates'),
                Tables\Columns\TextColumn::make('distance'),
                Tables\Columns\TextColumn::make('travel_time_minutes'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('default_assignee'),

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

            RelationManagers\MachineRelationManager::class,
            RelationManagers\NotesRelationManager::class,
            ServiceLogsRelationManager::class,
            ActivitiesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'view' => Pages\ViewSite::route('/{record}/view'),
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasPermissionTo('Create Site');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->hasPermissionTo('Delete Site');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasPermissionTo('Edit Site');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasPermissionTo('View Site');
    }
}
