<?php

namespace App\Livewire\Admin\Hr;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class HrMemoNotif extends Component
{
    use WithPagination;

    public $search = '';
    public $hrMemoList = '';
    public $branch_id = '';

    protected $listeners = [
        'refreshMemoRecords' => 'loadMemoRecords',
    ];

    public function mount($branch_id)
    {
        $this->branch_id = $branch_id;
        $this->loadMemoRecords();
    }

    public function loadMemoRecords()
    {
        $cpar = DB::table('cpar_request_forms as a')
            ->join(
                'cpar_assignments as b',
                'a.id',
                '=',
                'b.cpar_id'
            )
            ->join(
                'cpar_investigations as c',
                'b.id',
                '=',
                'c.assigned_id'
            )
            ->join(
                'departments as g',
                'a.department_id',
                '=',
                'g.id'
            )
            ->join(
                'cpar_statuses as h',
                'b.status_id',
                '=',
                'h.id'
            )
            ->join(
                'employees as i',
                'b.assigned_to',
                '=',
                'i.id'
            )
            ->join(
                'priority_levels as k',
                'a.priority_level',
                '=',
                'k.id'
            )
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
            ->leftJoin(
                'cpar_employee_disciplinary_records as m',
                'b.id',
                '=',
                'm.assignment_id'
            )
            ->select(
                'a.id as record_id',
                'a.cpar_no as record_no',
                'a.reported_by',
                'a.date_open as record_date',
                DB::raw("'CPAR' as record_type"),
                'b.id as assignment_id',
                'b.assigned_to',
                'i.employee_no',
                DB::raw("
                CONCAT(i.first_name, ' ', i.last_name)
                AS employee_name
            "),

                'c.identified_cause',
                'c.provided_solution',
                'c.recommendation',
                'c.action_taken_by',
                'c.date_completed',
                'c.tat',

                'd.nte_no',
                'j.ir_id',

                'g.department_name',
                'h.status_name',

                'k.priority_name',

                'm.id as disciplinary_id',
                'm.status as disciplinary_status',
                'm.incident_date',
                'm.valid_until',
                DB::raw("
                (
                    SELECT COUNT(*)
                    FROM cpar_employee_disciplinary_records AS dc
                    INNER JOIN cpar_assignments AS da
                        ON da.id = dc.assignment_id
                    WHERE da.assigned_to = b.assigned_to
                    AND dc.status = 'FINAL'
                    AND da.id <= b.id
                ) AS employee_disciplinary_count
            ")
            )
            ->where('b.status_id', 40)
            ->where('b.record_type', 5)
            ->where('i.branch_id', $this->branch_id);


        $result = DB::table('result_error_forms as a')
            ->join(
                'cpar_assignments as b',
                'a.id',
                '=',
                'b.result_id'
            )
            ->join(
                'departments as g',
                'a.department_id',
                '=',
                'g.id'
            )
            ->join(
                'cpar_statuses as h',
                'b.status_id',
                '=',
                'h.id'
            )
            ->join(
                'employees as i',
                'b.assigned_to',
                '=',
                'i.id'
            )
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
            ->join(
                'priority_levels as k',
                'a.priority_level',
                '=',
                'k.id'
            )
            ->leftJoin(
                'cpar_investigations as l',
                'b.id',
                '=',
                'l.assigned_id'
            )
            ->leftJoin(
                'cpar_employee_disciplinary_records as m',
                'b.id',
                '=',
                'm.assignment_id'
            )
            ->select(
                'a.id as record_id',
                'a.result_no as record_no',
                'a.reported_by',
                'a.date_reported as record_date',
                DB::raw("'RESULT' as record_type"),

                'b.id as assignment_id',
                'b.assigned_to',

                'i.employee_no',
                DB::raw("
                CONCAT(i.first_name, ' ', i.last_name)
                AS employee_name
                "),

                DB::raw("NULL as identified_cause"),
                DB::raw("NULL as provided_solution"),
                DB::raw("NULL as recommendation"),
                DB::raw("NULL as action_taken_by"),
                DB::raw("NULL as date_completed"),
                DB::raw("NULL as tat"),

                'd.nte_no',
                'j.ir_id',

                'g.department_name',
                'h.status_name',

                'k.priority_name',

                'm.id as disciplinary_id',
                'm.status as disciplinary_status',
                'm.incident_date',
                'm.valid_until',
                DB::raw("
                (
                    SELECT COUNT(*)
                    FROM cpar_employee_disciplinary_records AS dc
                    INNER JOIN cpar_assignments AS da
                        ON da.id = dc.assignment_id
                    WHERE da.assigned_to = b.assigned_to
                    AND dc.status = 'FINAL'
                    AND da.id <= b.id
                ) AS employee_disciplinary_count
            ")
            )
            ->where('b.status_id', 40)
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
                    ->orWhere('d.nte_no', 'like', $search)
                    ->orWhere('j.ir_id', 'like', $search);
            });

            $result->where(function ($q) use ($search) {
                $q->where('a.result_no', 'like', $search)
                    ->orWhere('a.reported_by', 'like', $search)
                    ->orWhere('i.employee_no', 'like', $search)
                    ->orWhere('i.first_name', 'like', $search)
                    ->orWhere('i.last_name', 'like', $search)
                    ->orWhere('g.department_name', 'like', $search)
                    ->orWhere('d.nte_no', 'like', $search)
                    ->orWhere('j.ir_id', 'like', $search);
            });
        }

        $this->hrMemoList = $cpar
            ->unionAll($result)
            ->orderByDesc('record_date')
            ->get();
    }


    public function updatedBranchId($value = '')
    {
        $this->branch_id = $value;
        $this->loadMemoRecords();
    }

    public function viewMemo($id = '')
    {
        $this->dispatch('open-memo-cpar', id: $id);
    }

    public function viewResultMemo($id = '')
    {
        $this->dispatch('open-memo-result', id: $id);
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_memo_notif');
    }
}
