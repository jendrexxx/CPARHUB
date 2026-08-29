<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Illuminate\Support\Facades\DB;

use Livewire\Component;

#[Layout('components.layouts.app')]
class DeptHeadDashboard extends Component
{
    public $cpar_request_count = '';
    public $result_request_count = '';
    public $employee_no = '';
    public $submission_cpar = '';
    public $id = '';

    protected $listeners = [
        'refreshHeadCount' => 'loadHeadCount',
        'refreshSubmissionCount' => 'loadHeadSubmissionCount',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->employee_no = $info->employee_no;
            $this->id = $info->id;
        }
        $this->loadHeadCount();
        $this->loadHeadSubmissionCount();
        $this->loadResultCount();
    }

    public function loadResultCount()
    {
        $this->result_request_count = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.cpar_id')
            ->where('d.dept_head_assigned', $this->id)
            ->where('d.record_type', 10)
            ->where('d.status_id', 1)
            ->count();
    }

    public function loadHeadCount()
    {
        $this->cpar_request_count = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->join('employees as i', 'b.dept_head_assigned', 'i.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->where('b.status_id', 1)
            ->where('b.record_type', 5)
            ->where('i.id', $this->id)
            ->count();
    }

    public function loadHeadSubmissionCount()
    {
        $this->submission_cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->join('employees as j', 'b.assigned_to', 'j.id')
            ->where('b.status_id', 15)
            ->where('j.dept_head', $this->employee_no)
            ->count();
    }

    public function render()
    {
        return view('livewire.admin.dept_head_dashboard');
    }
}
