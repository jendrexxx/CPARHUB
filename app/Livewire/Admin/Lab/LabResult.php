<?php

namespace App\Livewire\Admin\Lab;

use Livewire\Component;
use App\Models\result_error_data_informations;
use App\Models\result_error_quality_accuracies;
use App\Models\result_error_technical_equipments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\employee;
use Carbon\Carbon;
use App\Models\cpar_employee_disciplinary_records;
use App\Models\cpar_assignments;

class LabResult extends Component
{
    public $data = [], $quality = [], $technical = [];
    public $user_id = '', $existing_ir_attachment = '';
    public bool $hasNTERequest = false;
    public $result_no = '', $reported_by = '', $patient_name = '', $attending_physician = '', $actual_released_date = '', $source_name = '', $complain_name = '', $concern_description = '', $department_name = '', $priority = '', $remarks = '', $status = '', $dept_head_assigned = '', $date_reported = '', $test_procedure = '', $complainant_name = '';
    public $id = '', $employee_no = '', $branch_id = '', $department_id = '', $assignment_remarks = '', $assignment_id = '', $result_id = '';
    public $data_information = [], $technical_information = [], $quality_information = [];
    public $employee_assigned_to = '', $employee_name = '';
    public $date_completed = '', $ir_id = '', $tat = '', $employeeName = '', $assigned_employee_no = '';
    public $identified_cause = '', $provided_solution = '', $recommendation = '', $ir_attachment = '';
    public $action_taken_by = '', $dept_head_remarks = '', $ir_request_id = '', $assigned_to = '';
    public $nte_id = '', $current_ir_attachment = '', $current_nte_attachment = '', $response_attachment = '';
    public $decisionCategories = [], $disciplinaryCategories = [], $offenseLevels = [];
    public $selectedCategories = [null], $selectedOffenseLevels = [''], $selectedHRDecisions = [''];
    public $isNoDisciplinaryAction = false;
    public $nte_no = '', $hr_decision_remarks = '', $management_remarks ='';

    protected $listeners = [
        'open-result-details' => 'open',
    ];

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
        $this->decisionCategories = DB::table('cpar_decision_categories')
            ->orderBy('id', 'asc')
            ->get();
        $this->disciplinaryCategories = DB::table('cpar_disciplinary_categories')
            ->orderBy('id', 'desc')
            ->get();
        $this->offenseLevels = DB::table('cpar_offense_levels')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function open($id = '')
    {
        $result = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('cpar_statuses as e', 'd.status_id', '=', 'e.id')
            ->leftJoin('employees as f', 'd.dept_head_assigned', '=', 'f.id')
            ->join('priority_levels as g', 'a.priority_level', '=', 'g.id')
            ->leftjoin('employees as h', 'd.assigned_to', '=', 'h.id')
            ->leftjoin('cpar_investigations as i', 'd.id', '=', 'i.assigned_id')
            ->leftJoin('cpar_employee_disciplinary_records as k', 'd.id', '=', 'k.assignment_id')
            ->leftJoin('cpar_ir_requests as j', 'd.id', '=', 'j.assignment_ir_id')
            ->leftJoin('cpar_notice_to_explains as l', 'j.id', '=', 'l.assignment_id')
            ->leftJoin('cpar_nte_responses as n', 'l.id', '=', 'n.nte_id')
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
                'd.result_id',
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
                // IR
                'j.id as ir_ids',
                'j.ir_id',
                'j.ir_attachment',
                'j.id as ir_request_id',
                // HR Decision
                'k.id as disciplinary_id',
                'k.discipline_ids',
                'k.offense_ids',
                'k.decision_ids',
                'k.status as decision_status',
                'k.remarks as decision_remarks',
                'k.management_remarks',
                // NTE
                'l.id as nte_id',
                'l.nte_no',
                'l.nte_attachment',
                'n.response_attachment'
            )
            ->where('d.id', $id)
            ->first();
        $this->assigned_employee_no = $result->employee_no;
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
        $this->nte_id = $result->nte_id;
        // IR Response 
        $this->existing_ir_attachment = $result->ir_attachment ?? '';
        // NTE Response
        $this->response_attachment = $result->response_attachment ?? '';
        $nte_request = DB::table('cpar_notice_to_explains')->where('assignment_id', $this->assignment_id)->first();
        $this->hasNTERequest = !is_null($nte_request);
        $this->id = $result->id;
        $this->ir_id = $result->ir_id;
        $this->nte_no = $result->nte_no;
        $this->employee_assigned_to = $result->employee_assigned_to;
        $this->result_id   = $result->result_id;
        $this->result_no = $result->result_no;
        $this->date_reported = Carbon::parse($result->date_reported)->format('m-d-Y');
        $this->reported_by = $result->reported_by;
        $this->test_procedure = $result->test_procedure;
        $this->patient_name = $result->patient_name;
        $this->attending_physician = $result->attending_physician;
        $this->actual_released_date = $result->actual_released_date;
        $this->source_name = $result->source_name;
        $this->quality_information = is_array($result->quality_information)
            ? $result->quality_information
            : json_decode($result->quality_information ?? '[]', true);
        $this->data_information = is_array($result->data_information)
            ? $result->data_information
            : json_decode($result->data_information ?? '[]', true);
        $this->technical_information = is_array($result->technical_information)
            ? $result->technical_information
            : json_decode($result->technical_information ?? '[]', true);
        $this->complain_name = $result->complain_name;
        $this->concern_description = $result->concern_description;
        $this->department_name = $result->department_name;
        $this->priority = $result->priority_name;
        $this->complainant_name = $result->complainant_name;
        $this->assigned_to = $result->assigned_to;
        $this->assignment_remarks = $result->remarks;
        $this->status = $result->status_name;
        $this->dept_head_assigned = $result->dept_head_assigned;
        $this->identified_cause = $result->identified_cause;
        $this->provided_solution = $result->provided_solution;
        $this->recommendation = $result->recommendation;
        $this->ir_id = $result->ir_id;
        $this->dept_head_remarks = $result->dept_head_remarks;
        $this->date_completed = $result->date_completed;
        $this->tat = $result->tat;
        $this->ir_request_id = $result->ir_request_id;
        $this->management_remarks = $result->management_remarks;
        // Basic information
        $this->selectedCategories = json_decode(
            $result->discipline_ids ?? '[]',
            true
        ) ?: [''];
        $this->selectedOffenseLevels = json_decode(
            $result->offense_ids ?? '[]',
            true
        ) ?: [''];
        $this->selectedHRDecisions = json_decode(
            $result->decision_ids ?? '[]',
            true
        ) ?: [''];
        $this->hr_decision_remarks = $result->decision_remarks;
        $this->modal('lab-result')->show();
    }

    public function backToHR()
    {
        if (!$this->id) {
            $this->dispatch(
                'toast',
                type: 'error',
                message: 'Unable to return CPAR. Assignment ID is missing.'
            );
            return;
        }

        DB::transaction(function () {
            DB::table('cpar_employee_disciplinary_records')
                ->where('assignment_id', $this->id)
                ->update([
                    'management_remarks'  => $this->management_remarks,
                    'updated_at' => now(),
                ]);
        });

        DB::transaction(function () {
            // OLD VALUES
            $oldRecord = DB::table('cpar_employee_disciplinary_records')
                ->where('assignment_id', $this->id)
                ->first();

            $oldAssignment = DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->first();

            DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->update([
                    'status_id'  => 30,
                    'updated_at' => now(),
                ]);

            // AUDIT LOG
            DB::table('audit_logs')->insert([
                'user_reported_by'       => $this->employeeName,
                'user_reported'     => $this->assigned_to,
                'action' => 'BACK TO HR RESULT',
                'old_value' => json_encode([
                    'management_remarks' => $oldRecord?->management_remarks,
                    'status_id'           => $oldAssignment?->status_id,
                ], JSON_UNESCAPED_UNICODE),
                'new_value' => json_encode([
                    'management_remarks' => $this->management_remarks,
                    'status_id'           => 30,
                ], JSON_UNESCAPED_UNICODE),
                'date'       => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
        $this->dispatch('toast',type: 'success',message: 'RESULT has been returned to HR.');
        // Close modal
        $this->dispatch('modal-close', name: 'LABModal');
        $this->dispatch('modal-close', name: 'LABrequest');
        // Refresh laboratory records/count
        $this->dispatch('refreshLABRecords');
        $this->dispatch('refreshLABCount');
    }

    public function verifiedLaboratory()
    {
        if (!$this->id) {
            return;
        }
        $selectedDecision = collect($this->selectedHRDecisions)
            ->filter(fn($value) => $value !== null && $value !== '')
            ->first();

        DB::transaction(function () use ($selectedDecision) {
            $oldAssignment = DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->first();
            $oldRecord = DB::table('cpar_employee_disciplinary_records')
                ->where('assignment_id', $this->id)
                ->first();
            $statusId = (int) $selectedDecision === 1 ? 55 : 40;
            $verificationStatus = (int) $selectedDecision === 1
                ? 'VERIFIED - NO DISCIPLINARY ACTION'
                : 'VERIFIED - WITH DISCIPLINARY ACTION';

            $oldValue = [
                'result_no'           => $this->result_no,
                'status_id'          => $oldAssignment?->status_id,
                'management_remarks' => $oldRecord?->management_remarks,
            ];

            DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->update([
                    'status_id'  => $statusId,
                    'updated_at' => now(),
                ]);

            DB::table('cpar_employee_disciplinary_records')
                ->where('assignment_id', $this->id)
                ->update([
                    'management_remarks' => $this->management_remarks,
                    'updated_at'         => now(),
                ]);

            $newValue = [
                'result_no'           => $this->result_no,
                'assigned_id'        => $this->id,
                'status_id'          => $statusId,
                'management_remarks' => $this->management_remarks,
                'decision'           => $verificationStatus,
            ];

            DB::table('audit_logs')->insert([
                'user_reported_by'       => $this->employeeName,
                'user_reported'     => $this->assigned_to,
                'action'     => 'RESULT LAB VERIFIED',
                'old_value'  => json_encode($oldValue),
                'new_value'  => json_encode($newValue),
                'status_changed_by' => $this->user_id,
                'date'       => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        $this->dispatch('toast',type: 'success',message: 'RESULT successfully verified by laboratory.');
        $this->dispatch('modal-close',name: 'LABModal');
        $this->dispatch('modal-close',name: 'LABrequest');
        $this->dispatch('refreshLABRecords');
        $this->dispatch('refreshLABCount');
        $this->dispatch('refreshNotificationCount');
    }

    public function render()
    {
        return view('livewire.admin.lab.lab_result');
    }
}
