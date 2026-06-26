@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand  {{ $attributes }}>
        <x-slot name="logo" class="flex w-full items-center justify-center ">
            <x-app-logo-icon class="size-20 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:sidebar.brand  {{ $attributes }}>
        <x-slot name="logo" class="flex w-full items-center justify-center ">
            <x-app-logo-icon class="size-20 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:sidebar.brand>
@endif
