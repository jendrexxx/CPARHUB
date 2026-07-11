@php
$isAdminView = request()->routeIs('admin_dashboard');
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