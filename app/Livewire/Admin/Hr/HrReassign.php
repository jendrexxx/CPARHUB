<?php

namespace App\Livewire\Admin\Hr;

use App\Models\cpar_assignments;
use App\Models\cpar_request_forms;
use App\Models\employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HrReassign extends Component
{
    protected $listeners = [
        'open-reassign' => 'open'
    ];

    public $cpar_id = '';
    public $remarks = '';
    public $employees = [];
    public $employee_no = '';
    public $branch_id = '';
    public $department_id = '';
    public $id = '';
    public $assigned = null;
    public $assigned_to = [];
    public $new_assignees = [];
    public $assigned_head = '';
    public $dept_head_assigned = '';

    public function mount()
    {
        $user = Auth::user();
        $info = Employee::where('email', $user->email)->first();

        if ($info) {
            $this->id          = $info->id;
            $this->employee_no = $info->employee_no;
            $this->branch_id   = $info->branch_id;
            $this->department_id = $info->department_id;
        }

        $this->assigned_to = [];
        $this->employees = employee::where('branch_id', $this->branch_id)
            ->orderBy('first_name')
            ->get()
            ->map(function ($employee) {
                $employee->full_name = $employee->first_name . ' ' . $employee->last_name;
                return $employee;
            });
    }

    public function addAssignee()
    {
        $this->new_assignees[] = null;
    }

    public function removeAssignee($index)
    {
        unset($this->new_assignees[$index]);

        $this->new_assignees = array_values($this->new_assignees);
    }

    public function open($id = '')
    {
        $this->cpar_id = $id;
        $cpar = DB::table('cpar_assignments as b')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->select(
                'b.id',
                'b.cpar_id',
                'b.assigned_to',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',
                'h.status_name',
                'i.branch_id',
                'i.department_name',
                'i.first_name',
                'i.last_name'
            )
            ->where('b.assigned_to', $id)
            ->first();
        $this->cpar_id = $cpar->cpar_id;
        $this->assigned = $cpar->assigned_to;
        $this->assigned_to = $cpar->assigned_to;
        $this->branch_id = $cpar->branch_id ?? null;
        $this->assigned_head = $cpar->assigned_to;
        $this->dept_head_assigned = $cpar->dept_head_assigned;
        $this->remarks = $cpar->remarks;
        $this->modal('reassign-cpar')->show();
    }

    public function HRAssigned()
    {
        $this->validate([
            'assigned' => 'required',
            'remarks' => 'required',
        ]);

        DB::transaction(function () {

            $mainAssignment = cpar_assignments::where('assigned_to', $this->assigned_to)->first();
            if ($mainAssignment) {
                $mainAssignment->update([
                    'assigned_to'   => (int) $this->assigned,
                    'assigned_date' => now(),
                    'remarks'       => $this->remarks,
                    'status_id'     => 10,
                ]);
            } else {

                $mainAssignment->update([
                    'assigned_to'   => (int) $this->assigned,
                    'assigned_date' => now(),
                    'remarks'       => $this->remarks,
                    'status_id'     => 10,
                ]);
            }

            foreach ($this->new_assignees as $employeeId) {

                if (!empty($employeeId)) {
                    cpar_assignments::create([
                        'cpar_id'            => $this->cpar_id,
                        'assigned_to'        => (int) $employeeId,
                        'department_id'      => $this->department_id,
                        'dept_head_assigned' => $this->dept_head_assigned,
                        'assigned_date'      => now(),
                        'remarks'            => $this->remarks,
                        'status_id'          => 10,
                        'created_by'         => auth()->id(),
                    ]);
                }
            }
        });

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'CPAR successfully re-assigned.'
        );

        $this->reset([
            'assigned',
            'assigned_to',
            'new_assignees',
            'remarks',
        ]);

        // ✅ CLOSE FLUX MODAL
        $this->dispatch('modal-close', name: 'reassign-cpar');
        $this->dispatch('modal-close', name: 'CPARHRModal');
        $this->dispatch('refreshHRData');
        $this->dispatch('refreshHRCount');
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_reassign');
    }
}
