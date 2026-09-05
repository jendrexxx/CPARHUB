<?php

namespace App\Livewire\User\Modal;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use App\Models\result_error_data_informations;
use App\Models\result_error_quality_accuracies;
use App\Models\result_error_technical_equipments;
use Carbon\Carbon;
use App\Models\cpar_assignments;
use App\Models\cpar_investigations;
use Livewire\WithFileUploads;

class ResultRespondForm extends Component
{
    use WithFileUploads;
    public $resultResponse = [];
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
    public $existing_ir_attachment = '', $employee_assigned_to = '', $employee_name = '';
    public $date_completed = '', $ir_id = '', $tat = '', $employeeName = '', $assigned_employee_no = '';
    public $identified_cause = '', $provided_solution = '', $recommendation = '', $ir_attachment = '';
    public $action_taken_by ='';

    protected $listeners = [
        'respond-Result' => 'open_modal',
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
            $this->employee_name = trim($info->first_name . ' ' . $info->last_name);
        }
        $this->action_taken_by = $this->employee_name;
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
        $this->date_completed = now()->format('m-d-Y');
        $year = now()->year;
        $lastNte = DB::table('cpar_ir_requests')
            ->whereYear('created_at', $year)
            ->orderByDesc('id')
            ->value('ir_id');

        if (empty($lastNte)) {
            $nextNumber = 1;
        } else {
            $lastNumber = (int) substr($lastNte, -5);
            $nextNumber = $lastNumber + 1;
        }
        $this->ir_id = 'IR-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function open_modal($id = null)
    {
        $result_assigned = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('cpar_statuses as e', 'd.status_id', '=', 'e.id')
            ->leftJoin('employees as f', 'd.dept_head_assigned', '=', 'f.id')
            ->join('priority_levels as g', 'a.priority_level', '=', 'g.id')
            ->leftjoin('employees as h', 'd.assigned_to', '=', 'h.id')
            ->leftjoin('cpar_investigations as i', 'd.id', '=', 'i.assigned_id')
            ->leftJoin('cpar_ir_requests as j', 'd.id', '=', 'j.assignment_ir_id')
            ->select(
                'a.result_no',
                'a.reported_by',
                'a.employee_no',
                'a.date_reported',
                'a.test_procedure',
                'a.quality_information',
                'a.actual_released_date',
                'a.data_information',
                'a.technical_information',
                'a.quality_information',
                'a.patient_name',
                'a.complain_name as complainant_name',
                'a.attending_physician',
                'a.technical_information',
                'a.concern_description',
                'b.source_name',
                'c.complain_name',
                'd.id',
                'd.cpar_id',
                'd.assigned_to',
                'd.remarks',
                'd.dept_head_assigned',
                'd.department_id',
                'e.status_name',
                'f.branch_id',
                'f.department_name',
                'f.first_name',
                'f.last_name',
                'g.priority_name',
                DB::raw("CONCAT(h.first_name, ' ', h.last_name) AS employee_assigned_to"),
                'h.employee_no as employee_no_assigned',
                'i.identified_cause',
                'i.provided_solution',
                'i.recommendation',
                'j.ir_id',
                'j.ir_attachment'
            )
            ->where('d.id', $id)
            ->first();
        if (!$result_assigned) {
            return;
        }
        $this->assigned_employee_no = $result_assigned->employee_no;
        $cpar_info = DB::table('employees as a')
            ->join('cpar_request_forms as b', 'a.employee_no', '=', 'b.employee_no')
            ->where('b.employee_no', $this->assigned_employee_no)
            ->select(
                'a.employee_no',
                'a.first_name',
                'a.last_name'
            )
            ->first();

        if ($cpar_info) {
            $this->employeeName = trim($cpar_info->first_name . ' ' . $cpar_info->last_name);
        }
        $this->existing_ir_attachment = $result_assigned->ir_attachment;
        $this->id = $result_assigned->id;
        $this->employee_assigned_to = $result_assigned->employee_assigned_to;
        $this->cpar_id   = $result_assigned->cpar_id;
        $this->result_no = $result_assigned->result_no;
        $this->date_reported = Carbon::parse($result_assigned->date_reported)->format('m-d-Y');
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
        $this->assigned_to = $result_assigned->assigned_to;
        $this->assignment_remarks = $result_assigned->remarks;
        $this->status = $result_assigned->status_name;
        $this->dept_head_assigned = $result_assigned->dept_head_assigned;
        $this->identified_cause = $result_assigned->identified_cause;
        $this->provided_solution = $result_assigned->provided_solution;
        $this->recommendation = $result_assigned->recommendation;
        $this->ir_id = $result_assigned->ir_id;
        $this->calculateTat();
        $this->modal('respond-result')->show();
    }

    public function calculateTat()
    {
        if (!$this->date_reported || !$this->date_completed) {
            $this->tat = '';
            return;
        }

        $days = Carbon::createFromFormat('m-d-Y', $this->date_reported)
            ->startOfDay()
            ->diffInDays(Carbon::createFromFormat('m-d-Y', $this->date_completed)
                ->startOfDay());

        $this->tat = "{$days} day(s)";
    }

    public function saveResponse()
    {
        $this->validate([
            'identified_cause'  => 'required',
            'provided_solution' => 'required',
            'recommendation'    => 'required',
            'date_completed'    => 'required',
            'tat'               => 'required',
            'ir_attachment'     => empty($this->existing_ir_attachment)
                ? 'required|file|mimes:pdf|max:10240'
                : 'nullable|file|mimes:pdf|max:10240',
        ]);

        DB::transaction(function () {

            $oldInvestigation = DB::table('cpar_investigations')
                ->where('assigned_id', $this->id)
                ->first();
            $oldAssignment = DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->first();
            $oldIR = DB::table('cpar_ir_requests')
                ->where('assignment_ir_id', $this->id)
                ->first();

            $irAttachmentPath = null;

            if ($this->ir_attachment) {

                $irAttachmentPath = $this->ir_attachment->store(
                    'cpar/ir',
                    'public'
                );
            }

            cpar_investigations::updateOrCreate(
                [
                    'assigned_id' => $this->id,
                ],
                [
                    'identified_cause'  => $this->identified_cause,
                    'provided_solution' => $this->provided_solution,
                    'recommendation'    => $this->recommendation,
                    'action_taken_by'   => $this->action_taken_by,
                    'date_completed'    => $this->date_completed,
                    'tat'               => $this->tat,
                ]
            );

            cpar_assignments::where('id', $this->id)
                ->update([
                    'employee_no' => $this->employee_no,
                    'status_id'   => 15,
                ]);

            if ($oldIR) {

                $updateData = [
                    'ir_id'         => $this->ir_id,
                    'employee_no'   => $this->employee_no,
                    'submitted_at'  => null,
                    'issued_at'     => now(),
                    'due_date'      => now()->addDay(),
                    'status'        => 'IR SUBMITTED',
                    'updated_at'    => now(),
                ];

                if ($irAttachmentPath) {
                    $updateData['ir_attachment'] = $irAttachmentPath;
                }

                DB::table('cpar_ir_requests')
                    ->where('id', $oldIR->id)
                    ->update($updateData);

                $newAttachmentPath = $irAttachmentPath
                    ?? $oldIR->ir_attachment;
            } else {

                DB::table('cpar_ir_requests')->insert([
                    'assignment_ir_id' => $this->id,
                    'ir_id'            => $this->ir_id,
                    'employee_no'      => $this->employee_no,
                    'ir_attachment'    => $irAttachmentPath,
                    'submitted_at'     => null,
                    'issued_at'        => now(),
                    'due_date'         => now()->addDay(),
                    'status'           => 'IR SUBMITTED',
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                $newAttachmentPath = $irAttachmentPath;
            }

            $oldValue = [
                'result_no'           => $this->result_no,
                'identified_cause'  => $oldInvestigation?->identified_cause,
                'provided_solution' => $oldInvestigation?->provided_solution,
                'recommendation'    => $oldInvestigation?->recommendation,
                'action_taken_by'   => $oldInvestigation?->action_taken_by,
                'date_completed'    => $oldInvestigation?->date_completed,
                'tat'               => $oldInvestigation?->tat,
                'ir_id'             => $oldIR?->ir_id,
                'employee_no'       => $oldIR?->employee_no,
                'ir_attachment'     => $oldIR?->ir_attachment,
                'submitted_at'      => $oldIR?->submitted_at,
                'issued_at'         => $oldIR?->issued_at,
                'due_date'          => $oldIR?->due_date,
                'ir_status'         => $oldIR?->status,
                'status_id'         => $oldAssignment?->status_id,
            ];

            $newValue = [
                'result_no'         => $this->result_no,
                'identified_cause'  => $this->identified_cause,
                'provided_solution' => $this->provided_solution,
                'recommendation'    => $this->recommendation,
                'action_taken_by'   => $this->action_taken_by,
                'date_completed'    => $this->date_completed,
                'tat'               => $this->tat,
                'ir_id'             => $this->ir_id,
                'employee_no'       => $this->employee_no,
                'ir_attachment'     => $newAttachmentPath,
                'submitted_at'      => null,
                'issued_at'         => now(),
                'due_date'          => now()->addDay(),
                'ir_status'         => 'IR SUBMITTED',
                'status_id'         => 15,
            ];

            $changedOldValue = [];
            $changedNewValue = [];

            foreach ($newValue as $field => $newFieldValue) {

                $oldFieldValue = $oldValue[$field] ?? null;

                if ($oldFieldValue instanceof \Carbon\Carbon) {
                    $oldFieldValue = $oldFieldValue->format('Y-m-d H:i:s');
                }

                if ($newFieldValue instanceof \Carbon\Carbon) {
                    $newFieldValue = $newFieldValue->format('Y-m-d H:i:s');
                }

                if ((string) $oldFieldValue !== (string) $newFieldValue) {

                    $changedOldValue[$field] = $oldFieldValue;
                    $changedNewValue[$field] = $newFieldValue;
                }
            }

            DB::table('audit_logs')->insert([
                'user_reported_by'  => $this->employeeName,
                'user_reported'     => $this->assigned_to,
                'action'            => 'RESULT RESPONSE SUBMITTED',
                'old_value'         => $changedOldValue
                    ? json_encode($changedOldValue, JSON_UNESCAPED_UNICODE)
                    : null,
                'new_value'         => $changedNewValue
                    ? json_encode($changedNewValue, JSON_UNESCAPED_UNICODE)
                    : null,
                'status_changed_by' => $this->assigned_to,
                'date'              => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        });
        $this->reset(['identified_cause','provided_solution','recommendation','action_taken_by','date_completed','tat','remarks','ir_attachment',]);
        $this->dispatch('toast',type: 'success',message: 'RESULT response submitted successfully.');
        $this->dispatch('modal-close',name: 'respond-result');
        $this->dispatch('modal-close',name: 'AssignedModal');
        $this->dispatch('refreshData');
        $this->dispatch('refreshResultCount');
    }

    public function saveDraft()
    {
        $this->validate([
            'identified_cause'  => 'nullable',
            'provided_solution' => 'nullable',
            'recommendation'    => 'nullable',
            'date_completed'    => 'nullable',
            'tat'               => 'nullable',
            'ir_attachment'     => 'nullable|file|mimes:pdf|max:10240',
        ]);

        DB::transaction(function () {

            $oldInvestigation = DB::table('cpar_investigations')
                ->where('assigned_id', $this->id)
                ->first();

            $oldAssignment = DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->first();

            $oldIR = DB::table('cpar_ir_requests')
                ->where('assignment_ir_id', $this->id)
                ->first();

            $newAttachmentPath = $oldIR?->ir_attachment;

            if ($this->ir_attachment) {

                // Store new attachment
                $newAttachmentPath = $this->ir_attachment->store(
                    'result/ir',
                    'public'
                );

                if (
                    $oldIR?->ir_attachment &&
                    \Storage::disk('public')->exists($oldIR->ir_attachment)
                ) {
                    \Storage::disk('public')->delete(
                        $oldIR->ir_attachment
                    );
                }
            }

            cpar_investigations::updateOrCreate(
                [
                    'assigned_id' => $this->id,
                ],
                [
                    'identified_cause'  => $this->identified_cause ?: null,
                    'provided_solution' => $this->provided_solution ?: null,
                    'recommendation'    => $this->recommendation ?: null,
                    'action_taken_by'   => $this->employee_name,
                    'date_completed'    => $this->date_completed ?: null,
                    'tat'               => $this->tat ?: null,
                ]
            );

            if ($oldIR) {
                // Existing IR record → UPDATE
                DB::table('cpar_ir_requests')
                    ->where('id', $oldIR->id)
                    ->update([
                        'employee_no'   => $this->employee_no,
                        'ir_attachment' => $newAttachmentPath,
                        'updated_at'    => now(),
                    ]);
            } else {
                // No existing IR record → INSERT
                DB::table('cpar_ir_requests')
                    ->insert([
                        'assignment_ir_id' => $this->id,
                        'ir_id'            => $this->ir_id,
                        'employee_no'      => $this->employee_no,
                        'ir_attachment'    => $newAttachmentPath,
                        'submitted_at'     => null,
                        'issued_at'        => null,
                        'due_date'         => null,
                        'status'           => 'DRAFT',
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
            }

            cpar_assignments::where('id', $this->id)
                ->update([
                    'employee_no' => $this->employee_no,
                    'status_id'   => 10,
                    'updated_at'  => now(),
                ]);

            $oldData = [
                'identified_cause'  => $oldInvestigation?->identified_cause,
                'provided_solution' => $oldInvestigation?->provided_solution,
                'recommendation'    => $oldInvestigation?->recommendation,
                'action_taken_by'   => $oldInvestigation?->action_taken_by,
                'date_completed'    => $oldInvestigation?->date_completed,
                'tat'               => $oldInvestigation?->tat,
                'ir_id'             => $this->ir_id,
                'employee_no'       => $oldIR?->employee_no,
                'ir_attachment'     => $oldIR?->ir_attachment,
                'ir_status'         => $oldIR?->status,
                'status_id'         => $oldAssignment?->status_id,
            ];

            $newData = [
                'identified_cause'  => $this->identified_cause ?: null,
                'provided_solution' => $this->provided_solution ?: null,
                'recommendation'    => $this->recommendation ?: null,
                'action_taken_by'   => $this->employee_name,
                'date_completed'    => $this->date_completed ?: null,
                'tat'               => $this->tat ?: null,
                'ir_id'             => $this->ir_id,
                'employee_no'       => $this->employee_no,
                'ir_attachment'     => $newAttachmentPath,
                'ir_status'         => 'DRAFT',
                'status_id'         => 10,
            ];

            $changedOldValue = [];
            $changedNewValue = [];

            foreach ($newData as $field => $newValue) {

                $oldValue = $oldData[$field] ?? null;

                if ($oldValue instanceof \Carbon\Carbon) {
                    $oldValue = $oldValue->format('Y-m-d H:i:s');
                }

                if ($newValue instanceof \Carbon\Carbon) {
                    $newValue = $newValue->format('Y-m-d H:i:s');
                }

                if ((string) $oldValue !== (string) $newValue) {

                    $changedOldValue[$field] = $oldValue;
                    $changedNewValue[$field] = $newValue;
                }
            }

            if (!empty($changedNewValue)) {
                DB::table('audit_logs')->insert([
                    'user_reported_by' => $this->employeeName,
                    'user_reported' => $this->assigned_to,
                    'action' => 'RESULT RESPONSE DRAFT',
                    'old_value' => json_encode(
                        $changedOldValue,
                        JSON_UNESCAPED_UNICODE
                    ),
                    'new_value' => json_encode(
                        $changedNewValue,
                        JSON_UNESCAPED_UNICODE
                    ),
                    'status_changed_by' => $this->assigned_to,
                    'date'       => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        $this->dispatch('toast',type: 'success',message: 'Result Successfully Saved as Draft.');
        $this->dispatch('modal-close',name: 'respond-result');
        $this->dispatch('refreshData');
        $this->dispatch('refreshResultCount');
    }

    public function render()
    {
        return view('livewire.user.modal.result_respond_form');
    }
}
