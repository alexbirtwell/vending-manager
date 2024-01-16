<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteNoteResource\Pages;
use App\Filament\Resources\SiteNoteResource\RelationManagers;
use App\Models\SiteNote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiteNoteResource extends Resource
{
    protected static ?string $model = SiteNote::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('site_id')
                    ->required(),
                Forms\Components\TextInput::make('user_id')
                    ->required(),
                Forms\Components\Textarea::make('note')
                    ->maxLength(16777215),
                Forms\Components\TextInput::make('note_type')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('site_id'),
                Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('note'),
                Tables\Columns\TextColumn::make('note_type'),
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
            'index' => Pages\ListSiteNotes::route('/'),
            'create' => Pages\CreateSiteNote::route('/create'),
            'edit' => Pages\EditSiteNote::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasPermissionTo('Create Site Note');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->hasPermissionTo('Delete Site Note');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasPermissionTo('Edit Site Note');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasPermissionTo('View Site Note');
    }
}
