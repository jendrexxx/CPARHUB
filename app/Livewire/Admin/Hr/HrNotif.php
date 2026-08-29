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
    public $branch_id = '';

    protected $listeners = [
        'refreshHRData' => 'loadHRRecords',
    ];

    public function mount($branch_id)
    {
        $this->branch_id = $branch_id;
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
            ->join('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->join('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->join('employees as i', 'b.assigned_to', '=', 'i.id')
            ->select(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'b.cpar_id',
                'i.branch_id',
                'g.department_name',
                'h.status_name',
                DB::raw("
                GROUP_CONCAT(
                    DISTINCT b.id
                    ORDER BY b.id
                    SEPARATOR ','
                ) as assignment_ids
            "),
                DB::raw("
                GROUP_CONCAT(
                    DISTINCT b.assigned_to
                    ORDER BY b.id
                    SEPARATOR ','
                ) as assigned_to
            "),
                DB::raw("
                GROUP_CONCAT(
                    DISTINCT CONCAT(i.first_name, ' ', i.last_name)
                    ORDER BY b.id
                    SEPARATOR ', '
                ) as dept_head_name
            ")
            )
            ->where('i.branch_id', $this->branch_id)
            ->where('b.status_id', 5)
            ->where('b.record_type', 5)
            ->groupBy(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'b.cpar_id',
                'i.branch_id',
                'g.department_name',
                'h.status_name'
            )
            ->orderByDesc('a.id')
            ->get();
    }

    public function updatedBranchId($value = '')
    {
        $this->branch_id = $value;
        $this->loadHRRecords();
    }

    public function viewDetails($id)
    {
        $this->dispatch('view-CPAR', id: $id);
    }

    public function UpdateAssign($cpar_id)
    {
        $this->dispatch('open-reassign', id: $cpar_id);
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_notif');
    }
}
