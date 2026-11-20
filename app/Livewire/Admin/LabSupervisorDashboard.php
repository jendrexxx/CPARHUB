<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class LabSupervisorDashboard extends Component
{
    public $assigned_count = '';
    public $cpar_request_count = '';
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
    protected $listeners = [
        'refreshLABCount' => 'loadLABCount',
        'refreshAssignedCount'  => 'loadAssignedCount'
    ];

    public function mount()
    {
        $this->offenseCategories = DB::table('cpar_decision_categories')
            ->orderBy('id')
            ->get();
        $this->disciplinaryCategories = DB::table('cpar_disciplinary_categories')
            ->orderBy('id')
            ->get();
        $this->loadLABCount();
        $this->loadAssignedCount();
    }

    public function loadAssignedCount()
    {
        $credentials = Auth::user()?->employee;

        $cparCount = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('employees as c', 'b.assigned_to', '=', 'c.id')
            ->leftJoin('employees as d', 'b.dept_head_assigned', '=', 'd.id')
            ->where('b.status_id', '!=', 50)
            ->where('b.record_type', 5)
            ->where('c.branch_id', $credentials->branch_id)
            ->count('b.id');

        $resultCount = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->leftJoin('employees as c', 'b.assigned_to', '=', 'c.id')
            ->leftJoin('employees as d', 'b.dept_head_assigned', '=', 'd.id')
            ->where('b.status_id', '!=', 50)
            ->where('b.record_type', 10)
            ->where('c.branch_id', $credentials->branch_id)
            ->count('b.id');

        $this->assigned_count = $cparCount + $resultCount;
    }

    public function loadLABCount()
    {
        $credentials = Auth::user()?->employee;
        // CPAR count
        $cparCount = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->join('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as r', 'a.employee_no', '=', 'r.employee_no')
            ->where('r.branch_id', $credentials->branch_id)
            ->where('b.status_id', 35)
            ->count();

        // Result Error count
        $resultCount = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as r', 'a.employee_no', '=', 'r.employee_no')
            ->where('r.branch_id', $credentials->branch_id)
            ->where('b.status_id', 35)
            ->count();
        // Combined count
        $this->cpar_request_count = $cparCount + $resultCount;
    }

    public function render()
    {
        return view('livewire.admin.lab_supervisor_dashboard');
    }
}
