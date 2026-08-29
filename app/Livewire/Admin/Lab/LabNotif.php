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
        $this->cpar_reviews = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->leftJoin('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->leftJoin('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->leftJoin('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->leftJoin('cpar_investigations as j', 'b.id', '=', 'j.assigned_id')
            ->leftJoin('cpar_employee_disciplinary_records as k', 'b.id', '=', 'k.assignment_id')
            ->leftJoin('cpar_ir_requests as m', 'b.id', '=', 'm.assignment_ir_id')
            ->leftJoin('cpar_notice_to_explains as l', 'm.id', '=', 'l.assignment_id')
            ->leftJoin('cpar_nte_responses as r', 'l.id', '=', 'r.nte_id')
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
                'a.date_open',
                'a.concern_description',
                'a.complainant_name',
                'b.id as assignment_id',
                'b.assigned_to',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',

                // -------------------------------------------------
                // ATTACHMENT
                // -------------------------------------------------
                'c.file_path',

                // -------------------------------------------------
                // SOURCE
                // -------------------------------------------------
                'd.source_name',

                // -------------------------------------------------
                // COMPLAIN CATEGORY
                // -------------------------------------------------
                'e.complain_name',
                'f.concern_name',
                'i.employee_no',
                DB::raw("
                CONCAT(
                    i.first_name,
                    ' ',
                    i.last_name
                ) AS employee_name
                "),

                'g.department_name',
                'h.status_name',
                'j.identified_cause',
                'j.provided_solution',
                'j.recommendation',
                'j.action_taken_by',
                'j.date_completed',
                'j.tat',
                'k.id as disciplinary_id',
                'k.discipline_ids',
                'k.offense_ids',
                'k.decision_ids',
                'k.status as decision_status',
                'k.remarks as decision_remarks',
                'l.id as nte_id',
                'l.nte_no',
                'l.nte_attachment',
                'm.id as ir_ids',
                'm.ir_id',
                'm.ir_attachment',
                'r.response_attachment',
                DB::raw("
                CASE
                    WHEN l.nte_no IS NOT NULL
                         AND l.nte_no != ''
                        THEN 'NTE'

                    WHEN m.ir_id IS NOT NULL
                        THEN 'IR'

                    ELSE NULL
                END AS document_type
            "),
                DB::raw("
                GROUP_CONCAT(
                    DISTINCT n.decision_name
                    ORDER BY n.id ASC
                    SEPARATOR ', '
                ) AS decision_name
            "),
                DB::raw("
                (
                    SELECT COUNT(*)
                    FROM cpar_employee_disciplinary_records r

                    INNER JOIN cpar_assignments ca
                        ON ca.id = r.assignment_id

                    WHERE ca.assigned_to = b.assigned_to
                    AND r.status = 'FINAL'
                ) AS offense_count
            ")
            )
            ->where('b.status_id', 35)
            ->where(function ($query) {

                $query
                    ->where(function ($q) {
                        $q->whereNotNull('l.nte_no')
                            ->where('l.nte_no', '!=', '');
                    })

                    ->orWhereNotNull('m.ir_id');
            })
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
                                'l.nte_no',
                                'like',
                                $search
                            )

                            ->orWhere(
                                'm.ir_id',
                                'like',
                                $search
                            );
                    });
                }
            )
            ->groupBy(

                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'a.concern_description',
                'a.complainant_name',

                'b.id',
                'b.assigned_to',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',

                'c.file_path',

                'd.source_name',

                'e.complain_name',

                'f.concern_name',

                'i.employee_no',
                'i.first_name',
                'i.last_name',

                'g.department_name',

                'h.status_name',

                'j.identified_cause',
                'j.provided_solution',
                'j.recommendation',
                'j.action_taken_by',
                'j.date_completed',
                'j.tat',

                'k.id',
                'k.discipline_ids',
                'k.offense_ids',
                'k.decision_ids',
                'k.status',
                'k.remarks',

                'l.id',
                'l.nte_no',
                'l.nte_attachment',

                'm.id',
                'm.ir_id',
                'm.ir_attachment',
                'r.response_attachment'
            )
            ->orderByDesc('b.id')
            ->get();
    }

    public function viewDetails($assignment_id = '')
    {
        $this->dispatch('open-lab-details', assignment_id: $assignment_id);
    }

    public function render()
    {
        return view('livewire.admin.lab.lab_notif');
    }
}
