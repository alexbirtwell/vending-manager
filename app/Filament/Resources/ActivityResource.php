<?php
namespace App\Filament\Resources;

use AlexJustesen\FilamentSpatieLaravelActivitylog\Contracts\IsActivitySubject;
use AlexJustesen\FilamentSpatieLaravelActivitylog\RelationManagers\ActivitiesRelationManager;
use AlexJustesen\FilamentSpatieLaravelActivitylog\ResourceFinder;
use App\Filament\Resources\ActivityResource\ListActivities;
use App\Filament\Resources\ActivityResource\ViewActivity;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class ActivityResource extends \AlexJustesen\FilamentSpatieLaravelActivitylog\Resources\ActivityResource
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               TextInput::make('causer')
                   ->placeholder(fn(Model $record) => $record->causer ? $record->causer->name : '')
                   ->label('User'),
               TextInput::make('subject')
                   ->placeholder(function (Model $record) {
                      if (! $record->subject) {
                            return new HtmlString('&mdash;');
                        }

                        if (method_exists($record->subject, 'getActivitySubjectDescription')) {
                            return $record->subject->getActivitySubjectDescription($record);
                        }

                        return $record->subject->name ?? new HtmlString('&mdash;');
                   })
                   ->label('Subject'),
               TextInput::make('subject_id')->label(__('filament-spatie-activitylog::activity.subject_id')),
               TextInput::make('subject_type_friendly')
                   ->placeholder(function (Model $record) {
                       $class = Str::afterLast($record->subject_type, '\\');
                       return Str::replace('model', $class, $record->description);
                   })
                   ->label('Change Summary'),
               KeyValue::make('properties.attributes')->label('New Values'),
               KeyValue::make('properties.old')->label('Old Values'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
               TextColumn::make('description')
                    ->label(__('filament-spatie-activitylog::activity.description'))
                    ->getStateUsing(function (Activity $record) {
                       $class = Str::afterLast($record->subject_type, '\\');
                       return Str::replace('model', $class, $record->description);
                    })
                    ->searchable(),
                TextColumn::make('subject.name')
                    ->label(__('filament-spatie-activitylog::activity.subject'))
                    ->hidden(fn (Component $livewire) => $livewire instanceof ActivitiesRelationManager)
                    ->getStateUsing(function (Activity $record) {
                        if (! $record->subject) {
                            return new HtmlString('&mdash;');
                        }

                        if (method_exists($record->subject, 'getActivitySubjectDescription')) {
                            return $record->subject->getActivitySubjectDescription($record);
                        }

                        return $record->subject->name ?? new HtmlString('&mdash;');


                    })
                    ->url(function (Activity $record) {
                        if (! $record->subject || ! $record->subject instanceof IsActivitySubject) {
                            return;
                        }

                        /** @var class-string<\Filament\Resources\Resource> */
                        $resource = ResourceFinder::find($record->subject::class);

                        if (! $resource) {
                            return;
                        }

                        return $resource::getUrl('edit', ['record' => $record->subject]) ?? null;
                    }, shouldOpenInNewTab: true),
                TextColumn::make('causer.name')
                    ->label(__('User'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('filament-spatie-activitylog::activity.logged_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('has_subject') ->label(__('filament-spatie-activitylog::activity.has_subject'))
                    ->query(fn (Builder $query) => $query->has('subject')),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'DESC');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivities::route('/'),
            'view' => ViewActivity::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasPermissionTo('View Activity');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->hasPermissionTo('Delete Activity');
    }
}
