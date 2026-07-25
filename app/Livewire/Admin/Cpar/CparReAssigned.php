<?php

namespace App\Livewire\Admin\Cpar;

use App\Models\cpar_assignments;
use App\Models\cpar_request_forms;
use App\Models\employee;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CparReAssigned extends Component
{
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

    public function open($id = '')
    {
        $this->cpar_id = $id;
        $cpar = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->join('employees as i', 'b.dept_head_assigned', '=', 'i.id')
            ->select(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'b.assigned_to',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',
                'g.department_name',
                'h.status_name',
                'i.branch_id',
            )
            ->where('a.id', $id)
            ->first();
        $assignedList = json_decode($cpar->assigned_to, true) ?? [];
        $this->assigned = $assignedList[0] ?? null;
        $this->assigned_to = array_slice($assignedList, 1);
        $this->branch_id = $cpar->branch_id ?? null;
        $this->dept_head_assigned = $cpar->dept_head_assigned;
        $this->department_id = $cpar->department_id;
        $this->remarks = $cpar->remarks;
        $this->modal('reassign-cpar')->show();
    }

    public function save()
    {
        $this->validate([
            'assigned' => 'required',
            'remarks' => 'required',
        ]);

        DB::transaction(function () {


            // UPDATE MAIN ASSIGNED
            cpar_assignments::where('cpar_id', $this->cpar_id)
                ->update([
                    'assigned_to' => json_encode([
                        (int)$this->assigned
                    ]),
                    'assigned_date' => now(),
                    'remarks' => $this->remarks,
                ]);



            // CREATE ADDITIONAL ASSIGNEES
            foreach ($this->new_assignees as $employeeId) {


                if ($employeeId) {

                    cpar_assignments::create([
                        'cpar_id' => $this->cpar_id,

                        'assigned_to' => json_encode([
                            (int)$employeeId
                        ]),

                        'department_id' => $this->department_id,

                        'dept_head_assigned' => $this->dept_head_assigned,

                        'assigned_date' => now(),

                        'remarks' => $this->remarks,

                        'status_id' => 1,

                        'created_by' => auth()->id(),

                    ]);
                }
            }
        });



        $this->dispatch(
            'toast',
            type: 'success',
            message: 'CPAR successfully reassigned.'
        );

        $this->reset([
            'assigned',
            'new_assignees',
            'remarks'
        ]);


        $this->modal('reassign-cpar')->close();
    }

    public function render()
    {
        return view('livewire.admin.cpar.cpar_re-assigned');
    }
}
