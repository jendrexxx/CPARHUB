<?php

namespace App\Livewire\Admin\Result;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Illuminate\Support\Facades\DB;

class ResultConcernForm extends Component
{

    public $resultRequests = [];
    public $employee_no = '';
    public $id = '';

    protected $listeners = [
        'refreshAcknowledgeData' => 'loadAcknowledgeRecords',
    ];
    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
        $this->loadAcknowledgeRecords();
    }

    public function loadAcknowledgeRecords()
    {
        $this->resultRequests = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'd.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'd.assigned_to', '=', 'i.id')
            ->join('priority_levels as m', 'a.priority_level', '=', 'm.id')
            ->select(
                'a.id',
                'a.result_no',
                'a.reported_by',
                'a.date_reported',
                'd.id as assignment_id',
                'd.status_id',
                'd.assigned_to',
                'g.department_name',
                'h.status_name',
                DB::raw("CONCAT(i.first_name, ' ', i.last_name) as assigned_names"),
                'm.priority_name'
            )
            ->where('d.status_id', 15)
            ->where('d.record_type', 10)
            ->orderByDesc('d.id')
            ->get();
    }

    public function ViewAcknowledgement($assignment_id)
    {
        $this->dispatch('open-acknowledgement-result', id: $assignment_id);
    }

    public function render()
    {
        return view('livewire.admin.result.result_concern_form');
    }
}
