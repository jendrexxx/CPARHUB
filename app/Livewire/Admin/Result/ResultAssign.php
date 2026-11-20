<?php

namespace App\Livewire\Admin\Result;

use App\Models\cpar_assignments;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use App\Models\result_error_data_informations;
use App\Models\result_error_form;
use App\Models\result_error_quality_accuracies;
use App\Models\result_error_technical_equipments;

class ResultAssign extends Component
{
    protected $listeners = [
        'open-assign-result' => 'open_modal'
    ];
    protected $casts = [
        'assigned_to' => 'array',
    ];
    public $result_no = '', $reported_by = '', $patient_name = '', $attending_physician = '', $actual_released_date = '', $source_name = '', $complain_name = '', $concern_description = '', $department_name = '', $priority = '', $remarks = '', $status = '', $dept_head_assigned = '', $date_reported = '', $test_procedure = '', $complainant_name = '';
    public $employeeName = '', $emp_reported = '', $id = '', $employee_no = '', $branch_id = '', $department_id = '', $assignment_remarks = '', $assignment_id = '', $result_id = '';
    public $employees = [];
    public $assigned_to = [];
    public $new_assignees = [];
    public $data = [];
    public $quality = [];
    public $technical = [];
    public $data_information = [];
    public $technical_information = [];
    public $quality_information = [];
    public $existing_attachment = '';

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
        $this->data = result_error_data_informations::all();
        $this->quality = result_error_quality_accuracies::all();
        $this->technical = result_error_technical_equipments::all();
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

    public function open_modal($id = null)
    {
        $result_assigned = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('cpar_attachments as e', 'd.result_id', '=', 'e.result_id')
            ->join('cpar_statuses as h', 'd.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'd.dept_head_assigned', '=', 'i.id')
            ->join('priority_levels as m', 'a.priority_level', '=', 'm.id')
            ->select(
                'a.result_no',
                'a.reported_by',
                'a.employee_no as emp_reported',
                'a.date_reported',
                'a.test_procedure',
                'a.quality_information',
                'a.actual_released_date',
                'a.data_information',
                'a.patient_name',
                'a.complain_name as complainant_name',
                'a.attending_physician',
                'a.technical_information',
                'a.concern_description',
                'b.source_name',
                'c.complain_name',
                'd.id as assignment_id',
                'd.result_id',
                'd.assigned_to',
                'd.remarks',
                'd.dept_head_assigned',
                'd.department_id',
                'e.file_path',
                'h.status_name',
                'i.branch_id',
                'i.department_name',
                'i.first_name',
                'i.last_name',
                'm.priority_name'
            )
            ->where('d.id', $id)
            ->first();
        if (!$result_assigned) {
            return;
        }
        $this->emp_reported = $result_assigned->emp_reported;
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
        $this->existing_attachment = $result_assigned->file_path;
        $this->assignment_id = $result_assigned->assignment_id;
        $this->result_no = $result_assigned->result_no;
        $this->date_reported = $result_assigned->date_reported;
        $this->reported_by = $result_assigned->reported_by;
        $this->test_procedure = $result_assigned->test_procedure;
        $this->patient_name = $result_assigned->patient_name;
        $this->attending_physician = $result_assigned->attending_physician;
        $this->actual_released_date = $result_assigned->actual_released_date;
        $this->source_name = $result_assigned->source_name;
        $this->data_information = is_array($result_assigned->data_information)
            ? $result_assigned->data_information
            : json_decode($result_assigned->data_information ?? '[]', true);
        $this->technical_information = is_array($result_assigned->technical_information)
            ? $result_assigned->technical_information
            : json_decode($result_assigned->technical_information ?? '[]', true);
        $this->quality_information = is_array($result_assigned->quality_information)
            ? $result_assigned->quality_information
            : json_decode($result_assigned->quality_information ?? '[]', true);
        $this->complain_name = $result_assigned->complain_name;
        $this->concern_description = $result_assigned->concern_description;
        $this->department_name = $result_assigned->department_name;
        $this->priority = $result_assigned->priority_name;
        $this->complainant_name = $result_assigned->complainant_name;
        // Assignment information
        $this->assigned_to = $result_assigned->assigned_to;
        $this->assignment_remarks = $result_assigned->remarks;
        $this->status = $result_assigned->status_name;
        $this->dept_head_assigned = $result_assigned->dept_head_assigned;
        $this->result_id = $result_assigned->result_id;
        $this->modal('reassign-result')->show();
    }

    public function reassignResult()
    {
        $this->validate([
            'assigned_to'        => 'required',
            'assignment_remarks' => 'required|string',
        ]);

        $mainAssignee = (int) $this->assigned_to;

        $additionalAssignees = collect($this->new_assignees ?? [])
            ->filter(fn($id) => filled($id))
            ->map(fn($id) => (int) $id)
            ->values()
            ->toArray();

        $allAssignees = collect([
            $mainAssignee,
            ...$additionalAssignees,
        ])
            ->unique()
            ->values()
            ->toArray();

        if (count($allAssignees) !== count(
            array_unique($allAssignees)
        )) {
            $this->addError(
                'assigned_to',
                'The same employee cannot be assigned more than once.'
            );

            return;
        }

        $alreadyAssigned = cpar_assignments::where(
            'result_id',
            $this->result_id
        )
            ->where('record_type', 10)
            ->whereIn('assigned_to', $allAssignees)
            ->when(
                $this->assignment_id,
                fn($query) => $query->where(
                    'id',
                    '!=',
                    $this->assignment_id
                )
            )
            ->pluck('assigned_to')
            ->map(fn($id) => (int) $id)
            ->unique()
            ->toArray();

        if (!empty($alreadyAssigned)) {
            $this->addError(
                'assigned_to',
                'One or more selected employees are already assigned to this Result.'
            );

            return;
        }

        DB::transaction(function () use ($mainAssignee, $additionalAssignees, $allAssignees) {

            $result = cpar_assignments::where(
                'id',
                $this->result_id
            )->first();

            if (!$result) {
                return;
            }

            $mainAssignment = cpar_assignments::where(
                'id',
                $this->assignment_id
            )->first();

            $oldStatus = null;
            $oldValue = null;

            if ($mainAssignment) {

                $oldStatus = DB::table('cpar_statuses')
                    ->where('id', $mainAssignment->status_id)
                    ->value('status_name');

                $oldValue = [
                    'result_id'    => $this->result_id,
                    'assigned_to'  => $mainAssignment->assigned_to,
                    'remarks'      => $mainAssignment->remarks,
                    'status_id'    => 1,
                    'status_name'  => $oldStatus ?? 'UNKNOWN',
                ];
            }

            /*
         * Create or update main assignment
         */
            if (!$mainAssignment) {

                $mainAssignment = cpar_assignments::create([
                    'result_id'          => $this->result_id,
                    'employee_no'        => $this->employee_no,
                    'assigned_to'        => $mainAssignee,
                    'department_id'      => $this->department_id,
                    'dept_head_assigned' => $this->dept_head_assigned,
                    'assigned_date'      => now(),
                    'remarks'            => $this->assignment_remarks,
                    'status_id'          => 5,
                    'record_type'        => 10,
                    'created_by'         => auth()->id(),
                ]);

                $mainAction = 'CREATED';
            } else {

                $mainAssignment->update([
                    'employee_no'   => $this->employee_no,
                    'assigned_to'   => $mainAssignee,
                    'assigned_date' => now(),
                    'remarks'       => $this->assignment_remarks,
                    'status_id'     => 5,
                    'record_type'   => 10,
                ]);

                $mainAction = 'ASSIGNED';
            }

            /*
         * Create assignments for additional employees
         */
            foreach ($additionalAssignees as $employeeId) {

                cpar_assignments::create([
                    'result_id'          => $this->result_id,
                    'employee_no'        => $this->employee_no,
                    'assigned_to'        => $employeeId,
                    'department_id'      => $this->department_id,
                    'dept_head_assigned' => $this->dept_head_assigned,
                    'assigned_date'      => now(),
                    'remarks'            => $this->assignment_remarks,
                    'status_id'          => 5,
                    'record_type'        => 10,
                    'created_by'         => auth()->id(),
                ]);
            }

            /*
         * Create one audit log for EACH employee
         */
            foreach ($allAssignees as $employeeId) {

                $employeeOldValue = null;

                /*
             * Old value is only applicable to the
             * existing main assignment.
             */
                if (
                    $mainAssignment &&
                    (int) $mainAssignment->getOriginal('assigned_to') === $employeeId
                ) {
                    $employeeOldValue = [
                        'result_id'   => $this->result_id,
                        'assigned_to' => $mainAssignment->getOriginal('assigned_to'),
                        'remarks'     => $mainAssignment->getOriginal('remarks'),
                        'status_id'   => $mainAssignment->getOriginal('status_id'),
                        'status_name' => $oldStatus ?? 'UNKNOWN',
                    ];
                }

                $newValue = [
                    'result_id'          => $this->result_id,
                    'assigned_to'        => $employeeId,
                    'remarks'            => $this->assignment_remarks,
                    'status_id'          => 5,
                    'status_name'        => 'ASSIGNED',
                    'record_type'        => 10,
                    'assigned_by'        => auth()->id(),
                    'date_assigned'      => now(),
                ];

                DB::table('audit_logs')->insert([
                    'user_reported_by'  => $this->reported_by,
                    'user_reported'     => $employeeId,
                    'action'            => $employeeId === $mainAssignee
                        ? $mainAction
                        : 'ASSIGNED',
                    'old_value'         => $employeeOldValue
                        ? json_encode($employeeOldValue)
                        : ($oldValue ? json_encode($oldValue) : null),
                    'new_value'         => json_encode($newValue),
                    'status_changed_by' => auth()->id(),
                    'date'              => now(),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        });

        $this->reset([
            'assigned_to',
            'new_assignees',
            'assignment_remarks',
        ]);

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'Result successfully assigned.'
        );

        $this->dispatch(
            'modal-close',
            name: 'reassign-cpar'
        );

        $this->dispatch(
            'modal-close',
            name: 'CPARHRModal'
        );

        $this->dispatch('refreshHeadRecords');
        $this->dispatch('refreshHeadCount');
    }

    public function render()
    {
        return view('livewire.admin.result.result_assign');
    }
}
