<div>
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
            Role Setup
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Manage system roles and control access to available permissions.
        </p>
    </div>
    <livewire:common.custom-table
        :model="'Spatie\Permission\Models\Role'"
        refreshEvent="refreshRoles"
        addRoute="role-create"
        addLabel="Role"
        :columns="[
            'id' => '#',
            'name' => 'Role',
            'actions' => 'Action',
        ]"
        :searchable="[
            'name',
        ]" />
    <livewire:system.modal.role />
</div>