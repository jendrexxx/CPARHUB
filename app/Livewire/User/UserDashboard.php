<?php

namespace App\Livewire\User;

use App\Models\cpar_histories;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;

#[Layout('components.layouts.app')]
class UserDashboard extends Component
{
    public $cpar_request_count = '';
    public $result_request_count = '';
    public $employee_no = '';
    public $assigned_cpar = '';
    public $id = '';
    public $nte_cpar = '';
    public $histories = [];
    public $ir_cpar = '';
    public $result_assign_count = '';

    protected $listeners = [
        'refreshCparCount' => 'loadCparCount',
        'refreshAssignedCount' => 'loadAssignedCount',
        'refreshNTECount' => 'loadNTECount',
        'refreshIRCount' => 'loadIRCount',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
        $this->loadCparCount();
        $this->loadAssignedCount();
        $this->loadNTECount();
        $this->loadIRCount();
        $this->loadResultCount();
        $this->loadResultAssignCount();
    }

    // result request 
    public function loadResultCount()
    {
        $this->result_request_count = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->where('employee_no', $this->employee_no)
            ->count();
    }
    public function loadResultAssignCount()
    {
        $this->result_assign_count = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.cpar_id')
            ->where('d.status_id', 10)
            ->where('d.record_type', 10)
            ->where('d.assigned_to', (int) $this->id)
            ->count();
    }
    // end result

    // cpar request count
    public function loadCparCount()
    {
        $this->cpar_request_count = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->select('a.employee_no', 'h.status_name')
            ->where('b.status_id', 1)
            ->where('b.record_type', 5)
            ->where('a.employee_no', $this->employee_no)
            ->count();
    }
    // cpar assigned count
    public function loadAssignedCount()
    {
        $this->assigned_cpar = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->leftJoin('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->leftJoin('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->leftJoin('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->join('employees as i', 'b.dept_head_assigned', 'i.id')
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
            ->count();
    }
    // cpar NTE count
    public function loadNTECount()
    {
        $this->nte_cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->join('cpar_ir_requests as i', 'b.id', '=', 'i.assignment_ir_id')
            ->join('cpar_notice_to_explains as j', 'j.assignment_id', '=', 'i.id')
            ->where('b.assigned_to', $this->id)
            ->where('b.status_id', 23)
            ->where('b.record_type', 5)
            ->count();
    }
    // cpar IR count
    public function loadIRCount()
    {
        $this->ir_cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->join('cpar_ir_requests as i', 'i.assignment_ir_id', '=', 'b.id')
            ->where('i.employee_no', $this->employee_no)
            ->where('b.status_id', 30)
            ->count();
    }

    public function printIncidentReport()
    {
        $this->dispatch('view-print', id: $this->id);
    }

    public function render()
    {
        return view('livewire.user.user_dashboard');
    }
}
