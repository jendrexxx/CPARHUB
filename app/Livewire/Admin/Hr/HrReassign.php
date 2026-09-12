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
    public $assignment_id = '', $priority = '', $emp_reported = '';
    public $cpar_no = '', $reported_by = '', $date_open = '', $department_name = '', $status_name = '', $source_name = '', $complain_name = '', $concern_name = '', $concern_description = '', $complainant_name = '', $attachment = '';

    public function mount($branch_id)
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
        $this->employees = employee::where('branch_id', $branch_id)
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

    public function open($id = '')
    {
        $this->cpar_id = $id;
        // Get CPAR details
        $cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->leftJoin('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->leftJoin('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->leftJoin('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->join('priority_levels as m', 'a.priority_level', '=', 'm.id')
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
                'd.source_name',
                'e.complain_name',
                'f.concern_name',
                'g.department_name',
                'c.file_path',
                'm.priority_name'
            )
            ->where('a.id', $this->cpar_id)
            ->first();
        if (!$cpar) {
            return;
        }
        $assignments = DB::table('cpar_assignments as b')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->select(
                'b.id',
                'b.cpar_id',
                'b.assigned_to',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',
                'h.status_name'
            )
            ->where('b.cpar_id', $this->cpar_id)
            ->orderBy('b.id')
            ->get();
        $this->emp_reported = $cpar->emp_reported;
        $cpar_info = DB::table('employees as a')
            ->join('cpar_request_forms as b', 'a.employee_no', '=', 'b.employee_no')
            ->where('b.employee_no', $this->emp_reported)
            ->select('a.employee_no', 'a.first_name', 'a.last_name')
            ->first();
        if ($cpar_info) {
            $this->emp_reported = trim($cpar_info->first_name . ' ' . $cpar_info->last_name);
        }
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
        $this->branch_id = $cpar->branch_id ?? null;
        $this->new_assignees = [];

        if ($assignments->count() > 0) {
            $firstAssignment = $assignments->first();
            $this->assignment_id = $firstAssignment->id;
            $this->assigned = $firstAssignment->assigned_to;
            $this->assigned_to = $firstAssignment->assigned_to;
            $this->assigned_head = $firstAssignment->assigned_to;
            $this->dept_head_assigned = $firstAssignment->dept_head_assigned;
            $this->remarks = $firstAssignment->remarks;
            $this->status_name = $firstAssignment->status_name;
            $this->new_assignees = $assignments
                ->skip(1)
                ->pluck('assigned_to')
                ->values()
                ->toArray();
        } else {
            $this->assignment_id = null;
            $this->assigned = '';
            $this->assigned_to = '';
            $this->assigned_head = '';
            $this->dept_head_assigned = '';
            $this->remarks = '';
            $this->status_name = '';
            $this->new_assignees = [];
        }

        $this->modal('reassign-cpar')->show();
    }

    public function HRAssigned()
    {
        $this->validate([
            'assigned' => 'required',
            'remarks'  => 'required|string',
        ]);

        DB::transaction(function () {

            $mainAssignment = cpar_assignments::where('id', $this->assignment_id)
                ->where('cpar_id', $this->cpar_id)
                ->first();

            if (!$mainAssignment) {
                throw new \Exception('CPAR assignment not found.');
            }

            $mainAssignee = (int) $this->assigned;
            // IMPORTANT: BEFORE update()
            $this->addReassignedAuditLog(
                $mainAssignment,
                $mainAssignee
            );

            $duplicateMain = cpar_assignments::where('cpar_id', $this->cpar_id)
                ->where('assigned_to', (int) $this->assigned)
                ->where('id', '!=', $mainAssignment->id)
                ->exists();

            if ($duplicateMain) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'assigned' => 'This employee is already assigned to this CPAR.',
                ]);
            }

            $mainAssignment->update([
                'dept_head_assigned' => $this->id,
                'assigned_to'        => (int) $this->assigned,
                'assigned_date'      => now(),
                'remarks'            => $this->remarks,
                'status_id'          => 10,
                'record_type'        => 5,
            ]);

            foreach ($this->new_assignees ?? [] as $employeeId) {

                if (empty($employeeId)) {
                    continue;
                }

                $employeeId = (int) $employeeId;

                // Don't duplicate MAIN ASSIGNEE
                if ($employeeId === (int) $this->assigned) {
                    continue;
                }

                $existingAssignment = cpar_assignments::where('cpar_id', $this->cpar_id)
                    ->where('assigned_to', $employeeId)
                    ->first();

                if ($existingAssignment) {

                    // UPDATE EXISTING ASSIGNEE
                    $existingAssignment->update([
                        'dept_head_assigned' => $this->id,
                        'assigned_date'      => now(),
                        'remarks'            => $this->remarks,
                        'status_id'          => 10,
                        'record_type'        => 5,
                    ]);

                    continue;
                }

                cpar_assignments::create([
                    'cpar_id'            => $this->cpar_id,
                    'assigned_to'        => $employeeId,
                    'department_id'      => $this->department_id,
                    'dept_head_assigned' => $this->id,
                    'assigned_date'      => now(),
                    'remarks'            => $this->remarks,
                    'status_id'          => 10,
                    'record_type'        => 5,
                    'created_by'         => auth()->id(),
                ]);
            }

            cpar_assignments::where('cpar_id', $this->cpar_id)
                ->update([
                    'status_id' => 10,
                ]);
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

        $this->dispatch('modal-close', name: 'reassign-cpar');
        $this->dispatch('modal-close', name: 'CPARHRModal');
        $this->dispatch('refreshHRData');
        $this->dispatch('refreshHRCount');
        $this->dispatch('refreshNotificationCount');
    }

    private function addReassignedAuditLog(
        $mainAssignment,
        $mainAssignee,
        array $additionalAssignees = []
    ): void {


        $oldStatus = DB::table('cpar_statuses')
            ->where('id', $mainAssignment->status_id)
            ->value('status_name');

        $oldValue = [
            'cpar_no'              => $this->cpar_no,
            'assigned_to'          => $mainAssignment->assigned_to,
            'additional_assignees' => [],
            'remarks'              => $mainAssignment->remarks,
            'status_id'            => $mainAssignment->status_id,
            'status_name'          => $oldStatus ?? 'UNKNOWN',
        ];

        $newValue = [
            'cpar_no'              => $this->cpar_no,
            'assigned_to'          => $mainAssignee,
            'additional_assignees' => array_values($additionalAssignees),
            'remarks'              => $this->remarks,
            'status_id'            => 10,
            'status_name'          => 'ASSIGNED',
        ];

        $userReported = array_values(
            array_merge(
                [$mainAssignee],
                $additionalAssignees
            )
        );

        DB::table('audit_logs')->insert([
            'user_reported_by'  => $this->emp_reported,
            'user_reported'     => json_encode($userReported),
            'action'            => 'RE-ASSIGNED',
            'old_value'         => json_encode($oldValue),
            'new_value'         => json_encode($newValue),
            'status_changed_by' => $this->id,
            'date'              => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_reassign');
    }
}
