<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white text-gray-900 dark:bg-zinc-900 dark:text-white">

    <div class="min-h-screen">

        <nav class="sticky top-0 z-50 w-full border-b border-gray-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">

            <div class="w-full px-4 sm:px-6 lg:px-8">

                <div class="flex h-16 w-full items-center">

                    <div class="flex min-w-0 items-center">

                        <div class="shrink-0">

                            <a
                                href="{{ route('user_dashboard') }}"
                                wire:navigate
                                class="flex items-center">

                                <img
                                    src="{{ asset('logo/premierelaboratory_cover-removebg-preview.png') }}"
                                    alt="Premiere Medical Cardiovascular Laboratory"
                                    class="h-10 w-auto object-contain">

                            </a>

                        </div>

                        <div class="ml-6 hidden md:flex items-center">

                            <div class="flex items-center gap-1">

                                @if(auth()->user()->can('View User Dashboard'))

                                <a
                                    href="{{ route('user_dashboard') }}"
                                    wire:navigate
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                        {{ request()->routeIs('user_dashboard')
                                            ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.home class="size-4" />

                                        User Dashboard

                                    </span>

                                </a>

                                @endif


                                @if(auth()->user()->can('View Department Dashboard'))

                                <a
                                    href="{{ route('dept_head_dashboard') }}"
                                    wire:navigate
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                        {{ request()->routeIs('dept_head_dashboard')
                                            ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.home class="size-4" />

                                        Dept Head Dashboard

                                    </span>

                                </a>

                                @endif


                                @if(auth()->user()->can('View HR Dashboard'))

                                <a
                                    href="{{ route('hr_dashboard') }}"
                                    wire:navigate
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                        {{ request()->routeIs('hr_dashboard')
                                            ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.home class="size-4" />

                                        HR Dashboard

                                    </span>

                                </a>

                                @endif


                                @if(auth()->user()->can('View Lab Supervisor') || auth()->user()->can('View PGL Supervisor'))

                                <a
                                    href="{{ route('lab_supervisor') }}"
                                    wire:navigate
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                        {{ request()->routeIs('lab_supervisor')
                                            ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.document class="size-4" />

                                        Lab Supervisor

                                    </span>

                                </a>

                                @endif


                                @if(auth()->user()->can('View Admin Dashboard'))

                                <a
                                    href="{{ route('admin_dashboard') }}"
                                    wire:navigate
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                        {{ request()->routeIs('admin_dashboard')
                                            ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.home class="size-4" />

                                        Admin Dashboard

                                    </span>

                                </a>

                                @endif


                                @if(auth()->user()->can('View CPAR Master File') || auth()->user()->can('View CPAR Reports'))

                                <flux:dropdown position="bottom" align="start">

                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-colors duration-200
                                            {{ request()->routeIs('cpar-master-file', 'cpar-report')
                                                ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                        <flux:icon.chart-bar class="size-4" />

                                        <span>Reports</span>

                                        <flux:icon.chevron-down class="size-3.5" />

                                    </button>


                                    <flux:menu class="min-w-56 dark:border-zinc-700 dark:bg-zinc-800">

                                        @if(auth()->user()->can('View CPAR Master File'))

                                        <flux:menu.item
                                            href="{{ route('cpar-master-file') }}"
                                            wire:navigate
                                            icon="document-text">

                                            Master File

                                        </flux:menu.item>

                                        @endif


                                        @if(auth()->user()->can('View CPAR Reports'))

                                        <flux:menu.item
                                            href="{{ route('cpar-report') }}"
                                            wire:navigate
                                            icon="chart-bar">

                                            Result/Others Reports

                                        </flux:menu.item>

                                        @endif

                                    </flux:menu>

                                </flux:dropdown>

                                @endif


                                @if(auth()->user()->can('View Employees'))

                                <a
                                    href="{{ route('employees') }}"
                                    wire:navigate
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                        {{ request()->routeIs('employees')
                                            ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.users class="size-4" />

                                        Employees

                                    </span>

                                </a>

                                @endif


                                @if(auth()->user()->can('View System Setup'))

                                <a
                                    href="{{ route('system_setup') }}"
                                    wire:navigate
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                        {{ request()->routeIs('system_setup')
                                            ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.cog-6-tooth class="size-4" />

                                        System Setup

                                    </span>

                                </a>

                                @endif

                            </div>

                        </div>

                    </div>


                    <div class="ml-auto hidden md:flex items-center">

                        <div class="relative group">

                            <button
                                type="button"
                                class="flex items-center gap-2 rounded-full
                                    px-2 py-1.5
                                    text-gray-700
                                    hover:bg-gray-100
                                    hover:text-gray-900
                                    dark:text-gray-300
                                    dark:hover:bg-zinc-800
                                    dark:hover:text-white
                                    transition">

                                <span
                                    class="flex size-9 items-center justify-center
                                        rounded-full
                                        bg-red-800
                                        text-sm font-semibold
                                        text-white">

                                    {{ auth()->user()->initials() }}

                                </span>


                                <span class="max-w-40 truncate text-sm font-medium">

                                    {{ auth()->user()->name }}

                                </span>


                                <flux:icon.chevron-down class="size-4" />

                            </button>


                            <div
                                class="invisible absolute right-0 top-full z-50 mt-2 w-64
                                    rounded-xl
                                    border border-gray-200
                                    bg-white
                                    py-1
                                    shadow-xl
                                    opacity-0
                                    transition-all duration-150
                                    dark:border-zinc-700
                                    dark:bg-zinc-800
                                    group-hover:visible
                                    group-hover:opacity-100">

                                <div class="px-4 py-4">

                                    <div class="flex items-center gap-3">

                                        <span
                                            class="flex size-10 shrink-0
                                                items-center justify-center
                                                rounded-full
                                                bg-red-800
                                                text-sm font-semibold
                                                text-white">

                                            {{ auth()->user()->initials() }}

                                        </span>


                                        <div class="min-w-0">

                                            <div class="truncate text-sm font-semibold text-gray-900 dark:text-white">

                                                {{ auth()->user()->name }}

                                            </div>

                                            <div class="truncate text-xs text-gray-500 dark:text-zinc-400">

                                                {{ auth()->user()->email }}

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <div class="border-t border-gray-200 dark:border-zinc-700"></div>


                                <a
                                    href="{{ route('settings.profile') }}"
                                    wire:navigate
                                    class="flex items-center gap-2
                                        px-4 py-3
                                        text-sm text-gray-700
                                        hover:bg-gray-100
                                        dark:text-gray-300
                                        dark:hover:bg-zinc-700">

                                    <flux:icon.cog class="size-4" />

                                    Settings

                                </a>


                                <div class="border-t border-gray-200 dark:border-zinc-700"></div>


                                <form
                                    method="POST"
                                    action="{{ route('logout') }}">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="flex w-full items-center gap-2
                                            px-4 py-3
                                            text-left text-sm
                                            text-gray-700
                                            hover:bg-gray-100
                                            hover:text-red-600
                                            dark:text-gray-300
                                            dark:hover:bg-zinc-700
                                            dark:hover:text-red-400">

                                        <flux:icon.arrow-right-start-on-rectangle class="size-4" />

                                        {{ __('Log Out') }}

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>


                    <div class="ml-auto flex md:hidden">

                        <button
                            type="button"
                            x-data
                            @click="$dispatch('toggle-mobile-menu')"
                            class="flex size-10 items-center justify-center
                                rounded-full
                                text-gray-600
                                hover:bg-gray-100
                                dark:text-gray-300
                                dark:hover:bg-zinc-800">

                            <flux:icon.bars-3 class="size-5" />

                        </button>

                    </div>

                </div>

            </div>


            <div
                x-data="{ open: false }"
                x-on:toggle-mobile-menu.window="open = !open"
                x-show="open"
                x-cloak
                class="border-t border-gray-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 md:hidden">

                <div class="space-y-1 px-4 py-3">

                    @if(auth()->user()->can('View User Dashboard'))

                    <a
                        href="{{ route('user_dashboard') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                            {{ request()->routeIs('user_dashboard')
                                ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                        User Dashboard

                    </a>

                    @endif


                    @if(auth()->user()->can('View Department Dashboard'))

                    <a
                        href="{{ route('dept_head_dashboard') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                            {{ request()->routeIs('dept_head_dashboard')
                                ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                        Dept Head Dashboard

                    </a>

                    @endif


                    @if(auth()->user()->can('View HR Dashboard'))

                    <a
                        href="{{ route('hr_dashboard') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                            {{ request()->routeIs('hr_dashboard')
                                ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                        HR Dashboard

                    </a>

                    @endif


                    @if(auth()->user()->can('View Lab Supervisor'))

                    <a
                        href="{{ route('lab_supervisor') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                            {{ request()->routeIs('lab_supervisor')
                                ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                        Lab Supervisor

                    </a>

                    @endif


                    @if(auth()->user()->can('View PGL Supervisor'))

                    <a
                        href="{{ route('lab_supervisor') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                            {{ request()->routeIs('lab_supervisor')
                                ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                        PGL Supervisor

                    </a>

                    @endif


                    @if(auth()->user()->can('View Admin Dashboard'))

                    <a
                        href="{{ route('admin_dashboard') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                            {{ request()->routeIs('admin_dashboard')
                                ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                        Admin Dashboard

                    </a>

                    @endif


                    @if(auth()->user()->can('View CPAR Reports') || auth()->user()->can('View CPAR Master File'))

                    <div class="pt-2">

                        <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-zinc-400">

                            Reports

                        </div>


                        @if(auth()->user()->can('View CPAR Master File'))

                        <a
                            href="{{ route('cpar-master-file') }}"
                            wire:navigate
                            class="block rounded-lg px-3 py-2 text-sm font-medium
                                {{ request()->routeIs('cpar-master-file')
                                    ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                            CPAR Master File

                        </a>

                        @endif


                        @if(auth()->user()->can('View CPAR Reports'))

                        <a
                            href="{{ route('cpar-report') }}"
                            wire:navigate
                            class="block rounded-lg px-3 py-2 text-sm font-medium
                                {{ request()->routeIs('cpar-report')
                                    ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                            CPAR Reports

                        </a>

                        @endif

                    </div>

                    @endif


                    <div
                        x-data="{ open: false }"
                        class="relative">

                        <button
                            type="button"
                            @click="open = !open"
                            class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium
                                text-gray-700 hover:bg-gray-100
                                dark:text-gray-300 dark:hover:bg-zinc-800">

                            <span>Setup</span>

                            <svg
                                class="h-4 w-4 transition-transform"
                                :class="{ 'rotate-180': open }"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m19 9-7 7-7-7" />

                            </svg>

                        </button>


                        <div
                            x-show="open"
                            x-transition
                            @click.outside="open = false"
                            class="mt-1 space-y-1 pl-4">

                            <a
                                href=""
                                wire:navigate
                                class="block rounded-lg px-3 py-2 text-sm font-medium
                                    text-gray-600 hover:bg-gray-100
                                    dark:text-gray-400 dark:hover:bg-zinc-800 dark:hover:text-white">

                                Result Setup

                            </a>


                            <a
                                href=""
                                wire:navigate
                                class="block rounded-lg px-3 py-2 text-sm font-medium
                                    text-gray-600 hover:bg-gray-100
                                    dark:text-gray-400 dark:hover:bg-zinc-800 dark:hover:text-white">

                                Other Setup

                            </a>

                        </div>

                    </div>


                    @if(auth()->user()->can('View Employees'))

                    <a
                        href="{{ route('employees') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                            {{ request()->routeIs('employees')
                                ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                        Employees

                    </a>

                    @endif


                    @if(auth()->user()->can('View System Setup'))

                    <a
                        href="{{ route('system_setup') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                            {{ request()->routeIs('system_setup')
                                ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                        System Setup

                    </a>

                    @endif

                </div>


                <div class="border-t border-gray-200 px-4 py-4 dark:border-zinc-700">

                    <div class="flex items-center gap-3">

                        <span
                            class="flex size-10 shrink-0 items-center justify-center
                                rounded-full
                                bg-red-800
                                text-sm font-semibold
                                text-white">

                            {{ auth()->user()->initials() }}

                        </span>


                        <div class="min-w-0">

                            <div class="truncate text-sm font-semibold text-gray-900 dark:text-white">

                                {{ auth()->user()->name }}

                            </div>

                            <div class="truncate text-xs text-gray-500 dark:text-zinc-400">

                                {{ auth()->user()->email }}

                            </div>

                        </div>

                    </div>


                    <div class="mt-3 space-y-1">

                        <a
                            href="{{ route('settings.profile') }}"
                            wire:navigate
                            class="block rounded-lg px-3 py-2 text-sm font-medium
                                text-gray-700 hover:bg-gray-100
                                dark:text-gray-300 dark:hover:bg-zinc-800">

                            Settings

                        </a>


                        <form
                            method="POST"
                            action="{{ route('logout') }}">

                            @csrf

                            <button
                                type="submit"
                                class="block w-full rounded-lg px-3 py-2
                                    text-left text-sm font-medium
                                    text-gray-700
                                    hover:bg-gray-100
                                    hover:text-red-600
                                    dark:text-gray-300
                                    dark:hover:bg-zinc-800
                                    dark:hover:text-red-400">

                                {{ __('Log Out') }}

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </nav>


        <main>
            {{ $slot }}
        </main>

    </div>


    @fluxScripts
    @livewireScripts

</body>

</html>