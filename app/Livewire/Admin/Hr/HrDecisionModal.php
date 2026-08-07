<?php

namespace App\Livewire\Admin\Hr;

use App\Models\cpar_assignments;
use App\Models\cpar_employee_disciplinary_records;
use App\Models\cpar_ir_request;
use App\Models\cpar_notice_to_explains;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Carbon;

class HrDecisionModal extends Component
{
    use WithFileUploads;

    public $id = '';
    public $cpar_id = '';
    public $cpar_no = '';
    public $employee_name = '';
    public $department_name = '';
    public $date_open = '';
    public $identified_cause = '';
    public $provided_solution = '';
    public $recommendation = '';
    public $nte_response = '';
    public $hr_decision = '';
    public $hr_decision_remarks = '';
    public $nte_id = '';
    public $decisionCategories = [];
    public $disciplinaryCategories = [];
    public $offenseLevels = [];
    public $offense_level_id = null;
    public $categoryOffense = [];
    public $categoryDecision = [];
    public $selectedCategories = [null];
    public $selectedOffenseLevels = [''];
    public $selectedHRDecisions = [''];
    public $nte_attachment = null;
    public $current_nte_attachment;
    public $ir_attachment = '';
    public $ir_id = '';
    public $current_ir_attachment;

    protected $listeners = [
        'open-decision-cpar' => 'open_decision'
    ];

    public function mount()
    {
        $this->decisionCategories = DB::table('cpar_decision_categories')
            ->orderBy('decision_name', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $this->disciplinaryCategories = DB::table('cpar_disciplinary_categories')
            ->orderBy('category_name', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $this->offenseLevels = DB::table('cpar_offense_levels')
            ->orderBy('id')
            ->get();
    }

    public function addCategory()
    {
        $last = count($this->selectedCategories) - 1;

        if (
            empty($this->selectedCategories[$last]) ||
            empty($this->selectedOffenseLevels[$last]) ||
            empty($this->selectedHRDecisions[$last])
        ) {
            $this->addError('rows', 'Please complete the current row before adding another.');
            return;
        }

        $this->resetErrorBag('rows');

        $this->selectedCategories[] = '';
        $this->selectedOffenseLevels[] = '';
        $this->selectedHRDecisions[] = '';
    }

    public function removeCategory($index)
    {
        unset($this->selectedCategories[$index]);
        unset($this->selectedOffenseLevels[$index]);
        unset($this->selectedHRDecisions[$index]);

        $this->selectedCategories = array_values($this->selectedCategories);
        $this->selectedOffenseLevels = array_values($this->selectedOffenseLevels);
        $this->selectedHRDecisions = array_values($this->selectedHRDecisions);
    }

    public function open_decision($id = null)
    {
        $cpar = DB::table('cpar_request_forms as a')
            ->join(
                'cpar_assignments as b',
                'a.id',
                '=',
                'b.cpar_id'
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
            ->leftJoin(
                'employees as i',
                'b.assigned_to',
                '=',
                'i.id'
            )
            ->leftJoin(
                'cpar_investigations as j',
                'b.id',
                '=',
                'j.assigned_id'
            )
            ->leftJoin(
                'cpar_notice_to_explains as d',
                'b.id',
                '=',
                'd.assignment_id'
            )
            ->leftJoin(
                'cpar_ir_requests as e',
                'b.id',
                '=',
                'e.assignment_ir_id'
            )
            ->leftJoin(
                'cpar_employee_disciplinary_records as f',
                'b.id',
                '=',
                'f.assignment_id'
            )
            ->select(
                // CPAR
                'a.id as cpar_id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                // Assignment
                'b.id as assignment_id',
                'b.assigned_to',
                'b.remarks',
                'b.dept_head_assigned',
                'b.department_id',
                // Employee
                'i.employee_no',
                'i.first_name',
                'i.last_name',
                // Department
                'g.department_name',
                // Status
                'h.status_name',
                // Investigation
                'j.identified_cause',
                'j.provided_solution',
                'j.recommendation',
                'j.action_taken_by',
                'j.date_completed',
                'j.tat',
                // NTE
                'd.id as nte_id',
                'd.nte_no',
                'd.nte_attachment',
                // IR
                'e.id as ir_ids',
                'e.ir_id',
                'e.ir_attachment',
                // HR Decision
                'f.id as disciplinary_id',
                'f.discipline_ids',
                'f.offense_ids',
                'f.decision_ids',
                'f.status as decision_status',
                'f.remarks as decision_remarks'
            )
            ->where('b.id', $id)
            ->first();

        if (!$cpar) {
            return;
        }
        // Basic information
        $this->selectedCategories = json_decode(
            $cpar->discipline_ids ?? '[]',
            true
        ) ?: [''];

        $this->selectedOffenseLevels = json_decode(
            $cpar->offense_ids ?? '[]',
            true
        ) ?: [''];

        $this->selectedHRDecisions = json_decode(
            $cpar->decision_ids ?? '[]',
            true
        ) ?: [''];

        $this->hr_decision_remarks = $cpar->decision_remarks;

        $this->id = $cpar->assignment_id;
        $this->cpar_id = $cpar->cpar_id;
        $this->cpar_no = $cpar->cpar_no;
        $this->nte_id = $cpar->nte_id;
        $this->ir_id = $cpar->ir_id;
        $this->employee_name =
            trim($cpar->first_name . ' ' . $cpar->last_name);

        $this->department_name = $cpar->department_name;
        $this->date_open = $cpar->date_open;
        $this->nte_attachment = $cpar->nte_attachment ?? '';

        // Investigation
        $this->identified_cause = $cpar->identified_cause ?? '';
        $this->provided_solution = $cpar->provided_solution ?? '';
        $this->recommendation = $cpar->recommendation ?? '';
        $this->ir_attachment = $cpar->ir_attachment ?? '';

        // NTE Response
        $this->nte_response = $cpar->response_attachment ?? '';
        // Clear HR decision fields
        $this->hr_decision = '';
        // Open modal
        $this->modal('hr-decision-cpar')->show();
    }

    protected function validateHRDecision()
    {

        $this->validate([

            'selectedCategories' => [
                'required',
                'array',
                'min:1'
            ],

            'selectedCategories.*' => [
                'required'
            ],


            'selectedOffenseLevels.*' => [
                'required'
            ],


            'selectedHRDecisions.*' => [
                'required'
            ],


            'hr_decision_remarks' => [
                'required',
                'string'
            ],

        ], [

            'selectedCategories.*.required'
            => 'Please select disciplinary category.',


            'selectedOffenseLevels.*.required'
            => 'Please select offense level.',


            'selectedHRDecisions.*.required'
            => 'Please select HR decision.',


            'hr_decision_remarks.required'
            => 'HR remarks is required.',

        ]);
    }

    protected function validateSupportingDocuments()
    {
        $rules = [];
        $messages = [];
        if (!empty($this->nte_id)) {
            $rules['nte_attachment'] = [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ];
            $messages['nte_attachment.required'] =
                'Please upload NTE document.';
            $messages['nte_attachment.mimes'] =
                'NTE document must be PDF only.';
        }
        if (!empty($this->ir_id)) {
            $rules['ir_attachment'] = [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ];
            $messages['ir_attachment.required'] =
                'Please upload IR document.';
            $messages['ir_attachment.mimes'] =
                'IR document must be PDF only.';
        }
        $this->validate($rules, $messages);
    }

    public function saveHRDecision()
    {
        $this->validateHRDecision();
        $this->validateSupportingDocuments();
        DB::transaction(function () {
            $this->saveDisciplinaryRecord('FINAL');
            cpar_assignments::where('id', $this->id)
                ->update([
                    'status_id' => 43
                ]);
        });
        $this->dispatch(
            'toast',
            type: 'success',
            message: 'HR Decision successfully saved.'
        );
        // ✅ CLOSE FLUX MODAL
        $this->dispatch('modal-close', name: 'hr-decision-cpar');
        $this->dispatch('modal-close', name: 'HRDecisionModal');
        $this->dispatch('refreshDecisionRecords');
        $this->dispatch('refreshDecisionCount');
    }

    public function saveDraft()
    {
        DB::transaction(function () {

            $this->saveDisciplinaryRecord('DRAFT');
        });

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'HR Decision draft saved.'
        );
        // ✅ CLOSE FLUX MODAL
        $this->dispatch('modal-close', name: 'hr-decision-cpar');
        $this->dispatch('modal-close', name: 'HRDecisionModal');
        $this->dispatch('refreshDecisionRecords');
        $this->dispatch('refreshDecisionCount');
    }

    protected function saveDisciplinaryRecord($status)
    {
        $nteAttachment = $this->current_nte_attachment;
        $irAttachment  = $this->current_ir_attachment;


        // New NTE upload
        if ($this->nte_attachment instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {

            $nteAttachment = $this->nte_attachment->store('cpar/nte', 'public');
        }


        // New IR upload
        if ($this->ir_attachment instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {

            $irAttachment = $this->ir_attachment->store('cpar/ir', 'public');
        }


        cpar_employee_disciplinary_records::updateOrCreate(
            [
                'assignment_id' => $this->id,
            ],
            [
                'discipline_ids' => json_encode($this->selectedCategories),
                'offense_ids'    => json_encode($this->selectedOffenseLevels),
                'decision_ids'   => json_encode($this->selectedHRDecisions),
                'incident_date'  => Carbon::parse($this->date_open)->format('Y-m-d'),
                'valid_until'    => Carbon::parse($this->date_open)
                    ->addMonths(6)
                    ->format('Y-m-d'),
                'remarks'        => $this->hr_decision_remarks,
                'status'         => $status,
                'created_by'     => auth()->id(),
            ]
        );

        // Update NTE Attachment
        if ($this->nte_id && $nteAttachment) {

            DB::table('cpar_notice_to_explains')
                ->where('id', $this->nte_id)
                ->update([
                    'nte_attachment' => $nteAttachment,
                    'updated_at'     => now(),
                ]);
        }


        // Update IR Attachment
        if ($this->ir_id && $irAttachment) {

            DB::table('cpar_ir_requests')
                ->where('assignment_ir_id', $this->id)
                ->update([
                    'ir_attachment' => $irAttachment,
                    'updated_at'    => now(),
                ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_decision_modal');
    }
}
