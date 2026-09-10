<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

#[Layout('components.layouts.app')]
class ResultPdf extends Component
{
    public $data = [], $quality = [], $technical = [];
    public $user_id = '', $existing_ir_attachment = '';
    public bool $hasNTERequest = false;
    public $result_no = '', $reported_by = '', $patient_name = '', $attending_physician = '', $actual_released_date = '', $source_name = '', $complain_name = '', $concern_description = '', $department_name = '', $priority = '', $remarks = '', $status = '', $dept_head_assigned = '', $date_reported = '', $test_procedure = '', $complainant_name = '';
    public $id = '', $employee_no = '', $branch_id = '', $department_id = '', $assignment_remarks = '', $assignment_id = '', $result_id = '';
    public $data_information = [], $technical_information = [], $quality_information = [];
    public $employee_assigned_to = '', $employee_name = '';
    public $date_completed = '', $ir_id = '', $tat = '', $employeeName = '', $assigned_employee_no = '';
    public $identified_cause = '', $provided_solution = '', $recommendation = '', $ir_attachment = '';
    public $action_taken_by = '', $dept_head_remarks = '', $ir_request_id = '', $assigned_to = '';
    public $nte_id = '', $current_ir_attachment = '', $current_nte_attachment = '', $response_attachment = '';
    public $decisionCategories = [], $disciplinaryCategories = [], $offenseLevels = [];
    public $selectedCategories = [null], $selectedOffenseLevels = [''], $selectedHRDecisions = [''];
    public $isNoDisciplinaryAction = false;
    public $nte_no = '', $hr_decision_remarks = '', $management_remarks = '';

    public function result($assignment_id)
    {
        $data = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
            ->join('cpar_statuses as e', 'd.status_id', '=', 'e.id')
            ->leftJoin('employees as f', 'd.dept_head_assigned', '=', 'f.id')
            ->join('priority_levels as g', 'a.priority_level', '=', 'g.id')
            ->leftjoin('employees as h', 'd.assigned_to', '=', 'h.id')
            ->leftjoin('cpar_investigations as i', 'd.id', '=', 'i.assigned_id')
            ->leftJoin('cpar_employee_disciplinary_records as k', 'd.id', '=', 'k.assignment_id')
            ->leftJoin('cpar_ir_requests as j', 'd.id', '=', 'j.assignment_ir_id')
            ->leftJoin('cpar_notice_to_explains as l', 'j.id', '=', 'l.assignment_id')
            ->leftJoin('cpar_nte_responses as n', 'l.id', '=', 'n.nte_id')
            ->select([
                'a.result_no',
                'a.reported_by',
                'a.employee_no',
                'a.date_reported',
                'a.test_procedure',
                'a.quality_information',
                'a.data_information',
                'a.technical_information',
                'a.actual_released_date',
                'a.patient_name',
                'a.attending_physician',
                'a.concern_description',
                'a.complain_name as complainant_name',
                'b.source_name',
                'c.complain_name',
                'd.id',
                'd.result_id',
                'd.assigned_to',
                'd.remarks',
                'd.dept_head_assigned',
                'd.department_id',
                'e.status_name',
                'f.branch_id',
                'f.department_name',
                'f.first_name',
                'f.last_name',
                'g.priority_name',
                DB::raw("CONCAT(h.first_name, ' ', h.last_name) AS employee_assigned_to"),
                'h.employee_no as employee_no_assigned',
                'i.identified_cause',
                'i.provided_solution',
                'i.recommendation',
                'i.date_completed',
                'i.tat',
                'i.remarks as dept_head_remarks',
                // IR
                'j.id as ir_ids',
                'j.ir_id',
                'j.ir_attachment',
                'j.id as ir_request_id',
                // HR Decision
                'k.id as disciplinary_id',
                'k.discipline_ids',
                'k.offense_ids',
                'k.decision_ids',
                'k.status as decision_status',
                'k.remarks as decision_remarks',
                'k.management_remarks',
                // NTE
                'l.id as nte_id',
                'l.nte_no',
                'l.nte_attachment',
                'n.response_attachment',
                DB::raw("
                    (
                        SELECT GROUP_CONCAT(
                            q.quality_name
                            ORDER BY q.id
                            SEPARATOR ', '
                        )
                        FROM result_error_quality_accuracies q
                        WHERE JSON_CONTAINS(
                            a.quality_information,
                            JSON_ARRAY(q.id)
                        )
                    ) AS quality_information_names
                "),
            ])
            ->where('d.id', $assignment_id)
            ->first();

        abort_if(!$data, 404);
        $this->assigned_employee_no = $data->employee_no;
        $cpar_info = DB::table('employees as a')
            ->join('cpar_request_forms as b', 'a.employee_no', '=', 'b.employee_no')
            ->where('b.employee_no', $this->assigned_employee_no)
            ->select(
                'a.employee_no',
                'a.first_name',
                'a.last_name'
            )
            ->first();

        if ($cpar_info) {
            $this->employeeName = trim($cpar_info->first_name . ' ' . $cpar_info->last_name);
        }
        $this->nte_id = $data->nte_id;
        // IR Response 
        $this->existing_ir_attachment = $data->ir_attachment ?? '';
        // NTE Response
        $this->response_attachment = $data->response_attachment ?? '';
        $nte_request = DB::table('cpar_notice_to_explains')->where('assignment_id', $this->assignment_id)->first();
        $this->hasNTERequest = !is_null($nte_request);
        $this->id = $data->id;
        $this->ir_id = $data->ir_id;
        $this->nte_no = $data->nte_no;
        $this->employee_assigned_to = $data->employee_assigned_to;
        $this->result_id   = $data->result_id;
        $this->result_no = $data->result_no;
        $this->date_reported = Carbon::parse($data->date_reported)->format('m-d-Y');
        $this->reported_by = $data->reported_by;
        $this->test_procedure = $data->test_procedure;
        $this->patient_name = $data->patient_name;
        $this->attending_physician = $data->attending_physician;
        $this->actual_released_date = $data->actual_released_date;
        $this->source_name = $data->source_name;
        $this->quality_information = is_array($data->quality_information)
            ? $data->quality_information
            : json_decode($data->quality_information ?? '[]', true);

        $this->data_information = is_array($data->data_information)
            ? $data->data_information
            : json_decode($data->data_information ?? '[]', true);

        $this->technical_information = is_array($data->technical_information)
            ? $data->technical_information
            : json_decode($data->technical_information ?? '[]', true);

        $qualityInformation = DB::table('result_error_quality_accuracies')
            ->whereIn('id', $this->quality_information)
            ->pluck('quality_name')
            ->toArray();
        $dataInformation = DB::table('result_error_data_informations')
            ->whereIn('id', $this->data_information)
            ->pluck('data_name')
            ->toArray();
        $technicalInformation = DB::table('result_error_technical_equipments')
            ->whereIn('id', $this->technical_information)
            ->pluck('technical_name')
            ->toArray();
        $data->quality_information_names = implode(', ', $qualityInformation);
        $data->data_information_names = implode(', ', $dataInformation);
        $data->technical_information_names = implode(', ', $technicalInformation);
        $this->complainant_name = $data->complainant_name;
        $this->complain_name = $data->complain_name;
        $this->concern_description = $data->concern_description;
        $this->department_name = $data->department_name;
        $this->priority = $data->priority_name;
        $this->assigned_to = $data->assigned_to;
        $this->assignment_remarks = $data->remarks;
        $this->status = $data->status_name;
        $this->dept_head_assigned = $data->dept_head_assigned;
        $this->identified_cause = $data->identified_cause;
        $this->provided_solution = $data->provided_solution;
        $this->recommendation = $data->recommendation;
        $this->ir_id = $data->ir_id;
        $this->dept_head_remarks = $data->dept_head_remarks;
        $this->date_completed = $data->date_completed;
        $this->tat = $data->tat;
        $this->ir_request_id = $data->ir_request_id;
        $this->management_remarks = $data->management_remarks;
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
            'livewire.admin.reports.result_pdf',
            [
                'result_data' => $data,
            ]
        )->stream($data->result_no . '.pdf');
    }

    public function render()
    {
        return view('livewire.admin.reports.result_pdf');
    }
}
