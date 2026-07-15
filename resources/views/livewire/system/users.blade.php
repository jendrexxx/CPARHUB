<div>
    <livewire:common.custom-table
        :model="'App\Models\User'"
        refreshEvent="refreshUsers"
        refreshMethod="fetchUser"
        addRoute="user-create"
        addLabel="User"
        :columns="[
            'full_name' => 'Employee Name',
            'employee_no' => 'Employee No',
            'username' => 'Username',
            'department_name' => 'Department',
            'branch_name' => 'Branch',
            'status' => 'Status',
            'role' => 'Role',
            'actions' => 'Actions',
        ]"
        :searchable="[
            'full_name',
            'employee_no',
            'username',
            'department_name',
            'branch_name',
        ]" />

    <livewire:system.modal.create_user />

    <livewire:system.modal.permission />

</div>