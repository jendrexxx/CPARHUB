<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-white text-gray-900 dark:bg-zinc-900 dark:text-white">

    <div class="min-h-screen">

        <nav
            class="sticky top-0 z-50 w-full border-b border-gray-200 bg-white shadow-md
            dark:border-zinc-700 dark:bg-zinc-900">

            <div class="w-full px-3 sm:px-4 lg:px-6 xl:px-8">

                <div class="flex min-h-16 w-full items-center gap-2">

                    <div class="flex min-w-0 shrink-0 items-center">

                        <a
                            href="{{ route('user_dashboard') }}"
                            wire:navigate
                            class="flex shrink-0 items-center">

                            <img
                                src="{{ asset('logo/premierelaboratory_cover-removebg-preview.png') }}"
                                alt="Premiere Medical Cardiovascular Laboratory"
                                class="h-8 w-auto object-contain sm:h-9 lg:h-10">

                        </a>

                    </div>


                    <div class="ml-2 hidden min-w-0 flex-1 items-center lg:flex">

                        <div
                            class="flex min-w-0 items-center gap-1 overflow-x-auto
                            scrollbar-hide">

                            @if(auth()->user()->can('View User Dashboard'))

                            <a
                                href="{{ route('user_dashboard') }}"
                                wire:navigate
                                class="shrink-0 rounded-lg px-2.5 py-2 text-sm font-medium
                                    transition-all duration-200 xl:px-3
                                    {{ request()->routeIs('user_dashboard')
                                        ? 'bg-red-800 text-white dark:bg-red-800 dark:text-white'
                                        : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                <span class="inline-flex items-center gap-2 whitespace-nowrap">

                                    <flux:icon.home class="size-4 shrink-0" />

                                    User Dashboard

                                </span>

                            </a>

                            @endif


                            @if(auth()->user()->can('View Department Dashboard'))

                            <a
                                href="{{ route('dept_head_dashboard') }}"
                                wire:navigate
                                class="shrink-0 rounded-lg px-2.5 py-2 text-sm font-medium
                                    transition-all duration-200 xl:px-3
                                    {{ request()->routeIs('dept_head_dashboard')
                                        ? 'bg-red-800 text-white dark:bg-red-800 dark:text-white'
                                        : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                <span class="inline-flex items-center gap-2 whitespace-nowrap">

                                    <flux:icon.home class="size-4 shrink-0" />

                                    Dept Head Dashboard

                                </span>

                            </a>

                            @endif


                            @if(auth()->user()->can('View HR Dashboard'))

                            <a
                                href="{{ route('hr_dashboard') }}"
                                wire:navigate
                                class="shrink-0 rounded-lg px-2.5 py-2 text-sm font-medium
                                    transition-all duration-200 xl:px-3
                                    {{ request()->routeIs('hr_dashboard')
                                        ? 'bg-red-800 text-white dark:bg-red-800 dark:text-white'
                                        : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                <span class="inline-flex items-center gap-2 whitespace-nowrap">

                                    <flux:icon.home class="size-4 shrink-0" />

                                    HR Dashboard

                                </span>

                            </a>

                            @endif


                            @if(
                            auth()->user()->can('View Lab Supervisor') ||
                            auth()->user()->can('View PGL Supervisor')
                            )

                            <a
                                href="{{ route('lab_supervisor') }}"
                                wire:navigate
                                class="shrink-0 rounded-lg px-2.5 py-2 text-sm font-medium
                                    transition-all duration-200 xl:px-3
                                    {{ request()->routeIs('lab_supervisor')
                                        ? 'bg-red-800 text-white dark:bg-red-800 dark:text-white'
                                        : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                <span class="inline-flex items-center gap-2 whitespace-nowrap">

                                    <flux:icon.document class="size-4 shrink-0" />

                                    Lab Supervisor

                                </span>

                            </a>

                            @endif


                            @if(auth()->user()->can('View Admin Dashboard'))

                            <a
                                href="{{ route('admin_dashboard') }}"
                                wire:navigate
                                class="shrink-0 rounded-lg px-2.5 py-2 text-sm font-medium
                                    transition-all duration-200 xl:px-3
                                    {{ request()->routeIs('admin_dashboard')
                                        ? 'bg-red-800 text-white dark:bg-red-800 dark:text-white'
                                        : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                <span class="inline-flex items-center gap-2 whitespace-nowrap">

                                    <flux:icon.home class="size-4 shrink-0" />

                                    Admin Dashboard

                                </span>

                            </a>

                            @endif


                            @if(
                            auth()->user()->can('View CPAR Master File') ||
                            auth()->user()->can('View CPAR Reports')
                            )

                            <flux:dropdown position="bottom" align="start">

                                <button
                                    type="button"
                                    class="inline-flex shrink-0 items-center gap-2 rounded-lg
                                        px-2.5 py-2 text-sm font-medium transition-colors duration-200 xl:px-3
                                        {{ request()->routeIs('cpar-master-file', 'cpar-report')
                                            ? 'bg-red-800 text-white dark:bg-red-800 dark:text-white'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                    <flux:icon.chart-bar class="size-4 shrink-0" />

                                    <span class="whitespace-nowrap">
                                        Reports
                                    </span>

                                    <flux:icon.chevron-down class="size-3.5 shrink-0" />

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


                            @if(
                            auth()->user()->can('View Result Setup') ||
                            auth()->user()->can('View Others Setup')
                            )

                            <flux:dropdown position="bottom" align="start">

                                <button
                                    type="button"
                                    class="inline-flex shrink-0 items-center gap-2 rounded-lg
                                        px-2.5 py-2 text-sm font-medium transition-colors duration-200 xl:px-3
                                        {{ request()->routeIs('result-setup', 'others-setup')
                                            ? 'bg-red-800 text-white dark:bg-red-800 dark:text-white'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                    <flux:icon.cog-6-tooth class="size-4 shrink-0" />

                                    <span class="whitespace-nowrap">
                                        Configuration Setup
                                    </span>

                                    <flux:icon.chevron-down class="size-3.5 shrink-0" />

                                </button>


                                <flux:menu class="min-w-56 dark:border-zinc-700 dark:bg-zinc-800">

                                    @if(auth()->user()->can('View Result Setup'))

                                    <flux:menu.item
                                        href="{{ route('result-setup') }}"
                                        wire:navigate
                                        icon="document-text">
                                        Result Setup
                                    </flux:menu.item>

                                    @endif


                                    @if(auth()->user()->can('View Others Setup'))

                                    <flux:menu.item
                                        href="{{ route('others-setup') }}"
                                        wire:navigate
                                        icon="cog-6-tooth">
                                        Others Setup
                                    </flux:menu.item>

                                    @endif

                                </flux:menu>

                            </flux:dropdown>

                            @endif


                            @if(auth()->user()->can('View Employees'))

                            <a
                                href="{{ route('employees') }}"
                                wire:navigate
                                class="shrink-0 rounded-lg px-2.5 py-2 text-sm font-medium
                                    transition-all duration-200 xl:px-3
                                    {{ request()->routeIs('employees')
                                        ? 'bg-red-800 text-white dark:bg-red-800 dark:text-white'
                                        : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                <span class="inline-flex items-center gap-2 whitespace-nowrap">

                                    <flux:icon.users class="size-4 shrink-0" />

                                    Employees

                                </span>

                            </a>

                            @endif


                            @if(auth()->user()->can('View System Setup'))

                            <a
                                href="{{ route('system_setup') }}"
                                wire:navigate
                                class="shrink-0 rounded-lg px-2.5 py-2 text-sm font-medium
                                    transition-all duration-200 xl:px-3
                                    {{ request()->routeIs('system_setup')
                                        ? 'bg-red-800 text-white dark:bg-red-800 dark:text-white'
                                        : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                <span class="inline-flex items-center gap-2 whitespace-nowrap">

                                    <flux:icon.cog-6-tooth class="size-4 shrink-0" />

                                    System Setup

                                </span>

                            </a>

                            @endif

                        </div>

                    </div>


                    <div class="ml-auto hidden shrink-0 items-center lg:flex">

                        <div class="relative group">

                            <button
                                type="button"
                                class="flex max-w-xs items-center gap-2 rounded-full px-2 py-1.5
                                text-gray-700 transition
                                hover:bg-gray-100 hover:text-gray-900
                                dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white">

                                <span
                                    class="flex size-9 shrink-0 items-center justify-center rounded-full
                                    bg-red-800 text-sm font-semibold text-white">
                                    {{ auth()->user()->initials() }}
                                </span>

                                <span class="hidden max-w-40 truncate text-sm font-medium xl:block">
                                    {{ auth()->user()->name }}
                                </span>

                                <flux:icon.chevron-down class="size-4 shrink-0" />

                            </button>


                            <div
                                class="invisible absolute right-0 top-full z-50 mt-2 w-64
                                rounded-xl border border-gray-200 bg-white py-1 opacity-0 shadow-xl
                                transition-all duration-150
                                group-hover:visible group-hover:opacity-100
                                dark:border-zinc-700 dark:bg-zinc-800">

                                <div class="px-4 py-4">

                                    <div class="flex items-center gap-3">

                                        <span
                                            class="flex size-10 shrink-0 items-center justify-center
                                            rounded-full bg-red-800 text-sm font-semibold text-white">
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
                                    class="flex items-center gap-2 px-4 py-3 text-sm
                                    {{ request()->routeIs('settings.*')
                                        ? 'bg-red-800 text-white'
                                        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-700 dark:hover:text-white' }}">

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
                                        class="flex w-full items-center gap-2 px-4 py-3 text-left text-sm
                                        text-gray-700 hover:bg-gray-100 hover:text-red-600
                                        dark:text-gray-300 dark:hover:bg-zinc-700 dark:hover:text-red-400">

                                        <flux:icon.arrow-right-start-on-rectangle class="size-4" />

                                        {{ __('Log Out') }}

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>


                    <div class="ml-auto flex shrink-0 items-center lg:hidden">

                        <button
                            type="button"
                            x-data
                            @click="$dispatch('toggle-mobile-menu')"
                            class="flex size-10 items-center justify-center rounded-full
                            text-gray-600 hover:bg-gray-100
                            dark:text-gray-300 dark:hover:bg-zinc-800">

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
                class="border-t border-gray-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 lg:hidden">

                <div class="max-h-[calc(100vh-4rem)] overflow-y-auto px-3 py-3 sm:px-4">

                    <div class="space-y-1">

                        @if(auth()->user()->can('View User Dashboard'))

                        <a
                            href="{{ route('user_dashboard') }}"
                            wire:navigate
                            class="block rounded-lg px-3 py-2.5 text-sm font-medium
                                {{ request()->routeIs('user_dashboard')
                                    ? 'bg-red-800 text-white'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                            <span class="flex items-center gap-2">
                                <flux:icon.home class="size-4" />
                                User Dashboard
                            </span>

                        </a>

                        @endif


                        @if(auth()->user()->can('View Department Dashboard'))

                        <a
                            href="{{ route('dept_head_dashboard') }}"
                            wire:navigate
                            class="block rounded-lg px-3 py-2.5 text-sm font-medium
                                {{ request()->routeIs('dept_head_dashboard')
                                    ? 'bg-red-800 text-white'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                            <span class="flex items-center gap-2">
                                <flux:icon.home class="size-4" />
                                Dept Head Dashboard
                            </span>

                        </a>

                        @endif


                        @if(auth()->user()->can('View HR Dashboard'))

                        <a
                            href="{{ route('hr_dashboard') }}"
                            wire:navigate
                            class="block rounded-lg px-3 py-2.5 text-sm font-medium
                                {{ request()->routeIs('hr_dashboard')
                                    ? 'bg-red-800 text-white'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                            <span class="flex items-center gap-2">
                                <flux:icon.home class="size-4" />
                                HR Dashboard
                            </span>

                        </a>

                        @endif


                        @if(
                        auth()->user()->can('View Lab Supervisor') ||
                        auth()->user()->can('View PGL Supervisor')
                        )

                        <a
                            href="{{ route('lab_supervisor') }}"
                            wire:navigate
                            class="block rounded-lg px-3 py-2.5 text-sm font-medium
                                {{ request()->routeIs('lab_supervisor')
                                    ? 'bg-red-800 text-white'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                            <span class="flex items-center gap-2">
                                <flux:icon.document class="size-4" />
                                Lab Supervisor
                            </span>

                        </a>

                        @endif


                        @if(auth()->user()->can('View Admin Dashboard'))

                        <a
                            href="{{ route('admin_dashboard') }}"
                            wire:navigate
                            class="block rounded-lg px-3 py-2.5 text-sm font-medium
                                {{ request()->routeIs('admin_dashboard')
                                    ? 'bg-red-800 text-white'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                            <span class="flex items-center gap-2">
                                <flux:icon.home class="size-4" />
                                Admin Dashboard
                            </span>

                        </a>

                        @endif


                        @if(
                        auth()->user()->can('View CPAR Reports') ||
                        auth()->user()->can('View CPAR Master File')
                        )

                        <div class="pt-2">

                            <div
                                class="px-3 py-2 text-xs font-semibold uppercase tracking-wider
                                    {{ request()->routeIs('cpar-master-file', 'cpar-report')
                                        ? 'text-red-800 dark:text-red-400'
                                        : 'text-gray-500 dark:text-zinc-400' }}">
                                Reports
                            </div>


                            @if(auth()->user()->can('View CPAR Master File'))

                            <a
                                href="{{ route('cpar-master-file') }}"
                                wire:navigate
                                class="block rounded-lg px-3 py-2 text-sm font-medium
                                        {{ request()->routeIs('cpar-master-file')
                                            ? 'bg-red-800 text-white'
                                            : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                <span class="flex items-center gap-2">
                                    <flux:icon.document-text class="size-4" />
                                    CPAR Master File
                                </span>

                            </a>

                            @endif


                            @if(auth()->user()->can('View CPAR Reports'))

                            <a
                                href="{{ route('cpar-report') }}"
                                wire:navigate
                                class="block rounded-lg px-3 py-2 text-sm font-medium
                                        {{ request()->routeIs('cpar-report')
                                            ? 'bg-red-800 text-white'
                                            : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                <span class="flex items-center gap-2">
                                    <flux:icon.chart-bar class="size-4" />
                                    Result/Others Reports
                                </span>

                            </a>

                            @endif

                        </div>

                        @endif


                        @if(
                        auth()->user()->can('View Result Setup') ||
                        auth()->user()->can('View Others Setup')
                        )

                        <div
                            x-data="{ open: {{ request()->routeIs('result-setup', 'others-setup') ? 'true' : 'false' }} }"
                            class="relative pt-2">

                            <button
                                type="button"
                                @click="open = !open"
                                class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium
                                    transition-colors
                                    {{ request()->routeIs('result-setup', 'others-setup')
                                        ? 'bg-red-800 text-white'
                                        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                <span class="inline-flex items-center gap-2">

                                    <flux:icon.cog-6-tooth class="size-4" />

                                    Setup

                                </span>

                                <flux:icon.chevron-down
                                    class="size-4 transition-transform duration-200"
                                    x-bind:class="{ 'rotate-180': open }" />

                            </button>


                            <div
                                x-show="open"
                                x-collapse
                                class="mt-1 space-y-1 pl-4">

                                @if(auth()->user()->can('View Result Setup'))

                                <a
                                    href="{{ route('result-setup') }}"
                                    wire:navigate
                                    class="block rounded-lg px-3 py-2 text-sm font-medium
                                            {{ request()->routeIs('result-setup')
                                                ? 'bg-red-800 text-white'
                                                : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-zinc-800 dark:hover:text-white' }}">
                                    Result Setup
                                </a>

                                @endif


                                @if(auth()->user()->can('View Others Setup'))

                                <a
                                    href="{{ route('others-setup') }}"
                                    wire:navigate
                                    class="block rounded-lg px-3 py-2 text-sm font-medium
                                            {{ request()->routeIs('others-setup')
                                                ? 'bg-red-800 text-white'
                                                : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-zinc-800 dark:hover:text-white' }}">
                                    Others Setup
                                </a>

                                @endif

                            </div>

                        </div>

                        @endif


                        @if(auth()->user()->can('View Employees'))

                        <a
                            href="{{ route('employees') }}"
                            wire:navigate
                            class="block rounded-lg px-3 py-2.5 text-sm font-medium
                                {{ request()->routeIs('employees')
                                    ? 'bg-red-800 text-white'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                            <span class="flex items-center gap-2">
                                <flux:icon.users class="size-4" />
                                Employees
                            </span>

                        </a>

                        @endif


                        @if(auth()->user()->can('View System Setup'))

                        <a
                            href="{{ route('system_setup') }}"
                            wire:navigate
                            class="block rounded-lg px-3 py-2.5 text-sm font-medium
                                {{ request()->routeIs('system_setup')
                                    ? 'bg-red-800 text-white'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                            <span class="flex items-center gap-2">
                                <flux:icon.cog-6-tooth class="size-4" />
                                System Setup
                            </span>

                        </a>

                        @endif

                    </div>


                    <div class="mt-3 border-t border-gray-200 pt-4 dark:border-zinc-700">

                        <div class="flex items-center gap-3">

                            <span
                                class="flex size-10 shrink-0 items-center justify-center rounded-full
                                bg-red-800 text-sm font-semibold text-white">
                                {{ auth()->user()->initials() }}
                            </span>


                            <div class="min-w-0 flex-1">

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
                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium
                                {{ request()->routeIs('settings.*')
                                    ? 'bg-red-800 text-white'
                                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                <flux:icon.cog class="size-4" />

                                Settings

                            </a>


                            <form
                                method="POST"
                                action="{{ route('logout') }}">

                                @csrf

                                <button
                                    type="submit"
                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2
                                    text-left text-sm font-medium
                                    text-gray-700 hover:bg-gray-100 hover:text-red-600
                                    dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-red-400">

                                    <flux:icon.arrow-right-start-on-rectangle class="size-4" />

                                    {{ __('Log Out') }}

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </nav>


        <main class="min-w-0 overflow-x-hidden">
            {{ $slot }}
        </main>

    </div>


    @fluxScripts
    @livewireScripts

</body>

</html>