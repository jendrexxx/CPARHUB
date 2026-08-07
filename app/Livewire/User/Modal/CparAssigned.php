<?php

namespace App\Livewire\User\Modal;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Illuminate\Support\Facades\DB;

class CparAssigned extends Component
{
    public $cpar_requests = [];
    public $editingId = null;
    public $showEditModal = false;
    public $edit_cpar_no = '';
    public $edit_reported_by = '';
    public $edit_date_open = '';
    public $edit_department_name = '';
    public $employee_no = '';
    public $id = '';

    protected $listeners = [
        'refreshAssignedData' => 'loadAssignedRecords',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
        $this->loadAssignedRecords();
    }

    public function loadAssignedRecords()
    {
        $this->cpar_requests = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->select(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'b.id as assignment_id',
                'b.assigned_to',
                'g.department_name',
                'h.status_name',
            )
            ->whereIn('h.status_name', ['ASSIGNED', 'RE-ASSIGNED'])
            ->where('b.assigned_to', $this->id)
            ->distinct()
            ->orderByDesc('a.id')
            ->get();

        foreach ($this->cpar_requests as $request) {
            $employee = Employee::find($request->assigned_to);
            $request->assigned_names = $employee
                ? $employee->first_name . ' ' . $employee->last_name
                : 'Unknown Employee';
        }
    }

    public function viewDetails($id)
    {
        $this->dispatch('view-CPAR', id: $id);
    }

    public function respondCpar($id)
    {
        $this->dispatch('respond-CPAR', id: $id);
    }

    public function render()
    {
        return view('livewire.user.modal.cpar_assigned');
    }
}
