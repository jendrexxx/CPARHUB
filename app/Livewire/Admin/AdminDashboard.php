<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Livewire\Component;

#[Layout('components.layouts.app')]
class AdminDashboard extends Component
{
    public $reported_count = '';
    public $assigned_count = '';
    public $nte_count = '';
    public $acknowledge_count = '';
    public $employee_no = '';
    public $branch_id = '';
    public $branch_name = '';
    public $hr_decision_count = '';
    public $memo_count = '';
    public $branches = [];

    protected $listeners = [
        'refreshAdminCount' => 'loadHeadCount',
        'refreshAssignedCount' => 'loadAssignedCount',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->employee_no = $info->employee_no;
            $this->branch_id = $info->branch_id;
        }
        $this->branches = DB::table('branches')
            ->orderBy('branch_name')
            ->get();
        $this->loadHeadCount();
        $this->loadAssignedCount();
        $this->loadNTECount();
        $this->loadAcknowledgeCount();
        $this->loadDecisionCount();
        $this->loadMemoCount();
    }

    public function updatedBranchId()
    {
        $this->loadHeadCount();
        $this->loadAssignedCount();
        $this->loadNTECount();
        $this->loadAcknowledgeCount();
        $this->loadDecisionCount();
        $this->loadMemoCount();
    }


    public function loadHeadCount()
    {
        $resultRequestCount = DB::table('result_error_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->leftJoin('employees as c', 'a.employee_no', '=', 'c.employee_no')
            ->where('c.branch_id', $this->branch_id)
            ->where('b.record_type', 10)
            ->where('b.status_id', 1)
            ->count();

        $cparRequestCount = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('employees as c', 'a.employee_no', '=', 'c.employee_no')
            ->where('c.branch_id', $this->branch_id)
            ->where('b.record_type', 5)
            ->where('b.status_id', 1)
            ->count();

        $this->reported_count = $resultRequestCount + $cparRequestCount;
    }
    // assigned
    public function loadAssignedCount()
    {
        // CPAR count
        $cparCount = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('employees as i', 'b.assigned_to', '=', 'i.id')
            ->where('b.record_type', 5)
            ->whereIn('b.status_id', [5, 10])
            ->where('i.branch_id', $this->branch_id)
            ->count(DB::raw('DISTINCT a.id'));

        // Result Error count
        $resultCount = DB::table('result_error_forms as a')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('employees as i', 'd.assigned_to', '=', 'i.id')
            ->where('d.record_type', 10)
            ->whereIn('d.status_id', [5, 10])
            ->where('i.branch_id', $this->branch_id)
            ->count(DB::raw('DISTINCT a.id'));

        // Combined count
        $this->assigned_count = $cparCount + $resultCount;
    }
    // NTE count
    public function loadNTECount()
    {
        $cparCount = DB::table('cpar_assignments as b')
            ->join('cpar_request_forms as a', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_ir_requests as i', 'b.id', '=', 'i.assignment_ir_id')
            ->join('cpar_notice_to_explains as j', 'j.assignment_id', '=', 'i.id')
            ->where('b.status_id', 23)
            ->where('b.record_type', 5)
            ->count();

        $resultCount = DB::table('cpar_assignments as b')
            ->join('result_error_forms as a', 'a.id', '=', 'b.result_id')
            ->where('b.status_id', 23)
            ->where('b.record_type', 10)
            ->count();
        $this->nte_count = $cparCount + $resultCount;
    }
    // acknowledge
    public function loadAcknowledgeCount()
    {
        // CPAR count
        $cparCount = DB::table('cpar_assignments as b')
            ->join('cpar_request_forms as a', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->join('cpar_investigations as j', 'b.id', '=', 'j.assigned_id')
            ->where('b.status_id', 20)
            ->where('b.record_type', 5)
            ->where('i.branch_id', $this->branch_id)
            ->count();

        // Result Error count
        $resultCount = DB::table('cpar_assignments as b')
            ->join('result_error_forms as a', 'a.id', '=', 'b.result_id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->where('b.status_id', 20)
            ->where('b.record_type', 10)
            ->where('i.branch_id', $this->branch_id)
            ->count();

        // Combined count
        $this->acknowledge_count = $cparCount + $resultCount;
    }
    // decision
    public function loadDecisionCount()
    {
        $cparCount = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->join('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->whereIn('b.status_id', [20, 25, 30])
            ->where('b.record_type', 5)
            ->where('i.branch_id', $this->branch_id)
            ->count();

        $resultCount = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('employees as i', 'd.assigned_to', '=', 'i.id')
            ->whereIn('d.status_id', [20, 25, 30])
            ->where('d.record_type', 10)
            ->where('i.branch_id', $this->branch_id)
            ->count();

        $this->hr_decision_count = $cparCount + $resultCount;
    }
    // memo
    public function loadMemoCount()
    {
        // Regular CPAR
        $cparCount = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->where('b.status_id', 40)
            ->where('branch_id', $this->branch_id)
            ->count();
        // Result Error
        $resultCount = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->where('b.status_id', 40)
            ->where('branch_id', $this->branch_id)
            ->count();
        // Combined count
        $this->memo_count = $cparCount + $resultCount;
    }

    public function render()
    {
        return view('livewire.admin.admin_dashboard');
    }
}
