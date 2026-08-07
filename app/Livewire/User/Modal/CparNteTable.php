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
        $this->nteList = DB::table('cpar_notice_to_explains as nte')
            ->join(
                'cpar_assignments as ca',
                'ca.id',
                '=',
                'nte.assignment_id'
            )
            ->leftJoin(
                'cpar_request_forms as crf',
                'crf.id',
                '=',
                'ca.cpar_id'
            )
            ->where('ca.assigned_to', $this->id)
            ->where('ca.status_id', 30)
            ->select(
                'nte.*',
                'crf.cpar_no'
            )
            ->orderBy('nte.due_date')
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
