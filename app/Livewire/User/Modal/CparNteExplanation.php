<?php

namespace App\Livewire\User\Modal;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Livewire\WithFileUploads;

class CparNteExplanation extends Component
{
    use WithFileUploads;

    public $showViewNte = false;
    public $ir_attachment = '';
    public $nte_attachment = '';
    public bool $hasNTERequest = false;
    public $date_open = '', $reported_by = '', $department_name = '', $status_name = '', $source_name = '', $complain_name = '', $concern_name = '', $concern_description = '', $complainant_name = '';
    public $nte_id = '';
    public $employee_no = '';
    public $status_id = '';
    public $dept_head_assigned = '';
    public $cpar_id = '';
    public $department_id = '';
    public $assigned_to = '';
    public $assigned_id = '';
    public $identified_cause = '';
    public $provided_solution = '';
    public $recommendation = '';
    public $action_taken_by = '';
    public $date_completed = '';
    public $tat = '';
    public $remarks = '';
    public $nte_no = '';
    public $viewEmployeeName = '';
    public $assignment_id = '';
    public $employee_name = '', $ir_id = '';
    public $cpar_no = '', $id = '', $priority = '', $emp_reported = '', $employeeName = '', $user_id = '';
    public $response_attachment = null;

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
    }

    public function viewNte($id)
    {
        $nte = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->leftJoin('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->leftJoin('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->leftJoin('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->leftJoin('cpar_investigations as j', 'b.id', '=', 'j.assigned_id')
            ->leftJoin('cpar_ir_requests as k', 'b.id', '=', 'k.assignment_ir_id')
            ->leftJoin('cpar_notice_to_explains as l', 'k.id', '=', 'l.assignment_id')
            ->join('priority_levels as o', 'a.priority_level', '=', 'o.id')
            ->where('b.id', $id)
            ->select(
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'a.concern_description',
                'a.complainant_name',
                'a.employee_no as emp_reported',
                'b.id',
                'b.cpar_id',
                'b.assigned_to',
                'b.status_id',
                'b.dept_head_assigned',
                'b.department_id',
                'd.source_name',
                'e.complain_name',
                'f.concern_name',
                'g.department_name',
                'c.file_path',
                'h.status_name',
                'i.branch_id',
                'i.first_name',
                'i.last_name',
                DB::raw("CONCAT(i.first_name,' ',i.last_name) as employee_name"),
                'i.employee_no',
                'j.assigned_id',
                'j.identified_cause',
                'j.provided_solution',
                'j.recommendation',
                'j.action_taken_by',
                'j.date_completed',
                'j.tat',
                'j.remarks',
                'k.ir_attachment',
                'k.ir_id',
                'l.nte_attachment',
                'l.id as nte_id',
                'l.issued_at',
                'l.due_date',
                'l.nte_no',
                'o.priority_name'
            )
            ->first();
        if (!$nte) {
            return;
        }
        $this->emp_reported = $nte->emp_reported;
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
        $this->nte_no = $nte->nte_no;
        $this->ir_id = $nte->ir_id;
        $this->nte_id = $nte->nte_id;
        $this->viewEmployeeName = trim(
            $nte->first_name . ' ' .
                ($nte->middle_name ?? '') . ' ' .
                $nte->last_name
        );
        $this->nte_attachment = $nte->nte_attachment;
        $this->assignment_id = $nte->id;
        $this->employee_name = $nte->employee_name;
        $this->priority = $nte->priority_name;
        $this->cpar_no = $nte->cpar_no;
        $this->employee_no = $nte->employee_no;
        $this->status_id = $nte->status_id;
        $this->dept_head_assigned = $nte->dept_head_assigned;
        $this->department_id = $nte->department_id;
        $this->cpar_id = $nte->cpar_id;
        $this->assigned_to = $nte->assigned_to;
        $this->ir_attachment = $nte->ir_attachment;
        $nte_request = DB::table('cpar_notice_to_explains')->where('assignment_id', $this->assignment_id)->first();
        $this->hasNTERequest = !is_null($nte_request);
        $this->assigned_id = $nte->assigned_id;
        $this->identified_cause = $nte->identified_cause;
        $this->provided_solution = $nte->provided_solution;
        $this->recommendation = $nte->recommendation;
        $this->action_taken_by = $nte->action_taken_by;
        $this->date_completed = $nte->date_completed;
        $this->tat = $nte->tat;
        $this->remarks = $nte->remarks;
        $this->date_open = $nte->date_open;
        $this->department_name = $nte->department_name;
        $this->status_name = $nte->status_name;
        $this->source_name = $nte->source_name;
        $this->complain_name = $nte->complain_name;
        $this->concern_name = $nte->concern_name;
        $this->concern_description = $nte->concern_description;
        $this->complainant_name = $nte->complainant_name;
        $this->reported_by = $nte->reported_by;
        $this->modal('view-notice-to-explain')->show();
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
                ->where('id', $this->assignment_id)
                ->first();

            $path = $this->response_attachment->store(
                'cpar/response',
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
                ->where('id', $this->assignment_id)
                ->update([
                    'status_id' => 25,
                    'updated_at' => $submittedAt,
                ]);

            $oldValue = [
                'cpar_no'    => $this->cpar_no,
                'nte_status' => $oldNte?->status,
                'status_id' => $oldAssignment?->status_id,
                'response_attachment' => null,
                'submitted_at' => null,
            ];

            $newValue = [
                'cpar_no'    => $this->cpar_no,
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
        return view('livewire.user.modal.cpar_nte_explanation');
    }
}
