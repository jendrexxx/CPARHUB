<?php

namespace App\Livewire\User\Modal;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ResultNotif extends Component
{
    public $resultRequests = [];

    public function mount()
    {
        $this->ResultloadRecords();
    }

    public function ResultloadRecords()
    {
        $this->resultRequests = DB::table('result_error_forms as a')
            ->join('result_error_source_of_infos as b', 'a.source_of_information', '=', 'b.id')
            ->join('result_complain_categories as c', 'a.complainant_category', '=', 'c.id')
            ->join('employees as d', 'a.employee_no', '=', 'd.employee_no')
            ->join('cpar_statuses as e', 'a.status_id', '=', 'e.id')
            ->select(
                'a.id',
                'a.result_no',
                'a.patient_name',
                'a.date_reported',
                'b.source_name',
                'c.complain_name',
                'd.department_name',
                'e.status_name',
                'e.badge_color'
            )
            ->get();
    }

    public function viewResult()
    {
        dd('Livewire works!');
    }

    public function render()
    {
        return view('livewire.user.modal.result_notif');
    }
}
