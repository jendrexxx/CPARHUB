<?php

namespace App\Livewire\Admin\Cpar;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CparEdit extends Component
{
    public $cparRecord = null;
    public $cpar_request = [];
    public $cpar_no = '', $reported_by = '', $date_open = '', $department_name = '', $status_name = '', $source_name = '', $complain_name = '', $concern_name = '';
    protected $listeners = [
        'view-CPAR' => 'viewCPAR',
    ];

    public function viewCPAR($id = '')
    {
        $cpar_request = DB::table('cpar_request_forms as a')
            ->leftJoin('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->leftJoin('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->leftJoin('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->leftJoin('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->leftJoin('departments as g', 'a.department_id', '=', 'g.id')
            ->leftJoin('cpar_statuses as h', 'a.status_id', '=', 'h.id')
            ->join('employees as i', 'b.dept_head_assigned', 'i.id')
            ->select(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'g.department_name',
                'h.status_name',
                'd.source_name',
                'e.complain_name',
                'f.concern_name'
            )
            ->where('a.id', $id)
            ->first();

        // assign data sa modal fields
        $this->cpar_no = $cpar_request->cpar_no;
        $this->reported_by = $cpar_request->reported_by;
        $this->date_open = $cpar_request->date_open;
        $this->department_name = $cpar_request->department_name;
        $this->status_name = $cpar_request->status_name;
        $this->source_name = $cpar_request->source_name;
        $this->complain_name = $cpar_request->complain_name;
        $this->concern_name = $cpar_request->concern_name;

        if (!$cpar_request) {
            return;
        }

        $this->modal('EditCPARModal')->show();
    }

    public function render()
    {
        return view('livewire.admin.cpar.cpar_edit');
    }
}
