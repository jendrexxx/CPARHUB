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
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->join('employees as i', 'b.assigned_to', '=', 'i.id')
            ->leftJoin(
                'cpar_ir_requests as j',
                'b.id',
                '=',
                'j.assignment_ir_id'
            )
            ->leftJoin(
                'cpar_notice_to_explains as d',
                'j.id',
                '=',
                'd.assignment_id'
            )
            ->select(
                'a.id as record_id',
                'a.cpar_no as record_no',
                'a.reported_by',
                'a.date_open as record_date',
                DB::raw("'CPAR' as record_type"),
                'b.id as assignment_id',
                'b.assigned_to',
                // Employee
                'i.employee_no',
                DB::raw("
                CONCAT(i.first_name, ' ', i.last_name)
                as employee_name
            "),
                // Investigation
                'c.identified_cause',
                'c.provided_solution',
                'c.recommendation',
                'c.action_taken_by',
                'c.date_completed',
                'c.tat',
                // NTE / IR
                'd.nte_no',
                'j.ir_id',
                // Department / Status
                'g.department_name',
                'h.status_name',
                // Previous offense count
                DB::raw("
                    (
                        CASE
                            WHEN b.record_type = 10 THEN (
                                SELECT COUNT(*)
                                FROM cpar_employee_disciplinary_records r
                                INNER JOIN cpar_assignments ca
                                    ON ca.id = r.assignment_id
                                WHERE ca.assigned_to = b.assigned_to
                                AND ca.record_type = 5
                                AND r.status = 'FINAL'
                            )
                            ELSE 0
                        END
                    ) AS offense_count
                "))
            ->whereIn('b.status_id', [20, 25, 30])
            ->where('b.record_type', 5)
            ->where('i.branch_id', $this->branch_id);

        $result = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->join('employees as i', 'b.assigned_to', '=', 'i.id')
            ->leftJoin(
                'cpar_ir_requests as j',
                'b.id',
                '=',
                'j.assignment_ir_id'
            )
            ->leftJoin(
                'cpar_notice_to_explains as d',
                'j.id',
                '=',
                'd.assignment_id'
            )
            ->select(
                // 1
                'a.id as record_id',

                // 2
                'a.result_no as record_no',

                // 3
                'a.reported_by',

                // 4
                'a.date_reported as record_date',

                // 5
                DB::raw("'RESULT' as record_type"),

                // 6
                'b.id as assignment_id',

                // 7
                'b.assigned_to',

                // 8
                'i.employee_no',

                // 9
                DB::raw("
            CONCAT(i.first_name, ' ', i.last_name)
            as employee_name
            "),

                // 10
                DB::raw("NULL as identified_cause"),

                // 11
                DB::raw("NULL as provided_solution"),

                // 12
                DB::raw("NULL as recommendation"),

                // 13
                DB::raw("NULL as action_taken_by"),

                // 14
                DB::raw("NULL as date_completed"),

                // 15
                DB::raw("NULL as tat"),

                // 16
                'd.nte_no',

                // 17
                'j.ir_id',

                // 18
                'g.department_name',

                // 19
                'h.status_name',

                // 20
                DB::raw("
                    (
                        CASE
                            WHEN b.record_type = 10 THEN (
                                SELECT COUNT(*)
                                FROM cpar_employee_disciplinary_records r
                                INNER JOIN cpar_assignments ca
                                    ON ca.id = r.assignment_id
                                WHERE ca.assigned_to = b.assigned_to
                                AND ca.record_type = 10
                                AND r.status = 'FINAL'
                            )
                            ELSE 0
                        END
                    ) AS offense_count
                "))
            ->whereIn('b.status_id', [20, 25, 30])
            ->where('b.record_type', 10)
            ->where('i.branch_id', $this->branch_id);

        if (!empty($this->search)) {

            $search = '%' . $this->search . '%';

            $cpar->where(function ($q) use ($search) {

                $q->where('a.cpar_no', 'like', $search)
                    ->orWhere('a.reported_by', 'like', $search)
                    ->orWhere('i.employee_no', 'like', $search)
                    ->orWhere('i.first_name', 'like', $search)
                    ->orWhere('i.last_name', 'like', $search)
                    ->orWhere('g.department_name', 'like', $search)
                    ->orWhere('d.nte_no', 'like', $search);
            });


            $result->where(function ($q) use ($search) {

                $q->where('a.result_no', 'like', $search)
                    ->orWhere('a.reported_by', 'like', $search)
                    ->orWhere('i.employee_no', 'like', $search)
                    ->orWhere('i.first_name', 'like', $search)
                    ->orWhere('i.last_name', 'like', $search)
                    ->orWhere('g.department_name', 'like', $search);
            });
        }
        $this->hrDecisionList = $cpar
            ->unionAll($result)
            ->orderByDesc('record_date')
            ->get();
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

    public function render()
    {
        return view('livewire.admin.hr.hr_decision_notif');
    }
}
