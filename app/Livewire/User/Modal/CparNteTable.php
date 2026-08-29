<?php

namespace App\Livewire\User\Modal;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;

class CparNteTable extends Component
{
    public $nte_cpar = 0;
    public $nteList = [];
    public $id = '';
    public $employee_no = '';

    protected $listeners = [
        'refreshCparNTEData' => 'loadNte',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
        $this->loadNte();
    }

    public function loadNte()
    {
        $this->nteList = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->join('cpar_ir_requests as i', 'b.id', '=', 'i.assignment_ir_id')
            ->join('cpar_notice_to_explains as j', 'j.assignment_id', '=', 'i.id')
            ->where('b.assigned_to', $this->id)
            ->where('b.status_id', 23)
            ->where('b.record_type', 5)
            ->select(
                'j.nte_no',
                'j.nte_attachment',
                'j.issued_at',
                'j.due_date',
                'j.status',
                'b.id',
                'b.cpar_id',
                'a.cpar_no'
            )
            ->get();

        $this->nte_cpar = $this->nteList->count();
    }

    public function viewNTE($id)
    {
        $this->dispatch('view-NTE', id: $id);
    }

    public function render()
    {
        return view('livewire.user.modal.cpar_nte_table');
    }
}
