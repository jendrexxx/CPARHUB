<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-r border-zinc-200 dark:border-zinc-700 bg-red-800 text-white [&_*]:text-white">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
            <x-app-logo class="size-8" href="#"></x-app-logo>
        </a>

        <flux:navlist
            variant="outline"
            class="!border-red-600 !ring-red-600 !text-white [&_*]:!text-white [&_*]:!bg-red-800">
            <flux:navlist.group heading="Platform" class="grid">
                <flux:navlist.item icon="home" :href="route('user_dashboard')" :current="request()->routeIs('user_dashboard')" wire:navigate>User Dashboard</flux:navlist.item>
                <flux:navlist.item icon="document" :href="route('admin_dashboard')" :current="request()->routeIs('admin_dashboard')" wire:navigate>Admin Dashboard</flux:navlist.item>
                <flux:navlist.item
                    icon="document"
                    :href="route('user.cpar_request_form')"
                    :current="request()->routeIs('user.cpar_request_form')"
                    wire:navigate>
                    CPAR Request Form
                </flux:navlist.item>
                <flux:navlist.item
                    icon="document"
                    :href="route('user.result.result_request_form')"
                    :current="request()->routeIs('user.result.result_request_form')"
                    wire:navigate>
                    Result Concern Form
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>


        <flux:spacer />

        <!-- Desktop User Menu -->
        <flux:dropdown class="hidden lg:block !text-white[&_*]:!text-white [&_*]:!bg-red-800" position="bottom" align="start">
            <flux:profile
                :name="auth()->user()->name"
                :initials="auth()->user()->initials()"
                icon-trailing="chevrons-up-down" />
            <flux:menu class="w-[220px]">
                <flux:menu.separator />
                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>Settings</flux:menu.item>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:spacer />
        <flux:dropdown position="top" align="end">
            <flux:profile
                :initials="auth()->user()->initials()"
                icon-trailing="chevron-down" />
            <flux:menu>
                <flux:menu.separator />
                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>Settings</flux:menu.item>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
    @livewireScripts
</body>

</html>

</html>