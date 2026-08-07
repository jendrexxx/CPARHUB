<?php

namespace App\Livewire\Admin\Cpar;

use App\Models\employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CparSubmissionForm extends Component
{
    public $cpar_requests = '';
    public $employee_no = '';

    protected $listeners = [
        'refreshSubmission' => 'loadSubmissionRecords',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->employee_no = $info->employee_no;
        }
        $this->loadSubmissionRecords();
    }

    public function loadSubmissionRecords()
    {
        $this->cpar_requests = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->select(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'b.id as assignment_id',
                'b.assigned_to',
                'i.employee_no',
                'i.first_name',
                'i.last_name',
                'i.dept_head',
                'g.department_name',
                'h.status_name'
            )
            ->whereIn('h.status_name', ['RESOLVED', 'UNRESOLVED'])
            ->where('i.dept_head', $this->employee_no)
            ->orderByDesc('a.id')
            ->get();
    }

    public function ViewSubmissionCPAR($assignment_id)
    {
        $this->dispatch('open-submission-cpar', id: $assignment_id);
    }

    public function render()
    {
        return view('livewire.admin.cpar.cpar_submission_form');
    }
}
