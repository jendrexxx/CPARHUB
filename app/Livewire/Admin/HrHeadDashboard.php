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

    protected $listeners = [
        'refreshHRCount' => 'loadHRCount',
        'refreshAcknowledgeCount' => 'loadAcknowledgeCount',
        'refreshDecisionCount' => 'loadDecisionCount',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->employee_no = $info->employee_no;
            $this->branch_id = $info->branch_id;
        }

        $this->loadHRCount();
        $this->loadAcknowledgeCount();
        $this->loadDecisionCount();

        $this->result_request_count = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->where('employee_no', $this->employee_no)
            ->count();

        $this->branches = DB::table('branches')
            ->orderBy('branch_name')
            ->get();
    }

    public function loadHRCount()
    {
        $this->cpar_request_count = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->join('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->where('b.status_id', 5)
            ->count();
    }

    public function updatedBranchId()
    {
        $this->loadHRCount();
        $this->loadAcknowledgeCount();
        $this->loadDecisionCount();
    }

    public function loadAcknowledgeCount()
    {
        $this->acknowledged_cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->join('cpar_investigations as j', 'b.id', '=', 'j.assigned_id')
            ->whereIn('b.status_id', [25,30])
            ->count();
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
            ->whereIn('b.status_id', [35, 40])
            ->count();
    }

    public function render()
    {
        return view('livewire.admin.hr_head_dashboard');
    }
}
