<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>

    {{-- Livewire & Alpine Scripts --}}
    @livewireStyles
    @livewireScripts



    {{-- Optional: Push any additional scripts --}}
    @stack('scripts')

    {{-- AlpineJS --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-layouts.app.sidebar>
