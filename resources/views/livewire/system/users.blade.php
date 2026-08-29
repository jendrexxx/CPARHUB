<div>
    
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
            User Setup
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Manage system users, employee accounts, departments, branches, and assigned roles.
        </p>
    </div>

    <livewire:common.custom-table
        :model="'App\Models\User'"
        refreshEvent="refreshUsers"
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