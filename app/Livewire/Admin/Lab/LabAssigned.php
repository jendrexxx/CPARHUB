<?php

namespace App\Livewire\Admin\Lab;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class LabAssigned extends Component
{
    use WithPagination;
    public $perPage = 5;
    public $employee_no = '';
    public $lab_requests = [];
    public $labPage = 1;
    public $labTotal = 0;
    public $labLastPage = 1;

    protected $listeners = [
        'refresLABRData' => 'loadLabDetails',
    ];

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->employee_no = $info->employee_no;
        }
        $this->loadLabDetails();
    }

    public function loadLabDetails()
    {
        $cpar = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('departments as c', 'a.department_id', '=', 'c.id')
            ->join('cpar_statuses as d', 'b.status_id', '=', 'd.id')
            ->leftJoin('employees as e', 'b.assigned_to', '=', 'e.id')
            ->leftJoin('employees as f', 'b.dept_head_assigned', '=', 'f.id')
            ->select(
                'a.id',
                'a.cpar_no as record_no',
                'a.reported_by',
                'a.date_open as record_date',
                DB::raw("'CPAR' as record_type"),
                'b.cpar_id as record_id',
                DB::raw('NULL as result_id'),
                'b.id as assignment_id',
                'b.assigned_to',
                'b.updated_at',
                'c.department_name',
                'd.status_name',
                'e.branch_id',
                'e.department_name as employee_department',
                'f.department_name as dept_department',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as employee_name"),
                DB::raw("CONCAT(f.first_name, ' ', f.last_name) as emp_dept_name")
            )
            ->where('b.status_id', '!=', 50)
            ->where('b.record_type', 5);

        $result = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->join('departments as c', 'a.department_id', '=', 'c.id')
            ->join('cpar_statuses as d', 'b.status_id', '=', 'd.id')
            ->leftJoin('employees as e', 'b.assigned_to', '=', 'e.id')
            ->leftJoin('employees as f', 'b.dept_head_assigned', '=', 'f.id')
            ->select(
                'a.id',
                'a.result_no as record_no',
                'a.reported_by',
                'a.date_reported as record_date',
                DB::raw("'RESULT' as record_type"),
                DB::raw('NULL as record_id'),
                'b.result_id',
                'b.id as assignment_id',
                'b.assigned_to',
                'b.updated_at',
                'c.department_name',
                'd.status_name',
                'e.branch_id',
                'e.department_name as employee_department',
                'f.department_name as dept_department',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as employee_name"),
                DB::raw("CONCAT(f.first_name, ' ', f.last_name) as emp_dept_name")
            )
            ->where('b.status_id', '!=', 50)
            ->where('b.record_type', 10);

        $query = $cpar->unionAll($result);

        $allRequests = DB::query()
            ->fromSub($query, 'lab_requests')
            ->orderByDesc('assignment_id')
            ->get();

        $this->labTotal = $allRequests->count();

        $this->labLastPage = max(
            1,
            (int) ceil($this->labTotal / $this->perPage)
        );

        if ($this->labPage > $this->labLastPage) {
            $this->labPage = $this->labLastPage;
        }

        $offset = ($this->labPage - 1) * $this->perPage;

        $this->lab_requests = $allRequests
            ->slice($offset, $this->perPage)
            ->values();
    }

    public function updatedPerPage()
    {
        $this->perPage = (int) $this->perPage;

        $this->labPage = 1;

        $this->loadLabDetails();
    }

    public function nextLabPage()
    {
        if ($this->labPage < $this->labLastPage) {
            $this->labPage++;

            $this->loadLabDetails();
        }
    }

    public function previousLabPage()
    {
        if ($this->labPage > 1) {
            $this->labPage--;

            $this->loadLabDetails();
        }
    }

    public function goToLabPage($page)
    {
        $page = (int) $page;

        if ($page >= 1 && $page <= $this->labLastPage) {
            $this->labPage = $page;

            $this->loadLabDetails();
        }
    }

    public function UpdateAssign($assignment_id)
    {
        $this->dispatch('open-reassign', id: $assignment_id);
    }

    public function viewResultDetails($result_id)
    {
        $this->dispatch('view-Result', id: $result_id);
    }

    public function render()
    {
        return view('livewire.admin.lab.lab_assigned');
    }
}
