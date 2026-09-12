<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">

    <div class="min-h-screen">

        {{-- ========================================================= --}}
        {{-- NAVBAR --}}
        {{-- ========================================================= --}}
        <nav class="sticky top-0 z-50 w-full border-b border-gray-200 bg-white">

            {{-- FULL WIDTH --}}
            <div class="w-full px-4 sm:px-6 lg:px-8">

                <div class="flex h-16 w-full items-center">

                    {{-- ================================================= --}}
                    {{-- LEFT SIDE --}}
                    {{-- LOGO + NAVIGATION --}}
                    {{-- ================================================= --}}
                    <div class="flex min-w-0 items-center">

                        {{-- ================================================= --}}
                        {{-- LOGO --}}
                        {{-- ================================================= --}}
                        <div class="shrink-0">

                            <a
                                href="{{ route('user_dashboard') }}"
                                wire:navigate
                                class="flex items-center">

                                <img
                                    src="{{ asset('logo/premiere_header_logo.jpeg') }}"
                                    alt="Premiere Medical Cardiovascular Laboratory"
                                    class="h-10 w-auto object-contain">

                            </a>

                        </div>


                        {{-- ================================================= --}}
                        {{-- DESKTOP NAVIGATION --}}
                        {{-- ================================================= --}}
                        <div class="ml-6 hidden md:flex items-center">

                            <div class="flex items-center gap-1">

                                {{-- ================================================= --}}
                                {{-- USER DASHBOARD --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()->can('View User Dashboard'))

                                <a
                                    href="{{ route('user_dashboard') }}"
                                    wire:navigate
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                            {{ request()->routeIs('user_dashboard')
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.home class="size-4" />

                                        User Dashboard

                                    </span>

                                </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- DEPARTMENT HEAD --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()->can('View Department Dashboard'))

                                <a
                                    href="{{ route('dept_head_dashboard') }}"
                                    wire:navigate
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                            {{ request()->routeIs('dept_head_dashboard')
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.home class="size-4" />

                                        Dept Head Dashboard

                                    </span>

                                </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- HR DASHBOARD --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()->can('View HR Dashboard'))

                                <a
                                    href="{{ route('hr_dashboard') }}"
                                    wire:navigate
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                            {{ request()->routeIs('hr_dashboard')
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                    <span class="inline-flex items-center gap-2">

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
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                {{ request()->routeIs('lab_supervisor')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                    <span class="inline-flex items-center gap-2">
                                        <flux:icon.document class="size-4" />
                                        Lab Supervisor
                                    </span>
                                </a>
                                @endif


                                {{-- ================================================= --}}
                                {{-- ADMIN DASHBOARD --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()->can('View Admin Dashboard'))

                                <a
                                    href="{{ route('admin_dashboard') }}"
                                    wire:navigate
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                            {{ request()->routeIs('admin_dashboard')
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.home class="size-4" />

                                        Admin Dashboard

                                    </span>

                                </a>

                                @endif

                                @if(auth()->user()->can('View CPAR Master File'))
                                <a
                                    href="{{ route('cpar-master-file') }}"
                                    wire:navigate
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                    {{ request()->routeIs('cpar-master-file')
                                        ? 'bg-gray-900 text-white'
                                        : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.document-text class="size-4" />

                                        Master File

                                    </span>

                                </a>
                                @endif

                                {{-- REPORTS --}}
                                @if(auth()->user()->can('View CPAR Reports'))
                                @if(auth()->user()->can('View CPAR Reports'))

                                <a
                                    href="{{ route('cpar-report') }}"
                                    wire:navigate
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                    {{ request()->routeIs('cpar-report')
                                        ? 'bg-gray-900 text-white'
                                        : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.chart-bar class="size-4" />

                                        Reports

                                    </span>

                                </a>

                                @endif

                                @endif

                                @if(auth()->user()->can('View Employees'))

                                <a
                                    href="{{ route('employees') }}"
                                    wire:navigate
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                            {{ request()->routeIs('employees')
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.users class="size-4" />

                                        Employees

                                    </span>

                                </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- SYSTEM SETUP --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()->can('View System Setup'))

                                <a
                                    href="{{ route('system_setup') }}"
                                    wire:navigate
                                    class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                            {{ request()->routeIs('system_setup')
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.cog-6-tooth class="size-4" />

                                        System Setup

                                    </span>

                                </a>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- RIGHT SIDE --}}
                    {{-- USER PROFILE --}}
                    {{-- ================================================= --}}
                    <div class="ml-auto hidden md:flex items-center">

                        <div class="relative group">

                            {{-- USER BUTTON --}}
                            <button
                                type="button"
                                class="flex items-center gap-2 rounded-full
                                    px-2 py-1.5
                                    text-gray-700
                                    hover:bg-gray-100
                                    hover:text-gray-900
                                    transition">

                                {{-- INITIALS --}}
                                <span
                                    class="flex size-9 items-center justify-center
                                        rounded-full
                                        bg-red-800
                                        text-sm font-semibold
                                        text-white">

                                    {{ auth()->user()->initials() }}

                                </span>


                                {{-- USER NAME --}}
                                <span class="max-w-40 truncate text-sm font-medium">

                                    {{ auth()->user()->name }}

                                </span>


                                {{-- CHEVRON --}}
                                <flux:icon.chevron-down class="size-4" />

                            </button>


                            {{-- ================================================= --}}
                            {{-- USER DROPDOWN --}}
                            {{-- ================================================= --}}
                            <div
                                class="invisible absolute right-0 top-full z-50 mt-2 w-64
                                    rounded-xl
                                    border border-gray-200
                                    bg-white
                                    py-1
                                    shadow-xl
                                    opacity-0
                                    transition-all duration-150
                                    group-hover:visible
                                    group-hover:opacity-100">

                                {{-- USER INFORMATION --}}
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

                                            <div class="truncate text-sm font-semibold text-gray-900">

                                                {{ auth()->user()->name }}

                                            </div>

                                            <div class="truncate text-xs text-gray-500">

                                                {{ auth()->user()->email }}

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <div class="border-t border-gray-200"></div>


                                {{-- SETTINGS --}}
                                <a
                                    href="{{ route('settings.profile') }}"
                                    wire:navigate
                                    class="flex items-center gap-2
                                        px-4 py-3
                                        text-sm text-gray-700
                                        hover:bg-gray-100">

                                    <flux:icon.cog class="size-4" />

                                    Settings

                                </a>


                                <div class="border-t border-gray-200"></div>


                                {{-- LOGOUT --}}
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
                                            hover:text-red-600">

                                        <flux:icon.arrow-right-start-on-rectangle class="size-4" />

                                        {{ __('Log Out') }}

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- MOBILE BUTTON --}}
                    {{-- ================================================= --}}
                    <div class="ml-auto flex md:hidden">

                        <button
                            type="button"
                            x-data
                            @click="$dispatch('toggle-mobile-menu')"
                            class="flex size-10 items-center justify-center
                                rounded-full
                                text-gray-600
                                hover:bg-gray-100">

                            <flux:icon.bars-3 class="size-5" />

                        </button>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- MOBILE NAVIGATION --}}
            {{-- ========================================================= --}}
            <div
                x-data="{ open: false }"
                x-on:toggle-mobile-menu.window="open = !open"
                x-show="open"
                x-cloak
                class="border-t border-gray-200 bg-white md:hidden">

                <div class="space-y-1 px-4 py-3">

                    {{-- USER DASHBOARD --}}
                    @if(auth()->user()->can('View User Dashboard'))

                    <a
                        href="{{ route('user_dashboard') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                                {{ request()->routeIs('user_dashboard')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-700 hover:bg-gray-100' }}">

                        User Dashboard

                    </a>

                    @endif


                    {{-- DEPARTMENT HEAD --}}
                    @if(auth()->user()->can('View Department Dashboard'))

                    <a
                        href="{{ route('dept_head_dashboard') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                                {{ request()->routeIs('dept_head_dashboard')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-700 hover:bg-gray-100' }}">

                        Dept Head Dashboard

                    </a>

                    @endif


                    {{-- HR DASHBOARD --}}
                    @if(auth()->user()->can('View HR Dashboard'))

                    <a
                        href="{{ route('hr_dashboard') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                                {{ request()->routeIs('hr_dashboard')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-700 hover:bg-gray-100' }}">

                        HR Dashboard

                    </a>

                    @endif


                    {{-- LAB SUPERVISOR --}}
                    @if(auth()->user()->can('View Lab Supervisor'))

                    <a
                        href="{{ route('lab_supervisor') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                                {{ request()->routeIs('lab_supervisor')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-700 hover:bg-gray-100' }}">

                        Lab Supervisor

                    </a>

                    @endif


                    {{-- ADMIN DASHBOARD --}}
                    @if(auth()->user()->can('View Admin Dashboard'))

                    <a
                        href="{{ route('admin_dashboard') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                                {{ request()->routeIs('admin_dashboard')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-700 hover:bg-gray-100' }}">

                        Admin Dashboard

                    </a>

                    @endif


                    {{-- REPORTS --}}
                    @if(
                    auth()->user()->can('View CPAR Reports') ||
                    auth()->user()->can('View CPAR Master File')
                    )

                    <div class="pt-2">

                        <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-gray-500">

                            Reports

                        </div>


                        @if(auth()->user()->can('View CPAR Master File'))

                        <a
                            href="{{ route('cpar-master-file') }}"
                            wire:navigate
                            class="block rounded-lg px-3 py-2 text-sm font-medium
                                        {{ request()->routeIs('cpar-master-file')
                                            ? 'bg-gray-900 text-white'
                                            : 'text-gray-700 hover:bg-gray-100' }}">

                            CPAR Master File

                        </a>

                        @endif


                        @if(auth()->user()->can('View CPAR Reports'))

                        <a
                            href="{{ route('cpar-report') }}"
                            wire:navigate
                            class="block rounded-lg px-3 py-2 text-sm font-medium
                                        {{ request()->routeIs('cpar-report')
                                            ? 'bg-gray-900 text-white'
                                            : 'text-gray-700 hover:bg-gray-100' }}">

                            CPAR Reports

                        </a>

                        @endif

                    </div>

                    @endif


                    {{-- EMPLOYEES --}}
                    @if(auth()->user()->can('View Employees'))

                    <a
                        href="{{ route('employees') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                                {{ request()->routeIs('employees')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-700 hover:bg-gray-100' }}">

                        Employees

                    </a>

                    @endif


                    {{-- SYSTEM SETUP --}}
                    @if(auth()->user()->can('View System Setup'))

                    <a
                        href="{{ route('system_setup') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                                {{ request()->routeIs('system_setup')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-700 hover:bg-gray-100' }}">

                        System Setup

                    </a>

                    @endif

                </div>


                {{-- ================================================= --}}
                {{-- MOBILE USER --}}
                {{-- ================================================= --}}
                <div class="border-t border-gray-200 px-4 py-4">

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

                            <div class="truncate text-sm font-semibold text-gray-900">

                                {{ auth()->user()->name }}

                            </div>

                            <div class="truncate text-xs text-gray-500">

                                {{ auth()->user()->email }}

                            </div>

                        </div>

                    </div>


                    <div class="mt-3 space-y-1">

                        {{-- SETTINGS --}}
                        <a
                            href="{{ route('settings.profile') }}"
                            wire:navigate
                            class="block rounded-lg px-3 py-2 text-sm font-medium
                                text-gray-700 hover:bg-gray-100">

                            Settings

                        </a>


                        {{-- LOGOUT --}}
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
                                    hover:text-red-600">

                                {{ __('Log Out') }}

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </nav>


        {{-- ========================================================= --}}
        {{-- MAIN CONTENT / LIVEWIRE SLOT --}}
        {{-- ========================================================= --}}
        <main class="min-h-screen">

            {{ $slot }}

        </main>

    </div>


    {{-- ========================================================= --}}
    {{-- SCRIPTS --}}
    {{-- ========================================================= --}}
    @fluxScripts
    @livewireScripts

</body>

</html>