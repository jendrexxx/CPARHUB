<div>
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