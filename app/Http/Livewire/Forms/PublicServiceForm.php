<?php

namespace App\Http\Livewire\Forms;

use App\Models\Country;
use App\Models\Machine;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class PublicServiceForm extends Component implements HasForms
{
    use InteractsWithForms;

    private ?Machine $machine = null;

    public function render(): \Illuminate\View\View
    {
        return view('service.form');
    }

    public function mount(): void
    {
        $this->machine = Machine::find(request()->get('machine_id'));
    }


    protected function getFormSchema(): array
    {
        return [
            Placeholder::make('site_details')
                ->label(fn () => 'You are currently logging a vending service request for ' . $this->machine->name . ' at ' . $this->machine->site->name),
            TextInput::make('name')
                ->autofocus()
                ->required()
                ->placeholder('Enter your name'),
            TextInput::make('email')
                ->required()
                ->email()
                ->placeholder('Enter your email address'),
            Hidden::make('machine_id')
                ->default(request()->get('machine_id')),
            Checkbox::make('notify')
                ->label('Keep me notified about this service.')
                ->default(true),
            Select::make('service_type')
                ->required()
                ->placeholder('Select a service type')
                ->options([
                    'restock' => 'Restock',
                    'fault' => 'Fault',
                    'other' => 'Other',
                ]),
            Textarea::make('description')
                ->label('Details')
                ->helperText('Please provide as many details as possible about the requirements.')
                ->required(),
        ];
    }

    public function submitService()
    {
        $data = $this->form->getState();
        dd('here');
    }

}
