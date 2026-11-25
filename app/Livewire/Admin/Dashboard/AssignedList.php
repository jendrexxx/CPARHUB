<?php

namespace App\Livewire\Admin\Dashboard;

use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AssignedList extends Component
{

    protected $casts = [
        'assigned_to' => 'array',
    ];

    protected $listeners = [
        'refreshAdminAssigned' => 'loadAssignedRecords',
    ];

    public $assigned_requests = [];
    public $editingId = null;
    public $showEditModal = false;
    public $edit_cpar_no = '';
    public $edit_reported_by = '';
    public $edit_date_open = '';
    public $edit_department_name = '';
    public $result_no = '', $reported_by = '', $patient_name = '', $attending_physician = '', $actual_released_date = '', $source_name = '', $complain_name = '', $concern_description = '', $department_name = '', $priority = '', $remarks = '', $status = '', $dept_head_assigned = '', $date_reported = '', $test_procedure = '', $complainant_name = '';
    public $employeeName = '', $emp_reported = '', $id = '', $employee_no = '', $branch_id = '', $department_id = '', $assignment_remarks = '', $assignment_id = '', $result_id = '';
    public $employees = [];
    public $assigned_to = [];
    public $new_assignees = [];
    public $data = [];
    public $quality = [];
    public $technical = [];
    public $data_information = [];
    public $technical_information = [];
    public $quality_information = [];
    public $existing_attachment = '';

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
        $this->loadAssignedRecords();
    }

    public function loadAssignedRecords()
    {
        $cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('departments as c', 'a.department_id', '=', 'c.id')
            ->leftJoin('employees as d', 'b.dept_head_assigned', '=', 'd.id')
            ->leftJoin('priority_levels as e', 'a.priority_level', '=', 'e.id')
            ->leftJoin('cpar_statuses as f', 'b.status_id', '=', 'f.id')
            ->leftJoin('employees as g', 'b.assigned_to', '=', 'g.id')
            ->select([
                'a.id',
                'a.cpar_no as record_no',
                'a.reported_by',
                'a.date_open as record_date',
                DB::raw("'CPAR' as record_type"),
                'a.concern_description',
                'a.complainant_name',
                DB::raw('NULL as data_information'),
                DB::raw('NULL as technical_information'),
                DB::raw('NULL as quality_information'),
                'b.id as assignment_id',
                'b.cpar_id as record_id',
                DB::raw('NULL as result_id'),
                'b.assigned_to',
                'b.status_id',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',
                'c.department_name',
                'e.priority_name',
                'f.status_name',
                DB::raw("
                    TRIM(
                        CONCAT(
                            COALESCE(d.first_name, ''),
                            ' ',
                            COALESCE(d.last_name, '')
                        )
                    ) as assigned_names
                "),
                DB::raw("
                    TRIM(
                        CONCAT(
                            COALESCE(g.first_name, ''),
                            ' ',
                            COALESCE(g.last_name, '')
                        )
                    ) as assigned_full_name
                "),
            ])
            ->whereIn('b.status_id', [5, 10])
            ->where('b.record_type', 5);

        $result = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->leftJoin('departments as c', 'a.department_id', '=', 'c.id')
            ->leftJoin('employees as d', 'b.dept_head_assigned', '=', 'd.id')
            ->leftJoin('priority_levels as e', 'a.priority_level', '=', 'e.id')
            ->leftJoin('cpar_statuses as f', 'b.status_id', '=', 'f.id')
            ->leftJoin('employees as g', 'b.assigned_to', '=', 'g.id')
            ->select([
                'a.id',
                'a.result_no as record_no',
                'a.reported_by',
                'a.date_reported as record_date',
                DB::raw("'RESULT' as record_type"),
                'a.concern_description',
                DB::raw('NULL as complainant_name'),
                'a.data_information',
                'a.technical_information',
                'a.quality_information',
                'b.id as assignment_id',
                DB::raw('NULL as record_id'),
                'b.result_id',
                DB::raw('NULL as assigned_to'),
                'b.status_id',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',
                'c.department_name',
                'e.priority_name',
                'f.status_name',
                DB::raw("
                    TRIM(
                        CONCAT(
                            COALESCE(d.first_name, ''),
                            ' ',
                            COALESCE(d.last_name, '')
                        )
                    ) as assigned_names
                "),
                DB::raw("
                    TRIM(
                        CONCAT(
                            COALESCE(g.first_name, ''),
                            ' ',
                            COALESCE(g.last_name, '')
                        )
                    ) as assigned_full_name
                "),
            ])
            ->whereIn('b.status_id', [5, 10])
            ->where('b.record_type', 10);

        $this->assigned_requests = $cpar
            ->unionAll($result)
            ->orderByDesc('record_date')
            ->get();
    }

    public function Assignresult($assignment_id = null)
    {
        $this->dispatch('open-assign-concern', id: $assignment_id);
    }

    public function render()
    {
        return view('livewire.admin.dashboard.assigned_list');
    }
}
