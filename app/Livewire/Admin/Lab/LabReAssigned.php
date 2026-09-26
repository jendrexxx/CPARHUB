<?php

namespace App\Livewire\Admin\Lab;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Illuminate\Support\Facades\DB;
use App\Models\cpar_assignments;

class LabReAssigned extends Component
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
    public $assignment_id = '', $priority = '', $emp_reported = '', $status_id = '';
    public $cpar_no = '', $reported_by = '', $date_open = '', $department_name = '', $status_name = '', $source_name = '', $complain_name = '', $concern_name = '', $concern_description = '', $complainant_name = '', $attachment = '';

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

    public function open($id = '')
    {
        if (!$id) {
            return;
        }

        $cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->leftJoin('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->leftJoin('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->leftJoin('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->join('priority_levels as h', 'a.priority_level', '=', 'h.id')
            ->leftJoin('employees as i', 'b.dept_head_assigned', '=', 'i.id')
            ->leftJoin('employees as j', 'b.assigned_to', '=', 'j.id')
            ->select(
                'a.id',
                'a.cpar_no',
                'a.employee_no as emp_reported',
                'a.reported_by',
                'a.date_open',
                'a.concern_description',
                'a.complainant_name',
                'a.department_id',
                'b.id as assignment_id',
                'b.assigned_to',
                'b.dept_head_assigned',
                'b.department_id as assignment_department_id',
                'b.remarks',
                'b.status_id',
                'd.source_name',
                'e.complain_name',
                'f.concern_name',
                'g.department_name',
                'c.file_path',
                'h.priority_name',
                'i.branch_id as dept_head_branch_id',
                'i.first_name as dept_head_first_name',
                'i.last_name as dept_head_last_name',
                'j.branch_id as assigned_branch_id',
                'j.first_name as assigned_first_name',
                'j.last_name as assigned_last_name'
            )
            ->where('b.id', $id)
            ->first();

        if (!$cpar) {
            return;
        }

        $this->emp_reported = $cpar->emp_reported;
        $this->cpar_id = $cpar->id;
        $this->assignment_id = $cpar->assignment_id;
        $this->cpar_no = $cpar->cpar_no;
        $this->reported_by = $cpar->reported_by;
        $this->date_open = $cpar->date_open;
        $this->department_name = $cpar->department_name;
        $this->source_name = $cpar->source_name;
        $this->complain_name = $cpar->complain_name;
        $this->concern_name = $cpar->concern_name;
        $this->concern_description = $cpar->concern_description;
        $this->complainant_name = $cpar->complainant_name;
        $this->attachment = $cpar->file_path;
        $this->priority = $cpar->priority_name;
        $this->assigned = $cpar->assigned_to ?? $cpar->dept_head_assigned;
        $this->remarks = $cpar->remarks;
        $this->status_id = $cpar->status_id;
        $this->assigned_to = $cpar->assigned_to
            ?? $cpar->dept_head_assigned
            ?? '';

        $this->dept_head_assigned = $cpar->dept_head_assigned
            ?? '';

        $this->branch_id = $cpar->assigned_branch_id
            ?? $cpar->dept_head_branch_id
            ?? null;

        $this->new_assignees = [];
        $this->employees = collect();

        if ($this->branch_id) {
            $this->employees = Employee::where('branch_id', $this->branch_id)
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get()
                ->map(function ($employee) {
                    $employee->full_name = trim(
                        $employee->first_name . ' ' . $employee->last_name
                    );

                    return $employee;
                });
        }

        $this->modal('lab-reassign-cpar')->show();
    }

    public function addAssignee()
    {
        $this->new_assignees[] = null;
    }

    public function removeAssignee($index)
    {
        $employeeId = $this->new_assignees[$index] ?? null;

        if (!$employeeId) {
            unset($this->new_assignees[$index]);
            $this->new_assignees = array_values($this->new_assignees);
            return;
        }

        DB::transaction(function () use ($employeeId) {
            cpar_assignments::where('cpar_id', $this->cpar_id)
                ->where('assigned_to', (int) $employeeId)
                ->where('id', '!=', $this->assignment_id)
                ->where('record_type', 5)
                ->delete();
        });
        unset($this->new_assignees[$index]);
        $this->new_assignees = array_values($this->new_assignees);
    }

    public function LabAssigned()
    {
        $this->validate([
            'assigned' => 'required',
            'remarks' => 'required|string',
        ]);

        DB::transaction(function () {

            $mainResultAssignment = cpar_assignments::where(
                'id',
                $this->assignment_id
            )->first();

            if ($mainResultAssignment) {

                $mainResultAssignment->update([
                    'assigned_to'   => (int) $this->assigned_to,
                    'assigned_date' => now(),
                    'remarks'       => $this->remarks,
                ]);
            } else {
                $mainResultAssignment = cpar_assignments::create([
                    'cpar_id'          => $this->cpar_id,
                    'employee_no'        => $this->employee_no,
                    'assigned_to'        => (int) $this->assigned_to,
                    'department_id'      => $this->department_id,
                    'dept_head_assigned' => $this->dept_head_assigned,
                    'assigned_date'      => now(),
                    'remarks'            => $this->remarks,
                    'status_id'          => 5,
                    'record_type'        => 5,
                    'created_by'         => auth()->id(),
                ]);

                $this->assignment_id = $mainResultAssignment->id;
            }

            foreach ($this->new_assignees as $employeeId) {

                if (blank($employeeId)) {
                    continue;
                }

                $employeeId = (int) $employeeId;

                if ($employeeId === (int) $this->assigned_to) {
                    continue;
                }

                $exists = cpar_assignments::where('cpar_id', $this->cpar_id)
                    ->where('record_type', 10)
                    ->where('assigned_to', $employeeId)
                    ->exists();

                if ($exists) {
                    continue;
                }

                cpar_assignments::create([
                    'cpar_id'          => $this->cpar_id,
                    'employee_no'        => $this->employee_no,
                    'assigned_to'        => $employeeId,
                    'department_id'      => $this->department_id,
                    'dept_head_assigned' => $this->dept_head_assigned,
                    'assigned_date'      => now(),
                    'remarks'            => $this->remarks,
                    'status_id'          => $this->status_id,
                    'record_type'        => 5,
                    'created_by'         => auth()->id(),
                ]);
            }
        });

        $this->reset([
            'assigned_to',
            'new_assignees',
            'remarks',
        ]);

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'Result successfully reassigned.'
        );

        $this->dispatch('modal-close', name: 'lab-reassign-result');
        $this->dispatch('modal-close', name: 'LABAssignedModal');
        $this->dispatch('refresLABRData');
        $this->dispatch('refreshAssignedCount');
    }

    public function render()
    {
        return view('livewire.admin.lab.lab_re-assigned');
    }
}
