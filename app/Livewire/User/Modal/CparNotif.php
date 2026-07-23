<?php

namespace App\Livewire\User\Modal;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;

class CparNotif extends Component
{
    public $cpar_requests = [];
    public $editingId = null;
    public $showEditModal = false;

    public $edit_cpar_no = '';
    public $edit_reported_by = '';
    public $edit_date_open = '';
    public $edit_department_name = '';
    public $employee_no = '';

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->employee_no = $info->employee_no;
        }
        $this->loadRecords();
    }

    public function loadRecords()
    {
        $this->cpar_requests = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('cpar_attachments as c', 'a.id', '=', 'c.cpar_id')
            ->join('cpar_source_origins as d', 'a.source_id', '=', 'd.id')
            ->join('cpar_complain_categories as e', 'a.complaint_category_id', '=', 'e.id')
            ->join('cpar_concern_categories as f', 'a.concern_category_id', '=', 'f.id')
            ->join('departments as g', 'a.department_id', '=', 'g.id')
            ->join('cpar_statuses as h', 'a.status_id', '=', 'h.id')
            ->select(
                'a.id',
                'a.cpar_no',
                'a.reported_by',
                'a.date_open',
                'g.department_name',
                'h.status_name'
            )
            ->where('a.employee_no', $this->employee_no)
            ->get();
    }

    public function editResult($id)
    {
        dd('test');
    }

    public function test()
    {
        dd('Livewire works!');
    }

    public function saveRecord()
    {
        DB::table('cpar_request_forms')
            ->where('id', $this->editingId)
            ->update([
                'reported_by' => $this->edit_reported_by,
                'date_open'   => $this->edit_date_open,
            ]);

        $this->showEditModal = false;
        $this->editingId     = null;
        $this->loadRecords();
    }

    public function cancelEdit()
    {
        $this->showEditModal = false;
        $this->editingId     = null;
    }

    public function render()
    {
        return view('livewire.user.modal.cpar_notif');
    }
}
