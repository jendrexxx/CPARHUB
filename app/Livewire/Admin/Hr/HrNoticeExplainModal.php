<?php

namespace App\Livewire\Admin\Hr;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\employee;
use Illuminate\Support\Facades\Auth;

class HrNoticeExplainModal extends Component
{
    use WithFileUploads;

    public $assignment_id = '';
    public $employee_name = '';
    public $cpar_no = '';
    public $nte_no = '';
    public $attachment = '';
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
    public $ir_attachment = '';
    public $nte_attachment = '';
    public $ir_request_id = '', $emp_reported = '', $employeeName = '', $user_id = '';
    public bool $hasNTERequest = false;
    public $date_open = '', $reported_by = '', $department_name = '', $status_name = '', $source_name = '', $complain_name = '', $concern_name = '', $concern_description = '', $complainant_name = '';


    protected $listeners = [
        'nte-cpar' => 'open_nte'
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->user_id = $info->id;
        }

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

    public function open_nte($id = null)
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
            ->leftJoin('cpar_ir_requests as k', 'b.id', '=', 'k.assignment_ir_id')
            ->where('k.id', $id)
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
                DB::raw("CONCAT(i.first_name, ' ', i.last_name) as employee_name"),
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
                'k.id as ir_request_id'
            )->first();
        if (!$request) {
            return;
        }
        $this->emp_reported = $request->emp_reported;
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
        $this->assignment_id = $request->id;
        $this->ir_request_id = $request->ir_request_id;
        $this->employee_name = $request->employee_name;
        $this->cpar_no = $request->cpar_no;
        $this->employee_no = $request->employee_no;
        $this->status_id = $request->status_id;
        $this->dept_head_assigned = $request->dept_head_assigned;
        $this->department_id = $request->department_id;
        $this->cpar_id = $request->cpar_id;
        $this->assigned_to = $request->assigned_to;
        $this->ir_attachment = $request->ir_attachment;
        $nte_request = DB::table('cpar_notice_to_explains')->where('assignment_id', $this->assignment_id)->first();
        $this->hasNTERequest = !is_null($nte_request);
        // cpar_investigations
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
        $this->modal('notice-to-explain')->show();
    }

    public function sendNoticeToExplain()
    {
        $this->validate([
            'assignment_id' => 'required|exists:cpar_assignments,id',
            'nte_no'        => 'required|string',
            'nte_attachment' => 'required|file|mimes:pdf|max:10240',
        ]);

        DB::transaction(function () {
            DB::table('cpar_assignments')
                ->where('id', $this->assignment_id)
                ->update([
                    'status_id'  => 23,
                    'updated_at' => now(),
                ]);
            // Insert assignment and get its ID
            $nteAttachmentPath = null;
            if ($this->nte_attachment) {
                $nteAttachmentPath = $this->nte_attachment->store(
                    'cpar/nte',
                    'public'
                );
            }
            // Create NTE using the newly created assignment ID
            DB::table('cpar_notice_to_explains')->insert([
                'assignment_id' => $this->ir_request_id,
                'nte_no'        => $this->nte_no,
                'nte_attachment' => $nteAttachmentPath,
                'status'        => 'FOR EXPLANATION',
                'issued_at'     => $this->date_open,
                'due_date'      => Carbon::parse($this->date_open)->addDays(5),
                'created_by'    => auth()->id(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $oldAssignment = DB::table('cpar_assignments')
                ->where('id', $this->assignment_id)
                ->first();

            DB::table('audit_logs')->insert([
                'user_reported_by'       => $this->employeeName,
                'user_reported'     => $this->assigned_to,
                'action'     => 'NTE REQUEST SENT',
                'old_value'  => json_encode([
                    'cpar_no'       => $this->cpar_no,
                    'assignment_id' => $this->assignment_id,
                    'status_id'     => 20,
                ]),
                'new_value'  => json_encode([
                    'cpar_no'           => $this->cpar_no,
                    'assignment_id' => $this->assignment_id,
                    'status_id'     => 23,
                ]),
                'status_changed_by' => $this->user_id,
                'date'       => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        // Reset attachment
        $this->reset('attachment');
        // ✅ CLOSE FLUX MODAL
        $this->dispatch('modal-close', name: 'acknowledge-cpar');
        $this->dispatch('modal-close', name: 'CPARAcknowledgeModal');
        $this->dispatch('refreshAcknowledgeRecords');
        $this->dispatch('refreshAcknowledgeCount');
        $this->dispatch('refreshDecisionRecords');
        $this->dispatch('refreshDecisionCount');
        $this->dispatch('refreshPreviousOffense');
        $this->dispatch('refreshNotificationCount');
        $this->dispatch('toast', type: 'success', message: 'Notice to Explain sent successfully..');
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_notice_explain_modal');
    }
}
