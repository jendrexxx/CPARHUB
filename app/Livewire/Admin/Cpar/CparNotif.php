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
        $cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('employees as c', 'b.dept_head_assigned', '=', 'c.id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->select(
                'a.id',
                'a.cpar_no as record_no',
                'a.reported_by',
                'a.date_open as date_reported',
                'b.id as assignment_id',
                'b.status_id',
                'b.assigned_to',
                'g.department_name',
                'h.status_name',
                DB::raw("CONCAT(c.first_name, ' ', c.last_name) as assigned_full_name"),
                DB::raw("'CPAR' as record_type")
            )
            ->where('c.id', $this->id)
            ->where('b.status_id', 1)
            ->where('b.record_type', 5);


        // =========================================================
        // RESULT ERROR
        // =========================================================
        $result = DB::table('result_error_forms as a')
            ->join(
                'result_error_source_of_infos as b',
                'a.source_of_information',
                '=',
                'b.id'
            )
            ->join(
                'result_complain_categories as c',
                'a.complainant_category',
                '=',
                'c.id'
            )
            ->join(
                'cpar_assignments as d',
                'a.id',
                '=',
                'd.result_id'
            )
            ->join(
                'departments as g',
                'a.department_id',
                '=',
                'g.id'
            )
            ->join(
                'cpar_statuses as h',
                'd.status_id',
                '=',
                'h.id'
            )
            ->leftJoin(
                'employees as i',
                'd.dept_head_assigned',
                '=',
                'i.id'
            )
            ->select(
                'a.id',
                'a.result_no as record_no',
                'a.reported_by',
                'a.date_reported',
                'd.id as assignment_id',
                'd.status_id',
                'd.assigned_to',
                'g.department_name',
                'h.status_name',
                DB::raw("CONCAT(i.first_name, ' ', i.last_name) as assigned_full_name"),
                DB::raw("'RESULT' as record_type")
            )
            ->where('i.id', $this->id)
            ->where('d.status_id', 1)
            ->where('d.record_type', 10);

        $this->cpar_requests = $cpar
            ->unionAll($result)
            ->orderByDesc('assignment_id')
            ->get();
    }

    public function Assigncpar($assignment_id)
    {
        $this->dispatch('open-reassign', id: $assignment_id);
    }
    
    public function Assignresult($assignment_id)
    {
        $this->dispatch('open-assign-result', id: $assignment_id);
    }

    public function render()
    {
        return view('livewire.admin.cpar.cpar_notif');
    }
}
