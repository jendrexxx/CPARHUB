<div class="space-y-2">

    <button
        wire:click="setTab('users')"
        @class([
            'w-full text-left px-3 py-2 rounded-lg transition',
            'bg-red-700 text-white' => $tab == 'users',
            'hover:bg-gray-100' => $tab != 'users'
        ])>
        User Setup
    </button>

    <button
        wire:click="setTab('permissions')"
        @class([
            'w-full text-left px-3 py-2 rounded-lg transition',
            'bg-red-700 text-white' => $tab == 'permissions',
            'hover:bg-gray-100' => $tab != 'permissions'
        ])>
        Permissions
    </button>

    <button
        wire:click="setTab('departments')"
        @class([
            'w-full text-left px-3 py-2 rounded-lg transition',
            'bg-red-700 text-white' => $tab == 'departments',
            'hover:bg-gray-100' => $tab != 'departments'
        ])>
        Departments
    </button>

    <button
        wire:click="setTab('branches')"
        @class([
            'w-full text-left px-3 py-2 rounded-lg transition',
            'bg-red-700 text-white' => $tab == 'branches',
            'hover:bg-gray-100' => $tab != 'branches'
        ])>
        Branches
    </button>

    <button
        wire:click="setTab('auditlogs')"
        @class([
            'w-full text-left px-3 py-2 rounded-lg transition',
            'bg-red-700 text-white' => $tab == 'auditlogs',
            'hover:bg-gray-100' => $tab != 'auditlogs'
        ])>
        Audit Logs
    </button>

</div>