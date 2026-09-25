<?php

namespace App\Livewire\Admin\Hr;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HrOffenseTbl extends Component
{
    public $offenseList = [];

    protected $listeners = [
        'open-offense-history' => 'open'
    ];

    public function open($employee_no = '')
    {
        $this->offenseList = collect();

        $cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_investigations as c', 'b.id', '=', 'c.assigned_id')
            ->leftJoin('cpar_ir_requests as d', 'b.id', '=', 'd.assignment_ir_id')
            ->leftJoin('cpar_notice_to_explains as e', 'd.id', '=', 'e.assignment_id')
            ->join('departments as f', 'a.department_id', '=', 'f.id')
            ->join('cpar_statuses as g', 'b.status_id', '=', 'g.id')
            ->join('employees as h', 'b.assigned_to', '=', 'h.id')
            ->join('priority_levels as i', 'a.priority_level', '=', 'i.id')
            ->join('cpar_employee_disciplinary_records as j', 'c.id', '=', 'j.assignment_id')
            ->leftJoin('cpar_decision_categories as k', DB::raw("JSON_CONTAINS(j.decision_ids, JSON_QUOTE(CAST(k.id AS CHAR)))"), '=', DB::raw('1'))
            ->leftJoin('cpar_disciplinary_categories as l', DB::raw("JSON_CONTAINS(j.discipline_ids, JSON_QUOTE(CAST(l.id AS CHAR)))"), '=', DB::raw('1'))
            ->leftJoin('cpar_offense_levels as m', DB::raw("JSON_CONTAINS(j.offense_ids, JSON_QUOTE(CAST(m.id AS CHAR)))"), '=', DB::raw('1'))
            ->select(
                'a.id as record_id',
                'a.cpar_no as record_no',
                'a.reported_by',
                'a.date_open as record_date',
                DB::raw("'CPAR' as record_type"),
                'b.id as assignment_id',
                'b.assigned_to',
                'h.employee_no',
                'h.employee_no as assigned_emp_id',
                DB::raw("
                    CONCAT(
                        COALESCE(h.last_name, ''),
                        ', ',
                        COALESCE(h.first_name, '')
                    ) as employee_name
                "),
                'c.identified_cause',
                'c.provided_solution',
                'c.recommendation',
                'c.action_taken_by',
                'c.date_completed',
                'c.tat',
                'd.ir_id',
                'e.nte_no',
                'f.department_name',
                'g.status_name',
                'i.priority_name',
                'j.id as disciplinary_id',
                'j.status as disciplinary_status',
                'j.incident_date',
                'j.valid_until',
                'k.decision_name',
                'l.category_name',
                'm.offense_name'
            )
            ->where('b.record_type', 5);

        $result = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->leftJoin('cpar_investigations as c', 'b.id', '=', 'c.assigned_id')
            ->leftJoin('cpar_ir_requests as d', 'b.id', '=', 'd.assignment_ir_id')
            ->leftJoin('cpar_notice_to_explains as e', 'd.id', '=', 'e.assignment_id')
            ->join('departments as f', 'a.department_id', '=', 'f.id')
            ->join('cpar_statuses as g', 'b.status_id', '=', 'g.id')
            ->join('employees as h', 'b.assigned_to', '=', 'h.id')
            ->join('priority_levels as i', 'a.priority_level', '=', 'i.id')
            ->join('cpar_employee_disciplinary_records as j', 'c.id', '=', 'j.assignment_id')
            ->leftJoin('cpar_decision_categories as k', DB::raw("JSON_CONTAINS(j.decision_ids, JSON_QUOTE(CAST(k.id AS CHAR)))"), '=', DB::raw('1'))
            ->leftJoin('cpar_disciplinary_categories as l', DB::raw("JSON_CONTAINS(j.discipline_ids, JSON_QUOTE(CAST(l.id AS CHAR)))"), '=', DB::raw('1'))
            ->leftJoin('cpar_offense_levels as m', DB::raw("JSON_CONTAINS(j.offense_ids, JSON_QUOTE(CAST(m.id AS CHAR)))"), '=', DB::raw('1'))
            ->select(
                'a.id as record_id',
                'a.result_no as record_no',
                'a.reported_by',
                'a.date_reported as record_date',
                DB::raw("'RESULT' as record_type"),
                'b.id as assignment_id',
                'b.assigned_to',
                'h.employee_no',
                'h.employee_no as assigned_emp_id',
                DB::raw("
                    CONCAT(
                        COALESCE(h.last_name, ''),
                        ', ',
                        COALESCE(h.first_name, '')
                    ) as employee_name
                "),
                'c.identified_cause',
                'c.provided_solution',
                'c.recommendation',
                'c.action_taken_by',
                'c.date_completed',
                'c.tat',
                'd.ir_id',
                'e.nte_no',
                'f.department_name',
                'g.status_name',
                'i.priority_name',
                'j.id as disciplinary_id',
                'j.status as disciplinary_status',
                'j.incident_date',
                'j.valid_until',
                'k.decision_name',
                'l.category_name',
                'm.offense_name'
            )
            ->where('b.record_type', 10)
            ->where(function ($query) {
                $query->whereNull('j.decision_ids')
                    ->orWhereRaw("NOT JSON_CONTAINS(j.decision_ids, JSON_QUOTE('1'))");
            });

        if (!empty($employee_no)) {
            $cpar->where('h.employee_no', $employee_no);
            $result->where('h.employee_no', $employee_no);
        }

        $this->offenseList = $cpar
            ->unionAll($result)
            ->orderByDesc('record_date')
            ->get();

        $this->modal('HROffenseModal')->show();
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_offense_tbl');
    }
}
