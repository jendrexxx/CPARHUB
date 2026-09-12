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
        $cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_ir_requests as i', 'b.id', '=', 'i.assignment_ir_id')
            ->join('cpar_statuses as c', 'b.status_id', '=', 'c.id')
            ->join('cpar_notice_to_explains as j','j.assignment_id','=','i.id')
            ->select(
                'j.id',
                'j.nte_no',
                'j.nte_attachment',
                'j.issued_at',
                'j.due_date',
                'b.id as assignment_id',
                'b.cpar_id as record_id',
                'c.status_name as status',
                'a.cpar_no as record_no',
                DB::raw("'CPAR' as record_type")
            )
            ->where('b.assigned_to', $this->id)
            ->where('b.status_id', 23)
            ->where('b.record_type', 5);

        $result = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->join('cpar_ir_requests as i', 'b.id', '=', 'i.assignment_ir_id')
            ->join('cpar_statuses as c', 'b.status_id', '=', 'c.id')
            ->join(
                'cpar_notice_to_explains as j',
                'j.assignment_id',
                '=',
                'i.id'
            )
            ->select(
                'j.id',
                'j.nte_no',
                'j.nte_attachment',
                'j.issued_at',
                'j.due_date',
                'b.id as assignment_id',
                'b.result_id as record_id',
                'c.status_name as status',
                'a.result_no as record_no',
                DB::raw("'RESULT' as record_type")
            )
            ->where('b.assigned_to', $this->id)
            ->where('b.status_id', 23)
            ->where('b.record_type', 10);

        $this->nteList = $cpar
            ->unionAll($result)
            ->orderByDesc('issued_at')
            ->get();

        $this->nte_cpar = $this->nteList->count();
    }

    public function viewNTE($assignment_id)
    {
        $this->dispatch('view-NTE', id: $assignment_id);
    }

    public function viewResultNTE($assignment_id)
    {
        $this->dispatch('View-Result-NTE', id: $assignment_id);
    }

    public function render()
    {
        return view('livewire.user.modal.cpar_nte_table');
    }
}
