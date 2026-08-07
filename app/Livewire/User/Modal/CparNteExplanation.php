<?php

namespace App\Livewire\User\Modal;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Livewire\WithFileUploads;

class CparNteExplanation extends Component
{
    public $showViewNte = false;
    public $viewNteNo = '';
    public $viewEmployeeName = '';
    public $viewCparNo = '';
    public $viewDepartment = '';
    public $viewIssuedAt = '';
    public $viewDueDate = '';
    public $viewContent = '';
    public $viewStatus = '';
    public $nteResponse = '';
    public $viewAttachment = '';
    public $id = '';
    public $employee_no = '';
    public $nte_id = '';
    public $response_attachment = '';
    public $viewID = '';
    public $viewAssignements = '';
    protected $listeners = [
        'view-NTE' => 'viewNTE',
        'refreshCparNTEData' => 'loadNte'
    ];

    use WithFileUploads;

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
    }

    public function viewNte($id)
    {
        $nte = DB::table('cpar_notice_to_explains as nte')
            ->join(
                'cpar_assignments as ca',
                'ca.id',
                '=',
                'nte.assignment_id'
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
            ->where('nte.id', $id)
            ->select(
                'nte.id',
                'nte.assignment_id',
                'nte.nte_no',
                'nte.status',
                'nte.nte_attachment',
                'nte.issued_at',
                'nte.due_date',
                'crf.cpar_no',
                'e.id as employee_id',
                'e.first_name',
                'e.middle_name',
                'e.last_name',
                'd.department_name'
            )
            ->first();
        if (!$nte) {
            return;
        }

        $this->viewNteNo = $nte->nte_no;

        $this->viewEmployeeName = trim(
            $nte->first_name . ' ' .
                ($nte->middle_name ?? '') . ' ' .
                $nte->last_name
        );
        $this->viewAssignements = $nte->assignment_id;
        $this->viewID = $nte->id;
        $this->viewCparNo = $nte->cpar_no;
        $this->viewDepartment = $nte->department_name ?? 'N/A';
        $this->viewIssuedAt = $nte->issued_at;
        $this->viewDueDate = $nte->due_date;
        $this->viewStatus = $nte->status;
        $this->viewAttachment = $nte->nte_attachment;
        $this->modal('view-notice-to-explain')->show();
    }

    public function submitNteResponse()
    {

        DB::transaction(function () {
            // Save employee response
            DB::table('cpar_nte_responses')->insert([
                'nte_id'              => $this->viewID,
                'employee_no'         => $this->employee_no,
                'response_attachment' => null,
                'submitted_at'        => now(),
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);

            // Update NTE status
            DB::table('cpar_notice_to_explains')
                ->where('id', $this->viewID)
                ->update([
                    'status'     => 'RESPONDED',
                    'updated_at' => now(),
                ]);

            // Update CPAR Assignment
            DB::table('cpar_assignments')
                ->where('id', $this->viewAssignements)
                ->update([
                    'status_id'  => 40,
                    'updated_at' => now(),
                ]);
        });

        // Clear uploaded file
        $this->reset('response_attachment');
        // ✅ CLOSE FLUX MODAL
        $this->dispatch('modal-close', name: 'view-notice-to-explain');
        $this->dispatch('modal-close', name: 'NoticeToExplainModal');
        $this->dispatch('refreshCparNTEData');
        $this->dispatch('refreshNTECount');

        session()->flash(
            'success',
            'NTE response submitted successfully.'
        );
    }

    public function render()
    {
        return view('livewire.user.modal.cpar_nte_explanation');
    }
}
