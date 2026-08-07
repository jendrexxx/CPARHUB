<?php

namespace App\Livewire\User\Modal;

use App\Models\cpar_assignments;
use App\Models\cpar_investigations;
use App\Models\cpar_request_forms;
use App\Models\employee;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CparRespondForm extends Component
{
    public $cpar_no = '', $date_opened = '', $date_open = '';
    public $identified_cause = '';
    public $provided_solution = '';
    public $recommendation = '';
    public $action_taken_by = '';
    public $date_completed = '';
    public $tat = '';
    public $remarks = '';
    public $selectedCparId = '';
    public $id = '';
    public $status = '';
    public $employee_no = '';

    protected $listeners = [
        'respond-CPAR' => 'respondCPAR',

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

    public function respondCPAR($id = '')
    {
        $cpar = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->leftJoin('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->leftJoin('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->leftJoin('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->select(
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'b.id',
                'g.department_name',
                'h.status_name',
                'd.source_name',
                'e.complain_name',
                'f.concern_name'
            )
            ->where('b.id', $id)
            ->first();
        // assign data sa modal fields
        $this->id = $cpar->id;
        $this->cpar_no = $cpar->cpar_no;
        $this->date_open = $cpar->date_open;
        $this->selectedCparId = $cpar->id;
        $this->date_completed = now()->format('m-d-Y');
        $this->date_opened = Carbon::parse($cpar->date_open)->format('m-d-Y');
        $this->calculateTat();

        if (!$cpar) {
            return;
        }

        $this->modal('respond-cpar')->show();
    }

    public function calculateTat()
    {
        if (!$this->date_opened || !$this->date_completed) {
            $this->tat = '';
            return;
        }

        $days = Carbon::createFromFormat('m-d-Y', $this->date_opened)
            ->startOfDay()
            ->diffInDays(
                Carbon::createFromFormat('m-d-Y', $this->date_completed)
                    ->startOfDay()
            );

        $this->tat = "{$days} day(s)";
    }

    public function saveResponse()
    {
        $this->validate([
            'identified_cause' => 'required',
            'provided_solution' => 'required',
            'recommendation' => 'required',
            'action_taken_by' => 'required',
            'date_completed' => 'required',
            'tat'            => 'required',
            'status'         => 'required',
        ]);

        cpar_investigations::create([
            'assigned_id'        => $this->id,
            'identified_cause'   => $this->identified_cause,
            'provided_solution'  => $this->provided_solution,
            'recommendation'     => $this->recommendation,
            'action_taken_by'    => $this->action_taken_by,
            'date_completed'     => $this->date_completed,
            'tat'                => $this->tat,
        ]);

        // Update CPAR status after response
        cpar_assignments::where('id', $this->id)
            ->update([
                'employee_no' => $this->employee_no,
                'status_id' => $this->status
            ]);

        $this->reset([
            'identified_cause',
            'provided_solution',
            'recommendation',
            'action_taken_by',
            'date_completed',
            'tat',
            'remarks'
        ]);

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'CPAR response submitted successfully.'
        );

        // ✅ CLOSE FLUX MODAL
        $this->dispatch('modal-close', name: 'respond-cpar');
        $this->dispatch('modal-close', name: 'CPARAssignedModal');
        $this->dispatch('refreshAssignedData');
        $this->dispatch('refreshAssignedCount');
    }

    public function render()
    {
        return view('livewire.user.modal.cpar_respond_form');
    }
}
