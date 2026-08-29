<?php

namespace App\Livewire\Admin\Reports\Modal;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\employee;
use Illuminate\Support\Facades\Auth;
use App\Models\cpar_complain_categories;
use App\Models\cpar_concern_categories;
use App\Models\priority_level;
use App\Models\cpar_source_origins;

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
    public $emp_reported = '', $employeeName = '', $user_id = '', $assigned_to = '', $employees = '', $priority = '', $complain_name_disabled ='';
    public $source_origin = [], $cpar_complain = [], $cpar_concern = [], $priority_level = [];

    protected $listeners = [
        'open-master-file' => 'open'
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->user_id = $info->id;
        }
        $this->decisionCategories = DB::table('cpar_decision_categories')
            ->orderBy('id', 'asc')
            ->get();
        $this->disciplinaryCategories = DB::table('cpar_disciplinary_categories')
            ->orderBy('id', 'desc')
            ->get();
        $this->offenseLevels = DB::table('cpar_offense_levels')
            ->orderBy('id', 'desc')
            ->get();
        $this->employees = employee::query()
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
                'b.id as assignment_id',
                'b.assigned_to',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',
                'b.status_id',
                'c.file_path',
                'd.source_name',
                'e.complain_name',
                'f.concern_name',
                'g.department_name',
                'h.status_name',
                'i.employee_no as assigned_employee_no',
                'i.first_name as assigned_first_name',
                'i.last_name as assigned_last_name',
                'i.department_name as assigned_department',
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
        $this->priority = $open_file->priority_name;
        $this->id = $open_file->assignment_id;
        $this->cpar_id = $open_file->cpar_id;
        $this->cpar_no = $open_file->cpar_no;
        $this->reported_by = $open_file->reported_by;
        $this->date_open = $open_file->date_open;
        $this->concern_description = $open_file->concern_description ?? '';
        $this->complainant_name = $open_file->complainant_name ?? '';
        $this->source_name = $open_file->source_name ?? '';
        $this->complain_name = $open_file->complain_name ?? '';
        $this->concern_name = $open_file->concern_name ?? '';
        $this->status_name = $open_file->status_name ?? '';
        $this->emp_reported = $open_file->emp_reported ?? '';
        $this->employeeName = $open_file->reported_employee ?? '';
        $this->assigned_to = $open_file->assigned_to ?? '';
        $this->employee_name = $open_file->assigned_employee ?? '';
        $this->department_name = $open_file->assigned_department ?? $open_file->department_name ?? '';
        $this->reported_by_department = $open_file->reported_by_department ?? '';
        $this->attachment = $open_file->file_path ?? '';
        $this->identified_cause = $open_file->identified_cause ?? '';
        $this->provided_solution = $open_file->provided_solution ?? '';
        $this->recommendation = $open_file->recommendation ?? '';
        $this->action_taken_by = $open_file->action_taken_by ?? '';
        $this->date_completed = $open_file->date_completed ?? '';
        $this->tat = $open_file->tat ?? '';
        $this->head_remarks = $open_file->head_remarks ?? '';
        $this->hr_decision_remarks = $open_file->decision_remarks ?? '';
        $this->management_remarks = $open_file->management_remarks ?? '';
        $this->selectedCategories = json_decode($open_file->discipline_ids ?? '[]', true) ?: [''];
        $this->selectedOffenseLevels = json_decode($open_file->offense_ids ?? '[]', true) ?: [''];
        $this->selectedHRDecisions = json_decode($open_file->decision_ids ?? '[]', true) ?: [''];
        $this->decisionCategories = !empty($this->selectedHRDecisions) ? DB::table('cpar_decision_categories')
            ->whereIn('id', array_filter($this->selectedHRDecisions))
            ->orderBy('id')
            ->get()
            : collect();
        $this->disciplinaryCategories = !empty($this->selectedCategories) ? DB::table('cpar_disciplinary_categories')
            ->whereIn('id', array_filter($this->selectedCategories))
            ->orderBy('id')
            ->get()
            : collect();
        $this->offenseLevels = !empty($this->selectedOffenseLevels) ? DB::table('cpar_offense_levels')
            ->whereIn('id', array_filter($this->selectedOffenseLevels))
            ->orderBy('id')
            ->get()
            : collect();
        $this->nte_id = $open_file->nte_id ?? '';
        $this->current_nte_attachment = $open_file->nte_attachment ?? '';
        $this->ir_ids = $open_file->ir_ids ?? '';
        $this->ir_id = $open_file->ir_id ?? '';
        $this->current_ir_attachment = $open_file->ir_attachment ?? '';
        $this->memo_no = $open_file->memo_no ?? '';
        $this->memo_date = $open_file->memo_date ?? '';
        $this->memo_subject = $open_file->subject ?? '';
        $this->memo_content = $open_file->memo_content ?? '';
        $this->current_memo_attachment = $open_file->memo_attachment ?? '';
        $this->modal('master-file-modal')->show();
    }

    public function updatedReportedBy($value)
    {
        $employee = collect($this->employees)->first(function ($employee) use ($value) {
            $fullName = trim($employee->first_name . ' ' . $employee->last_name);
            return $fullName === $value;
        });
    }

    public function updatedEmployeeName($value)
    {
        // Update Action Taken By
        $this->action_taken_by = $value;
        // Find selected employee
        $employee = collect($this->employees)->first(function ($employee) use ($value) {
            $fullName = trim(
                $employee->first_name . ' ' . $employee->last_name
            );

            return $fullName === $value;
        });

        // Update Department
        if ($employee) {
            $this->department_name =
                $employee->assigned_department
                ?? $employee->department_name
                ?? '';
        } else {
            $this->department_name = '';
        }
    }

    public function updatedComplainName($value = '')
    {
        if ($value === 'Employee') {
            $this->complainant_name = $this->reported_by;
            $this->complain_name_disabled = true;
        } else {
            $this->complainant_name = '';
            $this->complain_name_disabled = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.reports.modal.edit');
    }
}
