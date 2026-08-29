<?php

namespace App\Livewire\Admin\Cpar;

use App\Models\cpar_assignments;
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
    public $assigned = '';
    public $assigned_to = [];
    public $new_assignees = [];
    public $assigned_head = '';
    public $dept_head_assigned = '',$employeeName = '';
    public $assignment_id = '', $priority = '', $emp_reported = '';
    public $cpar_no = '', $reported_by = '', $date_open = '', $department_name = '', $status_name = '', $source_name = '', $complain_name = '', $concern_name = '', $concern_description = '', $complainant_name = '', $attachment = '';
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
        $cpar = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->leftJoin('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->leftJoin('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->leftJoin('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->join('employees as i', 'b.dept_head_assigned', 'i.id')
            ->leftJoin('employees as g', 'a.employee_no', '=', 'g.employee_no')
            ->join('priority_levels as m', 'a.priority_level', '=', 'm.id')
            ->select(
                'a.cpar_no',
                'a.employee_no as emp_reported',
                'a.reported_by',
                'a.date_open',
                'a.concern_description',
                'a.complainant_name',
                'b.id as assignment_id',
                'b.cpar_id',
                'b.assigned_to',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',
                'd.source_name',
                'e.complain_name',
                'f.concern_name',
                'g.department_name',
                'c.file_path',
                'h.status_name',
                'i.branch_id',
                'i.first_name',
                'i.last_name',
                'm.priority_name'
            )
            ->where('b.id', $id)
            ->first();

        if (!$cpar) {
            return;
        }
        $this->emp_reported = $cpar->emp_reported;
        $cpar_info = DB::table('employees as a')
            ->join('cpar_request_forms as b', 'a.employee_no', '=', 'b.employee_no')
            ->where('b.employee_no', $this->emp_reported)
            ->select(
                'a.employee_no',
                'a.first_name',
                'a.last_name'
            )
            ->first();

        if ($cpar_info) {
            $this->employeeName = trim($cpar_info->first_name . ' ' . $cpar_info->last_name);
        }

        $this->cpar_id = $cpar->cpar_id;
        $this->assignment_id = $cpar->assignment_id;
        $this->assigned = $cpar->assigned_to;
        $this->assigned_to = $cpar->assigned_to;
        $this->branch_id = $cpar->branch_id ?? null;
        $this->dept_head_assigned = $cpar->dept_head_assigned;
        $this->department_id = $cpar->department_id;
        $this->remarks = $cpar->remarks;
        $this->new_assignees = [];
        $this->cpar_no = $cpar->cpar_no;
        $this->reported_by = $cpar->reported_by;
        $this->date_open = $cpar->date_open;
        $this->department_name = $cpar->department_name;
        $this->status_name = $cpar->status_name;
        $this->source_name = $cpar->source_name;
        $this->complain_name = $cpar->complain_name;
        $this->concern_name = $cpar->concern_name;
        $this->concern_description = $cpar->concern_description;
        $this->complainant_name = $cpar->complainant_name;
        $this->attachment = $cpar->file_path;
        $this->priority = $cpar->priority_name;
        $this->modal('reassign-cpar')->show();
    }

    public function save()
    {
        $this->validate([
            'assigned' => 'required',
            'remarks'  => 'required|string',
        ]);
        $mainAssignee = (int) $this->assigned;
        $user = DB::table('employees')
            ->where('id', $mainAssignee)
            ->first([
                'id',
                'employee_no',
                'first_name',
                'last_name',
            ]);

        $additionalAssignees = collect($this->new_assignees ?? [])
            ->filter(fn($id) => !empty($id))
            ->map(fn($id) => (int) $id)
            ->values()
            ->toArray();

        $allAssignees = array_merge(
            [$mainAssignee],
            $additionalAssignees
        );

        if (count($allAssignees) !== count(array_unique($allAssignees))) {
            $this->addError(
                'assigned',
                'The same employee cannot be assigned more than once.'
            );

            return;
        }

        $alreadyAssigned = cpar_assignments::where('cpar_id', $this->cpar_id)
            ->whereIn('assigned_to', $allAssignees)
            ->when(
                $this->assignment_id,
                fn($query) => $query->where('id', '!=', $this->assignment_id)
            )
            ->pluck('assigned_to')
            ->map(fn($id) => (int) $id)
            ->unique()
            ->toArray();

        if (!empty($alreadyAssigned)) {
            $this->addError(
                'assigned',
                'One or more selected employees are already assigned to this CPAR.'
            );

            return;
        }

        DB::transaction(function () use ($mainAssignee, $additionalAssignees) {

            $mainAssignment = cpar_assignments::where('id', $this->assignment_id)
                ->first();

            $oldValue = null;

            if ($mainAssignment) {

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
            }

            if (!$mainAssignment) {

                $mainAssignment = cpar_assignments::create([
                    'cpar_id'            => $this->cpar_id,
                    'employee_no'        => $this->employee_no,
                    'assigned_to'        => $mainAssignee,
                    'department_id'      => $this->department_id,
                    'dept_head_assigned' => $this->dept_head_assigned,
                    'assigned_date'      => now(),
                    'remarks'            => $this->remarks,
                    'status_id'          => 5,
                    'record_type'        => 5,
                    'created_by'         => auth()->id(),
                ]);
            } else {

                $mainAssignment->update([
                    'employee_no'   => $this->employee_no,
                    'assigned_to'   => $mainAssignee,
                    'assigned_date' => now(),
                    'remarks'       => $this->remarks,
                    'status_id'     => 5,
                    'record_type'   => 5,
                ]);
            }

            foreach ($additionalAssignees as $employeeId) {

                cpar_assignments::create([
                    'cpar_id'            => $this->cpar_id,
                    'employee_no'        => $this->employee_no,
                    'assigned_to'        => $employeeId,
                    'department_id'      => $this->department_id,
                    'dept_head_assigned' => $this->dept_head_assigned,
                    'assigned_date'      => now(),
                    'remarks'            => $this->remarks,
                    'status_id'          => 5,
                    'record_type'        => 5,
                    'created_by'         => auth()->id(),
                ]);
            }

            $newValue = [
                'cpar_no'              => $this->cpar_no,
                'assigned_to'          => $mainAssignee,
                'additional_assignees' => array_values($additionalAssignees),
                'remarks'              => $this->remarks,
                'status_id'            => 5,
                'status_name'          => 'ASSIGNED',
            ];

            DB::table('audit_logs')->insert([
                'user_reported_by'  => $this->employeeName,
                'user_reported'     => json_encode(array_values(array_merge([$mainAssignee], $additionalAssignees))),
                'action'            => $mainAssignment->wasRecentlyCreated ? 'CREATED' : 'ASSIGNED',
                'old_value'         => $oldValue ? json_encode($oldValue) : null,
                'new_value'         => json_encode($newValue),
                'status_changed_by' => $this->id,
                'date'              => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        });

        $this->reset([
            'assigned',
            'assigned_to',
            'new_assignees',
            'remarks',
        ]);

        $this->dispatch('toast', type: 'success', message: 'CPAR successfully assigned.');
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
