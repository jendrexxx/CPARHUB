<?php

namespace App\Livewire\Admin\Hr;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

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
    public bool $hasNTERequest = false;

    protected $listeners = [
        'nte-cpar' => 'open_nte'
    ];

    public function mount()
    {
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
        $request = DB::table('cpar_assignments as b')
            ->join('cpar_request_forms as a', 'a.id', '=', 'b.cpar_id')
            ->join('employees as e', 'e.id', '=', 'b.assigned_to')
            ->join('cpar_investigations as j', 'b.id', '=', 'j.assigned_id')
            ->where('b.id', $id)
            ->select(
                'a.cpar_no',
                'b.status_id',
                'b.cpar_id',
                'b.dept_head_assigned',
                'b.id',
                'b.assigned_to',
                'b.department_id',
                'j.assigned_id',
                'j.identified_cause',
                'j.provided_solution',
                'j.recommendation',
                'j.action_taken_by',
                'j.date_completed',
                'j.tat',
                'j.remarks',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as employee_name"),
                'e.employee_no'
            )
            ->first();
        if (!$request) {
            return;
        }

        $this->assignment_id = $request->id;
        $this->employee_name = $request->employee_name;
        $this->cpar_no = $request->cpar_no;
        $this->employee_no = $request->employee_no;
        $this->status_id = $request->status_id;
        $this->dept_head_assigned = $request->dept_head_assigned;
        $this->department_id = $request->department_id;
        $this->cpar_id = $request->cpar_id;
        $this->assigned_to = $request->assigned_to;

        $nte_request = DB::table('cpar_notice_to_explains')
            ->where('assignment_id', $this->assignment_id)
            ->first();
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
        $this->modal('notice-to-explain')->show();
    }

    public function sendNoticeToExplain()
    {
        $this->validate([
            'assignment_id' => 'required|exists:cpar_assignments,id',
            'nte_no'        => 'required|string'
        ]);
        if ($this->status_id == 25) {
            DB::transaction(function () {
                // Create NTE
                DB::table('cpar_notice_to_explains')->insert([
                    'assignment_id' => $this->assignment_id,
                    'nte_no'        => $this->nte_no,
                    'status'        => 'FOR EXPLANATION',
                    'issued_at'     => now(),
                    'due_date'      => now()->addDay(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
                // Update CPAR Assignment
                DB::table('cpar_assignments')
                    ->where('id', $this->assignment_id)
                    ->update([
                        'status_id'  => 30,
                        'updated_at' => now(),
                    ]);
            });
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
                    'remarks'            => 'Notice to Explain Issued',
                    'created_by'         => auth()->id(),
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);

                // Create NTE using the newly created assignment ID
                DB::table('cpar_notice_to_explains')->insert([
                    'assignment_id' => $assignmentId,
                    'nte_no'        => $this->nte_no,
                    'status'        => 'FOR EXPLANATION',
                    'issued_at'     => now(),
                    'due_date'      => now()->addDay(),
                    'created_by'    => auth()->id(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
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
        // Reset attachment
        $this->reset('attachment');
        // ✅ CLOSE FLUX MODAL
        $this->dispatch('modal-close', name: 'acknowledge-cpar');
        $this->dispatch('modal-close', name: 'CPARAcknowledgeModal');
        $this->dispatch('refreshAcknowledgeRecords');
        $this->dispatch('refreshAcknowledgeCount');
        session()->flash(
            'success',
            'Notice to Explain sent successfully.'
        );
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_notice_explain_modal');
    }
}
