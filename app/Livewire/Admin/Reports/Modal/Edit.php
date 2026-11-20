<?php

namespace App\Livewire\Admin\Reports\Modal;

use App\Livewire\Admin\Hr\HrOffenseTbl;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\employee;
use Illuminate\Support\Facades\Auth;
use App\Models\cpar_complain_categories;
use App\Models\cpar_concern_categories;
use App\Models\cpar_decision_categories;
use App\Models\cpar_disciplinary_categories;
use App\Models\cpar_employee_disciplinary_records;
use App\Models\cpar_hr_decision;
use App\Models\cpar_offense_levels;
use App\Models\priority_level;
use App\Models\cpar_source_origins;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;
    public $id = '', $cpar_id = '', $cpar_no = '', $hr_decision_remarks = '', $nte_id = '', $current_memo_attachment = '';
    public $ir_id = '', $employee_name = '', $department_name = '', $date_open = '', $identified_cause = '', $current_nte_attachment = '';
    public $provided_solution = '', $recommendation = '', $head_remarks = '', $management_remarks = '', $current_ir_attachment = '';
    public $selectedCategories = [null], $selectedOffenseLevels = [''], $selectedHRDecisions = [''];
    public $reported_by = '', $action_taken_by = '', $date_completed = '', $tat = '', $ir_ids = '';
    public $memo_date = '', $memo_no = '', $memo_subject = '', $memo_content = '', $memo_attachment = '', $reported_by_department = '';
    public $status_name = '', $source_name = '', $complain_name = '', $concern_name = '', $concern_description = '', $complainant_name = '', $attachment = '';
    public $decisionCategories = [], $disciplinaryCategories = [], $offenseLevels = [];
    public $emp_reported = '', $employeeName = '', $user_id = '', $assigned_to = '', $employees = '', $priority = '', $complain_name_disabled = '';
    public $source_origin = [], $cpar_complain = [], $cpar_concern = [], $priority_level = [], $branch = [], $decision = [], $status = [];
    public $branch_id = '1005', $employee_id = '', $status_id = '', $investigation_id = '', $assigned_remarks = '', $nte_no = '';
    public $year = '', $assignment_ir_id = '', $response_attachment = '', $ir_attachment = '', $assigned_employee_no = '', $concern_attachment = '', $existing_concern_attachment = '';
    public $existing_ir_attachment = '';
    public $statuses = [];
    public $nte_attachment = '';
    public $isNoDisciplinaryAction = [];

    protected $listeners = [
        'open-master-file' => 'open',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->user_id = $info->id;
        }
        $this->employees = Employee::query()
            ->Where('branch_id', $this->branch_id)
            ->orderBy('first_name')
            ->get()
            ->map(function ($employee) {
                $employee->full_name = trim(
                    $employee->first_name . ' ' . $employee->last_name
                );

                return $employee;
            });
        $this->date_completed = now()->format('m-d-Y');
        $this->source_origin = cpar_source_origins::select('id', 'source_name')->get();
        $this->cpar_complain = cpar_complain_categories::select('id', 'complain_name')->get();
        $this->cpar_concern = cpar_concern_categories::select('id', 'concern_name')->get();
        $this->priority_level = priority_level::select('id', 'priority_name')->get();
        $this->decisionCategories = cpar_decision_categories::all();
        $this->disciplinaryCategories = cpar_disciplinary_categories::all();
        $this->offenseLevels = cpar_offense_levels::all();
        $this->statuses = DB::table('cpar_statuses')
            ->select('id', 'status_name')
            ->orderBy('id')
            ->get();
        $this->memo_no = $this->generateMemoNo();
        $this->memo_date = now()->format('m-d-Y');
        $this->calculateTAT();
        $this->branch = DB::table('branches')
            ->select('id', 'branch_name')
            ->get();
        $this->branch_id = DB::table('branches')
            ->where('branch_name', 'QUEZON CITY')
            ->value('id');
        $this->year = now()->year;
        $lastIR = DB::table('cpar_ir_requests')
            ->value('ir_id');
        if (empty($lastIR)) {
            $nextNumber = 1;
        } else {
            $lastNumber = (int) substr($lastIR, -5);
            $nextNumber = $lastNumber + 1;
        }

        $this->ir_id = 'IR-' . $this->year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function updatedSelectedHRDecisions($value, $key)
    {
        $key = (int) $key;

        $decision = collect($this->decisionCategories)
            ->firstWhere('id', $value);

        $decisionName = strtoupper(trim($decision->decision_name ?? ''));

        if ($decisionName === 'NO DISCIPLINARY ACTION') {

            $this->isNoDisciplinaryAction[$key] = true;

            // Get N/A Category
            $naCategory = collect($this->disciplinaryCategories)
                ->first(function ($item) {
                    return strtoupper(trim($item->category_name)) === 'N/A';
                });

            // Get N/A Offense
            $naOffense = collect($this->offenseLevels)
                ->first(function ($item) {
                    return strtoupper(trim($item->offense_name)) === 'N/A';
                });

            // Update only the selected row
            $this->selectedCategories[$key] = $naCategory?->id ?? '';
            $this->selectedOffenseLevels[$key] = $naOffense?->id ?? '';
        } else {

            $this->isNoDisciplinaryAction[$key] = false;
            // Reset only the selected row
            $this->selectedCategories[$key] = '';
            $this->selectedOffenseLevels[$key] = '';
        }
        $this->resetErrorBag('rows');
    }

    public function addCategory()
    {
        $last = count($this->selectedCategories) - 1;

        if (
            empty($this->selectedCategories[$last]) ||
            empty($this->selectedOffenseLevels[$last]) ||
            empty($this->selectedHRDecisions[$last])
        ) {
            $this->addError(
                'rows',
                'Please complete the current row before adding another.'
            );

            return;
        }

        $this->resetErrorBag('rows');

        $this->selectedCategories[] = '';
        $this->selectedOffenseLevels[] = '';
        $this->selectedHRDecisions[] = '';
    }

    public function removeCategory($index)
    {
        unset($this->selectedCategories[$index]);
        unset($this->selectedOffenseLevels[$index]);
        unset($this->selectedHRDecisions[$index]);

        $this->selectedCategories = array_values(
            $this->selectedCategories
        );

        $this->selectedOffenseLevels = array_values(
            $this->selectedOffenseLevels
        );

        $this->selectedHRDecisions = array_values(
            $this->selectedHRDecisions
        );
    }

    public function calculateTat()
    {
        if (empty($this->date_open) || empty($this->date_completed)) {
            $this->tat = '';
            return;
        }

        try {
            $dateOpen = Carbon::createFromFormat('m-d-Y', $this->date_open)
                ->startOfDay();

            $dateCompleted = Carbon::createFromFormat('m-d-Y', $this->date_completed)
                ->startOfDay();

            if ($dateCompleted->lt($dateOpen)) {
                $this->tat = '';
                return;
            }

            $days = $dateOpen->diffInDays($dateCompleted);

            $this->tat = "{$days} day(s)";
        } catch (\Exception $e) {
            $this->tat = '';
        }
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

    public function open($id = '')
    {
        $open_file = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->leftJoin('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->leftJoin('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->leftJoin('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->leftJoin('cpar_investigations as j', 'b.id', '=', 'j.assigned_id')
            ->leftJoin('cpar_employee_disciplinary_records as k', 'b.id', '=', 'k.assignment_id')
            ->leftJoin('cpar_ir_requests as m', 'b.id', '=', 'm.assignment_ir_id')
            ->leftJoin('cpar_notice_to_explains as l', 'm.id', '=', 'l.assignment_id')
            ->leftJoin('cpar_nte_responses as n', 'l.id', '=', 'n.nte_id')
            ->leftJoin('cpar_memos as o', 'b.id', '=', 'o.assignment_id')
            ->leftJoin('employees as p', 'a.employee_no', '=', 'p.employee_no')
            ->join('priority_levels as q', 'a.priority_level', '=', 'q.id')
            ->select([
                'a.id as cpar_id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'a.concern_description',
                'a.complainant_name',
                'a.employee_no as emp_reported',
                'a.priority_level',
                'b.id as assignment_id',
                'b.assigned_to',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',
                'b.status_id',
                'c.file_name',
                'c.file_path',
                'c.file_type',
                'd.source_name',
                'e.complain_name',
                'f.concern_name',
                'g.department_name',
                'h.status_name',
                'i.id as assigned_employee_id',
                'i.employee_no as assigned_employee_no',
                'i.first_name as assigned_first_name',
                'i.last_name as assigned_last_name',
                'i.department_name as assigned_department',
                'j.id as investigation_id',
                'j.identified_cause',
                'j.provided_solution',
                'j.recommendation',
                'j.action_taken_by',
                'j.date_completed',
                'j.tat',
                'j.remarks as head_remarks',
                'k.id as disciplinary_id',
                'k.discipline_ids',
                'k.offense_ids',
                'k.decision_ids',
                'k.status as decision_status',
                'k.remarks as decision_remarks',
                'k.management_remarks',
                'l.id as nte_id',
                'l.nte_no',
                'l.nte_attachment',
                'm.id as ir_ids',
                'm.assignment_ir_id',
                'm.ir_id',
                'm.ir_attachment',
                'n.response_attachment',
                'o.memo_no',
                'o.memo_date',
                'o.subject',
                'o.memo_content',
                'o.memo_attachment',
                'p.employee_no as reported_employee_no',
                'p.first_name as reported_first_name',
                'p.last_name as reported_last_name',
                'p.department_name as reported_by_department',
                'q.priority_name'
            ])
            ->selectRaw("
                CONCAT_WS(
                    ' ',
                    i.first_name,
                    i.last_name
                ) AS assigned_employee
            ")
            ->selectRaw("
                CONCAT_WS(
                    ' ',
                    p.first_name,
                    p.last_name
                ) AS reported_employee
            ")
            ->where('b.id', $id)
            ->first();
        if (!$open_file) {
            $this->dispatch(
                'toast',
                type: 'error',
                message: 'CPAR record not found.'
            );
            return;
        }
        $this->ir_ids = $open_file->ir_ids ?? '';
        $this->priority = $open_file->priority_level;
        $this->id = $open_file->assignment_id;
        $this->cpar_id = $open_file->cpar_id;
        $this->cpar_no = $open_file->cpar_no;
        $this->reported_by = $open_file->reported_by;
        $this->concern_description = $open_file->concern_description ?? '';
        $this->complainant_name = $open_file->complainant_name ?? '';
        $this->source_name = $open_file->source_name ?? '';
        $this->complain_name = $open_file->complain_name ?? '';
        $this->concern_name = $open_file->concern_name ?? '';
        $this->status = $open_file->status_id ?? '';
        $this->emp_reported = $open_file->emp_reported ?? '';
        $this->employeeName = $open_file->reported_employee ?? '';
        $this->assigned_to = $open_file->assigned_to ?? '';
        $this->employee_name = $open_file->assigned_employee ?? '';
        $this->employee_id = $open_file->assigned_employee_id ?? '';
        $this->department_name = $open_file->assigned_department ?? '';
        $this->reported_by_department = $open_file->reported_by_department ?? '';
        $this->attachment = $open_file->file_path ?? '';
        $this->identified_cause = $open_file->identified_cause ?? '';
        $this->provided_solution = $open_file->provided_solution ?? '';
        $this->recommendation = $open_file->recommendation ?? '';
        $this->action_taken_by = $open_file->action_taken_by ?? $open_file->assigned_employee;
        $this->assignment_ir_id = $open_file->assignment_ir_id ?? '';
        $this->response_attachment = $open_file->response_attachment ?? '';
        $this->existing_ir_attachment = $open_file->ir_attachment ?? '';
        $this->assigned_employee_no = $open_file->assigned_employee_no ?? '';
        $this->date_open = !empty($open_file->date_open) ? Carbon::parse($open_file->date_open)->format('m-d-Y') : '';
        $this->date_completed = !empty($open_file->date_completed)
            ? Carbon::createFromFormat('m-d-Y', $open_file->date_completed)->format('m-d-Y')
            : now()->format('m-d-Y');
        if (!empty($open_file->tat)) {
            $this->tat = $open_file->tat;
        } else {
            $this->calculateTat();
        }
        $this->head_remarks = $open_file->head_remarks ?? '';
        $this->assigned_remarks = $open_file->remarks ?? '';
        $this->hr_decision_remarks = $open_file->decision_remarks ?? '';
        $this->management_remarks = $open_file->management_remarks ?? '';
        $this->selectedCategories = $open_file->discipline_ids ?? [];
        $this->selectedOffenseLevels = $open_file->offense_ids ?? [];
        $this->selectedHRDecisions = $open_file->decision_ids ?? [];

        // Ensure all fields are arrays
        $this->selectedCategories = is_array($this->selectedCategories)
            ? $this->selectedCategories
            : [];

        $this->selectedOffenseLevels = is_array($this->selectedOffenseLevels)
            ? $this->selectedOffenseLevels
            : [];

        $this->selectedHRDecisions = is_array($this->selectedHRDecisions)
            ? $this->selectedHRDecisions
            : [];

        // Display at least one row even if all fields are empty
        $rowCount = max(
            1,
            count($this->selectedCategories),
            count($this->selectedOffenseLevels),
            count($this->selectedHRDecisions)
        );

        $this->selectedCategories = array_pad($this->selectedCategories, $rowCount, '');
        $this->selectedOffenseLevels = array_pad($this->selectedOffenseLevels, $rowCount, '');
        $this->selectedHRDecisions = array_pad($this->selectedHRDecisions, $rowCount, '');
        $this->nte_id = $open_file->nte_id ?? '';
        $this->current_nte_attachment = $open_file->nte_attachment ?? '';
        $this->current_ir_attachment = $open_file->ir_attachment ?? '';
        $this->memo_no = $open_memo->memo_no ?? $this->generateMemoNo();
        $this->memo_date = !empty($open_memo->memo_date) ? \Carbon\Carbon::parse($open_memo->memo_date)->format('m-d-Y') : now()->format('m-d-Y');
        $this->memo_subject = $open_file->subject ?? '';
        $this->memo_content = $open_file->memo_content ?? '';
        $this->current_memo_attachment = $open_file->memo_attachment ?? '';
        $this->status_id = $open_file->status_id ?? '';
        $this->investigation_id = $open_file->investigation_id ?? '';
        // file path
        $this->existing_concern_attachment = $open_file->file_path ?? '';
        // Check existing NTE record
        $open_file = DB::table('cpar_notice_to_explains')
            ->where('assignment_id', $this->id)
            ->first();

        if (!empty($open_file?->nte_no)) {
            // May existing NTE number: gamitin ang existing
            $this->nte_no = $open_file->nte_no;
        } else {
            // Walang existing NTE number: generate ng bago
            $year = now()->year;

            $lastNte = DB::table('cpar_notice_to_explains')
                ->whereYear('created_at', $year)
                ->where('nte_no', 'like', 'NTE-' . $year . '-%')
                ->orderByDesc('id')
                ->value('nte_no');

            if (empty($lastNte)) {
                $nextNumber = 1;
            } else {
                $lastNumber = (int) substr($lastNte, -5);
                $nextNumber = $lastNumber + 1;
            }

            $this->nte_no = 'NTE-' . $year . '-' .
                str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        }
        $this->dispatch('$refresh');
        $this->modal('master-file-modal')->show();
    }

    public function updatedReportedBy($value)
    {
        $employee = collect($this->employees)->first(function ($employee) use ($value) {
            $fullName = trim($employee->first_name . ' ' . $employee->last_name);
            return $fullName === $value;
        });
    }

    public function updatedEmployeeId($value)
    {
        $employee = collect($this->employees)
            ->firstWhere('id', $value);

        if ($employee) {
            $this->action_taken_by = trim(
                $employee->first_name . ' ' . $employee->last_name
            );
            $this->department_name = $employee->assigned_department ?? $employee->department_name ?? '';
        } else {
            $this->action_taken_by = '';
            $this->department_name = '';
        }
    }

    public function updatedComplainName($value = '')
    {
        if ($value === 'EMPLOYEE') {
            $this->complainant_name = $this->reported_by;
            $this->complain_name_disabled = true;
        } else {
            $this->complainant_name = '';
            $this->complain_name_disabled = false;
        }
    }

    public function update()
    {
        $newFilePath = null;
        $oldFilePath = null;

        $newIRFilePath = null;
        $oldIRFilePath = null;

        $committed = false;

        DB::beginTransaction();

        try {
            // 1. Get existing CPAR request.
            $oldRequest = DB::table('cpar_request_forms')
                ->where('id', $this->cpar_id)
                ->first();

            if (!$oldRequest) {
                throw new \RuntimeException('CPAR request not found.');
            }

            // 2. Get existing assignment.
            $oldAssignment = DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->first();

            if (!$oldAssignment) {
                throw new \RuntimeException('CPAR assignment not found.');
            }

            // 3. Look up selected categories.
            $source = DB::table('cpar_source_origins')
                ->where('source_name', $this->source_name)
                ->first();

            $complainCategory = DB::table('cpar_complain_categories')
                ->where('complain_name', $this->complain_name)
                ->first();

            $concernCategory = DB::table('cpar_concern_categories')
                ->where('concern_name', $this->concern_name)
                ->first();

            // 4. Find the reported employee.
            $reportedByEmployee = DB::table('employees')
                ->whereRaw(
                    "TRIM(CONCAT_WS(' ', first_name, last_name)) = ?",
                    [trim((string) $this->reported_by)]
                )
                ->first();

            // 5. Update concern attachment only if a new file was selected.
            if ($this->concern_attachment) {
                $existingAttachment = DB::table('cpar_attachments')
                    ->where('cpar_id', $this->cpar_id)
                    ->first();

                $oldFilePath = $existingAttachment->file_path ?? null;

                $newFilePath = $this->concern_attachment
                    ->store('cpar/concern', 'public');

                if (!$newFilePath) {
                    throw new \RuntimeException(
                        'Failed to upload concern attachment.'
                    );
                }

                $attachmentData = [
                    'file_name' => $this->concern_attachment
                        ->getClientOriginalName(),
                    'file_path' => $newFilePath,
                    'file_type' => $this->concern_attachment->getMimeType(),
                    'updated_at' => now(),
                ];

                if ($existingAttachment) {
                    DB::table('cpar_attachments')
                        ->where('cpar_id', $this->cpar_id)
                        ->update($attachmentData);
                } else {
                    DB::table('cpar_attachments')->insert([
                        'cpar_id' => $this->cpar_id,
                        ...$attachmentData,
                        'created_at' => now(),
                    ]);
                }
            }

            // 6. Update CPAR request details.
            DB::table('cpar_request_forms')
                ->where('id', $this->cpar_id)
                ->update([
                    'employee_no' => $reportedByEmployee?->employee_no,
                    'reported_by' => $this->reported_by,
                    'source_id' => $source?->id,
                    'complaint_category_id' => $complainCategory?->id,
                    'complainant_name' => $this->complainant_name,
                    'concern_category_id' => $concernCategory?->id,
                    'concern_description' => $this->concern_description,
                    'priority_level' => $this->priority,
                    'updated_at' => now(),
                ]);

            // 7. Update assignment details.
            DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->update([
                    'assigned_to' => $this->assigned_to,
                    'status_id' => $this->status,
                    'remarks' => $this->assigned_remarks,
                    'assigned_date' => $oldAssignment->assigned_date ?? now(),
                    'updated_at' => now(),
                ]);

            // 8. Insert or update investigation.
            $investigationData = [
                'identified_cause' => $this->identified_cause ?: null,
                'provided_solution' => $this->provided_solution ?: null,
                'recommendation' => $this->recommendation ?: null,
                'action_taken_by' => $this->action_taken_by ?: null,
                'date_completed' => $this->date_completed,
                'tat' => $this->tat ?: null,
                'remarks' => $this->head_remarks ?: null,
                'updated_at' => now(),
            ];

            $existingInvestigation = DB::table('cpar_investigations')
                ->where('assigned_id', $this->id)
                ->first();

            if ($existingInvestigation) {
                DB::table('cpar_investigations')
                    ->where('assigned_id', $this->id)
                    ->update($investigationData);

                $this->investigation_id = $existingInvestigation->id;
            } else {
                $this->investigation_id = DB::table('cpar_investigations')
                    ->insertGetId([
                        'assigned_id' => $this->id,
                        ...$investigationData,
                        'created_at' => now(),
                    ]);
            }

            // 9. Get existing IR request.
            $oldIR = DB::table('cpar_ir_requests')
                ->where('assignment_ir_id', $this->id)
                ->first();

            $oldIRFilePath = $oldIR->ir_attachment ?? null;
            // Preserve existing attachment unless a new one is uploaded.
            $irFilePath = $oldIRFilePath;

            if ($this->ir_attachment) {
                $newIRFilePath = $this->ir_attachment
                    ->store('cpar/ir', 'public');

                if (!$newIRFilePath) {
                    throw new \RuntimeException(
                        'Failed to upload IR attachment.'
                    );
                }

                $irFilePath = $newIRFilePath;
            }

            // Generate them only when creating a new IR request.
            $issuedAt = $oldIR->issued_at ?? now();
            $dueDate = $oldIR->due_date ?? now()->addDay();

            $irData = [
                'ir_id' => $this->ir_id,
                'employee_no' => $this->emp_reported,
                'ir_attachment' => $irFilePath,
                'issued_at' => $issuedAt,
                'due_date' => $dueDate,
                'updated_at' => now(),
            ];

            if ($oldIR) {
                // Existing IR request: UPDATE.
                DB::table('cpar_ir_requests')
                    ->where('assignment_ir_id', $this->id)
                    ->update($irData);
            } else {
                // No existing IR request: INSERT, even without an attachment.
                DB::table('cpar_ir_requests')->insert([
                    'assignment_ir_id' => $this->id,
                    ...$irData,
                    'status' => 'SUBMITTED',
                    'submitted_at' => now(),
                    'created_at' => now(),
                ]);
            }

            $oldNTE = DB::table('cpar_notice_to_explains')
                ->where('assignment_id', $this->ir_ids)
                ->first();
            $oldNTEFilePath = $oldNTE->nte_attachment ?? null;
            // Preserve existing attachment unless a new one is uploaded.
            $nteFilePath = $oldNTEFilePath;

            if ($this->nte_attachment) {
                $newNTEFilePath = $this->nte_attachment
                    ->store('cpar/nte', 'public');

                if (!$newNTEFilePath) {
                    throw new \RuntimeException(
                        'Failed to upload IR attachment.'
                    );
                }

                $nteFilePath = $newNTEFilePath;
            }

            // Generate them only when creating a new IR request.
            $issuedAt = $oldNTE->issued_at ?? now();
            $dueDate = $oldNTE->due_date ?? now()->addDay();

            $NTEData = [
                'assignment_id' => $this->ir_ids,
                'nte_attachment' => $nteFilePath,
                'issued_at' => $issuedAt,
                'due_date' => $dueDate,
                'updated_at' => now(),
            ];

            if ($oldNTE) {
                // Existing IR request: UPDATE.
                DB::table('cpar_notice_to_explains')
                    ->where('assignment_id', $this->ir_ids)
                    ->update($NTEData);
            } else {
                // No existing IR request: INSERT, even without an attachment.
                DB::table('cpar_notice_to_explains')->insert([
                    'assignment_id' => $this->id,
                    'nte_no' => $this->nte_no,
                    ...$NTEData,
                    'status' => 'FOR EXPLANATION',
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $oldNTE = DB::table('cpar_notice_to_explains')
                ->where('assignment_id', $this->ir_ids)
                ->first();
            $oldNTEFilePath = $oldNTE->nte_attachment ?? null;
            // Preserve existing attachment unless a new one is uploaded.
            $nteFilePath = $oldNTEFilePath;

            if ($this->nte_attachment) {
                $newNTEFilePath = $this->nte_attachment
                    ->store('cpar/nte', 'public');

                if (!$newNTEFilePath) {
                    throw new \RuntimeException(
                        'Failed to upload IR attachment.'
                    );
                }

                $nteFilePath = $newNTEFilePath;
            }

            // Generate them only when creating a new IR request.
            $issuedAt = $oldNTE->issued_at ?? now();
            $dueDate = $oldNTE->due_date ?? now()->addDay();

            $NTEData = [
                'assignment_id' => $this->ir_ids,
                'nte_attachment' => $nteFilePath,
                'issued_at' => $issuedAt,
                'due_date' => $dueDate,
                'updated_at' => now(),
            ];

            if ($oldNTE) {
                // Existing IR request: UPDATE.
                DB::table('cpar_notice_to_explains')
                    ->where('assignment_id', $this->ir_ids)
                    ->update($NTEData);
            } else {
                // No existing IR request: INSERT, even without an attachment.
                DB::table('cpar_notice_to_explains')->insert([
                    'assignment_id' => $this->id,
                    'nte_no' => $this->nte_no,
                    ...$NTEData,
                    'status' => 'FOR EXPLANATION',
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $oldDisciplinary = DB::table('cpar_employee_disciplinary_records')
                ->where('assignment_id', $this->id)
                ->first();

            $incidentDate = Carbon::parse($this->date_open);

            $validUntil = $incidentDate->month <= 6
                ? $incidentDate->copy()->month(6)->endOfMonth()
                : $incidentDate->copy()->month(12)->endOfMonth();

            if (empty($oldDisciplinary)) {
                // CREATE NEW RECORD
                cpar_employee_disciplinary_records::create([
                    'assignment_id' => $this->id,
                    'discipline_ids' => json_encode($this->selectedCategories),
                    'offense_ids' => json_encode($this->selectedOffenseLevels),
                    'decision_ids' => json_encode($this->selectedHRDecisions),
                    'incident_date' => $incidentDate->format('Y-m-d'),
                    'valid_until' => $validUntil->format('Y-m-d'),
                    'remarks' => $this->hr_decision_remarks,
                    'status' => 'FINAL',
                    'created_by' => auth()->id(),
                    'updated_at' => now(),
                ]);
            } else {
                // UPDATE EXISTING RECORD
                cpar_employee_disciplinary_records::where(
                    'assignment_id',
                    $this->id
                )->update([
                    'discipline_ids' => json_encode($this->selectedCategories),
                    'offense_ids' => json_encode($this->selectedOffenseLevels),
                    'decision_ids' => json_encode($this->selectedHRDecisions),
                    'incident_date' => $incidentDate->format('Y-m-d'),
                    'valid_until' => $validUntil->format('Y-m-d'),
                    'remarks' => $this->hr_decision_remarks,
                    'status' => 'FINAL UPDATED',
                    'created_by' => auth()->id(),
                    'updated_at' => now(),
                ]);
            }

            // 10. Commit database changes.
            DB::commit();
            $committed = true;

            // 11. Delete replaced files only after a successful commit.
            if (
                $oldFilePath &&
                $newFilePath &&
                $oldFilePath !== $newFilePath
            ) {
                Storage::disk('public')->delete($oldFilePath);
            }

            if (
                $oldIRFilePath &&
                $newIRFilePath &&
                $oldIRFilePath !== $newIRFilePath
            ) {
                Storage::disk('public')->delete($oldIRFilePath);
            }

            // 12. Reset upload properties.
            $this->concern_attachment = null;
            $this->ir_attachment = null;

            $this->dispatch(
                'toast',
                type: 'success',
                message: 'CPAR Master File updated successfully!'
            );

            $this->dispatch('modal-close', name: 'master-file-modal');
            $this->dispatch('refresh');
        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            // Delete newly uploaded files if the database transaction failed.
            if (!$committed) {
                if ($newFilePath) {
                    Storage::disk('public')->delete($newFilePath);
                }

                if ($newIRFilePath) {
                    Storage::disk('public')->delete($newIRFilePath);
                }
            }

            report($e);

            $this->dispatch(
                'toast',
                type: 'error',
                message: 'Failed to update CPAR Master File. Check the application logs.'
            );
        }
    }

    public function updatedBranchId($value)
    {
        $this->employees = Employee::query()
            ->where('branch_id', $value)
            ->orderBy('first_name')
            ->get()
            ->map(function ($employee) {
                $employee->full_name = trim(
                    $employee->first_name . ' ' . $employee->last_name
                );

                return $employee;
            });
        $this->reported_by = '';
        $this->emp_reported = '';
    }

    public function render()
    {
        return view('livewire.admin.reports.modal.edit');
    }
}
