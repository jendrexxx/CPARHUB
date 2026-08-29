<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
class Tabs extends Component
{
    public $cpar_request_count = '';
    public $result_request_count = '';
    public $employee_no = '';
    public $assigned_cpar = '';
    public $id = '';
    public $nte_cpar = '';
    public $histories = [];
    public $ir_cpar = '';
    public $cpar_offense = '';
    public $search = '';
    public $branchFilter = '';
    public $departmentFilter = '';
    public $statusFilter = '';
    public $offenseFilter = '';
    public $validityFilter = '';
    public $branches = [];
    public $departments = [];
    public $statuses = [];
    public $offenseCategories = [];
    public $disciplinaryCategories = [];
    public $categoryFilter = 'ALL';
    public $offense_name = '';

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->id = $info->id;
            $this->employee_no = $info->employee_no;
        }
        $this->result_request_count = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->where('employee_no', $this->employee_no)
            ->count();
        $this->statusFilter = 'ALL';
        $this->statuses = DB::table('cpar_statuses')
            ->orderBy('status_name')
            ->get();
        $this->loadCpar();
    }

    public function loadCpar()
    {
        $query = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('employees as i', 'b.assigned_to', '=', 'i.id')
            ->leftJoin('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->leftJoin('employees as c', 'b.dept_head_assigned', '=', 'c.id')
            ->select([
                'a.id as cpar_id',
                'a.cpar_no',
                'a.reported_by',
                'a.employee_no',

                'b.id as assignment_id',
                'b.status_id',
                'b.assigned_to',

                DB::raw("
                NULLIF(
                    TRIM(
                        CONCAT(
                            COALESCE(i.first_name, ''),
                            ' ',
                            COALESCE(i.last_name, '')
                        )
                    ),
                    ''
                ) as employee_name
            "),

                DB::raw("
                NULLIF(
                    TRIM(
                        CONCAT(
                            COALESCE(c.first_name, ''),
                            ' ',
                            COALESCE(c.last_name, '')
                        )
                    ),
                    ''
                ) as dept_employee_name
            "),

                'i.employee_no as assigned_employee_no',

                'h.status_name',
                'h.badge_color',
            ]);

        $query->where(function ($q) {
            $q->where('a.employee_no', $this->employee_no)
                ->orWhere('b.assigned_to', $this->id);
        });

        if (filled($this->search)) {

            $search = '%' . trim($this->search) . '%';

            $query->where(function ($q) use ($search) {

                $q->where('a.cpar_no', 'LIKE', $search)
                    ->orWhere('a.reported_by', 'LIKE', $search)
                    ->orWhere('a.employee_no', 'LIKE', $search)
                    ->orWhere('i.employee_no', 'LIKE', $search)
                    ->orWhere('i.first_name', 'LIKE', $search)
                    ->orWhere('i.last_name', 'LIKE', $search)
                    ->orWhere('c.first_name', 'LIKE', $search)
                    ->orWhere('c.last_name', 'LIKE', $search)
                    ->orWhere('h.status_name', 'LIKE', $search)
                    ->orWhereRaw("
                    CONCAT(
                        COALESCE(i.first_name, ''),
                        ' ',
                        COALESCE(i.last_name, '')
                    ) LIKE ?
                ", [$search])
                    ->orWhereRaw("
                    CONCAT(
                        COALESCE(c.first_name, ''),
                        ' ',
                        COALESCE(c.last_name, '')
                    ) LIKE ?
                ", [$search]);
            });
        }

        if ($this->statusFilter !== 'ALL') {
            $query->where(
                'b.status_id',
                $this->statusFilter
            );
        }

        $this->cpar_offense = $query
            ->orderByDesc('b.id')
            ->get();
    }

    public function updatedSearch()
    {
        $this->loadCpar();
    }

    public function updatedStatusFilter()
    {
        $this->loadCpar();
    }

    public function render()
    {
        return view('livewire.user.tabs');
    }
}
