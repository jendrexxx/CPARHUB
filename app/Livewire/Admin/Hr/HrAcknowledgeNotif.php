<?php

namespace App\Livewire\Admin\Hr;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HrAcknowledgeNotif extends Component
{
    public $cpar_acknowledge = '';

    protected $listeners = [
        'refreshAcknowledgeRecords' => 'AcknowledgeloadRecords',
    ];

    public function mount()
    {
        $this->AcknowledgeloadRecords();
    }

    public function AcknowledgeloadRecords()
    {
        $this->cpar_acknowledge = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->join('cpar_investigations as j', 'b.id', '=', 'j.assigned_id')
            ->whereIn('b.status_id', [25, 30])
            ->select(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'b.id as assignment_id',
                'b.assigned_to',
                'b.status_id',
                'i.first_name',
                'i.last_name',
                'g.department_name',
                'h.status_name'
            )
            ->get();
    }

    public function viewDetails($assignment_id)
    {
        $this->dispatch('open-acknowledge-cpar', id: $assignment_id);
    }

    public function createRequestIR($assignment_id)
    {
        $this->dispatch('ir-request-cpar', id: $assignment_id);
    }

    public function createNoticeToExplain($assignment_id)
    {
        $this->dispatch('nte-cpar', id: $assignment_id);
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_acknowledge_notif');
    }
}
