<?php

namespace App\Livewire\User\Modal;

use App\Models\cpar_investigations;
use App\Models\cpar_request_forms;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\Carbon;

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

    protected $listeners = [
        'respond-CPAR' => 'respondCPAR',
    ];

    public function respondCPAR($id = '')
    {
        $cpar = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->leftJoin('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->leftJoin('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->leftJoin('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->leftJoin('cpar_statuses as h', 'a.status_id', '=', 'h.id')
            ->select(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'g.department_name',
                'h.status_name',
                'd.source_name',
                'e.complain_name',
                'f.concern_name'
            )
            ->where('a.id', $id)
            ->first();
        // assign data sa modal fields
        $this->cpar_no = $cpar->cpar_no;
        $this->date_open = $cpar->date_open;
        $this->selectedCparId = $cpar->id;

        $this->date_opened = Carbon::parse($cpar->date_open)
            ->format('m-d-Y h:i:s A'); // display only

        if (!$cpar) {
            return;
        }

        $this->modal('respond-cpar')->show();
    }

    public function saveResponse()
    {
        $this->validate([
            'identified_cause' => 'required',
            'provided_solution' => 'required',
            'recommendation' => 'required',
            'action_taken_by' => 'required',
            'date_completed' => 'required|date',
            'tat'            => 'required',
        ]);

        cpar_investigations::create([
            'cpar_id'            => $this->selectedCparId,
            'identified_cause'   => $this->identified_cause,
            'provided_solution'  => $this->provided_solution,
            'recommendation'     => $this->recommendation,
            'action_taken_by'    => $this->action_taken_by,
            'date_completed'     => $this->date_completed,
            'tat'                => $this->tat,
        ]);

        // Update CPAR status after response
        cpar_request_forms::where('id', $this->selectedCparId)
            ->update([
                'status_id' => 15
            ]);

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'CPAR response submitted successfully.'
        );


        $this->reset([
            'identified_cause',
            'provided_solution',
            'recommendation',
            'action_taken_by',
            'date_completed',
            'tat',
            'remarks'
        ]);


        $this->modal('respond-cpar')->close();
    }

    public function updatedDateCompleted($value)
    {
        if (!$this->date_open || !$value) {
            $this->tat = '';
            return;
        }

        $days = Carbon::parse($this->date_open)
            ->startOfDay()
            ->diffInDays(Carbon::parse($value)->startOfDay());

        $this->tat = "{$days} day(s)";
    }

    public function render()
    {
        return view('livewire.user.modal.cpar_respond_form');
    }
}
