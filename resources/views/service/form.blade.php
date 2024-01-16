<div>
    <style>
        form .text-danger-600 {
            color: #e3342f;
        }
    </style>
    @if($this->submitted)
        <div class="w-full max-w-xs mx-auto bg-white shadow-md overflow-hidden md:max-w-6xl">
    <div class="md:flex">
        <div class="p-8">
            <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold"><x-dynamic-component component="heroicon-o-check" class="h-8 w-8 inline-block mx-6"/>Success</div>
            <div class="block mt-1 text-lg leading-tight font-medium text-black hover:underline">Your service request has been submitted successfully.</div>
            <p class="mt-2 text-gray-500">Your unique service identifier is: <span class="font-medium">{{ $submitted }}</span>. </p>
        </div>
    </div>
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
