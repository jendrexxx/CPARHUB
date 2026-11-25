<?php

namespace App\Livewire\Notification;

use App\Models\cpar_statuses;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;

class Message extends Component
{
    public bool $showNotifications = false;
    public array $notifications = [];
    public $id = '';
    public $employee_no = '';
    public $branch_id = '';
    public string $user_role = '';
    public $email = '';
    public $employee_id = '';
    public int $cpar_count = 0;
    public int $result_count = 0;
    public int $request_count = 0;
    public int $assigned_count = 0;
    public int $nte_cpar = 0;
    public int $acknowledgment_count = 0;
    public int $hr_request_count = 0;
    public int $acknowledged_cpar = 0;
    public int $hr_decision_count = 0;
    public int $memo_count = 0;
    public int $lab_request_count = 0;
    public int $concern_count = 0;
    public int $lab_reported_count = 0;

    protected $listeners = [
        'refreshNotificationCount' => 'loadNotifications',
    ];

    public function mount($branch_id = null)
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();

        if (!$info) {
            return;
        }
        $this->branch_id = $branch_id;
        $this->employee_id = $info->id;
        $this->employee_no = $info->employee_no;
        $this->email = $info->email;
        $this->id = $info->id;
        $this->user_role = $user->getRoleNames()->first() ?? '';

        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $credentials = Auth::user()?->employee;
        $this->cpar_count = 0;
        $this->result_count = 0;
        $this->request_count = 0;
        $this->assigned_count = 0;
        $this->nte_cpar = 0;
        $this->acknowledgment_count = 0;
        $this->hr_request_count = 0;
        $this->acknowledged_cpar = 0;
        $this->hr_decision_count = 0;
        $this->memo_count = 0;
        $this->lab_request_count = 0;
        $this->concern_count = 0;

        if (!$this->employee_id) {
            return;
        }

        $this->loadAssignedCount();
        $this->loadNTECount();

        $this->cpar_count = DB::table('cpar_request_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
            ->leftJoin('employees as c', 'b.assigned_to', '=', 'c.id')
            ->where('b.record_type', 5)
            ->where('b.status_id', 1)
            ->where('b.dept_head_assigned', $this->id)
            ->count();

        $this->result_count = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->leftJoin('employees as c', 'b.assigned_to', '=', 'c.id')
            ->where('b.record_type', 10)
            ->where('b.status_id', 1)
            ->where('b.dept_head_assigned', $this->id)
            ->count();

        $cparAcknowledgmentCount = DB::table('cpar_assignments as b')
            ->join('employees as j', 'b.assigned_to', '=', 'j.id')
            ->where('b.status_id', 15)
            ->where('b.record_type', 5)
            ->where('j.dept_head', $this->employee_no)
            ->where('j.branch_id', $credentials->branch_id)
            ->count();

        $resultAcknowledgmentCount = DB::table('cpar_assignments as d')
            ->join('employees as j', 'd.assigned_to', '=', 'j.id')
            ->where('d.status_id', 15)
            ->where('d.record_type', 10)
            ->where('j.dept_head', $this->employee_no)
            ->where('j.branch_id', $credentials->branch_id)
            ->count();

        $this->acknowledgment_count = $cparAcknowledgmentCount + $resultAcknowledgmentCount;


        if ($this->user_role === 'HR') {

            // Optional branch filtering
            $branchId = $this->branch_id;

            // CPAR Pending / HR Request
            $hrCparQuery = DB::table('cpar_request_forms as a')
                ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
                ->join('employees as i', 'b.assigned_to', '=', 'i.id')
                ->where('b.status_id', 5)
                ->where('b.record_type', 5);

            if (!empty($branchId)) {
                $hrCparQuery->where('i.branch_id', $branchId);
            }

            $hrCparCount = $hrCparQuery
                ->distinct()
                ->count('a.id');


            // RESULT Pending / HR Request
            $hrResultQuery = DB::table('result_error_forms as a')
                ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
                ->join('employees as i', 'd.assigned_to', '=', 'i.id')
                ->where('d.status_id', 5)
                ->where('d.record_type', 10);

            if (!empty($branchId)) {
                $hrResultQuery->where('i.branch_id', $branchId);
            }

            $hrResultCount = $hrResultQuery
                ->distinct()
                ->count('a.id');

            $this->hr_request_count = $hrCparCount + $hrResultCount;


            // HR ACKNOWLEDGED CPAR
            $hrAcknowledgedCparQuery = DB::table('cpar_assignments as b')
                ->join('cpar_request_forms as a', 'a.id', '=', 'b.cpar_id')
                ->join('cpar_investigations as j', 'b.id', '=', 'j.assigned_id')
                ->join('employees as i', 'b.assigned_to', '=', 'i.id')
                ->where('b.status_id', 20)
                ->where('b.record_type', 5);

            if (!empty($branchId)) {
                $hrAcknowledgedCparQuery->where('i.branch_id', $branchId);
            }

            $hrAcknowledgedCparCount = $hrAcknowledgedCparQuery->count();


            // HR ACKNOWLEDGED RESULT
            $hrAcknowledgedResultQuery = DB::table('cpar_assignments as b')
                ->join('result_error_forms as a', 'a.id', '=', 'b.result_id')
                ->join('cpar_investigations as j', 'b.id', '=', 'j.assigned_id')
                ->join('employees as i', 'b.assigned_to', '=', 'i.id')
                ->where('b.status_id', 20)
                ->where('b.record_type', 10);

            if (!empty($branchId)) {
                $hrAcknowledgedResultQuery->where('i.branch_id', $branchId);
            }

            $hrAcknowledgedResultCount = $hrAcknowledgedResultQuery->count();

            $this->acknowledged_cpar = $hrAcknowledgedCparCount + $hrAcknowledgedResultCount;


            // HR DECISION CPAR
            $hrDecisionCparQuery = DB::table('cpar_request_forms as a')
                ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
                ->join('employees as i', 'b.assigned_to', '=', 'i.id')
                ->whereIn('b.status_id', [20, 25, 30])
                ->where('b.record_type', 5);

            if (!empty($branchId)) {
                $hrDecisionCparQuery->where('i.branch_id', $branchId);
            }

            $hrDecisionCparCount = $hrDecisionCparQuery->count();


            // HR DECISION RESULT
            $hrDecisionResultQuery = DB::table('result_error_forms as a')
                ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
                ->join('employees as i', 'd.assigned_to', '=', 'i.id')
                ->whereIn('d.status_id', [20, 25, 30])
                ->where('d.record_type', 10);

            if (!empty($branchId)) {
                $hrDecisionResultQuery->where('i.branch_id', $branchId);
            }

            $hrDecisionResultCount = $hrDecisionResultQuery->count();

            $this->hr_decision_count = $hrDecisionCparCount + $hrDecisionResultCount;


            // MEMO CPAR
            $memoCparQuery = DB::table('cpar_request_forms as a')
                ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
                ->join('employees as i', 'b.assigned_to', '=', 'i.id')
                ->where('b.status_id', 40)
                ->where('b.record_type', 5);

            if (!empty($branchId)) {
                $memoCparQuery->where('i.branch_id', $branchId);
            }

            $memoCparCount = $memoCparQuery->count();


            // MEMO RESULT
            $memoResultQuery = DB::table('result_error_forms as a')
                ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
                ->join('employees as i', 'b.assigned_to', '=', 'i.id')
                ->where('b.status_id', 40)
                ->where('b.record_type', 10);

            if (!empty($branchId)) {
                $memoResultQuery->where('i.branch_id', $branchId);
            }

            $memoResultCount = $memoResultQuery->count();

            $this->memo_count = $memoCparCount + $memoResultCount;
        }


        if ($this->user_role === 'LAB SUPERVISOR') {

            $credentials = Auth::user()?->employee;

            $status = cpar_statuses::where('id', '!=', 50)
                ->get(['id', 'status_name']);

            $statusIds = $status->pluck('id')->toArray();

            $labCparCount = DB::table('cpar_request_forms as a')
                ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
                ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
                ->leftJoin('employees as r', 'b.assigned_to', '=', 'r.id')
                ->whereIn('h.id', $statusIds)
                ->where('r.branch_id', $credentials->branch_id)
                ->where('b.record_type', 5)
                ->count();

            $labResultCount = DB::table('result_error_forms as a')
                ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
                ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
                ->leftJoin('employees as r', 'b.assigned_to', '=', 'r.id')
                ->whereIn('h.id', $statusIds)
                ->where('r.branch_id', $credentials->branch_id)
                ->where('b.record_type', 10)
                ->count();

            $reportlabCparCount = DB::table('cpar_request_forms as a')
                ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
                ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
                ->where('h.id', 35)
                ->where('b.record_type', 5)
                ->count();

            $reportlabResultCount = DB::table('result_error_forms as a')
                ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
                ->join('cpar_statuses as h', 'b.status_id', '=', 'h.id')
                ->where('h.id', 35)
                ->where('b.record_type', 10)
                ->count();

            $this->lab_request_count = $labCparCount + $labResultCount;
            $this->lab_reported_count = $reportlabCparCount + $reportlabResultCount;

            $this->concern_count =
                $this->request_count +
                $this->assigned_count +
                $this->nte_cpar +
                $this->cpar_count +
                $this->result_count +
                $this->acknowledgment_count +
                $this->hr_request_count +
                $this->acknowledged_cpar +
                $this->hr_decision_count +
                $this->memo_count +
                $this->lab_request_count +
                $this->lab_reported_count;
        }
    }

    public function loadAssignedCount()
    {
        $cparCount = DB::table('cpar_request_forms as a')
            ->join(
                'cpar_assignments as b',
                'a.id',
                '=',
                'b.cpar_id'
            )
            ->where('b.status_id', 10)
            ->where('b.record_type', 5)
            ->where('b.assigned_to', $this->employee_id)
            ->distinct('a.id')
            ->count('a.id');

        $resultCount = DB::table('result_error_forms as a')
            ->join(
                'cpar_assignments as b',
                'a.id',
                '=',
                'b.result_id'
            )
            ->where('b.status_id', 10)
            ->where('b.record_type', 10)
            ->where('b.assigned_to', $this->employee_id)
            ->distinct('a.id')
            ->count('a.id');

        $this->assigned_count = $cparCount + $resultCount;
    }

    public function loadNTECount()
    {
        $cparCount = DB::table('cpar_assignments as b')
            ->join(
                'cpar_request_forms as a',
                'a.id',
                '=',
                'b.cpar_id'
            )
            ->join(
                'cpar_ir_requests as i',
                'b.id',
                '=',
                'i.assignment_ir_id'
            )
            ->join(
                'cpar_notice_to_explains as j',
                'j.assignment_id',
                '=',
                'i.id'
            )
            ->where('b.assigned_to', $this->employee_id)
            ->where('b.status_id', 23)
            ->where('b.record_type', 5)
            ->distinct('b.id')
            ->count('b.id');

        $resultCount = DB::table('cpar_assignments as b')
            ->join(
                'result_error_forms as a',
                'a.id',
                '=',
                'b.result_id'
            )
            ->where('b.assigned_to', $this->employee_id)
            ->where('b.status_id', 23)
            ->where('b.record_type', 10)
            ->distinct('b.id')
            ->count('b.id');

        $this->nte_cpar =
            $cparCount +
            $resultCount;
    }

    public function render()
    {
        return view('livewire.notification.message');
    }
}
