<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;

#[Layout('components.layouts.app')]
class Pdf extends Component
{
    protected $listeners = [
        'open-pdf-details' => 'view',
    ];

    public $selectedCpar;
    public $showView = false;

    public function pdf($assignment_id)
    {
        $data = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->join('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->join('employees as i', 'b.assigned_to', '=', 'i.id')
            ->join('cpar_investigations as j', 'b.id', '=', 'j.assigned_id')
            ->join('cpar_employee_disciplinary_records as k', 'b.id', '=', 'k.assignment_id')
            ->join('cpar_ir_requests as m', 'b.id', '=', 'm.assignment_ir_id')
            ->leftjoin('cpar_notice_to_explains as l', 'm.id', '=', 'l.assignment_id')
            ->leftjoin('cpar_nte_responses as n', 'b.id', '=', 'n.nte_id')
            ->select([
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
                'c.file_path',
                'd.source_name',
                'e.complain_name',
                'f.concern_name',
                'i.employee_no',
                DB::raw("
                CONCAT(
                    i.first_name,
                    ' ',
                    i.last_name
                ) as employee_name
                "),
                'g.department_name',
                'h.status_name',
                'j.identified_cause',
                'j.provided_solution',
                'j.recommendation',
                'j.action_taken_by',
                'j.date_completed',
                'j.tat',
                'j.remarks as head_remarks',
                'k.id as disciplinary_id',
                'k.discipline_ids',
                'k.offense_ids',
                'k.decision_ids',
                'k.status as decision_status',
                'k.remarks as decision_remarks',
                'k.management_remarks',
                'l.id as nte_id',
                'l.nte_no',
                'l.nte_attachment',
                'm.id as ir_ids',
                'm.ir_id',
                'm.ir_attachment',
                'n.response_attachment',
            ])
            ->where('b.id', $assignment_id)
            ->first();

        abort_if(!$data, 404);
        $disciplineIds = json_decode($data->discipline_ids ?? '[]', true) ?? [];
        $data->disciplineNames = DB::table('cpar_disciplinary_categories')
            ->whereIn('id', $disciplineIds)
            ->pluck('category_name')
            ->implode(', ');
        $offenseids = json_decode($data->offense_ids ?? '[]', true) ?? [];
        $data->offenseNames = DB::table('cpar_offense_levels')
            ->whereIn('id', $offenseids)
            ->pluck('offense_name')
            ->implode(', ');
        $decisionids = json_decode($data->decision_ids ?? '[]', true) ?? [];
        $data->decisionNames = DB::table('cpar_decision_categories')
            ->whereIn('id', $decisionids)
            ->pluck('decision_name')
            ->implode(', ');
        return DomPdf::loadView(
            'livewire.admin.reports.pdf',
            [
                'cpar_data' => $data,
            ]
        )->stream($data->cpar_no . '.pdf');
    }

    public function render()
    {
        return view('livewire.admin.reports.pdf');
    }
}
