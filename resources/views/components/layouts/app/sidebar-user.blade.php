<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<body class="min-h-screen bg-white dark:bg-zinc-800">

    <div class="min-h-full">

        <nav class="sticky top-0 z-50 bg-white border-b border-white shadow-md">

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <div class="flex h-16 items-center justify-between">

                    {{-- ================================================= --}}
                    {{-- LOGO + DESKTOP NAVIGATION --}}
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
                                    class="h-10 w-auto object-contain" />

                            </a>

                        </div>


                        {{-- ================================================= --}}
                        {{-- DESKTOP NAVIGATION --}}
                        {{-- ================================================= --}}
                        <div class="hidden md:block">

                            <div class="ml-6 flex items-center space-x-1">

                                {{-- USER DASHBOARD --}}
                                @if(auth()->user()->can('View User Dashboard'))

                                    <a
                                        href="{{ route('user_dashboard') }}"
                                        wire:navigate
                                        class="rounded-md px-3 py-2 text-sm font-medium
                                            {{ request()->routeIs('user_dashboard')
                                                ? 'bg-gray-100 text-gray-900'
                                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                        <span class="inline-flex items-center gap-2">

                                            <flux:icon.home class="size-4" />

                                            User Dashboard

                                        </span>

                                    </a>

                                @endif


                                {{-- DEPARTMENT HEAD --}}
                                @if(auth()->user()->can('View Department Dashboard'))

                                    <a
                                        href="{{ route('dept_head_dashboard') }}"
                                        wire:navigate
                                        class="rounded-md px-3 py-2 text-sm font-medium
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
                                        class="rounded-md px-3 py-2 text-sm font-medium
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
                                        class="rounded-md px-3 py-2 text-sm font-medium
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
                                        class="rounded-md px-3 py-2 text-sm font-medium
                                            {{ request()->routeIs('admin_dashboard')
                                                ? 'bg-gray-100 text-gray-900'
                                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                        <span class="inline-flex items-center gap-2">

                                            <flux:icon.home class="size-4" />

                                            Admin Dashboard

                                        </span>

                                    </a>

                                @endif


                                {{-- RESULT CONCERN --}}
                                @if(auth()->user()->can('View Result Concern Form'))

                                    <a
                                        href="{{ route('user.result.result_request_form') }}"
                                        wire:navigate
                                        class="rounded-md px-3 py-2 text-sm font-medium
                                            {{ request()->routeIs('user.result.result_request_form')
                                                ? 'bg-gray-100 text-gray-900'
                                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                        <span class="inline-flex items-center gap-2">

                                            <flux:icon.document class="size-4" />

                                            Result Concern

                                        </span>

                                    </a>

                                @endif


                                {{-- OTHER CONCERN --}}
                                @if(auth()->user()->can('View CPAR Request Form'))

                                    <a
                                        href="{{ route('cpar_request_form') }}"
                                        wire:navigate
                                        class="rounded-md px-3 py-2 text-sm font-medium
                                            {{ request()->routeIs('cpar_request_form')
                                                ? 'bg-gray-100 text-gray-900'
                                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                        <span class="inline-flex items-center gap-2">

                                            <flux:icon.document class="size-4" />

                                            Other Concern

                                        </span>

                                    </a>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- DESKTOP USER MENU --}}
                    {{-- ================================================= --}}
                    <div class="hidden md:flex items-center ml-4">

                        <div class="relative group">

                            {{-- USER BUTTON --}}
                            <button
                                type="button"
                                class="flex items-center gap-2 rounded-full
                                    px-2 py-1.5 text-gray-700
                                    hover:bg-gray-100 hover:text-gray-900">

                                <span
                                    class="flex size-8 items-center justify-center
                                        rounded-full bg-red-800 text-sm font-semibold
                                         text-white">

                                    {{ auth()->user()->initials() }}

                                </span>


                                <span class="max-w-32 truncate text-sm font-medium">

                                    {{ auth()->user()->name }}

                                </span>


                                <flux:icon.chevron-down class="size-4" />

                            </button>


                            {{-- USER DROPDOWN --}}
                            <div
                                class="invisible absolute right-0 top-full z-50 mt-2 w-64
                                    rounded-md bg-white py-1 shadow-xl
                                    ring-1 ring-gray-200
                                    opacity-0 transition-all duration-150
                                    group-hover:visible group-hover:opacity-100">

                                {{-- USER INFORMATION --}}
                                <div class="px-4 py-3">

                                    <div class="flex items-center gap-3">

                                        <span
                                            class="flex size-10 shrink-0 items-center
                                                justify-center rounded-full
                                                bg-gray-100 text-sm font-semibold
                                                text-gray-700">

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
                    {{-- MOBILE MENU BUTTON --}}
                    {{-- ================================================= --}}
                    <div class="flex md:hidden">

                        <button
                            type="button"
                            x-data
                            @click="$dispatch('toggle-mobile-menu')"
                            class="inline-flex items-center justify-center rounded-md
                                p-2 text-gray-700
                                hover:bg-gray-100 hover:text-gray-900">

                            <span class="sr-only">
                                Open main menu
                            </span>

                            <flux:icon.bars-3 class="size-6" />

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
                class="md:hidden border-t border-gray-200 bg-white">

                <div class="space-y-1 px-2 pb-3 pt-2">

                    {{-- USER DASHBOARD --}}
                    @if(auth()->user()->can('View User Dashboard'))

                        <a
                            href="{{ route('user_dashboard') }}"
                            wire:navigate
                            class="block rounded-md px-3 py-2 text-base font-medium
                                {{ request()->routeIs('user_dashboard')
                                    ? 'bg-gray-100 text-gray-900'
                                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                            User Dashboard

                        </a>

                    @endif


                    {{-- DEPARTMENT HEAD --}}
                    @if(auth()->user()->can('View Department Dashboard'))

                        <a
                            href="{{ route('dept_head_dashboard') }}"
                            wire:navigate
                            class="block rounded-md px-3 py-2 text-base font-medium
                                {{ request()->routeIs('dept_head_dashboard')
                                    ? 'bg-gray-100 text-gray-900'
                                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                            Dept Head Dashboard

                        </a>

                    @endif


                    {{-- HR DASHBOARD --}}
                    @if(auth()->user()->can('View HR Dashboard'))

                        <a
                            href="{{ route('hr_dashboard') }}"
                            wire:navigate
                            class="block rounded-md px-3 py-2 text-base font-medium
                                {{ request()->routeIs('hr_dashboard')
                                    ? 'bg-gray-100 text-gray-900'
                                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                            HR Dashboard

                        </a>

                    @endif


                    {{-- LAB SUPERVISOR --}}
                    @if(auth()->user()->can('View Lab Supervisor'))

                        <a
                            href="{{ route('lab_supervisor') }}"
                            wire:navigate
                            class="block rounded-md px-3 py-2 text-base font-medium
                                {{ request()->routeIs('lab_supervisor')
                                    ? 'bg-gray-100 text-gray-900'
                                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                            Lab Supervisor

                        </a>

                    @endif


                    {{-- ADMIN DASHBOARD --}}
                    @if(auth()->user()->can('View Admin Dashboard'))

                        <a
                            href="{{ route('admin_dashboard') }}"
                            wire:navigate
                            class="block rounded-md px-3 py-2 text-base font-medium
                                {{ request()->routeIs('admin_dashboard')
                                    ? 'bg-gray-100 text-gray-900'
                                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                            Admin Dashboard

                        </a>

                    @endif


                    {{-- RESULT CONCERN --}}
                    @if(auth()->user()->can('View Result Concern Form'))

                        <a
                            href="{{ route('user.result.result_request_form') }}"
                            wire:navigate
                            class="block rounded-md px-3 py-2 text-base font-medium
                                {{ request()->routeIs('user.result.result_request_form')
                                    ? 'bg-gray-100 text-gray-900'
                                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                            Result Concern

                        </a>

                    @endif


                    {{-- OTHER CONCERN --}}
                    @if(auth()->user()->can('View CPAR Request Form'))

                        <a
                            href="{{ route('cpar_request_form') }}"
                            wire:navigate
                            class="block rounded-md px-3 py-2 text-base font-medium
                                {{ request()->routeIs('cpar_request_form')
                                    ? 'bg-gray-100 text-gray-900'
                                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                            Other Concern

                        </a>

                    @endif

                </div>


                {{-- ================================================= --}}
                {{-- MOBILE USER --}}
                {{-- ================================================= --}}
                <div class="border-t border-gray-200 px-4 py-4 bg-white">

                    <div class="flex items-center gap-3">

                        <span
                            class="flex size-10 shrink-0 items-center justify-center
                                rounded-full bg-gray-100 text-sm font-semibold
                                text-gray-700">

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

        <main class="min-h-screen">

            {{ $slot }}

        </main>

    </div>


    @fluxScripts
    @livewireScripts

</body>

</html>
