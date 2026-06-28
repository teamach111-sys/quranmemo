<x-layouts::app.sidebar :title="$title ?? null">
    @include('layouts.app.topbar')
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
