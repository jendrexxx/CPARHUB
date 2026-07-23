<div>
    <livewire:common.custom-table
        :model="'App\Models\Department'"
        refreshEvent="refreshDepartments"
        :columns="[
            'id' => '#',
            'department_name' => 'Department Name',
        ]"
        :searchable="[
            'id',
            'department_name',
        ]"
    />
</div>