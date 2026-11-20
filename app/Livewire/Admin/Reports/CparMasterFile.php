<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class CparMasterFile extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'tailwind';
    public $perPage = 10;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function view($assignment_id = '')
    {
        $this->dispatch('open-master-file', id: $assignment_id);
    }

    public function viewResult($assignment_id = '')
    {
        $this->dispatch('result-master-file', id: $assignment_id);
    }

    public function deleteCPAR($assignment_id = null, $rec_id = null): void
    {
        abort_unless(auth()->check(), 403);

        if (!$assignment_id || !$rec_id) {
            session()->flash('error', 'Assignment ID and CPAR ID are required.');
            return;
        }

        try {
            DB::transaction(function () use ($assignment_id, $rec_id) {

                // Verify assignment before deleting related records.
                $assignmentExists = DB::table('cpar_assignments')
                    ->where('id', $assignment_id)
                    ->where('cpar_id', $rec_id)
                    ->exists();

                if (!$assignmentExists) {
                    throw new \RuntimeException('Assignment not found.');
                }

                // Get IR request IDs before deleting them.
                $irRequestIds = DB::table('cpar_ir_requests')
                    ->where('assignement_ir_id', $assignment_id)
                    ->pluck('id');

                // Delete NTE records linked to IR request IDs.
                if ($irRequestIds->isNotEmpty()) {
                    DB::table('cpar_notice_to_explains')
                        ->whereIn('assignement_id', $irRequestIds)
                        ->delete();
                }

                // Delete NTE responses linked directly to the assignment.
                DB::table('cpar_nte_responses')
                    ->where('assignement_id', $assignment_id)
                    ->delete();

                // Delete investigation records.
                DB::table('cpar_investigations')
                    ->where('assigned_id', $assignment_id)
                    ->delete();

                // Delete IR requests.
                DB::table('cpar_ir_requests')
                    ->where('assignement_ir_id', $assignment_id)
                    ->delete();

                // Delete the selected assignment.
                DB::table('cpar_assignments')
                    ->where('id', $assignment_id)
                    ->where('cpar_id', $rec_id)
                    ->delete();

                // Check whether other assignments still use this CPAR.
                $remainingAssignments = DB::table('cpar_assignments')
                    ->where('cpar_id', $rec_id)
                    ->exists();

                // Delete attachments only if no assignments remain.
                if (!$remainingAssignments) {
                    DB::table('cpar_attachments')
                        ->where('cpar_id', $rec_id)
                        ->delete();
                }
            });

            session()->flash(
                'success',
                'CPAR assignment and related records deleted successfully.'
            );
        } catch (\Throwable $e) {
            report($e);

            session()->flash(
                'error',
                'Unable to delete assignment or related records. Check the Laravel log.'
            );
        }
    }

    public function deleteResult($assignment_id = '') {}

    public function render()
    {
        $cpar = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('employees as c', 'b.assigned_to', '=', 'c.id')
            ->leftJoin('departments as d', 'a.department_id', '=', 'd.id')
            ->leftJoin('branches as e', 'c.branch_id', '=', 'e.id')
            ->leftJoin('cpar_statuses as f', 'b.status_id', '=', 'f.id')
            ->leftJoin(
                'employees as g',
                'b.dept_head_assigned',
                '=',
                'g.id'
            )
            ->when($this->search, function ($query) {

                $search = '%' . $this->search . '%';

                $query->where(function ($q) use ($search) {

                    $q->where('a.cpar_no', 'like', $search)
                        ->orWhere('a.reported_by', 'like', $search)
                        ->orWhere('c.first_name', 'like', $search)
                        ->orWhere('c.last_name', 'like', $search)
                        ->orWhere('d.department_name', 'like', $search)
                        ->orWhere('e.branch_name', 'like', $search)
                        ->orWhere('f.status_name', 'like', $search);
                });
            })
            ->select([
                'a.id as record_id',
                'a.cpar_no as record_no',
                'a.reported_by',
                'a.date_open as record_date',
                'b.id as assignment_id',
                'b.cpar_id as rec_id',
                'c.first_name',
                'c.last_name',
                'd.department_name',
                'e.branch_name',
                'f.status_name',
            ])
            ->selectRaw("
            'CPAR' AS record_type
        ")
            ->selectRaw("
            CONCAT_WS(' ', c.first_name, c.last_name)
            AS assigned_employee
        ")
            ->selectRaw("
            CONCAT_WS(' ', g.first_name, g.last_name)
            AS dept_head
        ");

        $result = DB::table('result_error_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->leftJoin('employees as c', 'b.assigned_to', '=', 'c.id')
            ->leftJoin('departments as d', 'a.department_id', '=', 'd.id')
            ->leftJoin('branches as e', 'c.branch_id', '=', 'e.id')
            ->leftJoin('cpar_statuses as f', 'b.status_id', '=', 'f.id')
            ->leftJoin('employees as g', 'b.dept_head_assigned', '=', 'g.id')
            ->when($this->search, function ($query) {

                $search = '%' . $this->search . '%';

                $query->where(function ($q) use ($search) {

                    $q->where('a.result_no', 'like', $search)
                        ->orWhere('a.reported_by', 'like', $search)
                        ->orWhere('c.first_name', 'like', $search)
                        ->orWhere('c.last_name', 'like', $search)
                        ->orWhere('d.department_name', 'like', $search)
                        ->orWhere('e.branch_name', 'like', $search)
                        ->orWhere('f.status_name', 'like', $search);
                });
            })
            ->select([
                'a.id as record_id',
                'a.result_no as record_no',
                'a.reported_by',
                'a.date_reported as record_date',
                'b.id as assignment_id',
                'b.result_id as rec_id',
                'c.first_name',
                'c.last_name',
                'd.department_name',
                'e.branch_name',
                'f.status_name',
            ])
            ->selectRaw("'RESULT' AS record_type")
            ->selectRaw("CONCAT_WS(' ', c.first_name, c.last_name)AS assigned_employee")
            ->selectRaw("CONCAT_WS(' ', g.first_name, g.last_name)AS dept_head");

        $records = $cpar
            ->unionAll($result)
            ->orderByDesc('record_date');

        $cpars = $records->paginate($this->perPage);

        return view('livewire.admin.reports.cpar_master_file', ['cpars' => $cpars,])->layout('layouts.app');
    }
}
