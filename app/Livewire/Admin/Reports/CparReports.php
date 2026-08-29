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
        $query = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b','a.id','=','b.cpar_id')
            ->leftJoin('employees as c','b.assigned_to','=','c.id')
            ->leftJoin('departments as d','a.department_id','=','d.id')
            ->leftJoin('cpar_statuses as e','b.status_id','=','e.id')
            ->leftJoin('cpar_employee_disciplinary_records as f','b.id','=','f.assignment_id')
            ->leftJoin('cpar_decision_categories as g',
                DB::raw("JSON_CONTAINS(f.decision_ids,JSON_QUOTE(CAST(g.id AS CHAR)))"),'=',
                DB::raw('1'))
            ->leftJoin('branches as h','c.branch_id','=','h.id')
            ->select(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'b.id as assignment_id',
                'b.status_id',
                'c.employee_no',
                DB::raw("CONCAT(c.first_name,' ',c.last_name) as employee_name"),
                'c.department_name',
                'e.status_name',
                'h.branch_name',
                'f.incident_date',
                'f.valid_until',
                DB::raw("
                    GROUP_CONCAT(
                        DISTINCT g.decision_name
                        ORDER BY g.id
                        SEPARATOR ', '
                    ) as decision_name")
            )->whereIn('b.status_id', [50, 55]);
            $query->when(
                !empty($this->search),
                function ($query) {
                    $search = '%' . trim($this->search) . '%';
                    $query->where(function ($q) use ($search) {
                        $q->where(
                            'a.cpar_no',
                            'like',
                            $search
                        )
                        ->orWhere(
                            'a.reported_by',
                            'like',
                            $search
                        )
                        ->orWhere(
                            'c.employee_no',
                            'like',
                            $search
                        )
                        ->orWhere(
                            'c.first_name',
                            'like',
                            $search
                        )
                        ->orWhere(
                            'c.last_name',
                            'like',
                            $search
                        )
                        ->orWhere(
                            'c.department_name',
                            'like',
                            $search
                        )
                        ->orWhere(
                            'h.branch_name',
                            'like',
                            $search
                        )
                        ->orWhere(
                            'e.status_name',
                            'like',
                            $search
                        )
                        ->orWhere(
                            'g.decision_name',
                            'like',
                            $search
                        );
                    });
                }
            );
            $query->when(
                $this->branchFilter !== 'ALL',
                function ($query) {
                    $query->where(
                        'c.branch_id',
                        $this->branchFilter
                    );
                }
            );
            $query->when(
                $this->departmentFilter !== 'ALL',
                function ($query) {
                    $query->where(
                        'a.department_id',
                        $this->departmentFilter
                    );
                }
            );
            $query->when(
                $this->statusFilter !== 'ALL',
                function ($query) {
                    $query->where(
                        'b.status_id',
                        $this->statusFilter
                    );
                }
            );
            $query->when(
                $this->categoryFilter !== 'ALL',
                function ($query) {
                    $query->whereRaw(
                        "
                        JSON_CONTAINS(
                            f.decision_ids,
                            JSON_QUOTE(
                                CAST(? AS CHAR)
                            )
                        )
                        ",
                        [
                            $this->categoryFilter
                        ]
                    );
                }
            );
            $query->when(
                !empty($this->dateFrom),
                function ($query) {

                    $query->whereDate(
                        'a.date_open',
                        '>=',
                        $this->dateFrom
                    );
                }
            );
            $query->when(
                !empty($this->dateTo),
                function ($query) {

                    $query->whereDate(
                        'a.date_open',
                        '<=',
                        $this->dateTo
                    );
                }
            );
        $cparReports = $query
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
                'e.status_name',
                'h.branch_name',
                'f.incident_date',
                'f.valid_until'
            )
            ->orderByDesc('a.date_open')
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
        return view('livewire.admin.reports.cpar_reports',[
                'cparReports' => $cparReports,
                'branches' => $branches,
                'departments' => $departments,
                'statuses' => $statuses,
                'decisions' => $decisions,
            ])->layout('layouts.app');
    }
}
