<?php

namespace App\Livewire\Admin\Cpar;

use App\Models\cpar_assignments;
use App\Models\employee;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\CparHistoryTrait;

class CparReAssigned extends Component
{
    use CparHistoryTrait;
    protected $casts = [
        'assigned_to' => 'array',
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
    protected $listeners = [
        'open-reassign' => 'open'
    ];

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
        $this->employees = Employee::where('branch_id', $this->branch_id)
            ->where('department_id', $this->department_id)
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

    public function open($id = null)
    {
        $cpar = DB::table('cpar_assignments as b')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->select(
                'b.id as assignment_id',
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
            ->where('b.id', $id)
            ->first();

        if (!$cpar) {
            return;
        }
        $this->cpar_id = $cpar->cpar_id;
        $this->assigned = $cpar->assigned_to;
        $this->assigned_to = $cpar->assigned_to;
        $this->branch_id = $cpar->branch_id ?? null;
        $this->dept_head_assigned = $cpar->dept_head_assigned;
        $this->department_id = $cpar->department_id;
        $this->remarks = $cpar->remarks;
        $this->new_assignees = [];
        $this->modal('reassign-cpar')->show();
    }

    public function save()
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
                    'status_id'     => 5,
                ]);

                $oldStatus = DB::table('cpar_statuses')
                    ->where('id', $mainAssignment->status_id)
                    ->value('status_name');

                // History for updated assignment
                $this->addCparHistory([
                    'cpar_id'     => $mainAssignment->cpar_id,
                    'action'      => 'CPAR Reassigned',
                    'old_status'  => $oldStatus,
                    'new_status'  => 'ASSIGNED',
                    'assigned_id' => $this->assigned,
                    'remarks'     => $this->remarks,
                ]);
            } else {
                $mainAssignment->update([
                    'assigned_to'   => (int) $this->assigned,
                    'assigned_date' => now(),
                    'remarks'       => $this->remarks,
                    'status_id'     => 5,
                ]);
                // History for created assignment
                $this->addCparHistory([
                    'cpar_id'     => $mainAssignment->cpar_id,
                    'action'      => 'CPAR Assigned',
                    'old_status'  => 'PENDING',
                    'new_status'  => 'ASSIGNED',
                    'assigned_id' => $this->assigned,
                    'remarks'     => $this->remarks,
                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | CREATE ADDITIONAL ASSIGNEES
        |--------------------------------------------------------------------------
        */
            foreach ($this->new_assignees as $employeeId) {
                if (!empty($employeeId)) {
                    cpar_assignments::create([
                        'cpar_id'            => $this->cpar_id,
                        'employee_no'        => $this->employee_no,
                        'assigned_to'        => (int) $employeeId,
                        'department_id'      => $this->department_id,
                        'dept_head_assigned' => $this->dept_head_assigned,
                        'assigned_date'      => now(),
                        'remarks'            => $this->remarks,
                        'status_id'          => 5,
                        'created_by'         => auth()->id(),
                    ]);

                    // History for additional assignee
                    $this->addCparHistory([
                        'cpar_id'     => $this->cpar_id,
                        'action'      => 'Additional Assignee Added',
                        'old_status'  => 'PENDING',
                        'new_status'  => 'ASSIGNED',
                        'assigned_id' => $employeeId,
                        'remarks'     => $this->remarks,
                    ]);
                }
            }
        });


        $this->reset([
            'assigned',
            'assigned_to',
            'new_assignees',
            'remarks',
        ]);

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'CPAR successfully assigned.'
        );

        // ✅ CLOSE FLUX MODAL
        $this->dispatch('modal-close', name: 'reassign-cpar');
        $this->dispatch('modal-close', name: 'CPARHRModal');
        $this->dispatch('refreshHeadRecords');
        $this->dispatch('refreshHeadCount');
    }

    public function render()
    {
        return view('livewire.admin.cpar.cpar_re-assigned');
    }
}
