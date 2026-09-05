<?php

namespace App\Livewire\Admin\Result;

use App\Models\employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\result_error_data_informations;
use App\Models\result_error_quality_accuracies;
use App\Models\result_error_technical_equipments;
use Illuminate\Support\Facades\Auth;

class ResultConcernModal extends Component
{
    protected $listeners = [
        'open-acknowledgement-result' => 'open'
    ];

    public $resultResponse = [];
    public $result_no = '', $reported_by = '', $patient_name = '', $attending_physician = '', $actual_released_date = '', $source_name = '', $complain_name = '', $concern_description = '', $department_name = '', $priority = '', $remarks = '', $status = '', $dept_head_assigned = '', $date_reported = '', $test_procedure = '', $complainant_name = '';
    public $id = '', $employee_no = '', $branch_id = '', $department_id = '', $assignment_remarks = '', $assignment_id = '', $cpar_id = '';
    public $employees = [];
    public $assigned_to = [];
    public $new_assignees = [];
    public $data = [];
    public $quality = [];
    public $technical = [];
    public $data_information = [], $technical_information = [], $quality_information = [];
    public $existing_ir_attachment = '', $employee_assigned_to = '', $employee_name = '';
    public $date_completed = '', $ir_id = '', $tat = '', $employeeName = '', $assigned_employee_no = '';
    public $identified_cause = '', $provided_solution = '', $recommendation = '', $ir_attachment = '';
    public $action_taken_by = '', $dept_head_remarks = '', $user_id = '';

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->user_id = $info->id;
        }
        $this->data = result_error_data_informations::all();
        $this->quality = result_error_quality_accuracies::all();
        $this->technical = result_error_technical_equipments::all();
    }

    public function open($id = null)
    {
        $result_acknowledge = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('cpar_statuses as e', 'd.status_id', '=', 'e.id')
            ->leftJoin('employees as f', 'd.dept_head_assigned', '=', 'f.id')
            ->join('priority_levels as g', 'a.priority_level', '=', 'g.id')
            ->leftjoin('employees as h', 'd.assigned_to', '=', 'h.id')
            ->leftjoin('cpar_investigations as i', 'd.id', '=', 'i.assigned_id')
            ->leftJoin('cpar_ir_requests as j', 'd.id', '=', 'j.assignment_ir_id')
            ->select(
                'a.result_no',
                'a.reported_by',
                'a.employee_no',
                'a.date_reported',
                'a.test_procedure',
                'a.quality_information',
                'a.actual_released_date',
                'a.data_information',
                'a.technical_information',
                'a.quality_information',
                'a.patient_name',
                'a.complain_name as complainant_name',
                'a.attending_physician',
                'a.technical_information',
                'a.concern_description',
                'b.source_name',
                'c.complain_name',
                'd.id',
                'd.cpar_id',
                'd.assigned_to',
                'd.remarks',
                'd.dept_head_assigned',
                'd.department_id',
                'e.status_name',
                'f.branch_id',
                'f.department_name',
                'f.first_name',
                'f.last_name',
                'g.priority_name',
                DB::raw("CONCAT(h.first_name, ' ', h.last_name) AS employee_assigned_to"),
                'h.employee_no as employee_no_assigned',
                'i.identified_cause',
                'i.provided_solution',
                'i.recommendation',
                'i.date_completed',
                'i.tat',
                'i.remarks as dept_head_remarks',
                'j.ir_id',
                'j.ir_attachment'
            )
            ->where('d.id', $id)
            ->first();
        if (!$result_acknowledge) {
            return;
        }
        $this->assigned_employee_no = $result_acknowledge->employee_no;
        $cpar_info = DB::table('employees as a')
            ->join('cpar_request_forms as b', 'a.employee_no', '=', 'b.employee_no')
            ->where('b.employee_no', $this->assigned_employee_no)
            ->select(
                'a.employee_no',
                'a.first_name',
                'a.last_name'
            )
            ->first();

        if ($cpar_info) {
            $this->employeeName = trim($cpar_info->first_name . ' ' . $cpar_info->last_name);
        }
        $this->existing_ir_attachment = $result_acknowledge->ir_attachment;
        $this->id = $result_acknowledge->id;
        $this->employee_assigned_to = $result_acknowledge->employee_assigned_to;
        $this->cpar_id   = $result_acknowledge->cpar_id;
        $this->result_no = $result_acknowledge->result_no;
        $this->date_reported = Carbon::parse($result_acknowledge->date_reported)->format('m-d-Y');
        $this->reported_by = $result_acknowledge->reported_by;
        $this->test_procedure = $result_acknowledge->test_procedure;
        $this->patient_name = $result_acknowledge->patient_name;
        $this->attending_physician = $result_acknowledge->attending_physician;
        $this->actual_released_date = $result_acknowledge->actual_released_date;
        $this->source_name = $result_acknowledge->source_name;
        $this->quality_information = is_array($result_acknowledge->quality_information)
            ? $result_acknowledge->quality_information
            : json_decode($result_acknowledge->quality_information ?? '[]', true);
        $this->data_information = is_array($result_acknowledge->data_information)
            ? $result_acknowledge->data_information
            : json_decode($result_acknowledge->data_information ?? '[]', true);
        $this->technical_information = is_array($result_acknowledge->technical_information)
            ? $result_acknowledge->technical_information
            : json_decode($result_acknowledge->technical_information ?? '[]', true);
        $this->complain_name = $result_acknowledge->complain_name;
        $this->concern_description = $result_acknowledge->concern_description;
        $this->department_name = $result_acknowledge->department_name;
        $this->priority = $result_acknowledge->priority_name;
        $this->complainant_name = $result_acknowledge->complainant_name;
        $this->assigned_to = $result_acknowledge->assigned_to;
        $this->assignment_remarks = $result_acknowledge->remarks;
        $this->status = $result_acknowledge->status_name;
        $this->dept_head_assigned = $result_acknowledge->dept_head_assigned;
        $this->identified_cause = $result_acknowledge->identified_cause;
        $this->provided_solution = $result_acknowledge->provided_solution;
        $this->recommendation = $result_acknowledge->recommendation;
        $this->ir_id = $result_acknowledge->ir_id;
        $this->dept_head_remarks = $result_acknowledge->dept_head_remarks;
        $this->date_completed = $result_acknowledge->date_completed;
        $this->tat = $result_acknowledge->tat;
        $this->modal('acknowledge-result')->show();
    }

    public function submitRESULT()
    {
        $this->validate([
            'dept_head_remarks' => 'required|string',
        ], [
            'dept_head_remarks.required' => 'Remarks is required.',
        ]);

        DB::transaction(function () {

            // Get current assignment
            $oldAssignment = DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->first();

            // Get current investigation
            $oldInvestigation = DB::table('cpar_investigations')
                ->where('assigned_id', $this->id)
                ->first();

            // Make sure records exist
            if (!$oldAssignment) {
                throw new \Exception('Assignment record not found.');
            }

            if (!$oldInvestigation) {
                throw new \Exception('Investigation record not found.');
            }

            /*
        |--------------------------------------------------------------------------
        | OLD VALUES
        |--------------------------------------------------------------------------
        */

            $oldValue = [
                'result_no' => $this->result_no,
                'remarks'   => $oldInvestigation->remarks,
                'status_id' => $oldAssignment->status_id,
            ];

            /*
        |--------------------------------------------------------------------------
        | UPDATE INVESTIGATION
        |--------------------------------------------------------------------------
        */

            DB::table('cpar_investigations')
                ->where('assigned_id', $this->id)
                ->update([
                    'remarks'    => $this->dept_head_remarks,
                    'updated_at' => now(),
                ]);

            /*
        |--------------------------------------------------------------------------
        | UPDATE ASSIGNMENT STATUS
        |--------------------------------------------------------------------------
        */

            DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->update([
                    'status_id'  => 20,
                    'updated_at' => now(),
                ]);

            /*
        |--------------------------------------------------------------------------
        | NEW VALUES
        |--------------------------------------------------------------------------
        */

            $newValue = [
                'result_no' => $this->result_no,
                'remarks'   => $this->dept_head_remarks,
                'status_id' => 20,
            ];

            /*
        |--------------------------------------------------------------------------
        | AUDIT LOG
        |--------------------------------------------------------------------------
        */

            DB::table('audit_logs')->insert([
                'user_reported_by'  => $this->employeeName,

                // If assigned_to is an array, convert it to JSON
                'user_reported'     => is_array($this->assigned_to)
                    ? json_encode($this->assigned_to)
                    : $this->assigned_to,

                'action'            => 'RESULT ACKNOWLEDGED',

                'old_value'         => json_encode($oldValue),
                'new_value'         => json_encode($newValue),

                'status_changed_by' => $this->user_id,
                'date'              => now(),

                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        });

        /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'RESULT acknowledged successfully submitted.'
        );

        $this->dispatch(
            'modal-close',
            name: 'acknowledge-result'
        );

        $this->dispatch(
            'modal-close',
            name: 'ResultSubmissionModal'
        );

        $this->dispatch('refreshAcknowledgeData');
        $this->dispatch('refreshAcknowledgeCount');

        $this->reset([
            'dept_head_remarks',
        ]);
    }


    public function render()
    {
        return view('livewire.admin.result.result_concern_modal');
    }
}
