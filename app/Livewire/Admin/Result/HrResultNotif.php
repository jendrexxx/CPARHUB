<?php

namespace App\Livewire\Admin\Result;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;

class HrResultNotif extends Component
{
    public $resultRequests = [];
    public $employee_no = '';
    public $id = '';
    public $branch_id = '';

    protected $listeners = [
        'HRrefreshData' => 'HRloadRecords',
    ];

    public function mount($branch_id)
    {
        $this->branch_id = $branch_id;
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
        $this->HRloadRecords();
    }

    public function HRloadRecords()
    {
        $this->resultRequests = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('employees as d', 'a.employee_no', '=', 'd.employee_no')
            ->join('cpar_assignments as e', 'a.id', '=', 'e.cpar_id')
            ->join('cpar_statuses as f', 'e.status_id', '=', 'f.id')
            ->select(
                'a.id',
                'a.result_no',
                'a.patient_name',
                'a.date_reported',
                'b.source_name',
                'c.complain_name',
                'd.department_name',
                'f.status_name',
                'f.badge_color',
                'e.id as assigned_id'
            )
            ->where('d.branch_id', $this->branch_id)
            ->where('e.status_id', 5)
            ->where('e.record_type', 10)
            ->get();
    }

    public function viewResultDetails($assigned_id)
    {
        $this->dispatch('view-Result', id: $assigned_id);
    }

    public function updatedBranchId($value = '')
    {
        $this->branch_id = $value;
        $this->HRloadRecords();
    }

    public function render()
    {
        return view('livewire.admin.result.hr_result_notif');
    }
}
