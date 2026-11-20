<?php

namespace App\Livewire\Admin\Lab;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\employee;

#[Layout('components.layouts.app')]
class Tabs extends Component
{
    use WithPagination;
    public $search = '';
    public $perPage = 5;
    public $department_name = '';

    public function mount()
    {
        $user = Auth::user();

        $info = employee::where('email', $user->email)->first();

        if ($info) {
            $this->department_name = $info->department_name;
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedOffenseTab()
    {
        $this->search = '';

        $this->resetPage();
    }

    public function updatedOffenseFilter()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedDepartmentFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedValidityFilter()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';

        $this->resetPage();
    }

    public function render()
    {
        $credentials = Auth::user()?->employee;
        $cparQuery = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('departments as c', 'a.department_id', '=', 'c.id')
            ->leftJoin('cpar_statuses as d', 'b.status_id', '=', 'd.id')
            ->leftJoin('employees as r', 'b.id', '=', 'r.id')
            ->leftJoin('employees as e', 'b.assigned_to', '=', 'e.id')
            ->leftJoin('cpar_ir_requests as f', 'b.id', '=', 'f.assignment_ir_id')
            ->leftJoin('cpar_employee_disciplinary_records as g', 'b.id', '=', 'g.assignment_id')
            ->leftJoin('cpar_notice_to_explains as h', 'f.id', '=', 'h.assignment_id')
            ->leftJoin(
                'cpar_decision_categories as i',
                DB::raw("JSON_CONTAINS(g.decision_ids, JSON_QUOTE(CAST(i.id AS CHAR)))"),
                '=',
                DB::raw('1')
            )
            ->leftJoin(
                'cpar_disciplinary_categories as j',
                DB::raw("JSON_CONTAINS(g.discipline_ids, JSON_QUOTE(CAST(j.id AS CHAR)))"),
                '=',
                DB::raw('1')
            )
            ->leftJoin(
                'cpar_offense_levels as k',
                DB::raw("JSON_CONTAINS(g.offense_ids, JSON_QUOTE(CAST(k.id AS CHAR)))"),
                '=',
                DB::raw('1')
            )
            ->leftJoin('employees as l', 'b.dept_head_assigned', '=', 'l.id')
            ->select(
                'a.id',
                'a.cpar_no as record_no',
                'a.reported_by',
                'r.employee_no as reporter_employee_no',
                'r.department_name as reporter_department_name',

                DB::raw("
                TRIM(CONCAT(
                    COALESCE(r.first_name, ''),
                    ' ',
                    COALESCE(r.last_name, '')
                )) as reporter_name
            "),

                'a.date_open as record_date',
                DB::raw("'CPAR' as record_type"),

                'b.id as assignment_id',
                'b.cpar_id as record_id',
                'b.assigned_to',
                'b.dept_head_assigned',
                'b.status_id',

                'e.employee_no',
                'e.branch_id',
                'e.department_name as assigned_department_name',

                'l.employee_no as dept_head_employee_no',

                DB::raw("
                CASE
                    WHEN e.id IS NOT NULL
                        AND TRIM(CONCAT(
                            COALESCE(e.first_name, ''),
                            ' ',
                            COALESCE(e.last_name, '')
                        )) <> ''
                    THEN TRIM(CONCAT(
                        COALESCE(e.first_name, ''),
                        ' ',
                        COALESCE(e.last_name, '')
                    ))
                    WHEN l.id IS NOT NULL
                        AND TRIM(CONCAT(
                            COALESCE(l.first_name, ''),
                            ' ',
                            COALESCE(l.last_name, '')
                        )) <> ''
                    THEN TRIM(CONCAT(
                        COALESCE(l.first_name, ''),
                        ' ',
                        COALESCE(l.last_name, '')
                    ))
                    ELSE NULL
                END as employee_name
            "),

                DB::raw("
                NULLIF(
                    TRIM(CONCAT(
                        COALESCE(l.first_name, ''),
                        ' ',
                        COALESCE(l.last_name, '')
                    )),
                    ''
                ) as dept_head_name
            "),

                'c.department_name as department_name',

                DB::raw("MAX(g.incident_date) as incident_date"),
                DB::raw("MAX(g.valid_until) as valid_until"),

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT d.status_name
                    ORDER BY d.id
                    SEPARATOR ', '
                ) as status_name
            "),

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT h.nte_no
                    ORDER BY h.id
                    SEPARATOR ', '
                ) as nte_no
            "),

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT f.ir_id
                    ORDER BY f.id
                    SEPARATOR ', '
                ) as ir_id
            "),

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT i.decision_name
                    ORDER BY i.id
                    SEPARATOR ', '
                ) as decision_name
            "),

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT j.category_name
                    ORDER BY j.id
                    SEPARATOR ', '
                ) as category_name
            "),

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT k.offense_name
                    ORDER BY k.id
                    SEPARATOR ', '
                ) as offense_name
            ")
            )
            ->where('b.record_type', 5)
            ->where('b.status_id', '!=', 50)
            ->where('r.branch_id', $credentials->branch_id)
            ->groupBy(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'r.employee_no',
                'r.first_name',
                'r.last_name',
                'r.department_name',
                'b.id',
                'b.cpar_id',
                'b.assigned_to',
                'b.dept_head_assigned',
                'b.status_id',
                'e.id',
                'e.employee_no',
                'e.first_name',
                'e.last_name',
                'e.branch_id',
                'e.department_name',
                'l.id',
                'l.employee_no',
                'l.first_name',
                'l.last_name',
                'c.department_name'
            );

        $resultQuery = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->leftJoin('departments as c', 'a.department_id', '=', 'c.id')
            ->leftJoin('cpar_statuses as d', 'b.status_id', '=', 'd.id')
            ->leftJoin('employees as r', 'b.id', '=', 'r.id')
            ->leftJoin('employees as e', 'b.assigned_to', '=', 'e.id')
            ->leftJoin('cpar_ir_requests as f', 'b.id', '=', 'f.assignment_ir_id')
            ->leftJoin('cpar_employee_disciplinary_records as g', 'b.id', '=', 'g.assignment_id')
            ->leftJoin('cpar_notice_to_explains as h', 'f.id', '=', 'h.assignment_id')
            ->leftJoin(
                'cpar_decision_categories as i',
                DB::raw("JSON_CONTAINS(g.decision_ids, JSON_QUOTE(CAST(i.id AS CHAR)))"),
                '=',
                DB::raw('1')
            )
            ->leftJoin(
                'cpar_disciplinary_categories as j',
                DB::raw("JSON_CONTAINS(g.discipline_ids, JSON_QUOTE(CAST(j.id AS CHAR)))"),
                '=',
                DB::raw('1')
            )
            ->leftJoin(
                'cpar_offense_levels as k',
                DB::raw("JSON_CONTAINS(g.offense_ids, JSON_QUOTE(CAST(k.id AS CHAR)))"),
                '=',
                DB::raw('1')
            )
            ->leftJoin('employees as l', 'b.dept_head_assigned', '=', 'l.id')
            ->select(
                'a.id',
                'a.result_no as record_no',
                'a.reported_by',
                'r.employee_no as reporter_employee_no',
                'r.department_name as reporter_department_name',

                DB::raw("
                TRIM(CONCAT(
                    COALESCE(r.first_name, ''),
                    ' ',
                    COALESCE(r.last_name, '')
                )) as reporter_name
            "),

                'a.date_reported as record_date',
                DB::raw("'RESULT' as record_type"),

                'b.id as assignment_id',
                'b.result_id as record_id',
                'b.assigned_to',
                'b.dept_head_assigned',
                'b.status_id',

                'e.employee_no',
                'e.branch_id',
                'e.department_name as assigned_department_name',

                'l.employee_no as dept_head_employee_no',

                DB::raw("
                CASE
                    WHEN e.id IS NOT NULL
                        AND TRIM(CONCAT(
                            COALESCE(e.first_name, ''),
                            ' ',
                            COALESCE(e.last_name, '')
                        )) <> ''
                    THEN TRIM(CONCAT(
                        COALESCE(e.first_name, ''),
                        ' ',
                        COALESCE(e.last_name, '')
                    ))
                    WHEN l.id IS NOT NULL
                        AND TRIM(CONCAT(
                            COALESCE(l.first_name, ''),
                            ' ',
                            COALESCE(l.last_name, '')
                        )) <> ''
                    THEN TRIM(CONCAT(
                        COALESCE(l.first_name, ''),
                        ' ',
                        COALESCE(l.last_name, '')
                    ))
                    ELSE NULL
                END as employee_name
            "),

                DB::raw("
                NULLIF(
                    TRIM(CONCAT(
                        COALESCE(l.first_name, ''),
                        ' ',
                        COALESCE(l.last_name, '')
                    )),
                    ''
                ) as dept_head_name
            "),

                'c.department_name as department_name',

                DB::raw("MAX(g.incident_date) as incident_date"),
                DB::raw("MAX(g.valid_until) as valid_until"),

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT d.status_name
                    ORDER BY d.id
                    SEPARATOR ', '
                ) as status_name
            "),

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT h.nte_no
                    ORDER BY h.id
                    SEPARATOR ', '
                ) as nte_no
            "),

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT f.ir_id
                    ORDER BY f.id
                    SEPARATOR ', '
                ) as ir_id
            "),

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT i.decision_name
                    ORDER BY i.id
                    SEPARATOR ', '
                ) as decision_name
            "),

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT j.category_name
                    ORDER BY j.id
                    SEPARATOR ', '
                ) as category_name
            "),

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT k.offense_name
                    ORDER BY k.id
                    SEPARATOR ', '
                ) as offense_name
            ")
            )
            ->where('b.record_type', 10)
            ->where('b.status_id', '!=', 50)
            ->where('r.branch_id', $credentials->branch_id)
            ->groupBy(
                'a.id',
                'a.result_no',
                'a.reported_by',
                'a.date_reported',
                'r.employee_no',
                'r.first_name',
                'r.last_name',
                'r.department_name',
                'b.id',
                'b.result_id',
                'b.assigned_to',
                'b.dept_head_assigned',
                'b.status_id',
                'e.id',
                'e.employee_no',
                'e.first_name',
                'e.last_name',
                'e.branch_id',
                'e.department_name',
                'l.id',
                'l.employee_no',
                'l.first_name',
                'l.last_name',
                'c.department_name'
            );

        $query = DB::query()
            ->fromSub(
                $cparQuery->unionAll($resultQuery),
                'records'
            );

        $search = trim($this->search ?? '');

        if ($search !== '') {
            $search = '%' . $search . '%';

            $query->where(function ($q) use ($search) {
                $q->where('record_no', 'like', $search)
                    ->orWhere('reported_by', 'like', $search)
                    ->orWhere('reporter_name', 'like', $search)
                    ->orWhere('reporter_employee_no', 'like', $search)
                    ->orWhere('reporter_department_name', 'like', $search)
                    ->orWhere('employee_name', 'like', $search)
                    ->orWhere('employee_no', 'like', $search)
                    ->orWhere('assigned_department_name', 'like', $search)
                    ->orWhere('dept_head_name', 'like', $search)
                    ->orWhere('dept_head_employee_no', 'like', $search)
                    ->orWhere('department_name', 'like', $search)
                    ->orWhere('record_type', 'like', $search)
                    ->orWhere('status_name', 'like', $search)
                    ->orWhere('decision_name', 'like', $search)
                    ->orWhere('category_name', 'like', $search)
                    ->orWhere('offense_name', 'like', $search)
                    ->orWhere('nte_no', 'like', $search)
                    ->orWhere('ir_id', 'like', $search);
            });
        }

        $query->orderByDesc('assignment_id');

        $cpar_offense = $query->paginate($this->perPage);

        return view(
            'livewire.admin.lab.tabs',
            [
                'cpar_offense' => $cpar_offense,
            ]
        );
    }
}
