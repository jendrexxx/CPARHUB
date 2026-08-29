<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<body class="min-h-screen bg-white dark:bg-zinc-800">

    <div class="min-h-full">

        <nav class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-md">

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <div class="flex h-16 items-center justify-between">

                    {{-- ================================================= --}}
                    {{-- LEFT SIDE --}}
                    {{-- ================================================= --}}
                    <div class="flex items-center min-w-0">

                        {{-- LOGO --}}
                        <div class="shrink-0">

                            <a
                                href="{{ route('dashboard') }}"
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
                        <div class="hidden md:block">

                            <div class="ml-6 flex items-center space-x-1">

                                {{-- USER DASHBOARD --}}
                                <a
                                    href="{{ route('user_dashboard') }}"
                                    wire:navigate
                                    class="rounded-md px-3 py-2 text-sm font-medium transition
                                        {{ request()->routeIs('user_dashboard')
                                            ? 'bg-gray-100 text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.home class="size-4" />

                                        User Dashboard

                                    </span>

                                </a>


                                {{-- DEPARTMENT HEAD --}}
                                @if(auth()->user()->can('View Department Dashboard'))

                                <a
                                    href="{{ route('dept_head_dashboard') }}"
                                    wire:navigate
                                    class="rounded-md px-3 py-2 text-sm font-medium transition
                                        {{ request()->routeIs('dept_head_dashboard')
                                            ? 'bg-gray-100 text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.home class="size-4" />

                                        Dept Head Dashboard

                                    </span>

                                </a>

                                @endif


                                {{-- HR DASHBOARD --}}
                                @if(auth()->user()->can('View HR Dashboard'))

                                <a
                                    href="{{ route('hr_dashboard') }}"
                                    wire:navigate
                                    class="rounded-md px-3 py-2 text-sm font-medium transition
                                        {{ request()->routeIs('hr_dashboard')
                                            ? 'bg-gray-100 text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.home class="size-4" />

                                        HR Dashboard

                                    </span>

                                </a>

                                @endif


                                {{-- LAB SUPERVISOR --}}
                                @if(auth()->user()->can('View Lab Supervisor'))

                                <a
                                    href="{{ route('lab_supervisor') }}"
                                    wire:navigate
                                    class="rounded-md px-3 py-2 text-sm font-medium transition
                                        {{ request()->routeIs('lab_supervisor')
                                            ? 'bg-gray-100 text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.document class="size-4" />

                                        Lab Supervisor

                                    </span>

                                </a>

                                @endif


                                {{-- ADMIN DASHBOARD --}}
                                @if(auth()->user()->can('View Admin Dashboard'))

                                <a
                                    href="{{ route('admin_dashboard') }}"
                                    wire:navigate
                                    class="rounded-md px-3 py-2 text-sm font-medium transition
                                        {{ request()->routeIs('admin_dashboard')
                                            ? 'bg-gray-100 text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.home class="size-4" />

                                        Admin Dashboard

                                    </span>

                                </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- REPORTS --}}
                                {{-- ================================================= --}}
                                @if(
                                    auth()->user()->can('View CPAR Reports') ||
                                    auth()->user()->can('View CPAR Master File')
                                )

                                <div class="relative group">

                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-2 rounded-md px-3 py-2
                                            text-sm font-medium transition
                                            {{ request()->routeIs('cpar-report') ||
                                               request()->routeIs('cpar-master-file')
                                                ? 'bg-gray-100 text-gray-900'
                                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                        <flux:icon.clipboard-document-list class="size-4" />

                                        Reports

                                        <flux:icon.chevron-down class="size-4" />

                                    </button>


                                    {{-- REPORTS DROPDOWN --}}
                                    <div
                                        class="invisible absolute left-0 top-full z-50 mt-1 w-56
                                            origin-top-left rounded-md bg-white py-1
                                            shadow-lg ring-1 ring-gray-200
                                            opacity-0 transition-all duration-150
                                            group-hover:visible group-hover:opacity-100">

                                        {{-- CPAR MASTER FILE --}}
                                        @if(auth()->user()->can('View CPAR Master File'))

                                        <a
                                            href="{{ route('cpar-master-file') }}"
                                            wire:navigate
                                            class="flex items-center gap-2 px-4 py-2
                                                text-sm text-gray-700
                                                hover:bg-gray-100 hover:text-gray-900">

                                            <flux:icon.document-text class="size-4" />

                                            CPAR Master File

                                        </a>

                                        @endif


                                        {{-- CPAR REPORT --}}
                                        @if(auth()->user()->can('View CPAR Reports'))

                                        <a
                                            href="{{ route('cpar-report') }}"
                                            wire:navigate
                                            class="flex items-center gap-2 px-4 py-2
                                                text-sm text-gray-700
                                                hover:bg-gray-100 hover:text-gray-900">

                                            <flux:icon.chart-bar class="size-4" />

                                            CPAR Reports

                                        </a>

                                        @endif

                                    </div>

                                </div>

                                @endif


                                {{-- EMPLOYEES --}}
                                @if(auth()->user()->can('View Employees'))

                                <a
                                    href="{{ route('employees') }}"
                                    wire:navigate
                                    class="rounded-md px-3 py-2 text-sm font-medium transition
                                        {{ request()->routeIs('employees')
                                            ? 'bg-gray-100 text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                    <span class="inline-flex items-center gap-2">

                                        <flux:icon.users class="size-4" />

                                        Employees

                                    </span>

                                </a>

                                @endif


                                {{-- SYSTEM SETUP --}}
                                @if(auth()->user()->can('View System Setup'))

                                <a
                                    href="{{ route('system_setup') }}"
                                    wire:navigate
                                    class="rounded-md px-3 py-2 text-sm font-medium transition
                                        {{ request()->routeIs('system_setup')
                                            ? 'bg-gray-100 text-gray-900'
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
                    {{-- RIGHT SIDE: USER MENU --}}
                    {{-- ================================================= --}}
                    <div class="hidden md:flex items-center ml-4">

                        <div class="relative group">

                            {{-- USER BUTTON --}}
                            <button
                                type="button"
                                class="flex items-center gap-2 rounded-full
                                    text-gray-700 hover:bg-gray-100
                                    px-2 py-1.5 transition">

                                {{-- INITIALS --}}
                                <span
                                    class="flex size-8 items-center justify-center
                                        rounded-full bg-red-800 text-sm font-semibold
                                        text-white">

                                    {{ auth()->user()->initials() }}

                                </span>


                                {{-- NAME --}}
                                <span class="max-w-32 truncate text-sm font-medium">

                                    {{ auth()->user()->name }}

                                </span>


                                <flux:icon.chevron-down class="size-4" />

                            </button>


                            {{-- USER DROPDOWN --}}
                            <div
                                class="invisible absolute right-0 top-full z-50 mt-2 w-64
                                    rounded-md bg-white py-1 shadow-lg
                                    ring-1 ring-gray-200
                                    opacity-0 transition-all duration-150
                                    group-hover:visible group-hover:opacity-100">

                                {{-- USER INFORMATION --}}
                                <div class="px-4 py-3">

                                    <div class="flex items-center gap-3">

                                        <span
                                            class="flex size-10 shrink-0 items-center
                                                justify-center rounded-full
                                                bg-red-800 text-sm font-semibold
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
                                    class="flex items-center gap-2 px-4 py-2
                                        text-sm text-gray-700
                                        hover:bg-gray-100 hover:text-gray-900">

                                    <flux:icon.cog class="size-4" />

                                    Settings

                                </a>


                                <div class="border-t border-gray-200"></div>


                                {{-- LOGOUT --}}
                                <form
                                    method="POST"
                                    action="{{ route('logout') }}"
                                    class="w-full">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="flex w-full items-center gap-2
                                            px-4 py-2 text-left text-sm
                                            text-gray-700
                                            hover:bg-gray-100 hover:text-gray-900">

                                        <flux:icon.arrow-right-start-on-rectangle
                                            class="size-4" />

                                        {{ __('Log Out') }}

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- MOBILE BUTTON --}}
                    {{-- ================================================= --}}
                    <div class="flex md:hidden">

                        <button
                            type="button"
                            x-data
                            @click="$dispatch('toggle-mobile-menu')"
                            class="inline-flex items-center justify-center rounded-md
                                p-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900">

                            <span class="sr-only">
                                Open main menu
                            </span>

                            <flux:icon.bars-3 class="size-6" />

                        </button>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- MOBILE MENU --}}
            {{-- ========================================================= --}}
            <div
                x-data="{ open: false }"
                x-on:toggle-mobile-menu.window="open = !open"
                x-show="open"
                x-cloak
                class="md:hidden border-t border-gray-200 bg-white">

                <div class="space-y-1 px-2 pb-3 pt-2">

                    {{-- USER DASHBOARD --}}
                    <a
                        href="{{ route('user_dashboard') }}"
                        wire:navigate
                        class="block rounded-md px-3 py-2 text-base font-medium
                            text-gray-700 hover:bg-gray-100 hover:text-gray-900">

                        User Dashboard

                    </a>


                    {{-- DEPARTMENT HEAD --}}
                    @if(auth()->user()->can('View Department Dashboard'))

                    <a
                        href="{{ route('dept_head_dashboard') }}"
                        wire:navigate
                        class="block rounded-md px-3 py-2 text-base font-medium
                            text-gray-700 hover:bg-gray-100 hover:text-gray-900">

                        Dept Head Dashboard

                    </a>

                    @endif


                    {{-- HR DASHBOARD --}}
                    @if(auth()->user()->can('View HR Dashboard'))

                    <a
                        href="{{ route('hr_dashboard') }}"
                        wire:navigate
                        class="block rounded-md px-3 py-2 text-base font-medium
                            text-gray-700 hover:bg-gray-100 hover:text-gray-900">

                        HR Dashboard

                    </a>

                    @endif


                    {{-- LAB SUPERVISOR --}}
                    @if(auth()->user()->can('View Lab Supervisor'))

                    <a
                        href="{{ route('lab_supervisor') }}"
                        wire:navigate
                        class="block rounded-md px-3 py-2 text-base font-medium
                            text-gray-700 hover:bg-gray-100 hover:text-gray-900">

                        Lab Supervisor

                    </a>

                    @endif


                    {{-- ADMIN DASHBOARD --}}
                    @if(auth()->user()->can('View Admin Dashboard'))

                    <a
                        href="{{ route('admin_dashboard') }}"
                        wire:navigate
                        class="block rounded-md px-3 py-2 text-base font-medium
                            text-gray-700 hover:bg-gray-100 hover:text-gray-900">

                        Admin Dashboard

                    </a>

                    @endif


                    {{-- REPORTS --}}
                    @if(
                        auth()->user()->can('View CPAR Reports') ||
                        auth()->user()->can('View CPAR Master File')
                    )

                    <div class="pt-1">

                        <div class="px-3 py-2 text-xs font-semibold uppercase
                            tracking-wider text-gray-500">

                            Reports

                        </div>


                        {{-- CPAR MASTER FILE --}}
                        @if(auth()->user()->can('View CPAR Master File'))

                        <a
                            href="{{ route('cpar-master-file') }}"
                            wire:navigate
                            class="block rounded-md px-3 py-2 text-base
                                font-medium text-gray-700
                                hover:bg-gray-100 hover:text-gray-900">

                            CPAR Master File

                        </a>

                        @endif


                        {{-- CPAR REPORT --}}
                        @if(auth()->user()->can('View CPAR Reports'))

                        <a
                            href="{{ route('cpar-report') }}"
                            wire:navigate
                            class="block rounded-md px-3 py-2 text-base
                                font-medium text-gray-700
                                hover:bg-gray-100 hover:text-gray-900">

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
                        class="block rounded-md px-3 py-2 text-base font-medium
                            text-gray-700 hover:bg-gray-100 hover:text-gray-900">

                        Employees

                    </a>

                    @endif


                    {{-- SYSTEM SETUP --}}
                    @if(auth()->user()->can('View System Setup'))

                    <a
                        href="{{ route('system_setup') }}"
                        wire:navigate
                        class="block rounded-md px-3 py-2 text-base font-medium
                            text-gray-700 hover:bg-gray-100 hover:text-gray-900">

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
                                rounded-full bg-red-800 text-sm font-semibold
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
                            class="block rounded-md px-3 py-2 text-base
                                font-medium text-gray-700
                                hover:bg-gray-100 hover:text-gray-900">

                            Settings

                        </a>


                        {{-- LOGOUT --}}
                        <form
                            method="POST"
                            action="{{ route('logout') }}">

                            @csrf

                            <button
                                type="submit"
                                class="block w-full rounded-md px-3 py-2
                                    text-left text-base font-medium
                                    text-gray-700
                                    hover:bg-gray-100 hover:text-gray-900">

                                {{ __('Log Out') }}

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </nav>


        {{-- ========================================================= --}}
        {{-- PAGE CONTENT --}}
        {{-- ========================================================= --}}
        <main class="min-h-screen">

            {{ $slot }}

        </main>

    </div>


    @fluxScripts
    @livewireScripts

</body>

</html>