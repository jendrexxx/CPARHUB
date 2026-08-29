<?php

namespace App\Livewire\User\Modal;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use App\Models\result_error_data_informations;
use App\Models\result_error_quality_accuracies;
use App\Models\result_error_technical_equipments;

class ResultRespondForm extends Component
{
    public $resultResponse = [];
    public $result_no = '', $reported_by = '', $patient_name = '', $attending_physician = '', $actual_released_date = '', $source_name = '', $complain_name = '', $concern_description = '', $department_name = '', $priority = '', $remarks = '', $status = '', $dept_head_assigned = '', $date_reported = '', $test_procedure = '', $complainant_name = '';
    public $id = '', $employee_no = '', $branch_id = '', $department_id = '', $assignment_remarks = '', $assignment_id = '', $cpar_id = '';
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
        $this->employees = employee::where('branch_id', $this->branch_id)
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

    public function open_modal($id = null)
    {
        $result_assigned = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.cpar_id')
            ->join('cpar_statuses as h', 'd.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'd.dept_head_assigned', '=', 'i.id')
            ->join('priority_levels as m', 'a.priority_level', '=', 'm.id')
            ->select(
                'a.result_no',
                'a.reported_by',
                'a.employee_no',
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
                'd.cpar_id',
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
            ->where('d.id', $id)
            ->first();
        if (!$result_assigned) {
            return;
        }
        $this->assignment_id = $result_assigned->assignment_id;
        $this->cpar_id   = $result_assigned->cpar_id;
        $this->result_no = $result_assigned->result_no;
        $this->date_reported = $result_assigned->date_reported;
        $this->reported_by = $result_assigned->reported_by;
        $this->test_procedure = $result_assigned->test_procedure;
        $this->patient_name = $result_assigned->patient_name;
        $this->attending_physician = $result_assigned->attending_physician;
        $this->actual_released_date = $result_assigned->actual_released_date;
        $this->source_name = $result_assigned->source_name;
        $this->data_information = is_array($result_assigned->data_information)
            ? $result_assigned->data_information
            : json_decode($result_assigned->data_information ?? '[]', true);
        $this->technical_information = is_array($result_assigned->technical_information)
            ? $result_assigned->technical_information
            : json_decode($result_assigned->technical_information ?? '[]', true);
        $this->quality_information = is_array($result_assigned->quality_information)
            ? $result_assigned->quality_information
            : json_decode($result_assigned->quality_information ?? '[]', true);
        $this->complain_name = $result_assigned->complain_name;
        $this->concern_description = $result_assigned->concern_description;
        $this->department_name = $result_assigned->department_name;
        $this->priority = $result_assigned->priority_name;
        $this->complainant_name = $result_assigned->complainant_name;
        // Assignment information
        $this->assigned_to = $result_assigned->assigned_to;
        $this->assignment_remarks = $result_assigned->remarks;
        $this->status = $result_assigned->status_name;
        $this->dept_head_assigned = $result_assigned->dept_head_assigned;
        $this->modal('hr-reassign-result')->show();
    }

    public function render()
    {
        return view('livewire.user.modal.result_respond_form');
    }
}
