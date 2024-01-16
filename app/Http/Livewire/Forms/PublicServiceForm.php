<?php

namespace App\Http\Livewire\Forms;

use App\Models\Country;
use App\Models\Machine;
use App\Models\ServiceLog;
use Closure;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Livewire\Component;

class PublicServiceForm extends Component implements HasForms
{
    use InteractsWithForms;

    private ?Machine $machine = null;

    public ?int $submitted = null;
    public string $name;
    public string $email;
    public bool $notify = true;
    public string $notification_email;
    public string $service_type;
    public string $description;
    public ?string $machine_id;

    public function render(): \Illuminate\View\View
    {
        return view('service.form');
    }

    public function mount(): void
    {
//        $this->machine = Machine::find(request()->get('machine_id')) ?? Machine::where('uuid', request()->get('machine_id'))->first();
//        if(!$this->machine) {
//            abort('404', 'Sorry we think you may have followed an incorrect link.');
//        }
//        $this->machine_id =  $this->machine?->id;
    }


    protected function getFormSchema(): array
    {
        if ($this->submitted) {
            return [];
        }
        return [
            TextInput::make('machine_uuid')
                ->label('Machine Id')
                ->rule(
                            fn () => static function (string $attribute, ?string $value, Closure $fail): void {
                                $machine = Machine::where('uuid', $value)->first();
                                if (
                                    $value->isNotEmpty() && (
                                        $value->match('/^[a-zA-Z0-9?]/')->isEmpty()
                                        || $value->contains('//')
                                    )
                                ) {
                                    $fail(__(
                                        'The custom URL must be a valid, relative URL for '
                                        . request()->root()
                                    ));
                                }
                            }
                        )
                ->default(fn () => request()->get('machine_id'))
                ->helperText('The 5 digit code displayed on the machine by the QR code')
                ->required(),
            TextInput::make('name')
                ->autofocus()
                ->required()
                ->placeholder('Enter your name'),
            TextInput::make('notification_email')
                ->required()
                ->email()
                ->placeholder('Enter your email address'),
            Hidden::make('machine_id'),
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

        if(!$data['notify']) {
            unset($data['notification_email']);
        }

        $data['description'] = Str::title($data['service_type']) . " - " . $data['description'];

        $data['description'] .= " (online request by " . $data['name'] . ")";
        unset($data['notify'], $data['name'], $data['service_type']);
        $log = ServiceLog::create(
            $data
        );
        $this->submitted = $log->id;
    }

}
