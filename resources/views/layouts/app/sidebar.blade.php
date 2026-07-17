<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-darkcontentbg">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-darksidebar">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Platform')" class="grid">
                @canany(['prof', 'admin', 'sec'])
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                        wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                @endcanany
                @canany(['admin', 'sec'])
                    <flux:sidebar.item icon="users" :href="route('etudiants')" :current="request()->routeIs('etudiants')"
                        wire:navigate>
                        {{ __('Gestion des etudiants') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="book-open" :href="route('classes')" :current="request()->routeIs('classes')"
                        wire:navigate>
                        {{ __('Gestion des classes') }}
                    </flux:sidebar.item>
                @endcanany

                @can('view-notes')
                    <flux:sidebar.item icon="clipboard" :href="route('notes')" :current="request()->routeIs('notes')"
                        wire:navigate>
                        {{ __('Notes d\'etudiants') }}
                    </flux:sidebar.item>
                @endcan
                @canany(['admin', 'sec'])
                    <flux:sidebar.item icon="map" :href="route('programme')" :current="request()->routeIs('programme')"
                        wire:navigate>
                        {{ __('Filières') }}
                    </flux:sidebar.item>
                @endcanany
                @can(['admin'])
                    <flux:sidebar.item icon="adjustments-vertical" :href="route('parametres-etablissement')"
                        :current="request()->routeIs('parametres-etablissement')" wire:navigate>
                        {{ __('Configuration') }}
                    </flux:sidebar.item>
                @endcan

            </flux:sidebar.group>
        </flux:sidebar.nav>



        <flux:spacer />


        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <div class="h-20  w-full p-5 dark:text-white dark:bg-darkcontentbg">
            <select name="" id=""
                class="p-2 border h-10 focus:outline-none border border-[#DEDEDE] dark:border-[#3E3E3E] rounded-md w-40">
                <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">2026/2027
                </option>
                <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">2024/2025
                </option>
                <option class="dark:bg-white dark:text-black dark:hover:bg-slate-100 border" value="">2023/2024
                </option>
            </select>
        </div>

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Log out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>

    </flux:header>

    {{ $slot }}

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>
