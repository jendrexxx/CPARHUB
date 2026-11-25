<?php

namespace App\Livewire\Admin\Dashboard\Modal;

use Livewire\Component;
use App\Models\employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\result_error_data_informations;
use App\Models\result_error_quality_accuracies;
use App\Models\result_error_technical_equipments;
use App\Models\cpar_assignments;

class Assigned extends Component
{
    protected $listeners = [
        'open-assign-concern' => 'open_concern_modal',
    ];

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
    public $assigned = '', $assigned_head = '', $status_name = '', $result_id = '', $existing_attachment = '';


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
        $this->employees = employee::where('branch_id', $this->branch_id)
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

    public function open_concern_modal($id)
    {
        if (!$id) {
            return;
        }

        $result_assigned = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('cpar_attachments as e', 'd.result_id', '=', 'e.result_id')
            ->join('cpar_statuses as h', 'd.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'd.dept_head_assigned', '=', 'i.id')
            ->join('priority_levels as m', 'a.priority_level', '=', 'm.id')
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
                'e.file_path',
                'h.status_name',
                'i.branch_id',
                'i.first_name',
                'i.last_name',
                'm.priority_name'
            )
            ->where('d.result_id', $id)
            ->first();
        if (!$result_assigned) {
            return;
        }
        $this->existing_attachment = $result_assigned->file_path;
        $this->assignment_id = $result_assigned->assignment_id;
        $this->cpar_id = $result_assigned->result_id;
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
            : json_decode(
                $result_assigned->data_information ?? '[]',
                true
            );
        $this->technical_information = is_array($result_assigned->technical_information)
            ? $result_assigned->technical_information
            : json_decode(
                $result_assigned->technical_information ?? '[]',
                true
            );
        $this->quality_information = is_array($result_assigned->quality_information)
            ? $result_assigned->quality_information
            : json_decode(
                $result_assigned->quality_information ?? '[]',
                true
            );
        $this->complain_name = $result_assigned->complain_name;
        $this->complainant_name = $result_assigned->complainant_name;
        $this->concern_description = $result_assigned->concern_description;
        $this->priority = $result_assigned->priority_name;
        $this->result_id = $result_assigned->result_id;
        $assignments = DB::table('cpar_assignments as b')
            ->leftJoin(
                'cpar_statuses as h',
                'b.status_id',
                '=',
                'h.id'
            )
            ->select(
                'b.id',
                'b.result_id',
                'b.assigned_to',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',
                'h.status_name'
            )
            ->where('b.result_id', $this->cpar_id)
            ->where('b.record_type', 10)
            ->orderBy('b.id')
            ->get();
        if ($assignments->count() > 0) {
            $firstAssignment = $assignments->first();
            // Main assignment
            $this->assignment_id = $firstAssignment->id;
            $this->assigned = $firstAssignment->assigned_to;
            $this->assigned_to = $firstAssignment->assigned_to;
            $this->assigned_head = $firstAssignment->assigned_to;
            $this->dept_head_assigned = $firstAssignment->dept_head_assigned;
            $this->remarks = $firstAssignment->remarks;
            $this->assignment_remarks = $firstAssignment->remarks;
            $this->status_name = $firstAssignment->status_name;
            $this->status = $firstAssignment->status_name;
            $this->new_assignees = $assignments
                ->skip(1)
                ->pluck('assigned_to')
                ->map(fn($id) => (string) $id)
                ->values()
                ->toArray();
        } else {
            $this->assignment_id = null;
            $this->assigned = '';
            $this->assigned_to = '';
            $this->assigned_head = '';
            $this->dept_head_assigned = '';
            $this->remarks = '';
            $this->assignment_remarks = '';
            $this->status_name = '';
            $this->status = '';
            $this->new_assignees = [];
        }
        $this->department_name = null;
        if ($result_assigned->department_id) {
            $this->department_name = DB::table('departments')
                ->where('id', $result_assigned->department_id)
                ->value('department_name');
        }
        $this->modal('admin-reassign-result')->show();
    }

    public function reassignResult()
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

        $this->dispatch('modal-close', name: 'admin-reassign-result');
        $this->dispatch('modal-close', name: 'AssignedConcernModal');
        $this->dispatch('refreshAdminAssigned');
        $this->dispatch('refreshAssignedCount');
    }

    public function render()
    {
        return view('livewire.admin.dashboard.modal.assigned');
    }
}
