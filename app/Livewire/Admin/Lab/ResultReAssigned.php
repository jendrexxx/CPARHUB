<?php

namespace App\Livewire\Admin\Lab;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use App\Models\result_error_data_informations;
use App\Models\result_error_quality_accuracies;
use App\Models\result_error_technical_equipments;
use App\Models\cpar_assignments;
use Illuminate\Support\Facades\DB;

class ResultReAssigned extends Component
{
    public $result_no = '', $reported_by = '', $patient_name = '', $attending_physician = '', $actual_released_date = '', $source_name = '', $complain_name = '', $concern_description = '', $department_name = '', $priority = '', $remarks = '', $status = '', $dept_head_assigned = '', $date_reported = '', $test_procedure = '', $complainant_name = '';
    public $id = '', $employee_no = '', $branch_id = '', $department_id = '', $assignment_remarks = '', $assignment_id = '', $cpar_id = '';
    public $employees = [];
    public $assigned_to = [];
    public $new_assignees = [];
    public $data = [];
    public $quality = [];
    public $technical = [];
    public $data_information = [];
    public $technical_information = [];
    public $quality_information = [];
    public $assigned = '', $assigned_head = '', $status_name = '', $result_id = '', $status_id = '';

    protected $listeners = [
        'view-Result' => 'open_modal'
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
            ->orderBy('first_name')
            ->get()
            ->map(function ($employee) {
                $employee->full_name = trim(
                    $employee->first_name . ' ' . $employee->last_name
                );
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
        $employeeId = $this->new_assignees[$index] ?? null;

        if (!$employeeId) {
            unset($this->new_assignees[$index]);
            $this->new_assignees = array_values($this->new_assignees);
            return;
        }

        DB::transaction(function () use ($employeeId) {
            cpar_assignments::where('result_id', $this->cpar_id)
                ->where('assigned_to', (int) $employeeId)
                ->where('id', '!=', $this->assignment_id)
                ->where('record_type', 10)
                ->delete();
        });
        unset($this->new_assignees[$index]);
        $this->new_assignees = array_values($this->new_assignees);
    }

    public function open_modal($id = null)
    {
        if (!$id) {
            return;
        }

        $result_assigned = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('cpar_statuses as h', 'd.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'd.dept_head_assigned', '=', 'i.id')
            ->join('priority_levels as m', 'a.priority_level', '=', 'm.id')
            ->leftJoin('employees as n', 'd.assigned_to', '=', 'n.id')
            ->select(
                'a.id',
                'a.result_no',
                'a.reported_by',
                'a.employee_no',
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
                'd.remarks as assignment_remarks',
                'd.dept_head_assigned',
                'd.department_id',
                'd.status_id',
                'h.status_name',
                'i.branch_id as dept_head_branch_id',
                'i.first_name as dept_head_first_name',
                'i.last_name as dept_head_last_name',
                'm.priority_name',
                'n.branch_id as assigned_branch_id',
                'n.first_name as assigned_first_name',
                'n.last_name as assigned_last_name'
            )
            ->where('d.id', $id)
            ->first();

        if (!$result_assigned) {
            return;
        }

        $this->assignment_id = $result_assigned->assignment_id;
        $this->result_id = $result_assigned->result_id;
        $this->assigned_to = $result_assigned->assigned_to ?? $result_assigned->dept_head_assigned;
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
            : (json_decode($result_assigned->data_information ?? '[]', true) ?: []);
        $this->technical_information = is_array($result_assigned->technical_information)
            ? $result_assigned->technical_information
            : (json_decode($result_assigned->technical_information ?? '[]', true) ?: []);
        $this->quality_information = is_array($result_assigned->quality_information)
            ? $result_assigned->quality_information
            : (json_decode($result_assigned->quality_information ?? '[]', true) ?: []);
        $this->complain_name = $result_assigned->complain_name;
        $this->complainant_name = $result_assigned->complainant_name;
        $this->concern_description = $result_assigned->concern_description;
        $this->priority = $result_assigned->priority_name;
        $this->remarks = $result_assigned->assignment_remarks ?? '';
        $this->assignment_remarks = $result_assigned->assignment_remarks ?? '';
        $this->dept_head_assigned = $result_assigned->dept_head_assigned ?? '';
        $this->status_name = $result_assigned->status_name ?? '';
        $this->status = $result_assigned->status_name ?? '';
        $this->assigned = $result_assigned->assigned_to;
        $this->assigned_head = $result_assigned->assigned_to;
        $this->status_id = $result_assigned->status_id;
        $this->new_assignees = [];
        $this->department_name = null;
        if ($result_assigned->department_id) {
            $this->department_name = DB::table('departments')
                ->where('id', $result_assigned->department_id)
                ->value('department_name');
        }
        $this->branch_id = $result_assigned->assigned_branch_id
            ?? $result_assigned->dept_head_branch_id;
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
        $this->modal('lab-reassign-result')->show();
    }

    public function reassignResult()
    {
        $this->validate([
            'assigned_to' => 'required',
            'assignment_remarks' => 'required|string',
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
                    'remarks'       => $this->assignment_remarks,
                ]);
            } else {
                $mainResultAssignment = cpar_assignments::create([
                    'result_id'          => $this->result_id,
                    'employee_no'        => $this->employee_no,
                    'assigned_to'        => (int) $this->assigned_to,
                    'department_id'      => $this->department_id,
                    'dept_head_assigned' => $this->dept_head_assigned,
                    'assigned_date'      => now(),
                    'remarks'            => $this->assignment_remarks,
                    'status_id'          => 5,
                    'record_type'        => 10,
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

                $exists = cpar_assignments::where('result_id', $this->result_id)
                    ->where('record_type', 10)
                    ->where('assigned_to', $employeeId)
                    ->exists();

                if ($exists) {
                    continue;
                }

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
        });

        $this->reset([
            'assigned_to',
            'new_assignees',
            'assignment_remarks',
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
        return view('livewire.admin.lab.result_re-assigned');
    }
}
