<?php

namespace App\Livewire\Admin\Dashboard;

use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\Component;

class ReportedList extends Component
{
    use WithPagination;
    public $employee_no = '';
    public $id = '';
    public $reported_requests = '';

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
        $this->loadReportedRecords();
    }

    public function loadReportedRecords()
    {
        $cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('employees as c', 'b.dept_head_assigned', '=', 'c.id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->select(
                'a.id',
                'a.cpar_no as record_no',
                'a.reported_by',
                'a.date_open as date_reported',
                'b.id as assignment_id',
                'b.status_id',
                'b.assigned_to',
                'g.department_name',
                'h.status_name',
                DB::raw("CONCAT(c.first_name, ' ', c.last_name) as assigned_full_name"),
                DB::raw("'CPAR' as record_type")
            )
            ->where('b.status_id', 1)
            ->where('b.record_type', 5);

        $result = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'd.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'd.dept_head_assigned', '=', 'i.id')
            ->select(
                'a.id',
                'a.result_no as record_no',
                'a.reported_by',
                'a.date_reported',
                'd.id as assignment_id',
                'd.status_id',
                'd.assigned_to',
                'g.department_name',
                'h.status_name',
                DB::raw("CONCAT(i.first_name, ' ', i.last_name) as assigned_full_name"),
                DB::raw("'RESULT' as record_type")
            )
            ->where('d.status_id', 1)
            ->where('d.record_type', 10);

        $this->reported_requests = $cpar
            ->unionAll($result)
            ->orderByDesc('assignment_id')
            ->get();
    }

    
    

    public function render()
    {
        return view('livewire.admin.dashboard.reported_list');
    }
}
