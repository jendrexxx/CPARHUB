<?php

namespace App\Livewire\Notification;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;

class Message extends Component
{
    public int $cpar_count = 0;
    public int $result_count = 0;
    public int $concern_count = 0;
    public bool $showNotifications = false;
    public array $notifications = [];

    public $id = '';
    public $employee_no = '';

    protected $listeners = [
        'refreshNotificationCount' => 'loadNotifications',
    ];

    public function mount()
    {
        $user = Auth::user();

        $info = employee::where('email', $user->email)->first();

        if (!$info) {
            return;
        }
        $this->employee_no = $info->employee_no;
        $this->id = $info->id;
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (!$this->id) {
            $this->cpar_count = 0;
            $this->result_count = 0;
            $this->concern_count = 0;

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CPAR COUNT
        |--------------------------------------------------------------------------
        */

        $this->cpar_count = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->join('priority_levels as c', 'a.priority_level', 'c.id')
            ->where('b.dept_head_assigned', $this->id)
            ->where('b.record_type', 5)
            ->where('b.status_id', 1)
            ->count();

        $this->result_count = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->join('priority_levels as c', 'a.priority_level', 'c.id')
            ->where('dept_head_assigned', $this->id)
            ->where('record_type', 10)
            ->where('status_id', 1)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        $this->concern_count =
            $this->cpar_count +
            $this->result_count;
    }

    public function render()
    {
        return view('livewire.notification.message');
    }
}
