<div>
    <style>
        form .text-danger-600 {
            color: #e3342f;
        }
    </style>

      <form wire:submit.prevent="submitService">
         {{ $this->form }}
     </form>
    <div class="text-right">
        <x-filament-support::button icon="heroicon-o-check" class="btn btn-primary bg-blue-700 my-6" wire:click="submitService">Submit Information</x-filament-support::button>
    </div>
</div>
