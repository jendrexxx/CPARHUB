<?php

namespace App\Livewire\Admin\Hr;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\employee;
use Illuminate\Support\Facades\Auth;

class HrAcknowledgeModal extends Component
{
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
    public $cpar_no = '', $reported_by = '', $date_open = '', $department_name = '', $source_name = '', $complain_name = '', $concern_name = '', $status_name = '';
    public $identified_cause = '';
    public $provided_solution = '';
    public $recommendation = '';
    public $action_taken_by = '';
    public $date_completed = '';
    public $tat = '';
    public $ir_attachment = '', $emp_reported = '', $employeeName = '', $user_id = '';

    protected $listeners = [
        'open-acknowledge-cpar' => 'open_acknowledge'
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->user_id = $info->id;
        }
    }

    public function open_acknowledge($id = null)
    {
        $this->dispatch('acknowledge-cpar', id: $id);
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_acknowledge_modal');
    }
}
