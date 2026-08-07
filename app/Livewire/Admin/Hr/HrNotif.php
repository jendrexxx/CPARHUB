<?php

namespace App\Livewire\Admin\Hr;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Illuminate\Support\Facades\DB;

class HrNotif extends Component
{
    public $cpar_requests = [];
    public $editingId = null;
    public $showEditModal = false;

    public $edit_cpar_no = '';
    public $edit_reported_by = '';
    public $edit_date_open = '';
    public $edit_department_name = '';
    public $employee_no = '';

    protected $listeners = [
        'refreshHRData' => 'loadHRRecords',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->employee_no = $info->employee_no;
        }
        $this->loadHRRecords();
    }

    public function loadHRRecords()
    {
        $this->cpar_requests = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->join('employees as c', 'b.assigned_to', 'c.id')
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
            ->where('b.status_id', 5)
            ->orderByDesc('b.id')
            ->get();
    }

    public function viewDetails($id)
    {
        $this->dispatch('view-CPAR', id: $id);
    }

    public function UpdateAssign($id)
    {
        $this->dispatch('open-reassign', id: $id);
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_notif');
    }
}
