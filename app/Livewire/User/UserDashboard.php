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

        $this->result_request_count = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->where('employee_no', $this->employee_no)
            ->count();

        $this->loadCparCount();
        $this->loadAssignedCount();
        $this->loadNTECount();
        $this->refreshHistoryCount();
        $this->loadIRCount();
    }

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
            ->where('a.employee_no', $this->employee_no)
            ->count();
    }
    // cpar assigned count
    public function loadAssignedCount()
    {
        $this->assigned_cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->whereIn('b.status_id', [5, 10])
            ->where('b.assigned_to', (int) $this->id)
            ->count();
    }
    // cpar NTE count
    public function loadNTECount()
    {
        $this->nte_cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->join('cpar_notice_to_explains as i', 'i.assignment_id', '=', 'b.id')
            ->where('b.assigned_to', $this->id)
            ->where('b.status_id', 30)
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

    public function refreshHistoryCount()
    {
        $this->histories = cpar_histories::where('cpar_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.user.user_dashboard');
    }
}
