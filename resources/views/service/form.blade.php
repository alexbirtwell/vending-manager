<div>
    <style>
        form .text-danger-600 {
            color: #e3342f;
        }
    </style>
    <h2 class="mb-4 text-2xl font-extrabold text-gray-600 dark:text-white">Service Request</h2>

    @if($this->submitted)
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
            <p class="text-lg font-semibold">Service Request Submitted</p>
            <p>Your request has been successfully submitted and we will have an engineer on site ASAP. Your reference for this service is <strong>{{$submitted}}</strong>.</p>
        </div>
    @endif

      <form wire:submit.prevent="submitService">
         {{ $this->form }}
     </form>
    @if(!$this->submitted)
    <div class="text-right">
        <x-filament::button icon="heroicon-o-check" class="btn btn-primary bg-blue-700 my-6" wire:click="submitService">Submit Information</x-filament::button>
    </div>
    @endif
</div>
