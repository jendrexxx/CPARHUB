<?php

namespace App\Livewire\Admin\Cpar;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class CparTbl extends Component
{
    use WithPagination;

    public $cpar_request_count = '';
    public $result_request_count = '';
    public $employee_no = '';
    public $acknowledged_cpar = '';
    public $hr_decision_count = '';
    public $branch = '';
    public $branch_id = '';
    public $branch_name = '';
    public $branches = [];
    public $offenseTab = 'ALL';
    public $offenseCategories = [];
    public $disciplinaryCategories = [];
    public $department_name = '';
    // SEARCH
    public $search = '';
    // PAGINATION
    public $perPage = 5;

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->employee_no = $info->employee_no;
            $this->branch_id = $info->branch_id;
            $this->department_name = $info->department_name;
        }
        $this->offenseCategories = DB::table('cpar_decision_categories')
            ->orderBy('id')
            ->get();
        $this->disciplinaryCategories = DB::table('cpar_disciplinary_categories')
            ->orderBy('id')
            ->get();
        $this->result_request_count = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->where('employee_no', $this->employee_no)
            ->count();
        $this->branches = DB::table('branches')
            ->orderBy('branch_name')
            ->get();
    }

    public function refreshPreviousOffense()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
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
        $cparQuery = DB::table('cpar_request_forms as a')

            ->join(
                'cpar_assignments as b',
                'a.id',
                '=',
                'b.cpar_id'
            )

            // Original department of CPAR
            ->leftJoin(
                'departments as c',
                'a.department_id',
                '=',
                'c.id'
            )

            ->leftJoin(
                'cpar_statuses as d',
                'b.status_id',
                '=',
                'd.id'
            )

            // Reporter
            ->leftJoin(
                'employees as r',
                'a.employee_no',
                '=',
                'r.employee_no'
            )

            // Assigned employee
            ->leftJoin(
                'employees as e',
                'b.assigned_to',
                '=',
                'e.id'
            )

            ->leftJoin(
                'cpar_ir_requests as f',
                'b.id',
                '=',
                'f.assignment_ir_id'
            )

            ->leftJoin(
                'cpar_employee_disciplinary_records as g',
                'b.id',
                '=',
                'g.assignment_id'
            )

            ->leftJoin(
                'cpar_notice_to_explains as h',
                'f.id',
                '=',
                'h.assignment_id'
            )

            ->leftJoin(
                'cpar_decision_categories as i',
                DB::raw("
                JSON_CONTAINS(
                    g.decision_ids,
                    JSON_QUOTE(CAST(i.id AS CHAR))
                )
            "),
                '=',
                DB::raw('1')
            )

            ->leftJoin(
                'cpar_disciplinary_categories as j',
                DB::raw("
                JSON_CONTAINS(
                    g.discipline_ids,
                    JSON_QUOTE(CAST(j.id AS CHAR))
                )
            "),
                '=',
                DB::raw('1')
            )

            ->leftJoin(
                'cpar_offense_levels as k',
                DB::raw("
                JSON_CONTAINS(
                    g.offense_ids,
                    JSON_QUOTE(CAST(k.id AS CHAR))
                )
            "),
                '=',
                DB::raw('1')
            )

            ->select(
                'a.id',
                'a.cpar_no as record_no',

                'a.reported_by',

                // Reporter information
                'r.employee_no as reporter_employee_no',
                'r.department_name as reporter_department_name',

                DB::raw("
                CONCAT(
                    COALESCE(r.first_name, ''),
                    ' ',
                    COALESCE(r.last_name, '')
                ) as reporter_name
            "),

                'a.date_open as record_date',

                DB::raw("'CPAR' as record_type"),

                'b.id as assignment_id',
                'b.cpar_id as record_id',
                'b.assigned_to',
                'b.status_id',

                // Assigned employee information
                'e.employee_no',
                'e.branch_id',
                'e.department_name as assigned_department_name',

                DB::raw("
                CONCAT(
                    COALESCE(e.first_name, ''),
                    ' ',
                    COALESCE(e.last_name, '')
                ) as employee_name
            "),

                // Original department of CPAR
                'c.department_name as department_name',

                DB::raw("
                MAX(g.incident_date) as incident_date
            "),

                DB::raw("
                MAX(g.valid_until) as valid_until
            "),

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

            ->where(function ($query) {
                $query
                    ->where(
                        'r.department_name',
                        $this->department_name
                    )
                    ->orWhere(
                        'e.department_name',
                        $this->department_name
                    );
            })

            ->groupBy(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',

                // Reporter
                'r.employee_no',
                'r.first_name',
                'r.last_name',
                'r.department_name',

                'b.id',
                'b.cpar_id',
                'b.assigned_to',
                'b.status_id',

                // Assigned employee
                'e.employee_no',
                'e.first_name',
                'e.last_name',
                'e.branch_id',
                'e.department_name',

                'c.department_name'
            );


        $resultQuery = DB::table('result_error_forms as a')

            ->join(
                'cpar_assignments as b',
                'a.id',
                '=',
                'b.result_id'
            )

            // Original department of RESULT
            ->leftJoin(
                'departments as c',
                'a.department_id',
                '=',
                'c.id'
            )

            ->leftJoin(
                'cpar_statuses as d',
                'b.status_id',
                '=',
                'd.id'
            )
            ->leftJoin(
                'employees as r',
                'a.employee_no',
                '=',
                'r.employee_no'
            )


            // Assigned employee
            ->leftJoin(
                'employees as e',
                'b.assigned_to',
                '=',
                'e.id'
            )

            ->leftJoin(
                'cpar_ir_requests as f',
                'b.id',
                '=',
                'f.assignment_ir_id'
            )

            ->leftJoin(
                'cpar_employee_disciplinary_records as g',
                'b.id',
                '=',
                'g.assignment_id'
            )

            ->leftJoin(
                'cpar_notice_to_explains as h',
                'f.id',
                '=',
                'h.assignment_id'
            )

            ->leftJoin(
                'cpar_decision_categories as i',
                DB::raw("
                JSON_CONTAINS(
                    g.decision_ids,
                    JSON_QUOTE(CAST(i.id AS CHAR))
                )
            "),
                '=',
                DB::raw('1')
            )

            ->leftJoin(
                'cpar_disciplinary_categories as j',
                DB::raw("
                JSON_CONTAINS(
                    g.discipline_ids,
                    JSON_QUOTE(CAST(j.id AS CHAR))
                )
            "),
                '=',
                DB::raw('1')
            )

            ->leftJoin(
                'cpar_offense_levels as k',
                DB::raw("
                JSON_CONTAINS(
                    g.offense_ids,
                    JSON_QUOTE(CAST(k.id AS CHAR))
                )
            "),
                '=',
                DB::raw('1')
            )

            ->select(
                'a.id',
                'a.result_no as record_no',

                'a.reported_by',

                // Reporter information
                'r.employee_no as reporter_employee_no',
                'r.department_name as reporter_department_name',

                DB::raw("
                CONCAT(
                    COALESCE(r.first_name, ''),
                    ' ',
                    COALESCE(r.last_name, '')
                ) as reporter_name
            "),

                'a.date_reported as record_date',

                DB::raw("'RESULT' as record_type"),

                'b.id as assignment_id',
                'b.result_id as record_id',
                'b.assigned_to',
                'b.status_id',

                // Assigned employee information
                'e.employee_no',
                'e.branch_id',
                'e.department_name as assigned_department_name',

                DB::raw("
                CONCAT(
                    COALESCE(e.first_name, ''),
                    ' ',
                    COALESCE(e.last_name, '')
                ) as employee_name
            "),

                // Original department of RESULT
                'c.department_name as department_name',

                DB::raw("
                MAX(g.incident_date) as incident_date
            "),

                DB::raw("
                MAX(g.valid_until) as valid_until
            "),

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
            ->where(function ($query) {
                $query
                    ->where(
                        'r.department_name',
                        $this->department_name
                    )
                    ->orWhere(
                        'e.department_name',
                        $this->department_name
                    );
            })
            ->groupBy(
                'a.id',
                'a.result_no',
                'a.reported_by',
                'a.date_reported',

                // Reporter
                'r.employee_no',
                'r.first_name',
                'r.last_name',
                'r.department_name',

                'b.id',
                'b.result_id',
                'b.assigned_to',
                'b.status_id',

                // Assigned employee
                'e.employee_no',
                'e.first_name',
                'e.last_name',
                'e.branch_id',
                'e.department_name',
                'c.department_name'
            );


        $query = DB::query()
            ->fromSub(
                $cparQuery->unionAll($resultQuery),
                'records'
            );


        $query->when(
            trim($this->search ?? '') !== '',
            function ($query) {

                $search = '%' . trim($this->search) . '%';

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'record_no',
                        'like',
                        $search
                    )

                        ->orWhere(
                            'reported_by',
                            'like',
                            $search
                        )

                        ->orWhere(
                            'reporter_name',
                            'like',
                            $search
                        )

                        ->orWhere(
                            'reporter_department_name',
                            'like',
                            $search
                        )

                        ->orWhere(
                            'employee_name',
                            'like',
                            $search
                        )

                        ->orWhere(
                            'employee_no',
                            'like',
                            $search
                        )

                        ->orWhere(
                            'assigned_department_name',
                            'like',
                            $search
                        )

                        ->orWhere(
                            'record_type',
                            'like',
                            $search
                        );
                });
            }
        );


        $query->orderByDesc('assignment_id');


        $cpar_offense = $query->paginate(
            $this->perPage
        );


        return view(
            'livewire.admin.cpar.cpar_tbl',
            [
                'cpar_offense' => $cpar_offense,
            ]
        );
    }
}
