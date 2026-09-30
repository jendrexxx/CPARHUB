<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<body class="min-h-screen bg-white text-gray-900 dark:bg-zinc-900 dark:text-gray-100">

    <div class="min-h-screen">

        {{-- NAVBAR --}}
        <nav
            class="sticky top-0 z-50 w-full
                   border-b border-gray-200
                   bg-white
                   shadow-md
                   dark:border-zinc-700
                   dark:bg-zinc-900">

            <div class="w-full px-4 sm:px-6 lg:px-8">

                <div class="flex h-16 w-full items-center">

                    {{-- LOGO --}}
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


                    {{-- DESKTOP NAVIGATION --}}
                    <div class="ml-6 hidden md:flex items-center">

                        <div class="flex items-center gap-1">


                            {{-- USER DASHBOARD --}}
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


                            {{-- DEPARTMENT HEAD --}}
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


                            {{-- HR DASHBOARD --}}
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
                                            ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

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
                                            ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                <span class="inline-flex items-center gap-2">

                                    <flux:icon.home class="size-4" />

                                    Admin Dashboard

                                </span>

                            </a>

                            @endif


                            {{-- RESULT CONCERN --}}
                            @if(
                            auth()->user()->can('View Result Concern Form') &&
                            !auth()->user()->hasRole('USER')
                            )

                            <a
                                href="{{ route('user.result.result_request_form') }}"
                                wire:navigate
                                class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                                        {{ request()->routeIs('user.result.result_request_form')
                                            ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

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
                                            ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-zinc-800 dark:hover:text-white' }}">

                                <span class="inline-flex items-center gap-2">

                                    <flux:icon.document class="size-4" />

                                    Other Concern

                                </span>

                            </a>

                            @endif

                        </div>

                    </div>


                    {{-- DESKTOP USER MENU --}}
                    <div class="ml-auto hidden md:flex items-center">

                        <div class="relative group">

                            <button
                                type="button"
                                class="flex items-center gap-2
                                       rounded-full
                                       px-2 py-1.5
                                       text-gray-700
                                       hover:bg-gray-100
                                       hover:text-gray-900
                                       dark:text-gray-300
                                       dark:hover:bg-zinc-800
                                       dark:hover:text-white">

                                {{-- USER INITIALS --}}
                                <span
                                    class="flex size-9 items-center justify-center
                                           rounded-full
                                           bg-red-800
                                           text-sm font-semibold
                                           text-white">

                                    {{ auth()->user()->initials() }}

                                </span>


                                {{-- USER NAME --}}
                                <span
                                    class="max-w-40 truncate
                                           text-sm font-medium">

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
                                       group-hover:opacity-100

                                       dark:border-zinc-700
                                       dark:bg-zinc-800">


                                {{-- USER INFORMATION --}}
                                <div class="px-4 py-4">

                                    <div class="flex items-center gap-3">

                                        {{-- INITIALS --}}
                                        <span
                                            class="flex size-10 shrink-0
                                                   items-center justify-center
                                                   rounded-full
                                                   bg-red-800
                                                   text-sm font-semibold
                                                   text-white">

                                            {{ auth()->user()->initials() }}

                                        </span>


                                        {{-- NAME + EMAIL --}}
                                        <div class="min-w-0">

                                            <div
                                                class="truncate
                                                       text-sm font-semibold
                                                       text-gray-900
                                                       dark:text-white">

                                                {{ auth()->user()->name }}

                                            </div>


                                            <div
                                                class="truncate
                                                       text-xs
                                                       text-gray-500
                                                       dark:text-gray-400">

                                                {{ auth()->user()->email }}

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- DIVIDER --}}
                                <div class="border-t border-gray-200 dark:border-zinc-700"></div>


                                {{-- SETTINGS --}}
                                <a
                                    href="{{ route('settings.profile') }}"
                                    wire:navigate
                                    class="flex items-center gap-2
                                           px-4 py-3
                                           text-sm
                                           text-gray-700
                                           hover:bg-gray-100

                                           dark:text-gray-300
                                           dark:hover:bg-zinc-700
                                           dark:hover:text-white">

                                    <flux:icon.cog class="size-4" />

                                    Settings

                                </a>


                                {{-- DIVIDER --}}
                                <div class="border-t border-gray-200 dark:border-zinc-700"></div>


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


                    {{-- MOBILE BUTTON --}}
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
                                   dark:hover:bg-zinc-800
                                   dark:hover:text-white">

                            <flux:icon.bars-3 class="size-5" />

                        </button>

                    </div>

                </div>

            </div>


            {{-- MOBILE NAVIGATION --}}
            <div
                x-data="{ open: false }"
                x-on:toggle-mobile-menu.window="open = !open"
                x-show="open"
                x-cloak
                class="border-t border-gray-200
                       bg-white
                       dark:border-zinc-700
                       dark:bg-zinc-900
                       md:hidden">

                <div class="space-y-1 px-4 py-3">


                    {{-- MOBILE USER DASHBOARD --}}
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


                    {{-- MOBILE DEPARTMENT HEAD --}}
                    @if(auth()->user()->can('View Department Dashboard'))

                    <a
                        href="{{ route('dept_head_dashboard') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                                   text-gray-700
                                   hover:bg-gray-100
                                   dark:text-gray-300
                                   dark:hover:bg-zinc-800
                                   dark:hover:text-white">

                        Dept Head Dashboard

                    </a>

                    @endif


                    {{-- MOBILE HR --}}
                    @if(auth()->user()->can('View HR Dashboard'))

                    <a
                        href="{{ route('hr_dashboard') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                                   text-gray-700
                                   hover:bg-gray-100
                                   dark:text-gray-300
                                   dark:hover:bg-zinc-800
                                   dark:hover:text-white">

                        HR Dashboard

                    </a>

                    @endif


                    {{-- MOBILE LAB SUPERVISOR --}}
                    @if(
                    auth()->user()->can('View Lab Supervisor') ||
                    auth()->user()->can('View PGL Supervisor')
                    )

                    <a
                        href="{{ route('lab_supervisor') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                                   text-gray-700
                                   hover:bg-gray-100
                                   dark:text-gray-300
                                   dark:hover:bg-zinc-800
                                   dark:hover:text-white">

                        Lab Supervisor

                    </a>

                    @endif


                    {{-- MOBILE ADMIN --}}
                    @if(auth()->user()->can('View Admin Dashboard'))

                    <a
                        href="{{ route('admin_dashboard') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                                   text-gray-700
                                   hover:bg-gray-100
                                   dark:text-gray-300
                                   dark:hover:bg-zinc-800
                                   dark:hover:text-white">

                        Admin Dashboard

                    </a>

                    @endif


                    {{-- MOBILE RESULT CONCERN --}}
                    @if(
                    auth()->user()->can('View Result Concern Form') &&
                    !auth()->user()->hasRole('USER')
                    )

                    <a
                        href="{{ route('user.result.result_request_form') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                                   text-gray-700
                                   hover:bg-gray-100
                                   dark:text-gray-300
                                   dark:hover:bg-zinc-800
                                   dark:hover:text-white">

                        Result Concern

                    </a>

                    @endif


                    {{-- MOBILE OTHER CONCERN --}}
                    @if(auth()->user()->can('View CPAR Request Form'))

                    <a
                        href="{{ route('cpar_request_form') }}"
                        wire:navigate
                        class="block rounded-lg px-3 py-2 text-sm font-medium
                                   text-gray-700
                                   hover:bg-gray-100
                                   dark:text-gray-300
                                   dark:hover:bg-zinc-800
                                   dark:hover:text-white">

                        Other Concern

                    </a>

                    @endif

                </div>

            </div>

        </nav>


        {{-- PAGE CONTENT --}}
        <main>
            {{ $slot }}
        </main>

    </div>


    {{-- SCRIPTS --}}
    @fluxScripts
    @livewireScripts

</body>

</html>