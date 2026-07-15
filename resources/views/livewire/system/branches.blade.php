<div>
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
        ]"
    />
</div>