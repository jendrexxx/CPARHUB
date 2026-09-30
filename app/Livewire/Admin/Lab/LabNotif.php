<?php

namespace App\Livewire\Admin\Lab;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LabNotif extends Component
{
    public $cpar_reviews = [];
    public $perPage = 10;
    public $labReviewPage = 1;
    public $labReviewTotal = 0;
    public $labReviewLastPage = 1;
    public string $search = '';
    protected $listeners = [
        'refreshLABRecords' => 'loadLABReview',
    ];


    public function mount()
    {
        $this->loadLABReview();
    }

    public function loadLABReview()
    {
        $search = trim($this->search);

        $cparQuery = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->leftJoin('cpar_employee_disciplinary_records as k', 'b.id', '=', 'k.assignment_id')
            ->leftJoin('cpar_ir_requests as m', 'b.id', '=', 'm.assignment_ir_id')
            ->leftJoin('cpar_notice_to_explains as l', 'm.id', '=', 'l.assignment_id')
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
                DB::raw("NULL as result_no"),
                'a.reported_by',
                DB::raw("'CPAR' as record_type"),
                'b.id as assignment_id',
                'g.department_name',
                'h.status_name',
                'i.employee_no',
                DB::raw("
                CONCAT(
                    COALESCE(i.first_name, ''),
                    ' ',
                    COALESCE(i.last_name, '')
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
            ->groupBy(
                'a.id',
                'a.cpar_no',
                'b.id',
                'a.reported_by',
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

        $resultQuery = DB::table('result_error_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->leftJoin('cpar_employee_disciplinary_records as k', 'b.id', '=', 'k.assignment_id')
            ->leftJoin('cpar_ir_requests as m', 'b.id', '=', 'm.assignment_ir_id')
            ->leftJoin('cpar_notice_to_explains as l', 'm.id', '=', 'l.assignment_id')
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
                DB::raw("NULL as cpar_no"),
                'a.result_no',
                'a.reported_by',
                DB::raw("'RESULT' as record_type"),
                'b.id as assignment_id',
                'g.department_name',
                'h.status_name',
                'i.employee_no',
                DB::raw("
                CONCAT(
                    COALESCE(i.first_name, ''),
                    ' ',
                    COALESCE(i.last_name, '')
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
            ->groupBy(
                'a.id',
                'a.result_no',
                'b.id',
                'a.reported_by',
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

        $query = $cparQuery->unionAll($resultQuery);

        $allReviewsQuery = DB::query()
            ->fromSub($query, 'lab_reviews');

        if ($search !== '') {
            $searchTerm = '%' . $search . '%';

            $allReviewsQuery->where(function ($q) use ($searchTerm) {
                $q->where('cpar_no', 'like', $searchTerm)
                    ->orWhere('result_no', 'like', $searchTerm)
                    ->orWhere('reported_by', 'like', $searchTerm)
                    ->orWhere('employee_no', 'like', $searchTerm)
                    ->orWhere('employee_name', 'like', $searchTerm)
                    ->orWhere('department_name', 'like', $searchTerm)
                    ->orWhere('decision_name', 'like', $searchTerm)
                    ->orWhere('nte_no', 'like', $searchTerm)
                    ->orWhere('ir_id', 'like', $searchTerm)
                    ->orWhere('record_type', 'like', $searchTerm)
                    ->orWhere('status_name', 'like', $searchTerm);
            });
        }

        $allReviews = $allReviewsQuery
            ->orderByDesc('assignment_id')
            ->get();

        $this->labReviewTotal = $allReviews->count();

        $this->labReviewLastPage = max(
            1,
            (int) ceil($this->labReviewTotal / $this->perPage)
        );

        if ($this->labReviewPage > $this->labReviewLastPage) {
            $this->labReviewPage = $this->labReviewLastPage;
        }

        $offset = ($this->labReviewPage - 1) * $this->perPage;

        $this->cpar_reviews = $allReviews
            ->slice($offset, $this->perPage)
            ->values();
    }

    public function updatedSearch()
    {
        $this->labReviewPage = 1;
        $this->loadLABReview();
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
