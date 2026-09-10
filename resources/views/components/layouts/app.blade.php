    <head>
        @include('partials.head')
    </head>
<x-layouts.app.sidebar>
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
    <!-- notification -->
    <livewire:notification.message />