<?php

namespace App\Livewire\Admin\HrResult;

use Livewire\Component;
use App\Models\employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\result_error_data_informations;
use App\Models\result_error_quality_accuracies;
use App\Models\result_error_technical_equipments;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class ResultIrModal extends Component
{
    use WithFileUploads;

    public $resultResponse = [];
    public $result_no = '', $reported_by = '', $patient_name = '', $attending_physician = '', $actual_released_date = '', $source_name = '', $complain_name = '', $concern_description = '', $department_name = '', $priority = '', $remarks = '', $status = '', $dept_head_assigned = '', $date_reported = '', $test_procedure = '', $complainant_name = '';
    public $id = '', $employee_no = '', $branch_id = '', $department_id = '', $assignment_remarks = '', $assignment_id = '', $result_id = '';
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
    public bool $hasNTERequest = false;
    public $nte_attachment = '', $nte_no = '', $ir_request_id = '';

    protected $listeners = [
        'nte-result' => 'open'
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
        $year = now()->year;
        $lastNte = DB::table('cpar_notice_to_explains')
            ->whereYear('created_at', $year)
            ->orderByDesc('id')
            ->value('nte_no');

        if (empty($lastNte)) {
            $nextNumber = 1;
        } else {
            $lastNumber = (int) substr($lastNte, -5);
            $nextNumber = $lastNumber + 1;
        }
        $this->nte_no = 'NTE-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
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
                'j.ir_id',
                'j.ir_attachment',
                'j.id as ir_request_id'
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
        $nte_request = DB::table('cpar_notice_to_explains')->where('assignment_id', $this->assignment_id)->first();
        $this->hasNTERequest = !is_null($nte_request);
        $this->existing_ir_attachment = $result_acknowledge->ir_attachment;
        $this->id = $result_acknowledge->id;
        $this->employee_assigned_to = $result_acknowledge->employee_assigned_to;
        $this->result_id   = $result_acknowledge->result_id;
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
        $this->ir_request_id = $result_acknowledge->ir_request_id;
        $this->modal('ir-result')->show();
    }

    public function sendNTE()
    {
        $this->validate([
            'id' => 'required|exists:cpar_assignments,id',
            'nte_no'        => 'required|string',
            'nte_attachment' => 'required|file|mimes:pdf|max:10240',
        ]);

        DB::transaction(function () {
            DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->update([
                    'status_id'  => 23,
                    'updated_at' => now(),
                ]);

            $nteAttachmentPath = null;
            if ($this->nte_attachment) {
                $nteAttachmentPath = $this->nte_attachment->store(
                    'result/nte',
                    'public'
                );
            }

            DB::table('cpar_notice_to_explains')->insert([
                'assignment_id' => $this->ir_request_id,
                'nte_no'        => $this->nte_no,
                'nte_attachment' => $nteAttachmentPath,
                'status'        => 'RESULT NTE',
                'issued_at'     => $this->date_reported,
                'due_date'      => Carbon::parse($this->date_reported)->addDays(5),
                'created_by'    => auth()->id(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $oldAssignment = DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->first();

            DB::table('audit_logs')->insert([
                'user_reported_by'       => $this->employeeName,
                'user_reported'     => $this->assigned_to,
                'action'     => 'NTE REQUEST SENT',
                'old_value'  => json_encode([
                    'result_no'       => $this->result_no,
                    'assignment_id' => $this->id,
                    'status_id'     => 20,
                ]),
                'new_value'  => json_encode([
                    'result_no'           => $this->result_no,
                    'assignment_id' => $this->id,
                    'status_id'     => 23,
                ]),
                'status_changed_by' => $this->user_id,
                'date'       => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        $this->reset('nte_attachment');
        $this->dispatch('modal-close', name: 'acknowledge-cpar');
        $this->dispatch('modal-close', name: 'CPARAcknowledgeModal');
        $this->dispatch('refreshAcknowledgeRecords');
        $this->dispatch('refreshAcknowledgeCount');
        $this->dispatch('refreshDecisionRecords');
        $this->dispatch('refreshDecisionCount');
        $this->dispatch('refreshPreviousOffense');
        $this->dispatch('toast', type: 'success', message: 'Notice to Explain sent successfully..');
    }

    public function render()
    {
        return view('livewire.admin.hr_result.result_ir_modal');
    }
}
