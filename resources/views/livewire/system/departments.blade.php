<div>
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
            Department Setup
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Manage departments configured in the system.
        </p>
    </div>
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
        ]" />
</div>