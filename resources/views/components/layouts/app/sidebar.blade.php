@php
$isAdminView = request()->routeIs('admin_dashboard') || request()->routeIs('user_management') || request()->routeIs('employees') || request()->routeIs('system_setup');
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