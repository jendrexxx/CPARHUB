<?php

namespace App\Livewire\Admin\Cpar;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\employee;
use Illuminate\Support\Facades\Auth;

class CparSubmissionModal extends Component
{
    public $cpar_id = '';
    public $remarks = '';
    public $employees = [];
    public $employee_no = '';
    public $branch_id = '';
    public $department_id = '';
    public $id = '';
    public $assigned = null;
    public $assigned_to = [];
    public $new_assignees = [];
    public $assigned_head = '';
    public $dept_head_assigned = '';
    public $cpar_no = '', $reported_by = '', $date_open = '', $department_name = '', $source_name = '', $complain_name = '', $concern_name = '', $status_name = '';
    public $identified_cause = '';
    public $provided_solution = '';
    public $recommendation = '';
    public $action_taken_by = '';
    public $date_completed = '';
    public $tat = '', $emp_reported = '', $employeeName = '', $user_id = '';
    public $attachment = '', $priority = '';
    public $ir_attachment = '', $complainant_name = '', $concern_description = '';
    protected $listeners = [
        'open-submission-cpar' => 'open_submission'
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->user_id = $info->id;
        }
    }

    public function open_submission($id = null)
    {
        $cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->join('cpar_investigations as j', 'b.id', 'j.assigned_id')
            ->join('cpar_source_origins as k', 'a.source_id', 'k.id')
            ->join('cpar_complain_categories as l', 'a.complaint_category_id', 'l.id')
            ->join('cpar_concern_categories as m', 'a.concern_category_id', 'm.id')
            ->join('cpar_ir_requests as n', 'b.id', '=', 'n.assignment_ir_id')
            ->join('priority_levels as o', 'a.priority_level', '=', 'o.id')
            ->select(
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'a.employee_no as emp_reported',
                'a.complainant_name',
                'a.concern_description',
                'i.department_name',
                'b.id',
                'b.cpar_id',
                'b.assigned_to',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',
                'c.file_path',
                'h.status_name',
                'i.branch_id',
                'i.department_name',
                'i.first_name',
                'i.last_name',
                'j.identified_cause',
                'j.provided_solution',
                'j.recommendation',
                'j.action_taken_by',
                'j.date_completed',
                'j.tat',
                'k.source_name',
                'l.complain_name',
                'm.concern_name',
                'n.ir_attachment',
                'o.priority_name'
            )
            ->where('b.id', $id)
            ->first();
        if (!$cpar) {
            return;
        }
        $this->emp_reported = $cpar->emp_reported;
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
        $this->id = $cpar->id;
        $this->complainant_name = $cpar->complainant_name;
        $this->priority = $cpar->priority_name;
        $this->concern_description = $cpar->concern_description;
        $this->cpar_id = $cpar->cpar_id;
        $this->cpar_no = $cpar->cpar_no;
        $this->reported_by = $cpar->reported_by;
        $this->date_open = $cpar->date_open;
        $this->department_name = $cpar->department_name;
        $this->source_name = $cpar->source_name;
        $this->complain_name = $cpar->complain_name;
        $this->concern_name = $cpar->concern_name;
        $this->status_name = $cpar->status_name;
        $this->identified_cause = $cpar->identified_cause ?? null;
        $this->provided_solution = $cpar->provided_solution ?? null;
        $this->recommendation = $cpar->recommendation ?? null;
        $this->action_taken_by = $cpar->action_taken_by ?? null;
        $this->date_completed = $cpar->date_completed ?? null;
        $this->tat = $cpar->tat ?? null;
        $this->attachment = $cpar->file_path ?? null;
        $this->ir_attachment = $cpar->ir_attachment ?? null;
        $this->assigned_to = $cpar->assigned_to;
        $this->modal('submission-cpar')->show();
    }

    public function submitCPAR()
    {
        $this->validate([
            'remarks' => 'required|string',
        ], [
            'remarks.required' => 'Remarks is required.',
        ]);

        DB::transaction(function () {

            $oldAssignment = DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->first();

            $oldInvestigation = DB::table('cpar_investigations')
                ->where('assigned_id', $this->id)
                ->first();

            // OLD VALUES - only fields that will be changed
            $oldValue = [
                'cpar_no'           => $this->cpar_no,
                'remarks'   => $oldInvestigation?->remarks,
                'status_id' => $oldAssignment?->status_id,
            ];

            // UPDATE INVESTIGATION
            DB::table('cpar_investigations')
                ->where('assigned_id', $this->id)
                ->update([
                    'remarks'    => $this->remarks,
                    'updated_at' => now(),
                ]);

            // UPDATE ASSIGNMENT
            DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->update([
                    'status_id'  => 20,
                    'updated_at' => now(),
                ]);

            // NEW VALUES - only fields that were changed
            $newValue = [
                'cpar_no'   => $this->cpar_no,
                'remarks'   => $this->remarks,
                'status_id' => 20,
            ];

            DB::table('audit_logs')->insert([
                'user_reported_by'  => $this->employeeName,
                'user_reported'     => $this->assigned_to,
                'action'     => 'ACKNOWLEDGED',
                'old_value'  => json_encode($oldValue),
                'new_value'  => json_encode($newValue),
                'status_changed_by' => $this->user_id,
                'date'       => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'CPAR Acknowledge successfully submitted.'
        );

        $this->dispatch('modal-close', name: 'submission-cpar');
        $this->dispatch('modal-close', name: 'CPARSubmissionModal');
        $this->dispatch('refreshSubmission');
        $this->dispatch('refreshSubmissionCount');

        $this->reset([
            'remarks',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.cpar.cpar_submission_modal');
    }
}
