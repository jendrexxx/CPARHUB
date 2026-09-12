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
    public $request_count = '';
    public $assigned_count = [];

    protected $listeners = [
        'refreshCparCount' => 'loadRequestCount',
        'refreshAssignedCount' => 'loadAssignedCount',
        'refreshNTECount' => 'loadNTECount',
        'refreshIRCount' => 'loadIRCount',
        'refreshResultCount'  => 'loadResultAssignCount'
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
        $this->loadRequestCount();
        $this->loadAssignedCount();
        $this->loadNTECount();
    }

    // cpar request count
    public function loadRequestCount()
    {
        $cparCount = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->where('b.status_id', 1)
            ->where('b.record_type', 5)
            ->where('a.employee_no', $this->employee_no)
            ->count();
        $resultCount = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->where('b.status_id', 1)
            ->where('b.record_type', 10)
            ->where('a.employee_no', $this->employee_no)
            ->count();
        $this->request_count = $cparCount + $resultCount;
    }
    // cpar assigned count
    public function loadAssignedCount()
    {
        // CPAR assigned count
        $cparCount = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->where('b.status_id', 10)
            ->where('b.record_type', 5)
            ->where('b.assigned_to', (int) $this->id)
            ->count(DB::raw('DISTINCT a.id'));
        // Result Error assigned count
        $resultCount = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->where('b.status_id', 10)
            ->where('b.record_type', 10)
            ->where('b.assigned_to', (int) $this->id)
            ->count(DB::raw('DISTINCT a.id'));
        // Combined CPAR + Result Error count
        $this->assigned_count = $cparCount + $resultCount;
    }
    // cpar NTE count
    public function loadNTECount()
    {
        $cparCount = DB::table('cpar_assignments as b')
            ->join('cpar_request_forms as a', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_ir_requests as i', 'b.id', '=', 'i.assignment_ir_id')
            ->join('cpar_notice_to_explains as j', 'j.assignment_id', '=', 'i.id')
            ->where('b.assigned_to', $this->id)
            ->where('b.status_id', 23)
            ->where('b.record_type', 5)
            ->count();

        $resultCount = DB::table('cpar_assignments as b')
            ->join('result_error_forms as a', 'a.id', '=', 'b.result_id')
            ->where('b.assigned_to', $this->id)
            ->where('b.status_id', 23)
            ->where('b.record_type', 10)
            ->count();
        $this->nte_cpar = $cparCount + $resultCount;
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
