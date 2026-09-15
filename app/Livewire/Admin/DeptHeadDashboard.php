<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Illuminate\Support\Facades\DB;
use Flux\Flux;

use Livewire\Component;

#[Layout('components.layouts.app')]
class DeptHeadDashboard extends Component
{
    public $cpar_request_count = '';
    public $result_request_count = '';
    public $employee_no = '';
    public $submission_cpar = '';
    public $id = '';
    public $result_concern_count = '';
    public $concern_count = [];
    public $acknowledgment_count = [];
    public $request_count = 0;

    protected $listeners = [
        'refreshHeadCount' => 'loadHeadCount',
        'refreshSubmissionCount' => 'loadHeadSubmissionCount',
        'refreshResultCount' => 'loadResultCount',
        'refreshAcknowledgeCount' => 'loadAcknowledgeCount'
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
        $this->loadAcknowledgeCount();
        if (request()->query('open') === 'notifications') {
            Flux::modal('CPARModal')->show();
        }else{
            Flux::modal('CPARModal')->close();
        }
    }

    public function loadHeadCount()
    {
        $resultRequestCount = DB::table('result_error_forms as a')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->where('d.dept_head_assigned', $this->id)
            ->where('d.record_type', 10)
            ->where('d.status_id', 1)
            ->count();
        $cparRequestCount = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->where('b.dept_head_assigned', $this->id)
            ->where('b.record_type', 5)
            ->where('b.status_id', 1)
            ->count();
        $this->concern_count = $resultRequestCount + $cparRequestCount;
    }

    public function loadAcknowledgeCount()
    {
        $cparCount = DB::table('cpar_assignments as b')
            ->join('employees as j', 'b.assigned_to', '=', 'j.id')
            ->where('b.status_id', 15)
            ->where('b.record_type', 5)
            ->where('j.dept_head', $this->employee_no)
            ->count();
        $resultCount = DB::table('cpar_assignments as d')
            ->where('d.status_id', 15)
            ->where('d.record_type', 10)
            ->where('d.dept_head_assigned', $this->id)
            ->count();
            
        $this->acknowledgment_count = $cparCount + $resultCount;
    }

    public function render()
    {
        return view('livewire.admin.dept_head_dashboard');
    }
}
