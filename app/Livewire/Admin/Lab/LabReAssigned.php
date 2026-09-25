<?php

namespace App\Livewire\Admin\Lab;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Illuminate\Support\Facades\DB;

class LabReAssigned extends Component
{
    protected $listeners = [
        'open-reassign' => 'open'
    ];

    public $cpar_id = '';
    public $remarks = '';
    public $employees = [];
    public $employee_no = '';
    public $branch_id = '';
    public $department_id = '';
    public $id = '';
    public $assigned = null;
    public $assigned_to = [];
    public $new_assignees = [];
    public $assigned_head = '';
    public $dept_head_assigned = '';
    public $assignment_id = '', $priority = '', $emp_reported = '';
    public $cpar_no = '', $reported_by = '', $date_open = '', $department_name = '', $status_name = '', $source_name = '', $complain_name = '', $concern_name = '', $concern_description = '', $complainant_name = '', $attachment = '';

    public function mount()
    {
        $user = Auth::user();
        $info = Employee::where('email', $user->email)->first();
        if ($info) {
            $this->id          = $info->id;
            $this->employee_no = $info->employee_no;
            $this->branch_id   = $info->branch_id;
            $this->department_id = $info->department_id;
        }
        $this->assigned_to = [];
        $this->employees = employee::where('branch_id', $this->branch_id)
            ->orderBy('first_name')
            ->get()
            ->map(function ($employee) {
                $employee->full_name = $employee->first_name . ' ' . $employee->last_name;
                return $employee;
            });
    }

    


    public function render()
    {
        return view('livewire.admin.lab.lab_re-assigned');
    }
}
