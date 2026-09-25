<?php

namespace App\Livewire\Admin\Hr;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class HrDecisionNotif extends Component
{
    use WithPagination;

    public $search = '';
    public $hrDecisionList = '';
    public $branch_id = '';
    public $employee_disciplinary_count = '';
    public $offenseHistoryCounts = 0;

    protected $listeners = [
        'refreshDecisionRecords' => 'loadDecisionRecords',
    ];

    public function mount($branch_id)
    {
        $this->branch_id = $branch_id;
        $this->loadDecisionRecords();
    }

    public function updatedSearch()
    {
        $this->loadDecisionRecords();
    }

    public function loadDecisionRecords()
    {
        $cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_investigations as c', 'b.id', '=', 'c.assigned_id')
            ->join('departments as d', 'a.department_id', '=', 'd.id')
            ->join('cpar_statuses as e', 'b.status_id', '=', 'e.id')
            ->join('employees as f', 'b.assigned_to', '=', 'f.id')
            ->join('priority_levels as g', 'a.priority_level', '=', 'g.id')
            ->leftJoin('cpar_employee_disciplinary_records as h', 'c.id', '=', 'h.assignment_id')
            ->leftJoin('cpar_ir_requests as i', 'b.id', '=', 'i.assignment_ir_id')
            ->leftJoin('cpar_notice_to_explains as j', 'i.id', '=', 'j.assignment_id')
            ->select(
                'a.id as record_id',
                'a.cpar_no as record_no',
                'a.reported_by',
                'a.date_open as record_date',
                DB::raw("'CPAR' as record_type"),
                'b.id as assignment_id',
                'b.assigned_to',
                'f.employee_no',
                'f.employee_no as assigned_emp_id',
                DB::raw("
                CONCAT(f.first_name, ' ', f.last_name) as employee_name
                "),
                'c.identified_cause',
                'c.provided_solution',
                'c.recommendation',
                'c.action_taken_by',
                'c.date_completed',
                'c.tat',
                'j.nte_no',
                'i.ir_id',
                'd.department_name',
                'e.status_name',
                'g.priority_name',
                'h.id as disciplinary_id',
                'h.status as disciplinary_status',
            )
            ->whereIn('b.status_id', [20, 25, 30])
            ->where('b.record_type', 5)
            ->where('f.branch_id', $this->branch_id);

        $result = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->leftJoin('cpar_investigations as c', 'b.id', '=', 'c.assigned_id')
            ->join('departments as d', 'a.department_id', '=', 'd.id')
            ->join('cpar_statuses as e', 'b.status_id', '=', 'e.id')
            ->join('employees as f', 'b.assigned_to', '=', 'f.id')
            ->join('priority_levels as g', 'a.priority_level', '=', 'g.id')
            ->leftJoin('cpar_employee_disciplinary_records as h', 'c.id', '=', 'h.assignment_id')
            ->leftJoin('cpar_ir_requests as i', 'b.id', '=', 'i.assignment_ir_id')
            ->leftJoin('cpar_notice_to_explains as j', 'i.id', '=', 'j.assignment_id')
            ->select(
                'a.id as record_id',
                'a.result_no as record_no',
                'a.reported_by',
                'a.date_reported as record_date',
                DB::raw("'RESULT' as record_type"),
                'b.id as assignment_id',
                'b.assigned_to',
                'f.employee_no',
                'f.employee_no as assigned_emp_id',
                DB::raw("
                CONCAT(f.first_name, ' ', f.last_name) as employee_name
                "),
                'c.identified_cause',
                'c.provided_solution',
                'c.recommendation',
                'c.action_taken_by',
                'c.date_completed',
                'c.tat',
                'j.nte_no',
                'i.ir_id',
                'd.department_name',
                'e.status_name',
                'g.priority_name',
                'h.id as disciplinary_id',
                'h.status as disciplinary_status',
            )
            ->whereIn('b.status_id', [20, 25, 30])
            ->where('b.record_type', 10)
            ->where('f.branch_id', $this->branch_id);

        if (!empty($this->search)) {
            $search = '%' . $this->search . '%';

            $cpar->where(function ($q) use ($search) {
                $q->where('a.cpar_no', 'like', $search)
                    ->orWhere('a.reported_by', 'like', $search)
                    ->orWhere('f.employee_no', 'like', $search)
                    ->orWhere('f.first_name', 'like', $search)
                    ->orWhere('f.last_name', 'like', $search)
                    ->orWhere('d.department_name', 'like', $search)
                    ->orWhere('j.nte_no', 'like', $search);
            });

            $result->where(function ($q) use ($search) {
                $q->where('a.result_no', 'like', $search)
                    ->orWhere('a.reported_by', 'like', $search)
                    ->orWhere('f.employee_no', 'like', $search)
                    ->orWhere('f.first_name', 'like', $search)
                    ->orWhere('f.last_name', 'like', $search)
                    ->orWhere('d.department_name', 'like', $search)
                    ->orWhere('j.nte_no', 'like', $search);
            });
        }

        $this->hrDecisionList = $cpar
            ->unionAll($result)
            ->orderByDesc('record_date')
            ->get();

        $this->offenseHistoryCounts = DB::table('cpar_assignments as b')
            ->join('employees as f', 'b.assigned_to', '=', 'f.id')
            ->join('cpar_investigations as c', 'b.id', '=', 'c.assigned_id')
            ->join('cpar_employee_disciplinary_records as h', 'c.id', '=', 'h.assignment_id')
            ->whereIn('b.record_type', [5, 10])
            ->where('f.branch_id', $this->branch_id)
            ->whereNotNull('h.id')
            ->whereRaw('JSON_LENGTH(h.decision_ids) != 1 OR JSON_EXTRACT(h.decision_ids, "$[0]") != 1')
            ->select(
                'f.employee_no',
                DB::raw('COUNT(DISTINCT h.id) as offense_count')
            )
            ->groupBy('f.employee_no')
            ->pluck('offense_count', 'f.employee_no')
            ->toArray();
    }

    public function updatedBranchId($value = '')
    {
        $this->branch_id = $value;
        $this->loadDecisionRecords();
    }

    public function viewCpar($id)
    {
        $this->dispatch('open-decision-cpar', id: $id);
    }

    public function viewResult($id)
    {
        $this->dispatch('open-decision-result', id: $id);
    }

    public function viewOffenseHistory($employee_no = '')
    {
        $this->dispatch('open-offense-history', employee_no: $employee_no);
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_decision_notif');
    }
}
