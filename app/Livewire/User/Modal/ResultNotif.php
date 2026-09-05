<?php

namespace App\Livewire\User\Modal;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;

class ResultNotif extends Component
{
    public $resultRequests = [];
    public $employee_no = '';
    public $id = '';

    protected $listeners = [
        'refreshData' => 'ResultloadRecords',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
        $this->ResultloadRecords();
    }

    public function ResultloadRecords()
    {
        $this->resultRequests = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('employees as d', 'a.employee_no', '=', 'd.employee_no')
            ->join('cpar_assignments as e', 'a.id', '=', 'e.result_id')
            ->join('cpar_statuses as f', 'e.status_id', '=', 'f.id')
            ->join('employees as g', 'e.dept_head_assigned', '=', 'g.id')
            ->join('priority_levels as h', 'a.priority_level', '=', 'h.id')
            ->select(
                'a.id',
                'a.result_no',
                'a.reported_by',
                'a.date_reported',
                'b.source_name',
                'c.complain_name',
                'd.department_name',
                'e.id as assignment_id',
                'e.assigned_to',
                'e.status_id',
                'e.remarks',
                'e.dept_head_assigned',
                'f.status_name',
                'f.badge_color',

                'h.priority_name',
            )
            ->where('a.employee_no', $this->employee_no)
            ->where('e.status_id', 1)
            ->where('e.record_type', 10)
            ->get();
        foreach ($this->resultRequests as $request) {
            $employee = Employee::find($request->assigned_to);
            $request->assigned_names = $employee
                ? $employee->first_name . ' ' . $employee->last_name
                : 'Unknown Employee';
        }
    }

    public function viewResult()
    {
        dd('Livewire works!');
    }

    public function render()
    {
        return view('livewire.user.modal.result_notif');
    }
}
