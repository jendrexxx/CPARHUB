<div>
    <livewire:common.custom-table
        :model="'App\Models\Department'"
        refreshEvent="refreshDepartments"
        :columns="[
            'id' => 'id',
            'department_name' => 'Department Name',
            'status' => 'Status',
        ]"
        :searchable="[
            'id',
            'department_name',
        ]"
    />
</div>