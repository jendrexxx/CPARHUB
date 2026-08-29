<?php

namespace App\Livewire\User\Modal;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Illuminate\Support\Facades\DB;

class CparAssigned extends Component
{
    public $cpar_requests = [];
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
        $this->cpar_requests = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->leftJoin('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->leftJoin('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->leftJoin('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->leftJoin('employees as g', 'a.employee_no', '=', 'g.employee_no')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->join('employees as i', 'b.assigned_to', 'i.id')
            ->join('priority_levels as m', 'a.priority_level', '=', 'm.id')
            ->select(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'a.concern_description',
                'a.complainant_name',
                'b.id as assignment_id',
                'b.cpar_id',
                'b.assigned_to',
                'b.status_id',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',
                'd.source_name',
                'e.complain_name',
                'f.concern_name',
                'g.department_name',
                'c.file_path',
                'h.status_name',
                'i.branch_id',
                'i.first_name',
                'i.last_name',
                'm.priority_name'
            )
            ->where('b.status_id', 10)
            ->where('b.assigned_to', $this->id)
            ->distinct()
            ->orderByDesc('a.id')
            ->get();

        foreach ($this->cpar_requests as $request) {
            $employee = Employee::find($request->assigned_to);
            $request->assigned_names = $employee
                ? $employee->first_name . ' ' . $employee->last_name
                : 'Unknown Employee';
        }
    }

    public function viewDetails($id)
    {
        $this->dispatch('view-CPAR', id: $id);
    }

    public function respondCpar($id)
    {
        $this->dispatch('respond-CPAR', id: $id);
    }

    public function render()
    {
        return view('livewire.user.modal.cpar_assigned');
    }
}
