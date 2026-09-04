<?php

namespace App\Livewire\User\Modal;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Illuminate\Support\Facades\DB;

class ResultAssigned extends Component
{
    public $resultRequests = [];
    public $editingId = null;
    public $showEditModal = false;
    public $edit_cpar_no = '';
    public $edit_reported_by = '';
    public $edit_date_open = '';
    public $edit_department_name = '';
    public $employee_no = '';
    public $id = '';
    
    protected $listeners = [
        'refreshAssignedData' => 'loadAssignedRecords',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
        $this->loadAssignedRecords();
    }

    public function loadAssignedRecords()
    {
        $this->resultRequests = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('employees as e', 'd.assigned_to', '=', 'e.id')
            ->join('priority_levels as f', 'a.priority_level', '=', 'f.id')
            ->join('cpar_statuses as g', 'd.status_id', '=', 'g.id')
            ->select(
                'a.id',
                'a.result_no',
                'a.reported_by',
                'a.employee_no',
                'a.date_reported',
                'a.patient_name',
                'b.source_name',
                'c.complain_name',
                'a.attending_physician',
                'd.id as assignment_id',
                'd.assigned_to',
                'd.status_id',
                'd.remarks',
                'd.dept_head_assigned',
                'f.priority_name',
                'g.status_name',
            )
            ->where('d.status_id', 10)
            ->where('d.assigned_to', $this->id)
            ->distinct()
            ->orderByDesc('a.id')
            ->get();

        foreach ($this->resultRequests as $request) {
            $employee = Employee::find($request->assigned_to);
            $request->assigned_names = $employee
                ? $employee->first_name . ' ' . $employee->last_name
                : 'Unknown Employee';
        }
    }

    public function respondResult($id)
    {
        $this->dispatch('respond-Result', id: $id);
    }

    public function render()
    {
        return view('livewire.user.modal.result_assigned');
    }
}
