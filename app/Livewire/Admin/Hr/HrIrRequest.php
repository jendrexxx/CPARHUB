<?php

namespace App\Livewire\Admin\Hr;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HrIrRequest extends Component
{
    public $employee_name = '';
    public $employee_no = '';
    public $cpar_no = '';
    public $assignment_id = '';
    public $ir_id = '';
    public bool $hasIrRequest = false;
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
    public $attachment = '';
    public $date_open = '', $reported_by = '', $department_name = '', $status_name = '', $source_name = '', $complain_name = '', $concern_name = '', $concern_description = '', $complainant_name = '';

    protected $listeners = [
        'ir-request-cpar' => 'open_ir'
    ];

    public function mount()
    {
        $year = now()->year;
        $lastNte = DB::table('cpar_ir_requests')
            ->whereYear('created_at', $year)
            ->orderByDesc('id')
            ->value('ir_id');

        if (empty($lastNte)) {
            $nextNumber = 1;
        } else {
            $lastNumber = (int) substr($lastNte, -5);
            $nextNumber = $lastNumber + 1;
        }
        $this->ir_id = 'IR-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function open_ir($id = null)
    {
        $request = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->leftJoin('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->leftJoin('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->leftJoin('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', 'i.id')
            ->leftJoin('cpar_investigations as j', 'b.id', '=', 'j.assigned_id')
            ->where('b.id', $id)
            ->select(
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'a.concern_description',
                'a.complainant_name',
                'b.id as assignment_id',
                'b.cpar_id',
                'b.assigned_to',
                'b.remarks',
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
                DB::raw("CONCAT(i.first_name, ' ', i.last_name) as employee_name"),
                'i.employee_no',
                'j.assigned_id',
                'j.identified_cause',
                'j.provided_solution',
                'j.recommendation',
                'j.action_taken_by',
                'j.date_completed',
                'j.tat'
            )->first();
        if (!$request) {
            return;
        }
        $ir_request = DB::table('cpar_ir_requests')
            ->where('assignment_ir_id', $id)
            ->first();
        $this->hasIrRequest = !is_null($ir_request);
        $this->assignment_id = $request->assignment_id;
        $this->employee_name = $request->employee_name;
        $this->cpar_no = $request->cpar_no;
        $this->employee_no = $request->employee_no;
        $this->status_id = $request->status_id;
        $this->dept_head_assigned = $request->dept_head_assigned;
        $this->department_id = $request->department_id;
        $this->cpar_id = $request->cpar_id;
        $this->assigned_to = $request->assigned_to;
        $this->assigned_id = $request->assigned_id;
        $this->identified_cause = $request->identified_cause;
        $this->provided_solution = $request->provided_solution;
        $this->recommendation = $request->recommendation;
        $this->action_taken_by = $request->action_taken_by;
        $this->date_completed = $request->date_completed;
        $this->tat = $request->tat;
        $this->remarks = $request->remarks;
        $this->date_open = $request->date_open;
        $this->department_name = $request->department_name;
        $this->status_name = $request->status_name;
        $this->source_name = $request->source_name;
        $this->complain_name = $request->complain_name;
        $this->concern_name = $request->concern_name;
        $this->concern_description = $request->concern_description;
        $this->complainant_name = $request->complainant_name;
        $this->attachment = $request->file_path;
        $this->reported_by = $request->reported_by;
        $this->modal('incident-report-request')->show();
    }

    public function sendIncidentReportRequest()
    {
        $this->validate([
            'employee_no' => 'required',
        ]);

        if ($this->status_id == 25) {
            // Save IR request
            DB::table('cpar_ir_requests')->insert([
                'assignment_ir_id' => $this->assignment_id,
                'ir_id'        => $this->ir_id,
                'employee_no'  => $this->employee_no,
                'ir_attachment' => null,
                'submitted_at' => null,
                'issued_at'     => now(),
                'due_date'      => now()->addDay(),
                'status'       => 'IR REQUESTED',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
            // Update CPAR Assignment
            DB::table('cpar_assignments')
                ->where('id', $this->assignment_id)
                ->update([
                    'status_id'  => 30,
                    'updated_at' => now(),
                ]);

        } else {
            DB::transaction(function () {
                // Insert assignment and get its ID
                $assignmentId = DB::table('cpar_assignments')->insertGetId([
                    'cpar_id'            => $this->cpar_id,
                    'employee_no'        => $this->employee_no,
                    'dept_head_assigned' => $this->dept_head_assigned,
                    'department_id'      => $this->department_id,
                    'assigned_to'        => $this->assigned_to,
                    'assigned_date'      => now(),
                    'status_id'          => 30,
                    'remarks'            => 'Notice to Explain IR Requested',
                    'created_by'         => auth()->id(),
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);

                DB::table('cpar_ir_requests')->insert([
                    'assignment_ir_id' => $assignmentId,
                    'ir_id'        => $this->ir_id,
                    'employee_no'  => $this->employee_no,
                    'ir_attachment' => null,
                    'submitted_at' => null,
                    'issued_at'     => now(),
                    'due_date'      => now()->addDay(),
                    'status'       => 'IR REQUESTED',
                    'submitted_at' => auth()->id(),
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                // Create CPAR Investigation using the newly created assignment ID
                DB::table('cpar_investigations')->insert([
                    'assigned_id'      => $assignmentId,
                    'identified_cause' => $this->identified_cause,
                    'provided_solution' => $this->provided_solution,
                    'recommendation'   => $this->recommendation,
                    'action_taken_by'  => $this->action_taken_by,
                    'date_completed'   => $this->date_completed,
                    'tat'              => $this->tat,
                    'remarks'          => $this->remarks,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            });
        }

        $this->dispatch('modal-close', name: 'acknowledge-cpar');
        $this->dispatch('modal-close', name: 'CPARAcknowledgeModal');
        $this->dispatch('modal-close', name: 'incident-report-request');
        $this->dispatch('refreshAcknowledgeRecords');
        $this->dispatch('refreshAcknowledgeCount');
        // Toast
        $this->dispatch(
            'toast',
            type: 'success',
            message: 'Incident Report request successfully created.'
        );
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_ir_request');
    }
}
