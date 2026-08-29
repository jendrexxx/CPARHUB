<?php

namespace App\Livewire\Admin\Hr;

use App\Models\cpar_assignments;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\employee;
use Illuminate\Support\Facades\Auth;

class HrMemoModal extends Component
{
    use WithFileUploads;
    public $id = '', $cpar_id = '', $cpar_no = '', $hr_decision_remarks = '', $nte_id = '', $current_memo_attachment = '';
    public $ir_id = '', $employee_name = '', $department_name = '', $date_open = '', $identified_cause = '', $current_nte_attachment = '';
    public $provided_solution = '', $recommendation = '', $head_remarks = '', $management_remarks = '', $current_ir_attachment = '';
    public $selectedCategories = [null], $selectedOffenseLevels = [''], $selectedHRDecisions = [''];
    public $reported_by = '', $action_taken_by = '', $date_completed = '', $tat = '', $ir_ids = '';
    public $memo_date = '', $memo_no = '', $memo_subject = '', $memo_content = '', $memo_attachment = '', $reported_by_department = '';
    public $status_name = '', $source_name = '', $complain_name = '', $concern_name = '', $concern_description = '', $complainant_name = '', $attachment = '';
    public $decisionCategories = [];
    public $disciplinaryCategories = [];
    public $offenseLevels = [];
    public $emp_reported = '', $employeeName = '', $user_id = '', $assigned_to = '';

    protected $listeners = [
        'open-memo-cpar' => 'open_memo'
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->user_id = $info->id;
        }
        $this->memo_no = $this->generateMemoNo();
        $this->memo_date = now()->format('m-d-Y');
    }

    private function generateMemoNo()
    {
        $year = now()->year;
        $lastMemoNo = DB::table('cpar_memos')
            ->where('memo_no', 'like', 'MEMO-' . $year . '-%')
            ->orderByDesc('id')
            ->value('memo_no');

        if ($lastMemoNo) {
            $lastNumber = (int) substr($lastMemoNo, -5);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'MEMO-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function open_memo($id = null)
    {
        $open_memo = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->join('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', 'i.id')
            ->join('cpar_investigations as j', 'b.id', '=', 'j.assigned_id')
            ->join('cpar_employee_disciplinary_records as k', 'b.id', '=', 'k.assignment_id')
            ->join('cpar_ir_requests as m', 'b.id', '=', 'm.assignment_ir_id')
            ->leftJoin('cpar_notice_to_explains as l', 'm.id', '=', 'l.assignment_id')
            ->leftJoin('cpar_nte_responses as n', 'l.id', '=', 'n.nte_id')
            ->leftJoin('cpar_memos as o', 'b.id', '=', 'o.assignment_id')
            ->leftJoin('employees as p', 'a.employee_no', '=', 'p.employee_no')
            ->select(
                // CPAR
                'a.id as cpar_id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'a.concern_description',
                'a.complainant_name',
                'a.employee_no as emp_reported',
                // Assignment
                'b.id as assignment_id',
                'b.assigned_to',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',
                'c.file_path',
                'd.source_name',
                'e.complain_name',
                'f.concern_name',
                // Employee
                'i.employee_no',
                'i.first_name',
                'i.last_name',
                'i.department_name as reported_department',
                // Department
                'g.department_name',
                // Status
                'h.status_name',
                // Investigation
                'j.identified_cause',
                'j.provided_solution',
                'j.recommendation',
                'j.action_taken_by',
                'j.date_completed',
                'j.tat',
                'j.remarks as head_remarks',
                // HR Decision
                'k.id as disciplinary_id',
                'k.discipline_ids',
                'k.offense_ids',
                'k.decision_ids',
                'k.status as decision_status',
                'k.remarks as decision_remarks',
                'k.management_remarks',
                // NTE
                'l.id as nte_id',
                'l.nte_no',
                'l.nte_attachment',
                // IR
                'm.id as ir_ids',
                'm.ir_id',
                'm.ir_attachment',
                'n.response_attachment',
                'o.memo_no',
                'o.memo_date',
                'o.subject',
                'o.memo_content',
                'o.memo_attachment',
                'p.department_name as reported_by_department'
            )
            ->where('b.id', $id)
            ->first();

        if (!$open_memo) {
            return;
        }
        $this->emp_reported = $open_memo->emp_reported;
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
        // Basic information
        $this->selectedCategories = json_decode($open_memo->discipline_ids ?? '[]', true) ?: [''];
        $this->selectedOffenseLevels = json_decode($open_memo->offense_ids ?? '[]', true) ?: [''];
        $this->selectedHRDecisions = json_decode($open_memo->decision_ids ?? '[]', true) ?: [''];
        $this->decisionCategories = DB::table('cpar_decision_categories')
            ->whereIn('id', $this->selectedHRDecisions)
            ->orderBy('id', 'asc')
            ->get();
        $this->disciplinaryCategories = DB::table('cpar_disciplinary_categories')
            ->whereIn('id', $this->selectedCategories)
            ->orderBy('id', 'asc')
            ->get();
        $this->offenseLevels = DB::table('cpar_offense_levels')
            ->whereIn('id', $this->selectedOffenseLevels)
            ->orderBy('id', 'asc')
            ->get();
        //memo details
        $this->assigned_to = $open_memo->assigned_to;
        $this->memo_no = $open_memo->memo_no ?? $this->memo_no;
        $this->memo_date = $open_memo->memo_date ?? now()->format('m-d-Y');
        $this->memo_subject = $open_memo->subject;
        $this->memo_content = $open_memo->memo_content;
        $this->current_memo_attachment = $open_memo->memo_attachment;
        $this->hr_decision_remarks = $open_memo->decision_remarks;
        $this->id = $open_memo->assignment_id;
        $this->ir_ids = $open_memo->ir_ids;
        $this->cpar_id = $open_memo->cpar_id;
        $this->cpar_no = $open_memo->cpar_no;
        $this->nte_id = $open_memo->nte_id;
        $this->ir_id = $open_memo->ir_id;
        $this->employee_name = trim($open_memo->first_name . ' ' . $open_memo->last_name);
        $this->department_name = $open_memo->department_name;
        $this->reported_by_department = $open_memo->reported_by_department;
        $this->date_open = $open_memo->date_open;
        // Investigation
        $this->identified_cause = $open_memo->identified_cause ?? '';
        $this->provided_solution = $open_memo->provided_solution ?? '';
        $this->recommendation = $open_memo->recommendation ?? '';
        $this->reported_by = $open_memo->reported_by;
        $this->status_name = $open_memo->status_name;
        $this->source_name = $open_memo->source_name;
        $this->complain_name = $open_memo->complain_name;
        $this->concern_name = $open_memo->concern_name;
        $this->concern_description = $open_memo->concern_description;
        $this->complainant_name = $open_memo->complainant_name;
        $this->attachment = $open_memo->file_path;
        $this->action_taken_by = $open_memo->action_taken_by;
        $this->date_completed = $open_memo->date_completed;
        $this->tat = $open_memo->tat;
        $this->head_remarks = $open_memo->head_remarks;
        $this->management_remarks = $open_memo->management_remarks;
        // IR Response 
        $this->current_ir_attachment = $open_memo->ir_attachment ?? '';
        // NTE Response
        $this->current_nte_attachment = $open_memo->response_attachment ?? '';
        // Open modal
        $this->modal('hr-memo-modal')->show();
    }

    private function validateMemo()
    {
        return $this->validate([
            'memo_no' => [
                'required',
                'string',
                'max:50',
            ],

            'memo_date' => [
                'required',
                'string',
            ],

            'memo_subject' => [
                'required',
                'string',
                'max:255',
            ],

            'memo_content' => [
                'required',
                'string',
            ],

            'memo_attachment' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
        ]);
    }

    public function saveMemoDraft()
    {
        DB::transaction(function () {

            $memoAttachment = $this->current_memo_attachment;

            if (
                $this->memo_attachment instanceof
                \Livewire\Features\SupportFileUploads\TemporaryUploadedFile
            ) {
                $memoAttachment = $this->memo_attachment->store(
                    'cpar/memos',
                    'public'
                );
            }

            $memo = DB::table('cpar_memos')
                ->where('assignment_id', $this->id)
                ->first();

            $oldValues = [
                'cpar_no'   => $this->cpar_no,
                'memo_no' => $memo?->memo_no,
                'memo_date' => $memo?->memo_date,
                'subject' => $memo?->subject,
                'memo_content' => $memo?->memo_content,
                'memo_attachment' => $memo?->memo_attachment,
                'status' => $memo?->status,
            ];

            $newValues = [
                'cpar_no'   => $this->cpar_no,
                'memo_no' => $this->memo_no,
                'memo_date' => $this->memo_date,
                'subject' => $this->memo_subject,
                'memo_content' => $this->memo_content,
                'memo_attachment' => $memoAttachment,
                'status' => 'DRAFT',
            ];

            $changedOldValues = [];
            $changedNewValues = [];

            foreach ($newValues as $field => $newValue) {

                $oldValue = $oldValues[$field] ?? null;

                if ($oldValue != $newValue) {
                    $changedOldValues[$field] = $oldValue;
                    $changedNewValues[$field] = $newValue;
                }
            }

            $data = [
                'cpar_id' => $this->cpar_id,
                'assignment_id' => $this->id,
                'memo_no' => $this->memo_no,
                'memo_date' => $this->memo_date,
                'subject' => $this->memo_subject,
                'memo_content' => $this->memo_content,
                'memo_attachment' => $memoAttachment,
                'status' => 'DRAFT',
                'updated_at' => now(),
            ];

            if ($memo) {

                DB::table('cpar_memos')
                    ->where('assignment_id', $this->id)
                    ->update($data);
            } else {

                $data['created_at'] = now();

                DB::table('cpar_memos')
                    ->insert($data);
            }

            $this->current_memo_attachment = $memoAttachment;
            $this->memo_attachment = null;

            if (!empty($changedNewValues)) {

                DB::table('audit_logs')->insert([
                    'user_reported_by'  => $this->employeeName,
                    'user_reported'     => $this->assigned_to,
                    'action' => 'MEMO DRAFT',
                    'old_value' => json_encode(
                        $changedOldValues,
                        JSON_UNESCAPED_UNICODE
                    ),
                    'new_value' => json_encode(
                        $changedNewValues,
                        JSON_UNESCAPED_UNICODE
                    ),
                    'status_changed_by' => $this->user_id,
                    'date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'Memo draft saved.'
        );

        $this->dispatch(
            'modal-close',
            name: 'hr-memo-modal'
        );

        $this->dispatch(
            'modal-close',
            name: 'HRMemoModal'
        );

        $this->dispatch('refreshMemoRecords');
        $this->dispatch('refreshMemoCount');
    }

    public function issueMemo()
    {
        $this->validateMemo();

        DB::transaction(function () {

            $oldMemo = DB::table('cpar_memos')
                ->where('assignment_id', $this->id)
                ->first();

            $oldAssignment = DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->first();

            $memoAttachment = $this->current_memo_attachment;

            if (
                $this->memo_attachment instanceof
                \Livewire\Features\SupportFileUploads\TemporaryUploadedFile
            ) {

                $memoAttachment = $this->memo_attachment->store(
                    'cpar/memos',
                    'public'
                );
            }

            $newMemoData = [
                'cpar_id'          => $this->cpar_id,
                'assignment_id'    => $this->id,
                'memo_no'          => $this->memo_no,
                'memo_date'        => $this->memo_date,
                'subject'          => $this->memo_subject,
                'memo_content'     => $this->memo_content,
                'memo_attachment'  => $memoAttachment,
                'status'           => 'ISSUED',
            ];

            if ($oldMemo) {

                DB::table('cpar_memos')
                    ->where('assignment_id', $this->id)
                    ->update([
                        'cpar_id'          => $this->cpar_id,
                        'assignment_id'    => $this->id,
                        'memo_no'          => $this->memo_no,
                        'memo_date'        => $this->memo_date,
                        'subject'          => $this->memo_subject,
                        'memo_content'     => $this->memo_content,
                        'memo_attachment'  => $memoAttachment,
                        'status'           => 'ISSUED',
                        'updated_at'       => now(),
                    ]);
            } else {

                DB::table('cpar_memos')
                    ->insert([
                        'cpar_id'          => $this->cpar_id,
                        'assignment_id'    => $this->id,
                        'memo_no'          => $this->memo_no,
                        'memo_date'        => $this->memo_date,
                        'subject'          => $this->memo_subject,
                        'memo_content'     => $this->memo_content,
                        'memo_attachment'  => $memoAttachment,
                        'status'           => 'ISSUED',
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
            }

            cpar_assignments::where('id', $this->id)
                ->update([
                    'status_id' => 50,
                ]);

            $oldValues = [
                'cpar_no'           => $this->cpar_no,
                'memo_no'          => $oldMemo?->memo_no,
                'memo_date'        => $oldMemo?->memo_date,
                'subject'          => $oldMemo?->subject,
                'memo_content'     => $oldMemo?->memo_content,
                'memo_attachment'  => $oldMemo?->memo_attachment,
                'memo_status'      => $oldMemo?->status,
                'status_id'        => $oldAssignment?->status_id,
            ];

            $newValues = [
                'cpar_no'          => $this->cpar_no,
                'assigned_id'      => $this->id,
                'memo_no'          => $this->memo_no,
                'memo_date'        => $this->memo_date,
                'subject'          => $this->memo_subject,
                'memo_content'     => $this->memo_content,
                'memo_attachment'  => $memoAttachment,
                'memo_status'      => 'ISSUED',
                'status_id'        => 50,
            ];

            $changedOldValues = [];
            $changedNewValues = [];

            foreach ($newValues as $field => $newValue) {

                $oldValue = $oldValues[$field] ?? null;

                if ($oldValue instanceof \Carbon\Carbon) {
                    $oldValue = $oldValue->format('Y-m-d H:i:s');
                }

                if ($newValue instanceof \Carbon\Carbon) {
                    $newValue = $newValue->format('Y-m-d H:i:s');
                }

                $oldValue = $oldValue === null
                    ? null
                    : (string) $oldValue;

                $newValue = $newValue === null
                    ? null
                    : (string) $newValue;

                if ($oldValue !== $newValue) {

                    $changedOldValues[$field] = $oldValue;
                    $changedNewValues[$field] = $newValue;
                }
            }
            DB::table('audit_logs')->insert([
                'user_reported_by'  => $this->employeeName,
                'user_reported'     => $this->assigned_to,
                'action'     => 'MEMO ISSUED',
                'old_value'  => json_encode(
                    $changedOldValues,
                    JSON_UNESCAPED_UNICODE
                ),
                'new_value'  => json_encode(
                    $changedNewValues,
                    JSON_UNESCAPED_UNICODE
                ),
                'status_changed_by' => $this->user_id,
                'date'       => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->current_memo_attachment = $memoAttachment;
            $this->memo_attachment = null;
        });
        $this->dispatch(
            'toast',
            type: 'success',
            message: 'Memo has been issued successfully.'
        );
        $this->dispatch(
            'modal-close',
            name: 'hr-memo-modal'
        );

        $this->dispatch(
            'modal-close',
            name: 'HRMemoModal'
        );
        $this->dispatch('refreshMemoRecords');
        $this->dispatch('refreshMemoCount');
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_memo_modal');
    }
}
