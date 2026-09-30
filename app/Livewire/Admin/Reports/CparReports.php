<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class CparReports extends Component
{
    use WithPagination;

    public $search = '';
    public $branchFilter = 'ALL';
    public $departmentFilter = 'ALL';
    public $statusFilter = 'ALL';
    public $categoryFilter = 'ALL';
    public $recordFilter = 'ALL';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 10;

    public function updated($property)
    {
        if (in_array($property, [
            'search',
            'branchFilter',
            'departmentFilter',
            'statusFilter',
            'categoryFilter',
            'dateFrom',
            'dateTo',
            'recordFilter'
        ])) {
            $this->resetPage();
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->branchFilter = 'ALL';
        $this->departmentFilter = 'ALL';
        $this->statusFilter = 'ALL';
        $this->categoryFilter = 'ALL';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->recordFilter = 'ALL';
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';

        $this->resetPage();
    }

    public function render()
    {
        $hasActiveFilters =
            trim($this->search ?? '') !== ''
            || $this->branchFilter !== 'ALL'
            || $this->departmentFilter !== 'ALL'
            || $this->statusFilter !== 'ALL'
            || $this->categoryFilter !== 'ALL'
            || $this->recordFilter !== 'ALL'
            || !empty($this->dateFrom)
            || !empty($this->dateTo);

        $cparReports = new \Illuminate\Pagination\LengthAwarePaginator(
            collect(),
            0,
            $this->perPage,
            1,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        if ($hasActiveFilters) {
            $cparQuery = DB::table('cpar_request_forms as a')
                ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
                ->leftJoin('employees as c', 'b.assigned_to', '=', 'c.id')
                ->leftJoin('departments as d', 'a.department_id', '=', 'd.id')
                ->leftJoin('cpar_statuses as e', 'b.status_id', '=', 'e.id')
                ->leftJoin(
                    'cpar_employee_disciplinary_records as f',
                    'b.id',
                    '=',
                    'f.assignment_id'
                )
                ->leftJoin(
                    'cpar_decision_categories as g',
                    DB::raw("
                    JSON_CONTAINS(
                        f.decision_ids,
                        JSON_QUOTE(CAST(g.id AS CHAR))
                    )
                "),
                    '=',
                    DB::raw('1')
                )
                ->leftJoin('branches as h', 'c.branch_id', '=', 'h.id')
                ->leftJoin('cpar_memos as i', 'b.id', '=', 'i.assignment_id')
                ->leftJoin('employees as j', 'b.dept_head_assigned', '=', 'j.id')
                ->select([
                    'a.id',
                    'a.cpar_no as record_no',
                    'a.reported_by',
                    'a.date_open',
                    'b.id as assignment_id',
                    'b.status_id',
                    'c.employee_no',
                    DB::raw("
                    NULLIF(
                        TRIM(
                            CONCAT(
                                COALESCE(c.first_name, ''),
                                ' ',
                                COALESCE(c.last_name, '')
                            )
                        ),
                        ''
                    ) AS employee_name
                "),
                    'c.department_name',
                    'e.status_name',
                    'h.branch_name',
                    'c.branch_id',
                    'a.department_id',
                    'f.incident_date',
                    'f.valid_until',
                    DB::raw("
                    GROUP_CONCAT(
                        DISTINCT g.decision_name
                        ORDER BY g.id
                        SEPARATOR ', '
                    ) AS decision_name
                "),
                    DB::raw("
                    NULLIF(
                        TRIM(
                            CONCAT(
                                COALESCE(j.first_name, ''),
                                ' ',
                                COALESCE(j.last_name, '')
                            )
                        ),
                        ''
                    ) AS dept_head_name
                "),
                    DB::raw("'CPAR' AS record_type"),
                    'i.memo_attachment',
                ])
                ->groupBy([
                    'a.id',
                    'a.cpar_no',
                    'a.reported_by',
                    'a.date_open',
                    'b.id',
                    'b.status_id',
                    'c.employee_no',
                    'c.first_name',
                    'c.last_name',
                    'c.department_name',
                    'e.status_name',
                    'h.branch_name',
                    'c.branch_id',
                    'a.department_id',
                    'f.incident_date',
                    'f.valid_until',
                    'i.memo_attachment',
                    'j.first_name',
                    'j.last_name',
                ]);

            $resultQuery = DB::table('result_error_forms as a')
                ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
                ->leftJoin('employees as c', 'b.assigned_to', '=', 'c.id')
                ->leftJoin('departments as d', 'a.department_id', '=', 'd.id')
                ->leftJoin('cpar_statuses as e', 'b.status_id', '=', 'e.id')
                ->leftJoin(
                    'cpar_employee_disciplinary_records as f',
                    'b.id',
                    '=',
                    'f.assignment_id'
                )
                ->leftJoin(
                    'cpar_decision_categories as g',
                    DB::raw("
                    JSON_CONTAINS(
                        f.decision_ids,
                        JSON_QUOTE(CAST(g.id AS CHAR))
                    )
                "),
                    '=',
                    DB::raw('1')
                )
                ->leftJoin('branches as h', 'c.branch_id', '=', 'h.id')
                ->leftJoin('cpar_memos as i', 'b.id', '=', 'i.assignment_id')
                ->leftJoin('employees as j', 'b.dept_head_assigned', '=', 'j.id')
                ->select([
                    'a.id',
                    'a.result_no as record_no',
                    'a.reported_by',
                    'a.date_reported as date_open',
                    'b.id as assignment_id',
                    'b.status_id',
                    'c.employee_no',
                    DB::raw("
                    NULLIF(
                        TRIM(
                            CONCAT(
                                COALESCE(c.first_name, ''),
                                ' ',
                                COALESCE(c.last_name, '')
                            )
                        ),
                        ''
                    ) AS employee_name
                "),
                    'c.department_name',
                    'e.status_name',
                    'h.branch_name',
                    'c.branch_id',
                    'a.department_id',
                    'f.incident_date',
                    'f.valid_until',
                    DB::raw("
                    GROUP_CONCAT(
                        DISTINCT g.decision_name
                        ORDER BY g.id
                        SEPARATOR ', '
                    ) AS decision_name
                "),
                    DB::raw("
                    NULLIF(
                        TRIM(
                            CONCAT(
                                COALESCE(j.first_name, ''),
                                ' ',
                                COALESCE(j.last_name, '')
                            )
                        ),
                        ''
                    ) AS dept_head_name
                "),
                    DB::raw("'RESULT' AS record_type"),
                    'i.memo_attachment',
                ])
                ->groupBy([
                    'a.id',
                    'a.result_no',
                    'a.reported_by',
                    'a.date_reported',
                    'b.id',
                    'b.status_id',
                    'c.employee_no',
                    'c.first_name',
                    'c.last_name',
                    'c.department_name',
                    'e.status_name',
                    'h.branch_name',
                    'c.branch_id',
                    'a.department_id',
                    'f.incident_date',
                    'f.valid_until',
                    'i.memo_attachment',
                    'j.first_name',
                    'j.last_name',
                ]);

            $cparQuery->when(
                $this->recordFilter !== 'ALL',
                function ($query) {
                    $query->where('b.record_type', $this->recordFilter);
                }
            );

            $resultQuery->when(
                $this->recordFilter !== 'ALL',
                function ($query) {
                    $query->where('b.record_type', $this->recordFilter);
                }
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
                        $q->where('record_no', 'like', $search)
                            ->orWhere('reported_by', 'like', $search)
                            ->orWhere('employee_no', 'like', $search)
                            ->orWhere('employee_name', 'like', $search)
                            ->orWhere('dept_head_name', 'like', $search)
                            ->orWhere('department_name', 'like', $search)
                            ->orWhere('branch_name', 'like', $search)
                            ->orWhere('status_name', 'like', $search)
                            ->orWhere('decision_name', 'like', $search)
                            ->orWhere('record_type', 'like', $search);
                    });
                }
            );

            $query->when(
                $this->branchFilter !== 'ALL',
                function ($query) {
                    $query->where(
                        'branch_id',
                        $this->branchFilter
                    );
                }
            );

            $query->when(
                $this->departmentFilter !== 'ALL',
                function ($query) {
                    $query->where(
                        'department_id',
                        $this->departmentFilter
                    );
                }
            );

            $query->when(
                $this->statusFilter !== 'ALL',
                function ($query) {
                    if ((int) $this->statusFilter === 1) {
                        $query->where(function ($q) {
                            $q->where('status_id', '!=', 50)
                                ->orWhereNull('status_id');
                        });
                    } else {
                        $query->where(
                            'status_id',
                            $this->statusFilter
                        );
                    }
                }
            );

            $query->when(
                $this->categoryFilter !== 'ALL',
                function ($query) {
                    $decisionName = DB::table('cpar_decision_categories')
                        ->where(
                            'id',
                            $this->categoryFilter
                        )
                        ->value('decision_name');

                    if ($decisionName) {
                        $query->whereRaw(
                            "FIND_IN_SET(?, decision_name)",
                            [$decisionName]
                        );
                    }
                }
            );

            $query->when(
                !empty($this->dateFrom),
                function ($query) {
                    $query->whereDate(
                        'date_open',
                        '>=',
                        $this->dateFrom
                    );
                }
            );

            $query->when(
                !empty($this->dateTo),
                function ($query) {
                    $query->whereDate(
                        'date_open',
                        '<=',
                        $this->dateTo
                    );
                }
            );

            $cparReports = $query
                ->orderByDesc('assignment_id')
                ->paginate($this->perPage);
        }

        $branches = DB::table('branches')
            ->orderBy('branch_name')
            ->get();

        $departments = DB::table('departments')
            ->orderBy('department_name')
            ->get();

        $statuses = DB::table('cpar_statuses')
            ->whereIn('id', [1, 50])
            ->orderBy('id')
            ->get();

        $record = DB::table('record_types')
            ->orderByDesc('id')
            ->get();

        $decisions = DB::table('cpar_decision_categories')
            ->orderBy('id', 'asc')
            ->get();

        return view(
            'livewire.admin.reports.cpar_reports',
            [
                'cparReports' => $cparReports,
                'branches' => $branches,
                'departments' => $departments,
                'statuses' => $statuses,
                'decisions' => $decisions,
                'hasActiveFilters' => $hasActiveFilters,
                'record' => $record,
            ]
        );
    }
}
