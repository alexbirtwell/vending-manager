<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Machine Service Request') }}
        </h2>
    </x-slot>
<div>
    <livewire:forms.public-service-form />
</div>
</x-app-layout>
