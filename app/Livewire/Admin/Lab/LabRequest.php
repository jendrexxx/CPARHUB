<?php

namespace App\Livewire\Admin\Lab;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LabRequest extends Component
{
    public $cpar_id = '';
    public $remarks = '';
    public $employees = [];
    public $employee_no = '';
    public $branch_id = '';
    public $department_id = '';
    public $id = '';
    public $assigned = null;
    public $assigned_to = [];
    public $new_assignees = [];
    public $assigned_head = '';
    public $dept_head_assigned = '';
    public $cpar_no = '', $reported_by = '', $date_open = '', $department_name = '', $source_name = '', $complain_name = '', $concern_name = '', $status_name = '';
    public $identified_cause = '';
    public $provided_solution = '';
    public $recommendation = '';
    public $action_taken_by = '';
    public $date_completed = '';
    public $tat = '';
    public $selectedCategories = [null];
    public $selectedOffenseLevels = [''];
    public $selectedHRDecisions = [''];
    public $hr_decision_remarks = '';
    public $nte_id = '';
    public $ir_attachment = '';
    public $ir_id = '';
    public $nte_attachment = null;
    public $current_nte_attachment;
    public $nte_response = '';
    public $hr_decision = '';
    public $employee_name = '';
    public $decisionCategories = [];
    public $disciplinaryCategories = [];
    public $offenseLevels = [];

    protected $listeners = [
        'open-lab-details' => 'view',
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

    public function view($assignment_id = null)
    {

        if (!$assignment_id) {
            return;
        }


        $cpar_request = DB::table('cpar_request_forms as a')

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


            ->select([

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
                'e.id as ir_id',
                'e.ir_attachment',


                // HR Decision
                'f.id as disciplinary_id',
                'f.discipline_ids',
                'f.offense_ids',
                'f.decision_ids',
                'f.status as decision_status',
                'f.remarks as decision_remarks',

            ])

            ->where(
                'b.id',
                $assignment_id
            )

            ->first();



        if (!$cpar_request) {

            session()->flash(
                'error',
                'CPAR record not found.'
            );

            return;
        }



        /*
    |--------------------------------------------------------------------------
    | CPAR Information
    |--------------------------------------------------------------------------
    */
        $this->id = $cpar_request->assignment_id;
        $this->cpar_id = $cpar_request->cpar_id;
        $this->cpar_no = $cpar_request->cpar_no;
        $this->employee_name =
            trim(
                $cpar_request->first_name .
                    ' ' .
                    $cpar_request->last_name
            );
        $this->department_name = $cpar_request->department_name;
        $this->date_open = $cpar_request->date_open;
        $this->status_name = $cpar_request->status_name;
        /*
    |--------------------------------------------------------------------------
    | NTE
    |--------------------------------------------------------------------------
    */
        $this->nte_id = $cpar_request->nte_id;
        $this->nte_attachment = $cpar_request->nte_attachment ?? '';
        /*
    |--------------------------------------------------------------------------
    | IR
    |--------------------------------------------------------------------------
    */
        $this->ir_id = $cpar_request->ir_id;
        $this->ir_attachment = $cpar_request->ir_attachment ?? '';
        /*
    |--------------------------------------------------------------------------
    | Investigation
    |--------------------------------------------------------------------------
    */
        $this->identified_cause = $cpar_request->identified_cause ?? '';
        $this->provided_solution = $cpar_request->provided_solution ?? '';
        $this->recommendation = $cpar_request->recommendation ?? '';
        /*
    |--------------------------------------------------------------------------
    | HR Decision
    |--------------------------------------------------------------------------
    */
        $this->selectedCategories =
            json_decode(
                $cpar_request->discipline_ids ?? '[]',
                true
            ) ?: [''];
        $this->selectedOffenseLevels =
            json_decode(
                $cpar_request->offense_ids ?? '[]',
                true
            ) ?: [''];
        $this->selectedHRDecisions = json_decode($cpar_request->decision_ids ?? '[]', true) ?: [''];
        $this->hr_decision_remarks = $cpar_request->decision_remarks ?? '';
        $this->hr_decision = '';
        $this->reported_by = $cpar_request->reported_by;

        /*
    |--------------------------------------------------------------------------
    | Show Modal
    |--------------------------------------------------------------------------
    */
        $this->modal('LABrequest')->show();
    }

    public function verifiedLaboratory()
    {
        if (!$this->id) {
            return;
        }
        DB::transaction(function () {
            // Update CPAR Assignment Status
            DB::table('cpar_assignments')
                ->where('id', $this->id)
                ->update([
                    'status_id' => 45,
                    'updated_at' => now()

                ]);
            // Insert History
            DB::table('cpar_histories')
                ->insert([
                    'cpar_id' => $this->id,
                    'old_status' => $this->status_name,
                    'new_status' => 'VERIFIED',
                    'reported_by' => $this->reported_by,
                    'remarks' => 'Verified by Laboratory Supervisor',
                    'created_by' => auth()->user()->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
        });

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'CPAR successfully verified by laboratory.'
        );

        // ✅ CLOSE FLUX MODAL
        $this->dispatch('modal-close', name: 'LABModal');
        $this->dispatch('modal-close', name: 'LABrequest');
        $this->dispatch('refreshLABRecords');
        $this->dispatch('refreshLABCount');
    }

    public function render()
    {
        return view('livewire.admin.lab.lab_request');
    }
}
