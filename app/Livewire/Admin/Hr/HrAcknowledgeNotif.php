<?php

namespace App\Livewire\Admin\Hr;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HrAcknowledgeNotif extends Component
{
    public $cpar_acknowledge = '';
    public $branch_id = '';
    public $acknowledgment_requests = [];

    protected $listeners = [
        'refreshAcknowledgeRecords' => 'AcknowledgeloadRecords',
    ];

    public function mount($branch_id)
    {
        $this->AcknowledgeloadRecords();
    }

    public function AcknowledgeloadRecords()
    {
        // ==========================================
        // CPAR ACKNOWLEDGMENT
        // ==========================================
        $cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->join('cpar_investigations as j', 'b.id', '=', 'j.assigned_id')
            ->join('priority_levels as m', 'a.priority_level', '=', 'm.id')
            ->leftJoin('cpar_ir_requests as n', 'b.id', '=', 'n.assignment_ir_id')
            ->leftJoin('cpar_notice_to_explains as o', 'b.id', '=', 'o.assignment_id')
            ->select(
                'a.id',
                'a.cpar_no as record_no',
                'a.reported_by',
                'a.date_open as record_date',

                DB::raw("'CPAR' as record_type"),

                'b.id as assignment_id',
                'b.cpar_id as record_id',
                'b.assigned_to',
                'b.status_id',

                'i.employee_no',
                'i.first_name',
                'i.last_name',

                'g.department_name',
                'h.status_name',
                'm.priority_name',

                'n.id as ir_request_id',
                'n.ir_id',
                'o.nte_no'
            )
            ->where('b.status_id', 20)
            ->where('b.record_type', 5)
            ->where('i.branch_id', $this->branch_id)

            // Kunin lamang ang latest assignment
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('cpar_assignments as b2')
                    ->whereColumn('b2.cpar_id', 'b.cpar_id')
                    ->where('b2.status_id', 20)
                    ->where('b2.record_type', 5)
                    ->whereColumn('b2.id', '>', 'b.id');
            });


        // ==========================================
        // RESULT ERROR ACKNOWLEDGMENT
        // ==========================================
        $result = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->join('priority_levels as m', 'a.priority_level', '=', 'm.id')
            ->select(
                'a.id',
                'a.result_no as record_no',
                'a.reported_by',
                'a.date_reported as record_date',

                DB::raw("'RESULT' as record_type"),

                'b.id as assignment_id',
                'b.result_id as record_id',
                'b.assigned_to',
                'b.status_id',

                'i.employee_no',
                'i.first_name',
                'i.last_name',

                'g.department_name',
                'h.status_name',
                'm.priority_name',

                // CPAR-only fields
                DB::raw('NULL as ir_request_id'),
                DB::raw('NULL as ir_id'),
                DB::raw('NULL as nte_no')
            )
            ->where('b.status_id', 20)
            ->where('b.record_type', 10)
            ->where('i.branch_id', $this->branch_id)

            // Kunin lamang ang latest assignment
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('cpar_assignments as b2')
                    ->whereColumn('b2.result_id', 'b.result_id')
                    ->where('b2.status_id', 20)
                    ->where('b2.record_type', 10)
                    ->whereColumn('b2.id', '>', 'b.id');
            });


        // ==========================================
        // COMBINE CPAR + RESULT ERROR
        // ==========================================
        $this->acknowledgment_requests = $cpar
            ->unionAll($result)
            ->orderByDesc('record_date')
            ->get();
    }

    public function updatedBranchId($value = '')
    {
        $this->branch_id = $value;
        $this->AcknowledgeloadRecords();
    }

    public function viewDetails($assignment_id)
    {
        $this->dispatch('open-acknowledge-cpar', id: $assignment_id);
    }

    public function createRequestIR($assignment_id)
    {
        $this->dispatch('ir-request-cpar', id: $assignment_id);
    }

    public function createNoticeToExplain($assignment_id)
    {
        $this->dispatch('nte-cpar', id: $assignment_id);
    }

    public function render()
    {
        return view('livewire.admin.hr.hr_acknowledge_notif');
    }
}
