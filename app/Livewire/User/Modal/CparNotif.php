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
        $this->cpar_requests = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('employees as c', 'b.dept_head_assigned', 'c.id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->select(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'b.id as assignment_id',
                'b.assigned_to',
                'g.department_name',
                'h.status_name',
                DB::raw("CONCAT(c.first_name, ' ', c.last_name) as dept_head_name"),
            )
            ->where('h.status_name', 'PENDING')
            ->where('a.employee_no', $this->employee_no)
            ->get();
    }

    public function viewDetails($id)
    {
        $this->dispatch('view-CPAR', id: $id);
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
