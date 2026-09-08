<?php

namespace App\Livewire\User\Modal;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Illuminate\Support\Facades\DB;

class CparAssigned extends Component
{
    public $assigned_requests = [];
    public $editingId = null;
    public $showEditModal = false;
    public $edit_cpar_no = '';
    public $edit_reported_by = '';
    public $edit_date_open = '';
    public $edit_department_name = '';
    public $employee_no = '';
    public $id = '';

    protected $listeners = [
        'refreshAssignedData' => 'loadAssignedRecords',
    ];

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
        /*
    |--------------------------------------------------------------------------
    | CPAR ASSIGNED RECORDS
    |--------------------------------------------------------------------------
    */
        $cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('departments as d', 'a.department_id', '=', 'd.id')
            ->join('employees as e', 'b.assigned_to', '=', 'e.id')
            ->join('priority_levels as p', 'a.priority_level', '=', 'p.id')
            ->join('cpar_statuses as s', 'b.status_id', '=', 's.id')
            ->select(
                'a.id',

                // Common fields
                'a.cpar_no as record_no',
                'a.reported_by',
                'a.date_open as record_date',

                DB::raw("'CPAR' as record_type"),

                // CPAR information
                'a.concern_description',
                'a.complainant_name',

                // Assignment
                'b.id as assignment_id',
                'b.cpar_id as record_id',
                DB::raw('NULL as result_id'),
                'b.assigned_to',
                'b.status_id',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',

                // Department
                'd.department_name',

                // Priority / Status
                'p.priority_name',
                's.status_name',

                // Assigned employee
                DB::raw("
                CONCAT(
                    e.first_name,
                    ' ',
                    e.last_name
                ) as assigned_names
            ")
            )
            ->where('b.status_id', 10)
            ->where('b.assigned_to', (int) $this->id)
            ->where('b.record_type', 5);

        $result = DB::table('result_error_forms as a')
            ->join(
                'cpar_assignments as b',
                'a.id',
                '=',
                'b.result_id'
            )
            ->join(
                'employees as e',
                'b.assigned_to',
                '=',
                'e.id'
            )
            ->join(
                'priority_levels as p',
                'a.priority_level',
                '=',
                'p.id'
            )
            ->join(
                'cpar_statuses as s',
                'b.status_id',
                '=',
                's.id'
            )
            ->select(
                'a.id',
                // Common fields
                'a.result_no as record_no',
                'a.reported_by',
                'a.date_reported as record_date',
                DB::raw("'RESULT' as record_type"),
                // Result Error information
                'a.patient_name as concern_description',
                DB::raw('NULL as complainant_name'),
                // Assignment
                'b.id as assignment_id',
                DB::raw('NULL as record_id'),
                'b.result_id',
                'b.assigned_to',
                'b.status_id',
                'b.remarks',
                'b.dept_head_assigned',
                DB::raw('NULL as department_id'),
                DB::raw('NULL as department_name'),
                // Priority / Status
                'p.priority_name',
                's.status_name',
                // Assigned employee
                DB::raw("
                CONCAT(
                    e.first_name,
                    ' ',
                    e.last_name
                ) as assigned_names
            ")
            )
            ->where('b.status_id', 10)
            ->where('b.assigned_to', (int) $this->id)
            ->where('b.record_type', 10);

        $this->assigned_requests = $cpar
            ->unionAll($result)
            ->orderByDesc('record_date')
            ->get();
    }

    public function viewDetails($id)
    {
        $this->dispatch('view-CPAR', id: $id);
    }

    public function respondCpar($id)
    {
        $this->dispatch('respond-CPAR', id: $id);
    }

    public function respondResult($id)
    {
        $this->dispatch('respond-Result', id: $id);
    }

    public function render()
    {
        return view('livewire.user.modal.cpar_assigned');
    }
}
