<?php

namespace App\Livewire\Admin\Cpar;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Illuminate\Support\Facades\DB;

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
        'refreshHeadRecords' => 'loadHeadRecords',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
        $this->loadHeadRecords();
    }

    public function loadHeadRecords()
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
                'h.status_name'
            )
            ->where('c.id', $this->id)
            ->where('h.status_name', 'PENDING')
            ->orderByDesc('b.id')
            ->get();
    }

    public function viewDetails($id)
    {
        $this->dispatch('view-CPAR', id: $id);
    }

    public function reAssign($assignment_id)
    {
        $this->dispatch('open-reassign', id: $assignment_id);
    }

    public function render()
    {
        return view('livewire.admin.cpar.cpar_notif');
    }
}
