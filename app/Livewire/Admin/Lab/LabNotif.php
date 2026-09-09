<?php

namespace App\Livewire\Admin\Lab;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LabNotif extends Component
{
    public $cpar_reviews = [];
    public $search = '';

    protected $listeners = [
        'refreshLABRecords' => 'loadLABReview',
    ];


    public function mount()
    {
        $this->loadLABReview();
    }

    public function updatedSearch()
    {
        $this->loadLABReview();
    }

    public function loadLABReview()
    {
        $cparQuery = DB::table('cpar_request_forms as a')
            ->leftJoin(
                'cpar_assignments as b',
                'a.id',
                '=',
                'b.cpar_id'
            )
            ->leftJoin(
                'departments as g',
                'a.department_id',
                '=',
                'g.id'
            )
            ->leftJoin(
                'cpar_statuses as h',
                'b.status_id',
                '=',
                'h.id'
            )
            ->leftJoin(
                'employees as i',
                'b.assigned_to',
                '=',
                'i.id'
            )
            ->leftJoin(
                'cpar_employee_disciplinary_records as k',
                'b.id',
                '=',
                'k.assignment_id'
            )
            ->leftJoin(
                'cpar_ir_requests as m',
                'b.id',
                '=',
                'm.assignment_ir_id'
            )
            ->leftJoin(
                'cpar_notice_to_explains as l',
                'm.id',
                '=',
                'l.assignment_id'
            )
            ->leftJoin(
                'cpar_decision_categories as n',
                DB::raw("
                JSON_CONTAINS(
                    k.decision_ids,
                    JSON_QUOTE(CAST(n.id AS CHAR))
                )
            "),
                '=',
                DB::raw('1')
            )
            ->select(
                'a.id as cpar_id',
                'a.cpar_no',
                'a.reported_by',
                DB::raw("'CPAR' as record_type"),
                'b.id as assignment_id',

                'g.department_name',
                'h.status_name',

                'i.employee_no',

                DB::raw("
                CONCAT(
                    i.first_name,
                    ' ',
                    i.last_name
                ) AS employee_name
            "),

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT n.decision_name
                    ORDER BY n.id ASC
                    SEPARATOR ', '
                ) AS decision_name
            "),

                'l.nte_no',
                'l.nte_attachment',

                'm.ir_id',
                'm.ir_attachment',

                // Identify source
                DB::raw("'CPAR' AS source_type")
            )

            ->where('b.status_id', 35)

            ->where(function ($query) {
                $query
                    ->where(function ($q) {
                        $q->whereNotNull('l.nte_no')
                            ->where('l.nte_no', '!=', '');
                    })
                    ->orWhere(function ($q) {
                        $q->whereNotNull('m.ir_id')
                            ->where('m.ir_id', '!=', '');
                    });
            })

            ->when(
                !empty($this->search),
                function ($query) {

                    $search = '%' . $this->search . '%';

                    $query->where(function ($q) use ($search) {

                        $q->where('a.cpar_no', 'like', $search)
                            ->orWhere('a.reported_by', 'like', $search)
                            ->orWhere('i.employee_no', 'like', $search)
                            ->orWhere('i.first_name', 'like', $search)
                            ->orWhere('i.last_name', 'like', $search)
                            ->orWhere('g.department_name', 'like', $search)
                            ->orWhere('l.nte_no', 'like', $search)
                            ->orWhere('m.ir_id', 'like', $search);
                    });
                }
            )

            ->groupBy(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'b.id',
                'g.department_name',
                'h.status_name',
                'i.employee_no',
                'i.first_name',
                'i.last_name',
                'l.nte_no',
                'l.nte_attachment',
                'm.ir_id',
                'm.ir_attachment'
            );


        /*
    |--------------------------------------------------------------------------
    | RESULT ERROR REVIEWS
    |--------------------------------------------------------------------------
    */
        $resultQuery = DB::table('result_error_forms as a')
            ->leftJoin(
                'cpar_assignments as b',
                'a.id',
                '=',
                'b.result_id'
            )
            ->leftJoin(
                'departments as g',
                'a.department_id',
                '=',
                'g.id'
            )
            ->leftJoin(
                'cpar_statuses as h',
                'b.status_id',
                '=',
                'h.id'
            )
            ->leftJoin(
                'employees as i',
                'b.assigned_to',
                '=',
                'i.id'
            )
            ->leftJoin(
                'cpar_employee_disciplinary_records as k',
                'b.id',
                '=',
                'k.assignment_id'
            )
            ->leftJoin(
                'cpar_ir_requests as m',
                'b.id',
                '=',
                'm.assignment_ir_id'
            )
            ->leftJoin(
                'cpar_notice_to_explains as l',
                'm.id',
                '=',
                'l.assignment_id'
            )
            ->leftJoin(
                'cpar_decision_categories as n',
                DB::raw("
                JSON_CONTAINS(
                    k.decision_ids,
                    JSON_QUOTE(CAST(n.id AS CHAR))
                )
            "),
                '=',
                DB::raw('1')
            )
            ->select(
                'a.id as cpar_id',
                'a.result_no',
                'a.reported_by',
                DB::raw("'RESULT' as record_type"),
                'b.id as assignment_id',

                'g.department_name',
                'h.status_name',

                'i.employee_no',

                DB::raw("
                CONCAT(
                    i.first_name,
                    ' ',
                    i.last_name
                ) AS employee_name
            "),

                DB::raw("
                GROUP_CONCAT(
                    DISTINCT n.decision_name
                    ORDER BY n.id ASC
                    SEPARATOR ', '
                ) AS decision_name
            "),

                'l.nte_no',
                'l.nte_attachment',

                'm.ir_id',
                'm.ir_attachment',

                // Identify source
                DB::raw("'RESULT' AS source_type")
            )

            ->where('b.status_id', 35)

            ->where(function ($query) {
                $query
                    ->where(function ($q) {
                        $q->whereNotNull('l.nte_no')
                            ->where('l.nte_no', '!=', '');
                    })
                    ->orWhere(function ($q) {
                        $q->whereNotNull('m.ir_id')
                            ->where('m.ir_id', '!=', '');
                    });
            })

            ->when(
                !empty($this->search),
                function ($query) {

                    $search = '%' . $this->search . '%';

                    $query->where(function ($q) use ($search) {

                        $q->where('a.result_no', 'like', $search)
                            ->orWhere('a.reported_by', 'like', $search)
                            ->orWhere('i.employee_no', 'like', $search)
                            ->orWhere('i.first_name', 'like', $search)
                            ->orWhere('i.last_name', 'like', $search)
                            ->orWhere('g.department_name', 'like', $search)
                            ->orWhere('l.nte_no', 'like', $search)
                            ->orWhere('m.ir_id', 'like', $search);
                    });
                }
            )
            ->groupBy(
                'a.id',
                'a.result_no',
                'a.reported_by',
                'b.id',
                'g.department_name',
                'h.status_name',
                'i.employee_no',
                'i.first_name',
                'i.last_name',
                'l.nte_no',
                'l.nte_attachment',
                'm.ir_id',
                'm.ir_attachment'
            );


        /*
    |--------------------------------------------------------------------------
    | COMBINE CPAR + RESULT
    |--------------------------------------------------------------------------
    */
        $this->cpar_reviews = $cparQuery
            ->unionAll($resultQuery)
            ->orderByDesc('assignment_id')
            ->get();
    }

    public function viewDetails($assignment_id = '')
    {
        $this->dispatch('open-lab-details', assignment_id: $assignment_id);
    }

    public function viewLabResult($assignment_id = '')
    {
        $this->dispatch('open-result-details', id: $assignment_id);
    }

    public function render()
    {
        return view('livewire.admin.lab.lab_notif');
    }
}
