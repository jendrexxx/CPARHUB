<?php

namespace App\Livewire\Admin\Reports\Modal;

use App\Models\audit_logs;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use App\Models\result_error_data_informations;
use App\Models\result_error_quality_accuracies;
use App\Models\result_error_technical_equipments;
use App\Models\cpar_statuses;
use App\Models\priority_level;
use App\Models\result_complain_categories;
use App\Models\result_error_source_of_info;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class ResultFile extends Component
{
    use WithFileUploads;
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
    public $sources = [], $complainants = [], $priorities = [], $cpar_status = [];
    public $source_id = null, $complainant_category_id = null, $priority_id = null;
    public $existing_ir_attachment = '', $ir_attachment = '';
    public $identified_cause = '', $provided_solution = '', $recommendation = '', $action_taken_by = '', $date_completed = '', $tat = '0 day(s)';
    public $existing_nte_attachment = '', $existing_response_attachment = '';
    public $selectedCategories = [null];
    public $selectedOffenseLevels = [''];
    public $selectedHRDecisions = [''];
    public $decisionCategories = [];
    public $disciplinaryCategories = [];
    public $offenseLevels = [];
    public $isNoDisciplinaryAction = false;
    public $nte_no = '', $nte_attachment = '', $response_attachment = '', $response_nte = '';
    public $incident_date = '', $validUntil = '', $valid_until = '', $hr_decision_remarks = '', $management_remarks = '';
    protected $listeners = [
        'result-master-file' => 'open',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = Employee::where('email', $user->email)->first();

        if ($info) {
            $this->id          = $info->id;
            $this->employee_no = $info->employee_no;
            $this->branch_id   = $info->branch_id;
        }

        $this->assigned_to = [];
        $this->employees = employee::where('branch_id', $this->branch_id)
            ->orderBy('first_name')
            ->get()
            ->map(function ($employee) {
                $employee->full_name = $employee->employee_no . ' - '
                    . $employee->first_name . ' '
                    . $employee->last_name;

                return $employee;
            });
        $this->sources = result_error_source_of_info::orderBy('source_name')->get();
        $this->complainants = result_complain_categories::orderBy('complain_name')->get();
        $this->priorities = priority_level::orderBy('priority_name')->get();
        $this->cpar_status = cpar_statuses::orderBy('id')->get();
        $this->data = result_error_data_informations::all();
        $this->quality = result_error_quality_accuracies::all();
        $this->technical = result_error_technical_equipments::all();
        $this->decisionCategories = DB::table('cpar_decision_categories')
            ->orderBy('id', 'asc')
            ->get();

        $this->disciplinaryCategories = DB::table('cpar_disciplinary_categories')
            ->orderBy('id', 'desc')
            ->get();

        $this->offenseLevels = DB::table('cpar_offense_levels')
            ->orderBy('id', 'asc')
            ->get();
        $year = now()->year;
        $lastNte = DB::table('cpar_notice_to_explains')
            ->whereYear('created_at', $year)
            ->orderByDesc('id')
            ->value('nte_no');

        if (empty($lastNte)) {
            $nextNumber = 1;
        } else {
            $lastNumber = (int) substr($lastNte, -5);
            $nextNumber = $lastNumber + 1;
        }
        $this->nte_no = 'NTE-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function updateEmployeeDetails()
    {
        $employee = employee::where('branch_id', $this->branch_id)
            ->whereRaw(
                "TRIM(CONCAT(first_name, ' ', last_name)) = ?",
                [$this->reported_by]
            )
            ->first();

        if ($employee) {
            $this->employee_no = $employee->employee_no;
            $this->department_id = $employee->department_id;
        } else {
            $this->employee_no = null;
            $this->department_id = null;
        }
    }

    public function updatedAssignedTo($employeeId)
    {
        if (empty($employeeId)) {
            $this->department_name = '';
            return;
        }

        $employee = employee::where('id', $employeeId)
            ->where('branch_id', $this->branch_id)
            ->first();

        if ($employee) {
            $this->department_name = DB::table('departments')
                ->where('id', $employee->department_id)
                ->value('department_name') ?? '';
        } else {
            $this->department_name = '';
        }
    }

    public function open($id = '')
    {
        if (!$id) {
            return;
        }

        $result_assigned = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('cpar_statuses as e', 'd.status_id', '=', 'e.id')
            ->leftJoin('employees as f', 'd.dept_head_assigned', '=', 'f.id')
            ->join('priority_levels as g', 'a.priority_level', '=', 'g.id')
            ->leftJoin('cpar_investigations as h', 'd.result_id', '=', 'h.assigned_id')
            ->leftJoin('cpar_ir_requests as i', 'h.assigned_id', '=', 'i.assignment_ir_id')
            ->leftJoin('cpar_employee_disciplinary_records as j', 'd.id', '=', 'j.assignment_id')
            ->leftJoin('cpar_notice_to_explains as k', 'i.id', '=', 'k.assignment_id')
            ->leftJoin('cpar_nte_responses as l', 'k.id', '=', 'l.nte_id')
            ->select(
                'a.id',
                'a.result_no',
                'a.reported_by',
                'a.employee_no',
                'a.date_reported',
                'a.test_procedure',
                'a.source_of_information',
                'a.quality_information',
                'a.actual_released_date',
                'a.data_information',
                'a.patient_name',
                'a.complain_name as complainant_name',
                'a.attending_physician',
                'a.technical_information',
                'a.concern_description',
                'a.complainant_category',
                'a.priority_level',
                'a.department_id',
                'b.source_name',
                'c.complain_name',
                'd.id as assignment_id',
                'd.result_id',
                'd.assigned_to',
                'd.remarks as assignment_remarks',
                'd.dept_head_assigned',
                'd.department_id as assignment_department_id',
                'd.status_id',
                'e.status_name',
                'f.branch_id',
                'f.first_name',
                'f.last_name',
                'g.priority_name',
                'h.identified_cause',
                'h.provided_solution',
                'h.recommendation',
                'h.action_taken_by',
                'h.date_completed',
                'h.tat',
                'h.remarks',
                'i.ir_attachment',
                // HR Decision
                'j.id as disciplinary_id',
                'j.discipline_ids',
                'j.offense_ids',
                'j.decision_ids',
                'j.incident_date',
                'j.valid_until',
                'j.status as decision_status',
                'j.remarks as decision_remarks',
                'j.management_remarks',
                // NTE
                'k.id as nte_id',
                'k.nte_no',
                'k.nte_attachment',
                // NTE EXPLANATION
                'l.nte_id as response_nte',
                'l.employee_no',
                'l.response_attachment'
            )
            ->where('d.result_id', $id)
            ->first();

        if (!$result_assigned) {
            return;
        }
        $this->assignment_id = $result_assigned->assignment_id;
        $this->cpar_id = $result_assigned->result_id;
        $this->result_no = $result_assigned->result_no;
        $this->date_reported = $result_assigned->date_reported ? \Carbon\Carbon::parse($result_assigned->date_reported)->format('Y-m-d') : null;
        $this->reported_by = $result_assigned->reported_by;
        $this->test_procedure = strtoupper($result_assigned->test_procedure ?? '');
        $this->patient_name = strtoupper($result_assigned->patient_name ?? '');
        $this->attending_physician = strtoupper($result_assigned->attending_physician ?? '');
        $this->actual_released_date = $result_assigned->actual_released_date;
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
        $this->complainant_name = strtoupper($result_assigned->complainant_name ?? '');
        $this->concern_description = strtoupper($result_assigned->concern_description ?? '');
        $this->priority = $result_assigned->priority_name;
        $this->result_id = $result_assigned->result_id;
        $assignments = DB::table('cpar_assignments as b')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
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
        $this->source_id = $result_assigned->source_of_information;
        $this->complainant_category_id = $result_assigned->complainant_category;
        $this->source_name = $result_assigned->source_name;
        $this->priority_id = $result_assigned->priority_level;
        $this->status_id = $result_assigned->status_id;
        $this->department_id = $result_assigned->department_id;
        // investigation 
        $this->identified_cause = $result_assigned->identified_cause;
        $this->provided_solution = $result_assigned->provided_solution;
        $this->recommendation = $result_assigned->recommendation;
        $this->action_taken_by = $result_assigned->action_taken_by;
        $this->date_completed = $result_assigned->date_completed
            ? \Carbon\Carbon::parse($result_assigned->date_completed)->format('m/d/Y')
            : today()->format('m/d/Y');
        $this->tat = $result_assigned->tat;
        $this->remarks = $result_assigned->remarks;
        // cpar ir request
        $this->existing_ir_attachment = $result_assigned->ir_attachment;
        // hr decision
        $cpar = DB::table('cpar_employee_disciplinary_records')
            ->where('assignment_id', $this->assignment_id ?: $this->result_id)
            ->first();

        $this->selectedCategories = $cpar
            ? (json_decode($cpar->discipline_ids ?? '[]', true) ?: [''])
            : [''];

        $this->selectedOffenseLevels = $cpar
            ? (json_decode($cpar->offense_ids ?? '[]', true) ?: [''])
            : [''];

        $this->selectedHRDecisions = $cpar
            ? (json_decode($cpar->decision_ids ?? '[]', true) ?: [''])
            : [''];

        $this->selectedCategories = array_map(
            'strval',
            $this->selectedCategories
        );

        $this->selectedOffenseLevels = array_map(
            'strval',
            $this->selectedOffenseLevels
        );

        $this->selectedHRDecisions = array_map(
            'strval',
            $this->selectedHRDecisions
        );
        $this->calculateTAT();
        // nte hr attachment
        $this->existing_nte_attachment = $result_assigned->nte_attachment;
        $this->nte_no = $result_assigned->nte_no;
        // nte employee attachment
        $this->existing_response_attachment = $result_assigned->response_attachment;
        // nte response employee
        $this->employee_no = $result_assigned->employee_no;
        $this->response_nte = $result_assigned->response_nte;
        // employee disciplinary record
        $this->incident_date = $result_assigned->incident_date;
        $this->valid_until = $result_assigned->valid_until;
        $this->hr_decision_remarks = $result_assigned->decision_remarks;
        $this->management_remarks = $result_assigned->management_remarks;
        $this->modal('result-file')->show();
    }

    public function updatedSelectedHRDecisions($value, $key)
    {
        $decision = collect($this->decisionCategories)->firstWhere('id', $value);
        if (!$decision) {
            return;
        }

        $decisionName = strtoupper(trim($decision->decision_name));

        if ($decisionName === 'NO DISCIPLINARY ACTION') {

            $this->isNoDisciplinaryAction = true;

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

            // Set the IDs
            $this->selectedCategories = [
                $naCategory?->id ?? ''
            ];

            $this->selectedOffenseLevels = [
                $naOffense?->id ?? ''
            ];
        } else {

            $this->isNoDisciplinaryAction = false;

            $this->selectedCategories = [''];
            $this->selectedOffenseLevels = [''];
        }
    }

    public function calculateTAT()
    {
        if (empty($this->date_reported) || empty($this->date_completed)) {
            $this->tat = '0 day(s)';
            return;
        }

        $reported = \Carbon\Carbon::parse($this->date_reported)->startOfDay();
        $completed = \Carbon\Carbon::parse($this->date_completed)->startOfDay();

        $days = $reported->diffInDays($completed);

        $this->tat = $days . ' day(s)';
    }


    public function saveResult()
    {
        $this->validate([
            'ir_attachment' => 'nullable|file|mimes:pdf|max:10240',
            'nte_attachment' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $irAttachmentPath = $this->existing_ir_attachment;
        $nteAttachmentPath = $this->existing_nte_attachment;

        if ($this->ir_attachment) {
            $irAttachmentPath = $this->ir_attachment->store(
                'result/ir',
                'public'
            );
        }

        if ($this->nte_attachment) {
            $nteAttachmentPath = $this->nte_attachment->store(
                'cpar/nte',
                'public'
            );
        }

        DB::transaction(function () use (
            $irAttachmentPath,
            $nteAttachmentPath
        ) {
            DB::table('result_error_forms')
                ->where('id', $this->result_id)
                ->update([
                    'employee_no' => $this->employee_no,
                    'reported_by' => $this->reported_by,
                    'patient_name' => strtoupper($this->patient_name ?? ''),
                    'attending_physician' => strtoupper($this->attending_physician ?? ''),
                    'test_procedure' => strtoupper($this->test_procedure ?? ''),
                    'actual_released_date' => $this->actual_released_date ?: null,
                    'source_of_information' => $this->source_id ?: null,
                    'data_information' => json_encode(
                        array_map('strval', $this->data_information ?? [])
                    ),
                    'technical_information' => json_encode(
                        array_map('strval', $this->technical_information ?? [])
                    ),
                    'quality_information' => json_encode(
                        array_map('strval', $this->quality_information ?? [])
                    ),
                    'complainant_category' => $this->complainant_category_id ?: null,
                    'complain_name' => strtoupper($this->complainant_name ?? ''),
                    'concern_description' => strtoupper($this->concern_description ?? ''),
                    'department_id' => $this->department_id ?: null,
                    'priority_level' => $this->priority_id ?: null,
                    'updated_at' => now(),
                ]);

            DB::table('cpar_assignments')
                ->where('result_id', $this->result_id)
                ->update([
                    'assigned_to' => $this->assigned_to ?: null,
                    'assigned_date' => now(),
                    'remarks' => strtoupper($this->assignment_remarks ?? ''),
                    'status_id' => $this->status_id,
                    'updated_at' => now(),
                ]);

            DB::table('cpar_ir_requests')
                ->updateOrInsert(
                    [
                        'assignment_ir_id' => $this->result_id,
                    ],
                    [
                        'ir_attachment' => $irAttachmentPath,
                        'updated_at' => now(),
                    ]
                );

            DB::table('cpar_investigations')
                ->updateOrInsert(
                    [
                        'assigned_id' => $this->result_id,
                    ],
                    [
                        'identified_cause' => strtoupper(
                            $this->identified_cause ?? ''
                        ),
                        'provided_solution' => strtoupper(
                            $this->provided_solution ?? ''
                        ),
                        'recommendation' => strtoupper(
                            $this->recommendation ?? ''
                        ),
                        'date_completed' => $this->date_completed ?: null,
                        'tat' => $this->tat ?: null,
                        'remarks' => strtoupper($this->remarks ?? ''),
                        'updated_at' => now(),
                    ]
                );

            if ($nteAttachmentPath) {
                $now = now();

                $assignmentId = $this->assignment_id ?: $this->result_id;

                DB::table('cpar_notice_to_explains')->updateOrInsert(
                    [
                        'assignment_id' => $assignmentId,
                    ],
                    [
                        'nte_no' => $this->nte_no ?: $this->generateNteNo(),
                        'nte_attachment' => $nteAttachmentPath,
                        'issued_at' => $now,
                        'due_date' => $now->copy()->addHours(24),
                        'status' => 'RESULT NTE',
                        'created_by' => auth()->id(),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }

            if ($this->response_attachment) {
                $this->validate([
                    'response_attachment' => 'required|file|mimes:pdf|max:10240',
                ]);

                $responseAttachmentPath = $this->response_attachment
                    ->store('cpar/response', 'public');

                $now = now();

                $responseData = [
                    'response_attachment' => $responseAttachmentPath,
                    'submitted_at' => $now,
                    'updated_at' => $now,
                ];

                $response = DB::table('cpar_nte_responses')
                    ->where('nte_id', $this->response_nte);

                if (!empty($this->response_nte)) {
                    $response->where('nte_id', $this->response_nte);
                }

                $existingResponse = $response->first();

                if ($existingResponse) {
                    DB::table('cpar_nte_responses')
                        ->where('id', $existingResponse->id)
                        ->update($responseData);
                } else {
                    DB::table('cpar_nte_responses')->insert([
                        'nte_id' => $this->response_nte ?: null,
                        'employee_no' => $this->employee_no,
                        'response_attachment' => $responseAttachmentPath,
                        'submitted_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                $this->existing_response_attachment = $responseAttachmentPath;
                $this->response_attachment = null;
            }

            $assignmentId = $this->assignment_id ?: $this->result_id;

            $incidentDate = !empty($this->incident_date)
                ? \Carbon\Carbon::parse($this->incident_date)
                : now();

            $validUntil = $incidentDate
                ->copy()
                ->addMonthsNoOverflow(6)
                ->toDateString();

            $disciplineIds = collect($this->selectedCategories ?? [])
                ->filter(fn($id) => filled($id))
                ->map(fn($id) => (string) $id)
                ->unique()
                ->values()
                ->all();

            $offenseIds = collect($this->selectedOffenseLevels ?? [])
                ->filter(fn($id) => filled($id))
                ->map(fn($id) => (string) $id)
                ->unique()
                ->values()
                ->all();

            $decisionIds = collect($this->selectedHRDecisions ?? [])
                ->filter(fn($id) => filled($id))
                ->map(fn($id) => (string) $id)
                ->unique()
                ->values()
                ->all();

            $disciplinaryRecord = DB::table('cpar_employee_disciplinary_records')
                ->where('assignment_id', $assignmentId)
                ->first();

            $now = now();

            $disciplinaryData = [
                'discipline_ids' => json_encode($disciplineIds),
                'offense_ids' => json_encode($offenseIds),
                'decision_ids' => json_encode($decisionIds),
                'incident_date' => $now,
                'valid_until' => $validUntil,
                'remarks' => strtoupper($this->hr_decision_remarks ?? ''),
                'management_remarks' => strtoupper($this->management_remarks ?? ''),
                'status' => 'FINAL',
                'updated_at' => $now,
            ];

            if ($disciplinaryRecord) {
                DB::table('cpar_employee_disciplinary_records')
                    ->where('assignment_id', $assignmentId)
                    ->update($disciplinaryData);
            } else {
                DB::table('cpar_employee_disciplinary_records')->insert(
                    array_merge($disciplinaryData, [
                        'assignment_id' => $assignmentId,
                        'created_by' => auth()->id(),
                        'created_at' => $now,
                    ])
                );
            }
        });

        $this->existing_ir_attachment = $irAttachmentPath;
        $this->ir_attachment = null;

        $this->existing_nte_attachment = $nteAttachmentPath;
        $this->nte_attachment = null;

        $this->dispatch(
            'toast',
            message: 'Result updated successfully.',
            type: 'success'
        );

        $this->dispatch('refresh');
        $this->dispatch('modal-close', name: 'result-file');
    }

    public function render()
    {
        return view('livewire.admin.reports.modal.result_file');
    }
}
