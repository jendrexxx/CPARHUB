<?php

namespace App\Livewire\User\Modal;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;

class CparIrTable extends Component
{
    public $irRequests = [];
    public $id = '';
    public $employee_no = '';

    protected $listeners = [
        'refreshCparIRData' => 'loadIRRequests',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
        $this->loadIRRequests();
    }

    public function loadIRRequests()
    {
        $this->irRequests = DB::table('cpar_ir_requests as ir')
            ->join('cpar_assignments as ca', 'ir.assignment_ir_id', '=', 'ca.id')
            ->join('cpar_request_forms as crf', 'ca.cpar_id', '=', 'crf.id')
            ->join('departments as d', 'crf.department_id', '=', 'd.id')
            ->leftJoin('employees as e', 'ca.employee_no', '=', 'e.employee_no')
            ->select(
                'ir.id',
                'ir.ir_id',
                'ir.status',
                'ir.assignment_ir_id',
                'crf.cpar_no',
                'd.department_name',
                'e.first_name',
                'e.last_name'
            )
            ->where('ir.employee_no', $this->employee_no)
            ->where('ca.status_id', 30)
            ->orderBy('ir.created_at', 'desc')
            ->get();
    }

    public function viewIR($id = null)
    {
        // dito mo ilalagay ang view IR logic
        $this->dispatch('open-ir-view', id: $id);
    }

    public function render()
    {
        return view('livewire.user.modal.cpar_ir_table');
    }
}
