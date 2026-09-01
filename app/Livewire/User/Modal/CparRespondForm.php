<?php

namespace App\Livewire\User\Modal;

use App\Models\cpar_assignments;
use App\Models\cpar_investigations;
use App\Models\cpar_request_forms;
use App\Models\employee;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class CparRespondForm extends Component
{
    use WithFileUploads;
    public $cpar_no = '', $date_opened = '', $date_open = '';
    public $action_taken_by = '';
    public $date_completed = '';
    public $tat = '';
    public $remarks = '';
    public $selectedCparId = '';
    public $id = '';
    public $status = '';
    public $employee_no = '';
    public $assigned = '', $assigned_to = '', $branch_id = '', $dept_head_assigned = '', $department_id = '';
    public $attachment = '', $emp_reported = '', $employeeName = '';
    public $reported_by = '', $ir_id = '', $cpar_id = '';
    public $priority = '', $identified_cause = '', $provided_solution = '', $recommendation = '';
    public $assigned_name = '', $dept_head_department_name = '', $employee_name = '';
    public $ir_attachment = '', $existing_ir_attachment = '';
    public $department_name = '', $status_name = '', $source_name = '', $complain_name = '', $concern_name = '', $concern_description = '', $complainant_name = '';
    protected $listeners = [
        'respond-CPAR' => 'respondCPAR',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
            $this->employee_name = trim($info->first_name . ' ' . $info->last_name);
        }
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
        $this->action_taken_by = $this->employee_name;
        $this->ir_id = 'IR-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function respondCPAR($id = '')
    {
        $cpar = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->leftJoin('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->leftJoin('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->leftJoin('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->leftJoin('cpar_investigations as k', 'b.id', '=', 'k.assigned_id')
            ->join('priority_levels as m', 'a.priority_level', '=', 'm.id')
            ->leftJoin('cpar_ir_requests as n', 'b.id', '=', 'n.assignment_ir_id')
            ->select(
                'a.reported_by',
                'a.date_open',
                'a.cpar_no',
                'a.date_open',
                'a.employee_no as emp_reported',
                'a.concern_description',
                'a.complainant_name',
                'b.id',
                'b.cpar_id',
                'b.dept_head_assigned',
                'b.assigned_to',
                'b.remarks',
                'c.file_path',
                'g.department_name',
                'h.status_name',
                'd.source_name',
                'e.complain_name',
                'f.concern_name',
                'i.branch_id',
                'i.first_name',
                'i.last_name',
                'i.department_id',
                'k.identified_cause',
                'k.provided_solution',
                'k.recommendation',
                'k.action_taken_by',
                'm.priority_name',
                'n.ir_attachment'
            )
            ->where('b.id', $id)
            ->first();

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
        // assign data sa modal fields
        $this->existing_ir_attachment = $cpar->ir_attachment;
        $this->id = $cpar->id;
        $this->cpar_no = $cpar->cpar_no;
        $this->date_open = $cpar->date_open;
        $this->selectedCparId = $cpar->id;
        $this->date_completed = now()->format('m-d-Y');
        $this->date_opened = Carbon::parse($cpar->date_open)->format('m-d-Y');
        $this->branch_id = $cpar->branch_id ?? null;
        $this->dept_head_assigned = $cpar->dept_head_assigned;
        $this->department_id = $cpar->department_id;
        $this->remarks = $cpar->remarks;
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
        $this->identified_cause = $cpar->identified_cause;
        $this->provided_solution = $cpar->provided_solution;
        $this->recommendation = $cpar->recommendation;
        $this->cpar_id = $cpar->cpar_id;
        $this->assigned_to = $cpar->assigned_to;
        $this->calculateTat();
        if (!$cpar) {
            return;
        }

        $this->modal('respond-cpar')->show();
    }

    public function calculateTat()
    {
        if (!$this->date_opened || !$this->date_completed) {
            $this->tat = '';
            return;
        }

        $days = Carbon::createFromFormat('m-d-Y', $this->date_opened)
            ->startOfDay()
            ->diffInDays(
                Carbon::createFromFormat('m-d-Y', $this->date_completed)
                    ->startOfDay()
            );

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
                'cpar_no'           => $this->cpar_no,
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
                'cpar_no'           => $this->cpar_no,
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
                'action'            => 'CPAR RESPONSE SUBMITTED',
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

        $this->reset([
            'identified_cause',
            'provided_solution',
            'recommendation',
            'action_taken_by',
            'date_completed',
            'tat',
            'remarks',
            'ir_attachment',
        ]);

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'CPAR response submitted successfully.'
        );

        $this->dispatch(
            'modal-close',
            name: 'respond-cpar'
        );

        $this->dispatch(
            'modal-close',
            name: 'CPARAssignedModal'
        );

        $this->dispatch('refreshAssignedData');
        $this->dispatch('refreshAssignedCount');
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

            $irAttachmentPath = null;

            if ($this->ir_attachment) {
                $irAttachmentPath = $this->ir_attachment->store(
                    'cpar/ir',
                    'public'
                );
            }

            /*
        |--------------------------------------------------------------------------
        | SAVE INVESTIGATION
        |--------------------------------------------------------------------------
        */

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

            /*
        |--------------------------------------------------------------------------
        | SAVE IR REQUEST
        |--------------------------------------------------------------------------
        */

            if ($oldIR) {

                $updateData = [
                    'ir_id'       => $this->ir_id,
                    'employee_no' => $this->employee_no,
                    'updated_at'  => now(),
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
                    'issued_at'        => null,
                    'due_date'         => null,
                    'status'           => 'DRAFT',
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                $newAttachmentPath = $irAttachmentPath;
            }

            /*
        |--------------------------------------------------------------------------
        | UPDATE ASSIGNMENT
        |--------------------------------------------------------------------------
        */

            cpar_assignments::where('id', $this->id)
                ->update([
                    'employee_no' => $this->employee_no,
                    'status_id'   => 10,
                    'updated_at'  => now(),
                ]);

            /*
        |--------------------------------------------------------------------------
        | AUDIT LOG - ONLY CHANGED VALUES
        |--------------------------------------------------------------------------
        */

            $oldData = [
                'identified_cause'  => $oldInvestigation?->identified_cause,
                'provided_solution' => $oldInvestigation?->provided_solution,
                'recommendation'    => $oldInvestigation?->recommendation,
                'action_taken_by'   => $oldInvestigation?->action_taken_by,
                'date_completed'    => $oldInvestigation?->date_completed,
                'tat'               => $oldInvestigation?->tat,

                'ir_id'             => $oldIR?->ir_id,
                'employee_no'      => $oldIR?->employee_no,
                'ir_attachment'    => $oldIR?->ir_attachment,
                'ir_status'        => $oldIR?->status,

                'status_id'        => $oldAssignment?->status_id,
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

            /*
        |--------------------------------------------------------------------------
        | INSERT AUDIT LOG ONLY IF SOMETHING CHANGED
        |--------------------------------------------------------------------------
        */

            if (!empty($changedNewValue)) {

                DB::table('audit_logs')->insert([
                    'user' => auth()->id(),

                    'action' => 'CPAR RESPONSE DRAFT',

                    'old_value' => json_encode(
                        $changedOldValue,
                        JSON_UNESCAPED_UNICODE
                    ),

                    'new_value' => json_encode(
                        $changedNewValue,
                        JSON_UNESCAPED_UNICODE
                    ),

                    'date'       => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'CPAR response saved as draft.'
        );

        $this->dispatch(
            'modal-close',
            name: 'respond-cpar'
        );

        $this->dispatch('refreshAssignedData');
        $this->dispatch('refreshAssignedCount');
    }

    public function render()
    {
        return view('livewire.user.modal.cpar_respond_form');
    }
}
