<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class CparReports extends Component
{
    use WithPagination;

    public $search = '';
    public $branchFilter = 'ALL';
    public $departmentFilter = 'ALL';
    public $statusFilter = 'ALL';
    public $categoryFilter = 'ALL';
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
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('employees as c', 'b.assigned_to', '=', 'c.id')
            ->leftJoin('departments as d', 'a.department_id', '=', 'd.id')
            ->leftJoin('cpar_statuses as e', 'b.status_id', '=', 'e.id')
            ->leftJoin('cpar_employee_disciplinary_records as f','b.id','=','f.assignment_id')
            ->leftJoin('cpar_decision_categories as g',
                DB::raw("JSON_CONTAINS(f.decision_ids,JSON_QUOTE(CAST(g.id AS CHAR)))"),
                '=',
                DB::raw('1')
            )
            ->leftJoin('branches as h', 'c.branch_id', '=', 'h.id')
            ->select(
                'a.id',
                'a.cpar_no as record_no',
                'a.reported_by',
                'a.date_open',
                'b.id as assignment_id',
                'b.status_id',
                'c.employee_no',
                DB::raw("
                CONCAT(
                    COALESCE(c.first_name, ''),
                    ' ',
                    COALESCE(c.last_name, '')
                ) as employee_name
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
                ) as decision_name
            "),
                DB::raw("'CPAR' as record_type")
            )
            ->whereIn('b.status_id', [50, 55])
            ->groupBy(
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
                'c.branch_id',
                'a.department_id',
                'e.status_name',
                'h.branch_name',
                'f.incident_date',
                'f.valid_until'
            );

        $resultQuery = DB::table('result_error_forms as r')
            ->join('cpar_assignments as b','r.id','=','b.result_id')
            ->leftJoin('cpar_request_forms as a','b.cpar_id','=','a.id')
            ->leftJoin('employees as c','b.assigned_to','=','c.id')
            ->leftJoin('cpar_statuses as e','b.status_id','=','e.id')
            ->leftJoin('branches as h','c.branch_id','=','h.id')
            ->select(
                'r.id',
                'r.result_no as record_no',
                'r.reported_by',
                'r.created_at as date_open',
                'b.id as assignment_id',
                'b.status_id',
                'c.employee_no',
                DB::raw("
                CONCAT(
                    COALESCE(c.first_name, ''),
                    ' ',
                    COALESCE(c.last_name, '')
                ) as employee_name
            "),
                'c.department_name',
                'e.status_name',
                'h.branch_name',
                'c.branch_id',
                'a.department_id',
                DB::raw('NULL as incident_date'),
                DB::raw('NULL as valid_until'),
                DB::raw('NULL as decision_name'),
                DB::raw("'RESULT' as record_type")
            )
            ->whereIn('b.status_id', [50, 55]);

        $query = DB::query()
            ->fromSub(
                $cparQuery->unionAll($resultQuery),
                'records'
            );

        $query->when(
            !empty($this->search),
            function ($query) {
                $search = '%' . trim($this->search) . '%';

                $query->where(function ($q) use ($search) {
                    $q->where('record_no', 'like', $search)
                        ->orWhere('reported_by', 'like', $search)
                        ->orWhere('employee_no', 'like', $search)
                        ->orWhere('employee_name', 'like', $search)
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
                $query->where(
                    'status_id',
                    $this->statusFilter
                );
            }
        );

        $query->when(
            $this->categoryFilter !== 'ALL',
            function ($query) {
                $query->whereRaw(
                    "FIND_IN_SET(?, decision_name)",
                    [$this->categoryFilter]
                );
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
            ->orderByDesc('date_open')
            ->paginate($this->perPage);

        $branches = DB::table('branches')
            ->orderBy('branch_name')
            ->get();

        $departments = DB::table('departments')
            ->orderBy('department_name')
            ->get();

        $statuses = DB::table('cpar_statuses')
            ->whereIn('id', [50, 55])
            ->orderBy('status_name')
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
            ]
        )->layout('layouts.app');
    }
}
