<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;

#[Layout('components.layouts.app')]
class HrHeadDashboard extends Component
{
    public $cpar_request_count = '';
    public $result_request_count = '';
    public $employee_no = '';
    public $acknowledged_cpar = '';
    public $hr_decision_count = '';
    public $branch_id = '';
    public $branch_name = '';
    public $branches = [];
    public $offenseTab = 'ALL';
    public $offenseCategories = [];
    public $search = '';
    public $cpar_offense = '';
    public $offenseFilter = 'ALL';
    public $categoryFilter = 'ALL';
    public $departmentFilter = 'ALL';
    public $statusFilter = 'ALL';
    public $validityFilter = 'ALL';
    public $disciplinaryCategories = [];
    public $memo_count = '';
    public $result_ir_cpar_count = '', $decision_result_count = '';
    public $hr_request_count = [];

    protected $listeners = [
        'refreshHRCount' => 'loadHRCount',
        'refreshAcknowledgeCount' => 'loadAcknowledgeCount',
        'refreshDecisionCount' => 'loadDecisionCount',
        'refreshPreviousOffense' => 'loadPreviousOffense',
        'HRrefreshCount' => 'loadHRResultCount',
        'refreshMemoCount' => 'loadMemoCount'
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->employee_no = $info->employee_no;
            $this->branch_id = $info->branch_id;
        }
        $this->offenseCategories = DB::table('cpar_decision_categories')
            ->orderBy('id')
            ->get();
        $this->disciplinaryCategories = DB::table('cpar_disciplinary_categories')
            ->orderBy('id')
            ->get();
        $this->branches = DB::table('branches')
            ->orderBy('branch_name')
            ->get();
        $this->loadHRCount();
        $this->loadAcknowledgeCount();
        $this->loadDecisionCount();
        $this->loadMemoCount();
        // result count
        $this->loadHRResultCount();
        $this->loadHRIRResultCount();
        $this->loadDecisionResultCount();
    }

    public function loadHRCount()
    {
        // CPAR count
        $cparCount = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('employees as i', 'b.assigned_to', '=', 'i.id')
            ->where('b.status_id', 5)
            ->where('b.record_type', 5)
            ->where('i.branch_id', $this->branch_id)
            ->count(DB::raw('DISTINCT a.id'));

        // Result Error count
        $resultCount = DB::table('result_error_forms as a')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('employees as i', 'd.assigned_to', '=', 'i.id')
            ->where('d.status_id', 5)
            ->where('d.record_type', 10)
            ->where('i.branch_id', $this->branch_id)
            ->count(DB::raw('DISTINCT a.id'));

        // Combined count
        $this->hr_request_count = $cparCount + $resultCount;
    }

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
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('cpar_assignments as b2')
                    ->whereColumn('b2.cpar_id', 'b.cpar_id')
                    ->where('b2.status_id', 20)
                    ->where('b2.record_type', 5)
                    ->whereColumn('b2.id', '>', 'b.id');
            })
            ->count();


        // Result Error count
        $resultCount = DB::table('cpar_assignments as b')
            ->join('result_error_forms as a', 'a.id', '=', 'b.result_id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->where('b.status_id', 20)
            ->where('b.record_type', 10)
            ->where('i.branch_id', $this->branch_id)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('cpar_assignments as b2')
                    ->whereColumn('b2.result_id', 'b.result_id')
                    ->where('b2.status_id', 20)
                    ->where('b2.record_type', 10)
                    ->whereColumn('b2.id', '>', 'b.id');
            })
            ->count();

        // Combined count
        $this->acknowledged_cpar = $cparCount + $resultCount;
    }

    public function loadDecisionCount()
    {
        $this->hr_decision_count = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->join('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->whereIn('b.status_id', [20, 25, 30])
            ->where('branch_id', $this->branch_id)
            ->count();
    }

    public function loadMemoCount()
    {
        $this->memo_count = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->join('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->where('b.status_id', 40)
            ->where('branch_id', $this->branch_id)
            ->count();
    }

    // RESULT COUNT
    public function loadHRResultCount()
    {
        $this->result_request_count = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('employees as i', 'd.assigned_to', '=', 'i.id')
            ->where('d.status_id', 5)
            ->where('d.record_type', 10)
            ->where('i.branch_id', $this->branch_id)
            ->count(DB::raw('DISTINCT a.id'));
    }

    public function loadHRIRResultCount()
    {
        $this->result_ir_cpar_count = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('employees as i', 'd.assigned_to', '=', 'i.id')
            ->where('d.status_id', 20)
            ->where('d.record_type', 10)
            ->where('i.branch_id', $this->branch_id)
            ->count(DB::raw('DISTINCT a.id'));
    }

    public function loadDecisionResultCount()
    {
        $this->decision_result_count = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('employees as i', 'd.assigned_to', '=', 'i.id')
            ->whereIn('d.status_id', [20, 25, 30])
            ->where('d.record_type', 10)
            ->where('branch_id', $this->branch_id)
            ->count();
    }

    public function updatedBranchId()
    {
        $this->loadHRCount();
        $this->loadAcknowledgeCount();
        $this->loadDecisionCount();
        $this->loadMemoCount();
    }

    public function render()
    {
        return view('livewire.admin.hr_head_dashboard');
    }
}
