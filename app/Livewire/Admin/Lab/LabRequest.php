<?php

namespace App\Livewire\Admin\Lab;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\employee;
use Illuminate\Support\Facades\Auth;

class LabRequest extends Component
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
    public $tat = '';
    public $selectedCategories = [null];
    public $selectedOffenseLevels = [''];
    public $selectedHRDecisions = [''];
    public $hr_decision_remarks = '';
    public $nte_id = '';
    public $ir_attachment = '';
    public $attachment = '';
    public $ir_id = '';
    public $nte_attachment = null;
    public $current_nte_attachment;
    public $nte_response = '';
    public $hr_decision = '', $emp_reported = '', $employeeName = '', $user_id = '';
    public $employee_name = '', $assigned_department_head = '';
    public $decisionCategories = [];
    public $disciplinaryCategories = [];
    public $offenseLevels = [];
    public $concern_description = '', $complainant_name = '', $head_remarks = '', $management_remarks = '';

    protected $listeners = [
        'open-lab-details' => 'view',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->user_id = $info->id;
        }

        $this->decisionCategories = DB::table('cpar_decision_categories')
            ->orderBy('decision_name', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $this->disciplinaryCategories = DB::table('cpar_disciplinary_categories')
            ->orderBy('category_name', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $this->offenseLevels = DB::table('cpar_offense_levels')
            ->orderBy('id')
            ->get();
    }

    public function view($assignment_id = null)
    {
        if (!$assignment_id) {
            return;
        }

        $cpar_request = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->leftJoin('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->leftJoin('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->leftJoin('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', 'i.id')
            ->leftJoin('cpar_investigations as j', 'b.id', '=', 'j.assigned_id')
            ->leftJoin('cpar_employee_disciplinary_records as k', 'b.id', '=', 'k.assignment_id')
            ->leftJoin('cpar_ir_requests as m', 'b.id', '=', 'm.assignment_ir_id')
            ->leftJoin('cpar_notice_to_explains as l', 'm.id', '=', 'l.assignment_id')
            ->leftJoin('cpar_nte_responses as r', 'l.id', '=', 'r.nte_id')
            ->select(
                // CPAR
                'a.id as cpar_id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'a.concern_description',
                'a.complainant_name',
                'a.employee_no as emp_reported',
                // Assignment
                'b.id as assignment_id',
                'b.assigned_to',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',
                'c.file_path',
                'd.source_name',
                'e.complain_name',
                'f.concern_name',
                // Employee
                'i.employee_no',
                'i.first_name',
                'i.last_name',
                'i.department_name as assigned_department_head',
                // Department
                'g.department_name',
                // Status
                'h.status_name',
                // Investigation
                'j.identified_cause',
                'j.provided_solution',
                'j.recommendation',
                'j.action_taken_by',
                'j.date_completed',
                'j.tat',
                'j.remarks as head_remarks',
                // HR Decision
                'k.id as disciplinary_id',
                'k.discipline_ids',
                'k.offense_ids',
                'k.decision_ids',
                'k.status as decision_status',
                'k.remarks as decision_remarks',
                'k.management_remarks',
                // NTE
                'l.nte_no',
                'l.nte_attachment',
                // IR
                'm.id as ir_ids',
                'm.ir_id',
                'm.ir_attachment',
                'r.nte_id',
                'r.response_attachment'
            )
            ->where(
                'b.id',
                $assignment_id
            )
            ->first();

        if (!$cpar_request) {
            session()->flash('error', 'CPAR record not found.');
            return;
        }
        $this->emp_reported = $cpar_request->emp_reported;
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
        $this->assigned_to = $cpar_request->assigned_to;
        $this->id = $cpar_request->assignment_id;
        $this->cpar_id = $cpar_request->cpar_id;
        $this->cpar_no = $cpar_request->cpar_no;
        $this->employee_name = trim($cpar_request->first_name . ' ' . $cpar_request->last_name);
        $this->assigned_department_head = $cpar_request->assigned_department_head;
        $this->department_name = $cpar_request->department_name;
        $this->date_open = $cpar_request->date_open;
        $this->status_name = $cpar_request->status_name;
        $this->nte_id = $cpar_request->nte_id;
        $this->nte_attachment = $cpar_request->nte_attachment ?? '';
        $this->ir_id = $cpar_request->ir_id;
        $this->ir_attachment = $cpar_request->ir_attachment ?? '';
        $this->identified_cause = $cpar_request->identified_cause ?? '';
        $this->provided_solution = $cpar_request->provided_solution ?? '';
        $this->recommendation = $cpar_request->recommendation ?? '';
        $this->attachment = $cpar_request->file_path;
        $this->source_name = $cpar_request->source_name;
        $this->complain_name = $cpar_request->complain_name;
        $this->concern_name = $cpar_request->concern_name;
        $this->concern_description = $cpar_request->concern_description;
        $this->complainant_name = $cpar_request->complainant_name;
        $this->action_taken_by = $cpar_request->action_taken_by;
        $this->date_completed = $cpar_request->date_completed;
        $this->tat = $cpar_request->tat;
        $this->head_remarks = $cpar_request->head_remarks;
        $this->management_remarks = $cpar_request->management_remarks;
        $this->selectedCategories = json_decode($cpar_request->discipline_ids ?? '[]', true) ?: [''];
        $this->selectedOffenseLevels = json_decode($cpar_request->offense_ids ?? '[]', true) ?: [''];
        $this->selectedHRDecisions = json_decode($cpar_request->decision_ids ?? '[]', true) ?: [''];
        $this->hr_decision_remarks = $cpar_request->decision_remarks ?? '';
        $this->hr_decision = '';
        $this->reported_by = $cpar_request->reported_by;
        $this->modal('LABrequest')->show();
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
                'cpar_no'           => $this->cpar_no,
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
                'cpar_no'           => $this->cpar_no,
                'assigned_id'        => $this->id,
                'status_id'          => $statusId,
                'management_remarks' => $this->management_remarks,
                'decision'           => $verificationStatus,
            ];

            DB::table('audit_logs')->insert([
                'user_reported_by'       => $this->employeeName,
                'user_reported'     => $this->assigned_to,
                'action'     => 'LAB VERIFIED',
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
            message: 'CPAR successfully verified by laboratory.'
        );

        $this->dispatch(
            'modal-close',
            name: 'LABModal'
        );

        $this->dispatch(
            'modal-close',
            name: 'LABrequest'
        );

        $this->dispatch('refreshLABRecords');
        $this->dispatch('refreshLABCount');
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
                'action' => 'BACK TO HR',
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

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'CPAR has been returned to HR.'
        );

        // Close modal
        $this->dispatch('modal-close', name: 'LABModal');
        $this->dispatch('modal-close', name: 'LABrequest');

        // Refresh laboratory records/count
        $this->dispatch('refreshLABRecords');
        $this->dispatch('refreshLABCount');
    }

    public function render()
    {
        return view('livewire.admin.lab.lab_request');
    }
}
