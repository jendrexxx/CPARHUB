<?php

namespace App\Livewire\User\Modal;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\result_error_data_informations;
use App\Models\result_error_quality_accuracies;
use App\Models\result_error_technical_equipments;

class ResultNte extends Component
{
    use WithFileUploads;

    public $showViewNte = false;
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
    public $nte_id = '', $current_ir_attachment = '', $current_nte_attachment = '';
    public $decisionCategories = [], $disciplinaryCategories = [], $offenseLevels = [];
    public $selectedCategories = [null], $selectedOffenseLevels = [''], $selectedHRDecisions = [''];
    public $isNoDisciplinaryAction = false;
    public $nte_attachment = '', $response_attachment = '', $nte_no = '';

    protected $listeners = [
        'view-NTE' => 'viewNTE',
        'refreshCparNTEData' => 'loadNte'
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->user_id = $info->id;
            $this->employee_no = $info->employee_no;
        }
        $this->data = result_error_data_informations::all();
        $this->quality = result_error_quality_accuracies::all();
        $this->technical = result_error_technical_equipments::all();
    }

    public function viewNte($id)
    {
        $nte = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('cpar_statuses as e', 'd.status_id', '=', 'e.id')
            ->leftJoin('employees as f', 'd.dept_head_assigned', '=', 'f.id')
            ->join('priority_levels as g', 'a.priority_level', '=', 'g.id')
            ->leftjoin('employees as h', 'd.assigned_to', '=', 'h.id')
            ->leftjoin('cpar_investigations as i', 'd.id', '=', 'i.assigned_id')
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
                'i.action_taken_by',
                'i.remarks as dept_head_remarks',
                // IR
                'j.id as ir_ids',
                'j.ir_id',
                'j.ir_attachment',
                'j.id as ir_request_id',
                // NTE
                'l.id as nte_id',
                'l.nte_no',
                'l.nte_attachment',
                'n.response_attachment'
            )
            ->where('d.id', $id)
            ->first();
        if (!$nte) {
            return;
        }
        $this->assigned_employee_no = $nte->employee_no;
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
        $this->nte_id = $nte->nte_id;
        // IR Response 
        $this->existing_ir_attachment = $nte->ir_attachment ?? '';
        // NTE Response
        $this->current_nte_attachment = $cpar->response_attachment ?? '';

        $nte_request = DB::table('cpar_notice_to_explains')->where('assignment_id', $this->id)->first();
        $this->hasNTERequest = !is_null($nte_request);
        $this->id = $nte->id;
        $this->action_taken_by = $nte->action_taken_by;
        $this->employee_assigned_to = $nte->employee_assigned_to;
        $this->nte_id   = $nte->result_id;
        $this->result_no = $nte->result_no;
        $this->date_reported = Carbon::parse($nte->date_reported)->format('m-d-Y');
        $this->reported_by = $nte->reported_by;
        $this->test_procedure = $nte->test_procedure;
        $this->patient_name = $nte->patient_name;
        $this->attending_physician = $nte->attending_physician;
        $this->actual_released_date = $nte->actual_released_date;
        $this->source_name = $nte->source_name;
        $this->quality_information = is_array($nte->quality_information)
            ? $nte->quality_information
            : json_decode($nte->quality_information ?? '[]', true);
        $this->data_information = is_array($nte->data_information)
            ? $nte->data_information
            : json_decode($nte->data_information ?? '[]', true);
        $this->technical_information = is_array($nte->technical_information)
            ? $nte->technical_information
            : json_decode($nte->technical_information ?? '[]', true);
        $this->complain_name = $nte->complain_name;
        $this->concern_description = $nte->concern_description;
        $this->department_name = $nte->department_name;
        $this->priority = $nte->priority_name;
        $this->complainant_name = $nte->complainant_name;
        $this->assigned_to = $nte->assigned_to;
        $this->assignment_remarks = $nte->remarks;
        $this->status = $nte->status_name;
        $this->dept_head_assigned = $nte->dept_head_assigned;
        $this->identified_cause = $nte->identified_cause;
        $this->provided_solution = $nte->provided_solution;
        $this->recommendation = $nte->recommendation;
        $this->ir_id = $nte->ir_id;
        $this->dept_head_remarks = $nte->dept_head_remarks;
        $this->date_completed = $nte->date_completed;
        $this->tat = $nte->tat;
        $this->ir_request_id = $nte->ir_request_id;
        $this->nte_attachment = $nte->nte_attachment;
        $this->nte_no = $nte->nte_no;
        // Basic information
        $this->selectedCategories = json_decode(
            $cpar->discipline_ids ?? '[]',
            true
        ) ?: [''];

        $this->selectedOffenseLevels = json_decode(
            $cpar->offense_ids ?? '[]',
            true
        ) ?: [''];

        $this->selectedHRDecisions = json_decode(
            $cpar->decision_ids ?? '[]',
            true
        ) ?: [''];
        $this->modal('view-result-nte')->show();
    }

    public function submitNteResponse()
    {
        $this->validate([
            'response_attachment' => [
                'required',
                'file',
                'max:10240',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
            ],
        ], [
            'response_attachment.required' => 'Please attach your response document.',
            'response_attachment.file' => 'The attachment must be a valid file.',
            'response_attachment.max' => 'The attachment must not exceed 10MB.',
            'response_attachment.mimes' => 'Only PDF, DOC, DOCX, JPG, JPEG, and PNG files are allowed.',
        ]);

        DB::transaction(function () {
            $oldNte = DB::table('cpar_notice_to_explains')
                ->where('id', $this->nte_id)
                ->first();

            $oldAssignment = DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->first();

            $path = $this->response_attachment->store(
                'result/response',
                'public'
            );

            $submittedAt = now();

            DB::table('cpar_nte_responses')->insert([
                'nte_id' => $this->nte_id,
                'employee_no' => $this->employee_no,
                'response_attachment' => $path,
                'submitted_at' => $submittedAt,
                'created_at' => $submittedAt,
                'updated_at' => $submittedAt,
            ]);

            DB::table('cpar_notice_to_explains')
                ->where('id', $this->nte_id)
                ->update([
                    'status' => 'RESPONDED',
                    'updated_at' => $submittedAt,
                ]);

            DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->update([
                    'status_id' => 25,
                    'updated_at' => $submittedAt,
                ]);

            $oldValue = [
                'result_no'    => $this->result_no,
                'nte_status' => $oldNte?->status,
                'status_id' => $oldAssignment?->status_id,
                'response_attachment' => null,
                'submitted_at' => null,
            ];

            $newValue = [
                'result_no'    => $this->result_no,
                'nte_status' => 'RESPONDED',
                'status_id' => 25,
                'response_attachment' => $path,
                'submitted_at' => $submittedAt->format('Y-m-d H:i:s'),
            ];

            DB::table('audit_logs')->insert([
                'user_reported_by'       => $this->employeeName,
                'user_reported'     => $this->assigned_to,
                'action' => 'NTE RESPONSE SUBMITTED',
                'old_value' => json_encode(
                    $oldValue,
                    JSON_UNESCAPED_UNICODE
                ),
                'new_value' => json_encode(
                    $newValue,
                    JSON_UNESCAPED_UNICODE
                ),
                'status_changed_by' => $this->user_id,
                'date' => $submittedAt,
                'created_at' => $submittedAt,
                'updated_at' => $submittedAt,
            ]);
        });

        $this->reset([
            'response_attachment',
        ]);

        $this->dispatch('modal-close', name: 'view-notice-to-explain');
        $this->dispatch('modal-close', name: 'NoticeToExplainModal');
        $this->dispatch('refreshCparNTEData');
        $this->dispatch('refreshNTECount');
        $this->dispatch('toast', type: 'success', message: 'NTE response submitted successfully.');
    }

    public function render()
    {
        return view('livewire.user.modal.result-nte');
    }
}
