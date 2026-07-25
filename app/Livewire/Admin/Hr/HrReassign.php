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
        if (!is_array($this->assigned_to)) {
            $this->assigned_to = [];
        }

        $this->assigned_to[] = null;
    }

    public function removeAssignee($index)
    {
        if (is_array($this->assigned_to)) {

            unset($this->assigned_to[$index]);

            $this->assigned_to = array_values($this->assigned_to);
        }
    }

    public function open($id = '')
    {
        $this->cpar_id = $id;
        $cpar = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->leftJoin('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->leftJoin('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->leftJoin('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->leftJoin('cpar_statuses as h', 'a.status_id', '=', 'h.id')
            ->join('employees as i', 'b.dept_head_assigned', 'i.id')
            ->select(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'b.assigned_to',
                'b.dept_head_assigned',
                'b.remarks',
                'g.department_name',
                'h.status_name',
                'd.source_name',
                'e.complain_name',
                'f.concern_name',
                'g.department_name',
                'i.branch_id'
            )
            ->where('a.id', $id)
            ->first();
        $assignedList = json_decode($cpar->assigned_to, true) ?? [];
        $this->assigned = $assignedList[0] ?? null;
        $this->assigned_to = array_slice($assignedList, 1);
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

        $assignees = [];

        // Main Assign
        if ($this->assigned) {
            $assignees[] = (int) $this->assigned;
        }


        // Additional Assign
        foreach ($this->assigned_to ?? [] as $employeeId) {

            if ($employeeId) {
                $assignees[] = (int) $employeeId;
            }
        }


        // Remove duplicate
        $assignees = array_values(array_unique($assignees));


        DB::transaction(function () use ($assignees) {


            // Update assigned employees
            cpar_assignments::updateOrCreate(
                [
                    'cpar_id' => $this->cpar_id
                ],
                [
                    'assigned_to' => $assignees,
                    'dept_head_assigned' => $this->dept_head_assigned,
                    'assigned_date' => now(),
                    'remarks' => $this->remarks,
                    'created_by' => auth()->id(),
                ]
            );


            // Update CPAR Status
            cpar_request_forms::where('id', $this->cpar_id)
                ->update([
                    'status_id' => 10
                ]);
        });


        $this->dispatch(
            'toast',
            type: 'success',
            message: 'CPAR successfully reassigned.'
        );

        $this->reset([
            'assigned',
            'assigned_to',
            'remarks'
        ]);


        $this->modal('reassign-cpar')->close();
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_reassign');
    }
}
