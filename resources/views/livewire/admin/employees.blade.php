<div>

    <div class="mb-6">

        <flux:heading size="xl">
            Employee List
        </flux:heading>

    </div>

    @if($message)
    <div class="mb-4 rounded bg-green-100 p-3 text-green-700">
        {{ $message }}
    </div>
    @endif

    @if($error)
    <div class="mb-4 rounded bg-red-100 p-3 text-red-700">
        {{ $error }}
    </div>
    @endif

    <livewire:common.custom-table
        :model="'App\Models\Employee'"
        refreshEvent="refreshEmployees"
        addRoute="employee-create"
        addLabel="Employee"
        :columns="[
            'employee_no'=>'Employee No',
            'first_name'=>'First Name',
            'last_name'=>'Last Name',
            'department_name'=>'Department',
            'branch_name'=>'Branch',
            'position_name'=>'Position',
            'status'=>'Status',
            'email'=>'Email',
        ]"

        :searchable="[
            'employee_no',
            'first_name',
            'last_name',
            'email'
        ]" />

</div>