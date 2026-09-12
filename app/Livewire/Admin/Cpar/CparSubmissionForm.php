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
    public $acknowledgment_requests = [];

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
        $cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->select(
                'a.id',
                'a.cpar_no as record_no',
                'a.reported_by',
                'a.date_open as record_date',
                DB::raw("'CPAR' as record_type"),
                'b.id as assignment_id',
                'b.assigned_to',
                'b.status_id',
                'i.employee_no',
                'i.first_name',
                'i.last_name',
                'i.dept_head',
                'g.department_name',
                'h.status_name'
            )
            ->where('b.record_type', 5)
            ->where('b.status_id', 15)
            ->where('i.dept_head', $this->employee_no);

        $result = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'd.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'd.assigned_to', '=', 'i.id')
            ->join('priority_levels as m', 'a.priority_level', '=', 'm.id')
            ->select(
                'a.id',
                'a.result_no as record_no',
                'a.reported_by',
                'a.date_reported as record_date',

                DB::raw("'RESULT' as record_type"),

                'd.id as assignment_id',
                'd.assigned_to',
                'd.status_id',

                'i.employee_no',
                'i.first_name',
                'i.last_name',
                'i.dept_head',

                'g.department_name',
                'h.status_name'
            )
            ->where('d.status_id', 15)
            ->where('d.record_type', 10);

        $this->acknowledgment_requests = $cpar
            ->unionAll($result)
            ->orderByDesc('record_date')
            ->get();
    }

    public function ViewSubmissionCPAR($assignment_id)
    {
        $this->dispatch('open-submission-cpar', id: $assignment_id);
    }

    public function ViewSubmissionResult($assignment_id)
    {
        $this->dispatch('open-acknowledgement-result', id: $assignment_id);
    }

    public function render()
    {
        return view('livewire.admin.cpar.cpar_submission_form');
    }
}
