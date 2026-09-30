<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfAll extends Component
{

    public function pdf(Request $request)
    {
        $search = trim($request->search ?? '');
        $branch_id = $request->branch_id ?? 'ALL BRANCH';
        $department_id = $request->department_id ?? 'ALL';
        $status_id = $request->status_id ?? 'ALL';
        $category_id = $request->category_id ?? 'ALL';
        $date_from = $request->dateFrom ?? '';
        $date_to = $request->dateTo ?? '';
        $type_id = (int) $request->type_id;

        $cparQuery = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('employees as c', 'b.assigned_to', '=', 'c.id')
            ->leftJoin('departments as d', 'a.department_id', '=', 'd.id')
            ->leftJoin('cpar_statuses as e', 'b.status_id', '=', 'e.id')
            ->leftJoin('cpar_employee_disciplinary_records as f', 'b.id', '=', 'f.assignment_id')
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
            ->leftJoin(
                'cpar_disciplinary_categories as j',
                DB::raw("
                JSON_CONTAINS(
                    f.discipline_ids,
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
                    f.offense_ids,
                    JSON_QUOTE(CAST(k.id AS CHAR))
                )
            "),
                '=',
                DB::raw('1')
            )
            ->leftJoin('cpar_investigations as l', 'b.id', '=', 'l.assigned_id')
            ->leftJoin('cpar_source_origins as m', 'a.source_id', '=', 'm.id')
            ->leftJoin('cpar_complain_categories as n', 'a.complaint_category_id', '=', 'n.id')
            ->select([
                'a.id',
                'a.cpar_no as record_no',
                'a.reported_by',
                'a.date_open',
                'a.concern_description',
                'b.id as assignment_id',
                'b.status_id',
                'b.record_type',
                'c.employee_no',
                DB::raw("
                CONCAT(
                    COALESCE(c.first_name, ''),
                    ' ',
                    COALESCE(c.last_name, '')
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
                'i.memo_attachment',
                DB::raw("
                GROUP_CONCAT(
                    DISTINCT j.category_name
                    ORDER BY j.id
                    SEPARATOR ', '
                ) AS category_name
            "),
                DB::raw("
                GROUP_CONCAT(
                    DISTINCT k.offense_name
                    ORDER BY k.id
                    SEPARATOR ', '
                ) AS offense_name
            "),
                'l.identified_cause',
                'l.provided_solution',
                'l.date_completed',
                'l.tat',
                'm.source_name',
                'n.complain_name',
            ])
            ->groupBy([
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'a.concern_description',
                'b.id',
                'b.status_id',
                'b.record_type',
                'c.employee_no',
                'c.first_name',
                'c.last_name',
                'c.department_name',
                'c.branch_id',
                'a.department_id',
                'e.status_name',
                'h.branch_name',
                'f.incident_date',
                'f.valid_until',
                'i.memo_attachment',
                'l.identified_cause',
                'l.provided_solution',
                'l.date_completed',
                'l.tat',
                'm.source_name',
                'n.complain_name',
            ]);

        $resultQuery = DB::table('result_error_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->leftJoin('employees as c', 'b.assigned_to', '=', 'c.id')
            ->leftJoin('departments as d', 'a.department_id', '=', 'd.id')
            ->leftJoin('cpar_statuses as e', 'b.status_id', '=', 'e.id')
            ->leftJoin('cpar_employee_disciplinary_records as f', 'b.id', '=', 'f.assignment_id')
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
            ->leftJoin(
                'cpar_disciplinary_categories as j',
                DB::raw("
                JSON_CONTAINS(
                    f.discipline_ids,
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
                    f.offense_ids,
                    JSON_QUOTE(CAST(k.id AS CHAR))
                )
            "),
                '=',
                DB::raw('1')
            )
            ->leftJoin('cpar_investigations as l', 'b.id', '=', 'l.assigned_id')
            ->leftJoin('result_error_source_of_infos as m', 'a.source_of_information', '=', 'm.id')
            ->leftJoin('result_complain_categories as n', 'a.complainant_category', '=', 'n.id')
            ->select([
                'a.id',
                'a.result_no as record_no',
                'a.reported_by',
                'a.date_reported as date_open',
                'a.concern_description',
                'b.id as assignment_id',
                'b.status_id',
                'b.record_type',
                'c.employee_no',
                DB::raw("
                CONCAT(
                    COALESCE(c.first_name, ''),
                    ' ',
                    COALESCE(c.last_name, '')
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
                'i.memo_attachment',
                DB::raw("
                GROUP_CONCAT(
                    DISTINCT j.category_name
                    ORDER BY j.id
                    SEPARATOR ', '
                ) AS category_name
            "),
                DB::raw("
                GROUP_CONCAT(
                    DISTINCT k.offense_name
                    ORDER BY k.id
                    SEPARATOR ', '
                ) AS offense_name
            "),
                'l.identified_cause',
                'l.provided_solution',
                'l.date_completed',
                'l.tat',
                'm.source_name',
                'n.complain_name',
            ])
            ->groupBy([
                'a.id',
                'a.result_no',
                'a.reported_by',
                'a.date_reported',
                'a.concern_description',
                'b.id',
                'b.status_id',
                'b.record_type',
                'c.employee_no',
                'c.first_name',
                'c.last_name',
                'c.department_name',
                'c.branch_id',
                'a.department_id',
                'e.status_name',
                'h.branch_name',
                'f.incident_date',
                'f.valid_until',
                'i.memo_attachment',
                'l.identified_cause',
                'l.provided_solution',
                'l.date_completed',
                'l.tat',
                'm.source_name',
                'n.complain_name',
            ]);

        $query = DB::query()
            ->fromSub(
                $cparQuery->unionAll($resultQuery),
                'records'
            );

        if ($search !== '') {
            $searchValue = '%' . $search . '%';

            $query->where(function ($q) use ($searchValue) {
                $q->where('record_no', 'like', $searchValue)
                    ->orWhere('reported_by', 'like', $searchValue)
                    ->orWhere('employee_no', 'like', $searchValue)
                    ->orWhere('employee_name', 'like', $searchValue)
                    ->orWhere('department_name', 'like', $searchValue)
                    ->orWhere('branch_name', 'like', $searchValue)
                    ->orWhere('status_name', 'like', $searchValue)
                    ->orWhere('decision_name', 'like', $searchValue)
                    ->orWhere('record_type', 'like', $searchValue)
                    ->orWhere('source_name', 'like', $searchValue);
            });
        }

        if ($branch_id !== 'ALL' && $branch_id !== 'ALL BRANCH' && $branch_id !== '') {
            $query->where('branch_id', $branch_id);
        }

        if ($department_id !== 'ALL' && $department_id !== '') {
            $query->where('department_id', $department_id);
        }

        if ($status_id !== 'ALL' && $status_id !== '') {
            $query->where('status_id', $status_id);
        }

        $query->where('record_type', $type_id);

        if ($category_id !== 'ALL' && $category_id !== '') {
            $decisionName = DB::table('cpar_decision_categories')
                ->where('id', $category_id)
                ->value('decision_name');

            if ($decisionName) {
                $query->whereRaw(
                    'FIND_IN_SET(?, decision_name)',
                    [$decisionName]
                );
            }
        }

        if ($date_from !== '') {
            $query->whereDate('date_open', '>=', $date_from);
        }

        if ($date_to !== '') {
            $query->whereDate('date_open', '<=', $date_to);
        }

        $cparReports = $query
            ->orderByDesc('assignment_id')
            ->get();

        $branchNames = $cparReports
            ->pluck('branch_name')
            ->filter()
            ->unique()
            ->values();

        $branch_name = $branchNames->count() === 1
            ? $branchNames->first()
            : 'ALL BRANCH';

        $pdf = Pdf::loadView(
            'livewire.admin.reports.pdf_all',
            [
                'cparReports' => $cparReports,
                'branch_name' => $branch_name,
            ]
        );

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('REPORT.pdf');
    }

    public function render()
    {
        return view('livewire.admin.reports.pdf_all');
    }
}
