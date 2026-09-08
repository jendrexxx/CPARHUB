<?php

namespace App\Livewire\User\Modal;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;

class CparNotif extends Component
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
    public $requests = [];

    protected $listeners = [
        'refreshCparData' => 'loadRecords',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
        $this->loadRecords();
    }

    public function loadRecords()
    {
        $cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('employees as c', 'b.dept_head_assigned', '=', 'c.id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->join('priority_levels as i', 'a.priority_level', '=', 'i.id')
            ->select(
                'a.id',
                'a.cpar_no as record_no',
                'a.reported_by',
                'a.date_open as date_reported',
                DB::raw("'CPAR' as record_type"),
                DB::raw("NULL as source_name"),
                DB::raw("NULL as complain_name"),
                'g.department_name',
                'b.id as assignment_id',
                'b.assigned_to',
                'b.status_id',
                'b.remarks',
                'b.dept_head_assigned',
                'h.status_name',
                'h.badge_color',
                'i.priority_name',
                DB::raw("CONCAT(c.first_name, ' ', c.last_name) as dept_head_name")
            )
            ->where('b.status_id', 1)
            ->where('b.record_type', 5)
            ->where('a.employee_no', $this->employee_no);

        $result = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('employees as d', 'a.employee_no', '=', 'd.employee_no')
            ->join('cpar_assignments as e', 'a.id', '=', 'e.result_id')
            ->join('cpar_statuses as f', 'e.status_id', '=', 'f.id')
            ->join('employees as g', 'e.dept_head_assigned', '=', 'g.id')
            ->join('priority_levels as h', 'a.priority_level', '=', 'h.id')
            ->select(
                'a.id',
                'a.result_no as record_no',
                'a.reported_by',
                'a.date_reported',
                DB::raw("'RESULT' as record_type"),
                'b.source_name',
                'c.complain_name',
                'd.department_name',
                'e.id as assignment_id',
                'e.assigned_to',
                'e.status_id',
                'e.remarks',
                'e.dept_head_assigned',
                'f.status_name',
                'f.badge_color',
                'h.priority_name',
                DB::raw("CONCAT(g.first_name, ' ', g.last_name) as dept_head_name")
            )
            ->where('a.employee_no', $this->employee_no)
            ->where('e.status_id', 1)
            ->where('e.record_type', 10);

        $this->requests = $cpar
            ->unionAll($result)
            ->orderBy('date_reported', 'desc')
            ->get();

        // Get assigned employee names
        foreach ($this->requests as $request) {
            $employee = Employee::find($request->assigned_to);

            $request->assigned_names = $employee
                ? $employee->first_name . ' ' . $employee->last_name
                : 'Unknown Employee';
        }
    }

    public function viewCparDetails($id)
    {
        $this->dispatch('view-CPAR', id: $id);
    }

    public function viewResultDetails($id)
    {
        $this->dispatch('view-RESULT', id: $id);
    }

    public function respondCpar($assignment_id)
    {
        $this->dispatch('respond-CPAR', id: $assignment_id);
    }

    public function saveRecord()
    {
        DB::table('cpar_request_forms')
            ->where('id', $this->editingId)
            ->update([
                'reported_by' => $this->edit_reported_by,
                'date_open'   => $this->edit_date_open,
            ]);

        $this->showEditModal = false;
        $this->editingId     = null;
        $this->loadRecords();
    }

    public function cancelEdit()
    {
        $this->showEditModal = false;
        $this->editingId     = null;
    }

    public function render()
    {
        return view('livewire.user.modal.cpar_notif');
    }
}
