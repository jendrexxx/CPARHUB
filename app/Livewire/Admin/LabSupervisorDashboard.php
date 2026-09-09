<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class LabSupervisorDashboard extends Component
{
    public $cpar_request_count = '';
    public $offenseTab = 'ALL';
    public $offenseCategories = [];
    public $search = '';
    public $cpar_offense = '';
    public $offenseFilter = 'ALL';
    public $categoryFilter = 'ALL';
    public $departmentFilter = 'ALL';
    public $statusFilter = 'ALL';
    public $validityFilter = 'ALL';
    public $disciplinaryCategories = [];
    protected $listeners = [
        'refreshLABCount' => 'loadLABCount',
    ];

    public function mount()
    {
        $this->offenseCategories = DB::table('cpar_decision_categories')
            ->orderBy('id')
            ->get();
        $this->disciplinaryCategories = DB::table('cpar_disciplinary_categories')
            ->orderBy('id')
            ->get();
        $this->loadLABCount();
        $this->loadPreviousOffense();
    }

    public function loadLABCount()
    {
        // CPAR count
        $cparCount = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->join('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->where('b.status_id', 35)
            ->count();

        // Result Error count
        $resultCount = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->where('b.status_id', 35)
            ->count();

        // Combined count
        $this->cpar_request_count = $cparCount + $resultCount;
    }

    public function updatedOffenseTab()
    {
        $this->search = '';
        $this->loadPreviousOffense();
    }

    public function loadPreviousOffense()
    {
        $this->cpar_offense = DB::table('cpar_request_forms as a')

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

            ->leftJoin(
                'cpar_notice_to_explains as d',
                'b.id',
                '=',
                'd.assignment_id'
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
                'cpar_employee_disciplinary_records as k',
                'b.id',
                '=',
                'k.assignment_id'
            )

            // OFFENSE
            ->leftJoin(
                'cpar_decision_categories as l',
                DB::raw("
                JSON_CONTAINS(
                    k.offense_ids,
                    JSON_QUOTE(CAST(l.id AS CHAR))
                )
            "),
                '=',
                DB::raw('1')
            )

            // CATEGORY
            ->leftJoin(
                'cpar_disciplinary_categories as m',
                DB::raw("
                JSON_CONTAINS(
                    k.discipline_ids,
                    JSON_QUOTE(CAST(m.id AS CHAR))
                )
            "),
                '=',
                DB::raw('1')
            )

            ->select(

                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',

                'b.id as assignment_id',
                'b.assigned_to',

                'i.employee_no',

                DB::raw("
                CONCAT(
                    i.first_name,
                    ' ',
                    i.last_name
                ) as employee_name
            "),

                'd.nte_no',

                'g.department_name',

                'h.status_name',

                'j.ir_id',

                'k.incident_date',
                'k.valid_until',

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT l.decision_name
                    ORDER BY l.id
                    SEPARATOR ', '
                ) as decision_name
            "),

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT m.category_name
                    ORDER BY m.id
                    SEPARATOR ', '
                ) as category_name
            "),

                DB::raw("
                (
                    SELECT COUNT(*)
                    FROM cpar_employee_disciplinary_records r
                    INNER JOIN cpar_assignments ca
                        ON ca.id = r.assignment_id
                    WHERE ca.assigned_to = b.assigned_to
                    AND r.status = 'FINAL'
                    AND r.assignment_id != b.id
                ) as offense_count
            ")
            )

            ->whereIn('b.status_id', [43, 45])

            /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */
            ->when(
                !empty($this->search),
                function ($query) {

                    $search = '%' . $this->search . '%';

                    $query->where(function ($q) use ($search) {

                        $q->where(
                            'a.cpar_no',
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
                            );
                    });
                }
            )

            /*
        |--------------------------------------------------------------------------
        | OFFENSE FILTER
        |--------------------------------------------------------------------------
        */
            ->when(
                $this->offenseFilter !== 'ALL',
                function ($query) {

                    $query->whereRaw(
                        "
                    JSON_CONTAINS(
                        k.offense_ids,
                        JSON_QUOTE(
                            CAST(? AS CHAR)
                        )
                    )
                    ",
                        [$this->offenseFilter]
                    );
                }
            )

            /*
        |--------------------------------------------------------------------------
        | CATEGORY FILTER
        |--------------------------------------------------------------------------
        */
            ->when(
                $this->categoryFilter !== 'ALL',
                function ($query) {

                    $query->whereRaw(
                        "
                    JSON_CONTAINS(
                        k.discipline_ids,
                        JSON_QUOTE(
                            CAST(? AS CHAR)
                        )
                    )
                    ",
                        [$this->categoryFilter]
                    );
                }
            )

            /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */
            ->when(
                $this->statusFilter !== 'ALL',
                function ($query) {

                    $query->where(
                        'b.status_id',
                        $this->statusFilter
                    );
                }
            )

            /*
        |--------------------------------------------------------------------------
        | VALIDITY FILTER
        |--------------------------------------------------------------------------
        */
            ->when(
                $this->validityFilter === 'VALID',
                function ($query) {

                    $query->whereDate(
                        'k.valid_until',
                        '>=',
                        now()->toDateString()
                    );
                }
            )

            ->when(
                $this->validityFilter === 'EXPIRED',
                function ($query) {

                    $query->whereDate(
                        'k.valid_until',
                        '<',
                        now()->toDateString()
                    );
                }
            )

            ->groupBy(

                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',

                'b.id',
                'b.assigned_to',

                'i.employee_no',
                'i.first_name',
                'i.last_name',

                'd.nte_no',

                'g.department_name',
                'h.status_name',

                'j.ir_id',

                'k.incident_date',
                'k.valid_until'
            )

            ->orderByDesc('b.id')

            ->get();
    }

    public function clearOffenseFilters()
    {
        $this->search = '';

        $this->offenseFilter = 'ALL';

        $this->categoryFilter = 'ALL';

        $this->departmentFilter = 'ALL';

        $this->statusFilter = 'ALL';

        $this->loadPreviousOffense();
    }

    public function updated($property)
    {
        if (in_array($property, [
            'search',
            'offenseFilter',
            'categoryFilter',
            'statusFilter',
            'validityFilter',
        ])) {
            $this->loadPreviousOffense();
        }
    }

    public function render()
    {
        return view('livewire.admin.lab_supervisor_dashboard');
    }
}
