<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<body class="min-h-screen bg-white dark:bg-zinc-800">

    <div class="min-h-screen">

        {{-- ========================================================= --}}
        {{-- NAVBAR --}}
        {{-- ========================================================= --}}
        <nav class="sticky top-0 z-50 w-full border-b border-gray-200 bg-white shadow-md">

            <div class="w-full px-4 sm:px-6 lg:px-8">

                <div class="flex h-16 w-full items-center">

                    {{-- ================================================= --}}
                    {{-- LEFT: LOGO --}}
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
                    {{-- NAVIGATION --}}
                    {{-- ================================================= --}}
                    <div class="ml-6 hidden md:flex items-center">

                        <div class="flex items-center gap-1">

                            {{-- USER DASHBOARD --}}
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


                            {{-- DEPARTMENT HEAD --}}
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


                            {{-- HR DASHBOARD --}}
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


                            {{-- LAB SUPERVISOR --}}
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


                            {{-- ADMIN DASHBOARD --}}
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


                            {{-- RESULT CONCERN --}}
                            @if(auth()->user()->can('View Result Concern Form'))

                            <a
                                href="{{ route('user.result.result_request_form') }}"
                                wire:navigate
                                class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                        {{ request()->routeIs('user.result.result_request_form')
                                            ? 'bg-gray-900 text-white'
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
                                class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                        {{ request()->routeIs('cpar_request_form')
                                            ? 'bg-gray-900 text-white'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                <span class="inline-flex items-center gap-2">
                                    <flux:icon.document class="size-4" />
                                    Other Concern
                                </span>
                            </a>

                            @endif

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- RIGHT: USER --}}
                    {{-- ================================================= --}}
                    <div class="ml-auto hidden md:flex items-center">

                        <div class="relative group">

                            <button
                                type="button"
                                class="flex items-center gap-2 rounded-full
                                       px-2 py-1.5
                                       text-gray-700
                                       hover:bg-gray-100
                                       hover:text-gray-900">

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


                            {{-- USER DROPDOWN --}}
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


                    {{-- MOBILE BUTTON --}}
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

                    @if(auth()->user()->can('View Department Dashboard'))
                    <a
                        href="{{ route('dept_head_dashboard') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium">
                        Dept Head Dashboard
                    </a>
                    @endif

                    @if(auth()->user()->can('View HR Dashboard'))
                    <a
                        href="{{ route('hr_dashboard') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium">
                        HR Dashboard
                    </a>
                    @endif

                    @if(auth()->user()->can('View Lab Supervisor'))
                    <a
                        href="{{ route('lab_supervisor') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium">
                        Lab Supervisor
                    </a>
                    @endif

                    @if(auth()->user()->can('View Admin Dashboard'))
                    <a
                        href="{{ route('admin_dashboard') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium">
                        Admin Dashboard
                    </a>
                    @endif

                    @if(auth()->user()->can('View Result Concern Form'))
                    <a
                        href="{{ route('user.result.result_request_form') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium">
                        Result Concern
                    </a>
                    @endif

                    @if(auth()->user()->can('View CPAR Request Form'))
                    <a
                        href="{{ route('cpar_request_form') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium">
                        Other Concern
                    </a>
                    @endif

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