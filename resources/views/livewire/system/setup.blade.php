<div class="space-y-6">

    <div>
        <flux:heading size="xl">System Setup</flux:heading>

        <flux:subheading>
            Manage My User System settings
        </flux:subheading>

        <flux:separator class="mt-4"/>
    </div>

    <div class="grid grid-cols-12 gap-6">

        <div class="col-span-2">
            @include('livewire.system.sidebar')
        </div>

        <div class="col-span-10">

            @if($tab == 'users')
                @include('livewire.system.users')
            @elseif($tab == 'permissions')
                @include('livewire.system.rolepermission')
            @elseif($tab == 'departments')
                @include('livewire.system.departments')
            @elseif($tab == 'branches')
                @include('livewire.system.branches')
            @elseif($tab == 'auditlogs')
                @include('livewire.system.auditlogs')
            @endif

        </div>

    </div>

</div>