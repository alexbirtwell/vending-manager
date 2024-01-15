<x-filament::layouts.base :title="$header">
    <div class="filament-app-layout flex h-full w-full overflow-x-clip">
        <div class="max-w-6xl mx-auto p-6">
                <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                    <img src="https://www.bps-uk.co.uk/wp-content/uploads/2022/11/bps-logo-landscape.png"/>
                </div>


            <!-- Page Heading -->
            @if (isset($header))
                <x-filament::header.heading>
                    {{ $header }}
                </x-filament::header.heading>
            <br/>
                <x-filament-support::hr></x-filament-support::hr>
                <br/>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
</x-filament::layouts.base>
