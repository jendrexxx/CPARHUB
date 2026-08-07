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

    protected $listeners = [
        'refreshDecisionRecords' => 'loadDecisionRecords',
    ];

    public function mount()
    {
        $this->loadDecisionRecords();
    }

    public function updatedSearch()
    {
        $this->loadDecisionRecords();
    }

    public function loadDecisionRecords()
    {
        $this->hrDecisionList = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_investigations as c', 'b.id', '=', 'c.assigned_id')
            ->leftJoin('cpar_notice_to_explains as d', 'b.id', '=', 'd.assignment_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            // Employee
            ->join('employees as i', 'b.assigned_to', '=', 'i.id')
            ->leftJoin('cpar_ir_requests as j', 'b.id', '=', 'j.assignment_ir_id')
            ->select(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
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
                // NTE
                'd.nte_no',
                'g.department_name',
                'h.status_name',
                'j.ir_id',
                // Previous offense count
                DB::raw("
                (
                    SELECT COUNT(*)
                    FROM cpar_employee_disciplinary_records r
                    INNER JOIN cpar_assignments ca
                        ON ca.id = r.assignment_id
                    WHERE ca.assigned_to = b.assigned_to
                    AND r.status = 'FINAL'
                ) as offense_count
                ")
            )
            ->whereIn('b.status_id', [35, 40])
            ->when(
                !empty($this->search),
                function ($query) {
                    $search = '%' . $this->search . '%';
                    $query->where(function ($q) use ($search) {

                        $q->where('a.cpar_no', 'like', $search)

                            ->orWhere(
                                'a.reported_by',
                                'like',
                                $search
                            )

                            ->orWhere(
                                'i.employee_no',
                                'like',
                                $search
                            )

                            ->orWhere(
                                'i.first_name',
                                'like',
                                $search
                            )

                            ->orWhere(
                                'i.last_name',
                                'like',
                                $search
                            )

                            ->orWhere(
                                'g.department_name',
                                'like',
                                $search
                            )

                            ->orWhere(
                                'd.nte_no',
                                'like',
                                $search
                            );
                    });
                }
            )
            ->orderByDesc('b.id')
            ->get();
    }

    public function viewCpar($id)
    {
        $this->dispatch('open-decision-cpar', id: $id);
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_decision_notif');
    }
}
