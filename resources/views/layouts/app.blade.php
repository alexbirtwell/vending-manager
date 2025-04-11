<x-filament-panels::layout title="BPS Vending">
    <div class="filament-app-layout flex h-full w-full overflow-x-clip">
        <div class="max-w-6xl mx-auto p-6">
                <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                    <img src="https://www.bps-uk.co.uk/wp-content/uploads/bps-logo-white.png"/>
                </div>


            <!-- Page Heading -->
            @if (isset($header))
                <x-filament::section.heading>
                    {{ $header }}
                </x-filament::section.heading>
            <br/>
                <br/>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
</x-filament-panels::layout>
