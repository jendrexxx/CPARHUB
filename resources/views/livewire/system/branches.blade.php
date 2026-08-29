<div>
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
            Branch Setup
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Manage branches configured in the system.
        </p>
    </div>
    <livewire:common.custom-table
        :model="'App\Models\Branch'"
        refreshEvent="refreshBranches"
        :columns="[
            'id' => 'Branch Code',
            'branch_name' => 'Branch Name',
            'status' => 'Status',
        ]"
        :searchable="[
            'id',
            'branch_name',
        ]" />
</div>