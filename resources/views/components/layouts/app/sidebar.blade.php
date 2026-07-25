@php
$isAdminView = request()->routeIs([
    'admin_dashboard',
    'user_management',
    'employees',
    'system_setup',
    'dept_head_dashboard',
    'hr_dashboard'
]);
@endphp

@if($isAdminView)
    <x-layouts.app.sidebar-admin>
        {{ $slot }}
    </x-layouts.app.sidebar-admin>
@else
    <x-layouts.app.sidebar-user>
        {{ $slot }}
    </x-layouts.app.sidebar-user>
@endif