<?php

namespace App\Livewire\User\Modal;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use App\Models\result_error_data_informations;
use App\Models\result_error_quality_accuracies;
use App\Models\result_error_technical_equipments;

class ResultForm extends Component
{
    protected $listeners = [
        'view-RESULT' => 'viewResult',
    ];
    protected $casts = [
        'assigned_to' => 'array',
    ];

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

    public function mount()
    {
        $user = Auth::user();
        $info = Employee::where('email', $user->email)->first();

        if ($info) {
            $this->id          = $info->id;
            $this->employee_no = $info->employee_no;
            $this->branch_id   = $info->branch_id;
            $this->department_id = $info->department_id;
        }

        $this->assigned_to = [];
        $this->employees = Employee::where('branch_id', $this->branch_id)
            ->where('department_id', $this->department_id)
            ->orderBy('first_name')
            ->get()
            ->map(function ($employee) {
                $employee->full_name = $employee->first_name . ' ' . $employee->last_name;
                return $employee;
            });
        $this->data = result_error_data_informations::all();
        $this->quality = result_error_quality_accuracies::all();
        $this->technical = result_error_technical_equipments::all();
    }

    public function viewResult($id = null)
    {
        $result = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('cpar_statuses as h', 'd.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'd.dept_head_assigned', '=', 'i.id')
            ->join('priority_levels as m', 'a.priority_level', '=', 'm.id')
            ->select(
                'a.result_no',
                'a.reported_by',
                'a.employee_no as emp_reported',
                'a.date_reported',
                'a.test_procedure',
                'a.quality_information',
                'a.actual_released_date',
                'a.data_information',
                'a.patient_name',
                'a.complain_name as complainant_name',
                'a.attending_physician',
                'a.technical_information',
                'a.concern_description',
                'b.source_name',
                'c.complain_name',
                'd.id as assignment_id',
                'd.result_id',
                'd.assigned_to',
                'd.remarks',
                'd.dept_head_assigned',
                'd.department_id',
                'h.status_name',
                'i.branch_id',
                'i.department_name',
                'i.first_name',
                'i.last_name',
                'm.priority_name'
            )
            ->where('d.result_id', $id)
            ->first();
        if (!$result) {
            return;
        }
        $this->emp_reported = $result->emp_reported;
        $cpar_info = DB::table('employees as a')
            ->join('cpar_request_forms as b', 'a.employee_no', '=', 'b.employee_no')
            ->where('b.employee_no', $this->emp_reported)
            ->select(
                'a.employee_no',
                'a.first_name',
                'a.last_name'
            )
            ->first();
        if ($cpar_info) {
            $this->employeeName = trim($cpar_info->first_name . ' ' . $cpar_info->last_name);
        }
        $this->assignment_id = $result->assignment_id;
        $this->result_no = $result->result_no;
        $this->date_reported = $result->date_reported;
        $this->reported_by = $result->reported_by;
        $this->test_procedure = $result->test_procedure;
        $this->patient_name = $result->patient_name;
        $this->attending_physician = $result->attending_physician;
        $this->actual_released_date = $result->actual_released_date;
        $this->source_name = $result->source_name;
        $this->data_information = is_array($result->data_information)
            ? $result->data_information
            : json_decode($result->data_information ?? '[]', true);
        $this->technical_information = is_array($result->technical_information)
            ? $result->technical_information
            : json_decode($result->technical_information ?? '[]', true);
        $this->quality_information = is_array($result->quality_information)
            ? $result->quality_information
            : json_decode($result->quality_information ?? '[]', true);
        $this->complain_name = $result->complain_name;
        $this->concern_description = $result->concern_description;
        $this->department_name = $result->department_name;
        $this->priority = $result->priority_name;
        $this->complainant_name = $result->complainant_name;
        // Assignment information
        $this->assigned_to = $result->assigned_to;
        $this->assignment_remarks = $result->remarks;
        $this->status = $result->status_name;
        $this->dept_head_assigned = $result->dept_head_assigned;
        $this->result_id = $result->result_id;
        $this->modal('result-form')->show();
    }

    public function confirmCancelResult($assignment_id)
    {
        $this->dispatch('view-result-Cancel', id: $assignment_id);
    }

    public function render()
    {
        return view('livewire.user.modal.result_form');
    }
}
