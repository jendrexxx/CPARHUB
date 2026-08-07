<?php

namespace App\Livewire\User\Modal;

use App\Models\employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CparIrExplanation extends Component
{
    public $showViewIr = false;
    public $viewIrNo = '';
    public $viewEmployeeName = '';
    public $viewCparNo = '';
    public $viewDepartment = '';
    public $viewIssuedAt = '';
    public $viewDueDate = '';
    public $viewContent = '';
    public $viewStatus = '';
    public $viewAttachment = '';
    public $id = '';
    public $employee_no = '';
    public $ir_id = '';
    public $viewID = '';
    public $viewAssignements = '';

    protected $listeners = [
        'open-ir-view' => 'viewIR',
        'refreshCparIRData' => 'loadIR'
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
    }

    public function viewIR($id = null)
    {
        $ir = DB::table('cpar_ir_requests as ir')
            ->join(
                'cpar_assignments as ca',
                'ca.id',
                '=',
                'ir.assignment_ir_id'
            )
            ->join(
                'cpar_request_forms as crf',
                'crf.id',
                '=',
                'ca.cpar_id'
            )
            ->leftJoin(
                'employees as e',
                'e.id',
                '=',
                'ca.assigned_to'
            )
            ->leftJoin(
                'departments as d',
                'd.id',
                '=',
                'e.department_id'
            )
            ->where('ir.id', $id)
            ->select(
                'ir.id',
                'ir.assignment_ir_id',
                'ir.ir_id',
                'ir.status',
                'ir.issued_at',
                'ir.due_date',
                'ir.ir_attachment',
                'crf.cpar_no',
                'e.id as employee_id',
                'e.first_name',
                'e.middle_name',
                'e.last_name',
                'd.department_name'
            )
            ->first();

        if (!$ir) {
            return;
        }

        $this->viewIrNo = $ir->ir_id;
        $this->viewEmployeeName = trim(
            $ir->first_name . ' ' .
                ($ir->middle_name ?? '') . ' ' .
                $ir->last_name
        );
        $this->viewAssignements = $ir->assignment_ir_id;
        $this->viewID = $ir->id;
        $this->viewCparNo = $ir->cpar_no;
        $this->viewDepartment = $ir->department_name ?? 'N/A';
        $this->viewIssuedAt = $ir->issued_at;
        $this->viewDueDate = $ir->due_date;
        $this->viewStatus = $ir->status;
        $this->viewAttachment = $ir->ir_attachment;
        $this->showViewIr = true;
    }

    public function submitIrResponse()
    {
        // Save employee response
        DB::table('cpar_ir_responses')->insert([
            'ir_id'              => $this->viewID,
            'employee_no'         => $this->employee_no,
            'response_attachment' => null,
            'submitted_at'        => now(),
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        DB::table('cpar_ir_requests')
            ->where('id', $this->viewID)
            ->update([
                'ir_attachment' => null,
                'status'        => 'IR SUBMITTED',
                'submitted_at'  => now(),
                'updated_at'    => now(),
            ]);

        // Update CPAR Assignment
        DB::table('cpar_assignments')
            ->where('id', $this->viewAssignements)
            ->update([
                'status_id'  => 35,
                'updated_at' => now(),
            ]);

        // ✅ CLOSE FLUX MODAL
        $this->dispatch('modal-close', name: 'view-incident-report-request');
        $this->dispatch('modal-close', name: 'IncidentReportModal');
        $this->dispatch('refreshCparIRData');
        $this->dispatch('refreshIRCount');

        session()->flash(
            'success',
            'Incident Report submitted successfully.'
        );
    }

    public function render()
    {
        return view('livewire.user.modal.cpar_ir_explanation');
    }
}
