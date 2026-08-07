<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class LabSupervisorDashboard extends Component
{
    public $cpar_request_count = '';

    protected $listeners = [
        'refreshLABCount' => 'loadLABCount',
    ];

    public function mount()
    {
        $this->loadLABCount();
    }

    public function loadLABCount()
    {
        $this->cpar_request_count = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->join('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
            ->where('b.status_id', 43)
            ->count();
    }

    public function render()
    {
        return view('livewire.admin.lab_supervisor_dashboard');
    }
}
