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

    public function render()
    {
        $cpars = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('employees as c', 'b.assigned_to', '=', 'c.id')
            ->leftJoin('departments as d', 'a.department_id', '=', 'd.id')
            ->leftJoin('branches as e', 'c.branch_id', '=', 'e.id')
            ->leftJoin('cpar_statuses as f', 'b.status_id', '=', 'f.id')
            ->leftJoin('employees as g', 'b.dept_head_assigned', '=', 'g.id')
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
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'b.id as assignment_id',
                'c.first_name',
                'c.last_name',
                'c.branch_name',
                'd.department_name',
                'f.status_name',
            ])
            ->selectRaw("
                CONCAT_WS(' ', c.first_name, c.last_name) AS assigned_employee
            ")
            ->selectRaw("
                CONCAT_WS(' ', g.first_name, g.last_name) AS dept_head
            ")
            ->orderBy('a.id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.reports.cpar_master_file',['cpars' => $cpars])->layout('layouts.app');
    }
}
