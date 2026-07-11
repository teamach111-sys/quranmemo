<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main container class="!p-0 !max-w-none">
        <livewire:topbar />
        <div class="p-6">
            {{ $slot }}
        </div>
    </flux:main>
</x-layouts::app.sidebar>
