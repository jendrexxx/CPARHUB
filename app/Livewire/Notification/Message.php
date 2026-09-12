<?php

namespace App\Livewire\Notification;

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
        $this->employee_id = $info->id;
        $this->employee_no = $info->employee_no;
        $this->branch_id = $info->branch_id;
        $this->email = $info->email;
        $this->id = $info->id;
        $this->user_role = $user->getRoleNames()->first() ?? '';
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
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
            ->where('b.record_type', 5)
            ->where('b.status_id', 1)
            ->where('b.dept_head_assigned', $this->id)
            ->count();

        $this->result_count = DB::table('result_error_forms as a')
            ->join('cpar_assignments as b', 'a.id', '=', 'b.result_id')
            ->where('b.record_type', 10)
            ->where('b.status_id', 1)
            ->where('b.dept_head_assigned', $this->id)
            ->count();

        $cparAcknowledgmentCount = DB::table('cpar_assignments as b')
            ->join('employees as j', 'b.assigned_to', '=', 'j.id')
            ->where('b.status_id', 15)
            ->where('b.record_type', 5)
            ->where('j.dept_head', $this->employee_no)
            ->count();

        $resultAcknowledgmentCount = DB::table('cpar_assignments as d')
            ->join('employees as j', 'd.assigned_to', '=', 'j.id')
            ->where('d.status_id', 15)
            ->where('d.record_type', 10)
            ->where('j.dept_head', $this->employee_no)
            ->count();

        $this->acknowledgment_count = $cparAcknowledgmentCount + $resultAcknowledgmentCount;

        if ($this->user_role === 'HR') {

            $hrCparCount = DB::table('cpar_request_forms as a')
                ->join('cpar_assignments as b', 'a.id', '=', 'b.cpar_id')
                ->where('b.status_id', 5)
                ->where('b.record_type', 5)
                ->distinct('a.id')
                ->count('a.id');

            $hrResultCount = DB::table('result_error_forms as a')
                ->join('cpar_assignments as d', 'a.id', '=', 'd.result_id')
                ->where('d.status_id', 5)
                ->where('d.record_type', 10)
                ->distinct('a.id')
                ->count('a.id');

            $this->hr_request_count = $hrCparCount + $hrResultCount;

            $hrAcknowledgedCparCount = DB::table('cpar_assignments as b')
                ->join('cpar_request_forms as a', 'a.id', '=', 'b.cpar_id')
                ->join('cpar_investigations as j', 'b.id', '=', 'j.assigned_id')
                ->where('b.status_id', 20)
                ->where('b.record_type', 5)
                ->whereNotExists(function ($query) {

                    $query->select(DB::raw(1))
                        ->from('cpar_assignments as b2')
                        ->whereColumn(
                            'b2.cpar_id',
                            'b.cpar_id'
                        )
                        ->where('b2.status_id', 20)
                        ->where('b2.record_type', 5)
                        ->whereColumn(
                            'b2.id',
                            '>',
                            'b.id'
                        );
                })
                ->count();

            $hrAcknowledgedResultCount = DB::table('cpar_assignments as b')
                ->join(
                    'result_error_forms as a',
                    'a.id',
                    '=',
                    'b.result_id'
                )
                ->where('b.status_id', 20)
                ->where('b.record_type', 10)
                ->whereNotExists(function ($query) {

                    $query->select(DB::raw(1))
                        ->from('cpar_assignments as b2')
                        ->whereColumn(
                            'b2.result_id',
                            'b.result_id'
                        )
                        ->where('b2.status_id', 20)
                        ->where('b2.record_type', 10)
                        ->whereColumn(
                            'b2.id',
                            '>',
                            'b.id'
                        );
                })
                ->count();

            $this->acknowledged_cpar = $hrAcknowledgedCparCount + $hrAcknowledgedResultCount;

            $hrDecisionCparCount = DB::table('cpar_request_forms as a')
                ->join(
                    'cpar_assignments as b',
                    'a.id',
                    '=',
                    'b.cpar_id'
                )
                ->join(
                    'employees as i',
                    'b.assigned_to',
                    '=',
                    'i.id'
                )
                ->whereIn('b.status_id', [25, 30])
                ->where('b.record_type', 5)
                ->count();

            $hrDecisionResultCount = DB::table('result_error_forms as a')
                ->join(
                    'cpar_assignments as d',
                    'a.id',
                    '=',
                    'd.result_id'
                )
                ->join(
                    'employees as i',
                    'd.assigned_to',
                    '=',
                    'i.id'
                )
                ->whereIn('d.status_id', [25, 30])
                ->where('d.record_type', 10)
                ->where('i.branch_id', $this->branch_id)
                ->count();

            $this->hr_decision_count =
                $hrDecisionCparCount +
                $hrDecisionResultCount;

            $memoCparCount = DB::table('cpar_request_forms as a')
                ->join(
                    'cpar_assignments as b',
                    'a.id',
                    '=',
                    'b.cpar_id'
                )
                ->join(
                    'employees as i',
                    'b.assigned_to',
                    '=',
                    'i.id'
                )
                ->where('b.status_id', 40)
                ->where('b.record_type', 5)
                ->where('i.branch_id', $this->branch_id)
                ->count();

            $memoResultCount = DB::table('result_error_forms as a')
                ->join(
                    'cpar_assignments as b',
                    'a.id',
                    '=',
                    'b.result_id'
                )
                ->join(
                    'employees as i',
                    'b.assigned_to',
                    '=',
                    'i.id'
                )
                ->where('b.status_id', 40)
                ->where('b.record_type', 10)
                ->where('i.branch_id', $this->branch_id)
                ->count();

            $this->memo_count =
                $memoCparCount +
                $memoResultCount;
        }

        if ($this->user_role === 'PGL SUPERVISOR') {

            $labCparCount = DB::table('cpar_request_forms as a')
                ->join(
                    'cpar_assignments as b',
                    'a.id',
                    '=',
                    'b.cpar_id'
                )
                ->join(
                    'cpar_attachments as c',
                    'a.id',
                    '=',
                    'c.cpar_id'
                )
                ->join(
                    'cpar_source_origins as d',
                    'a.source_id',
                    '=',
                    'd.id'
                )
                ->join(
                    'cpar_complain_categories as e',
                    'a.complaint_category_id',
                    '=',
                    'e.id'
                )
                ->join(
                    'cpar_concern_categories as f',
                    'a.concern_category_id',
                    '=',
                    'f.id'
                )
                ->join(
                    'departments as g',
                    'a.department_id',
                    '=',
                    'g.id'
                )
                ->join(
                    'cpar_statuses as h',
                    'b.status_id',
                    '=',
                    'h.id'
                )
                ->where('b.status_id', 35)
                ->where('b.record_type', 5)
                ->count();

            $labResultCount = DB::table('result_error_forms as a')
                ->join(
                    'cpar_assignments as b',
                    'a.id',
                    '=',
                    'b.result_id'
                )
                ->join(
                    'departments as g',
                    'a.department_id',
                    '=',
                    'g.id'
                )
                ->join(
                    'cpar_statuses as h',
                    'b.status_id',
                    '=',
                    'h.id'
                )
                ->where('b.status_id', 35)
                ->where('b.record_type', 10)
                ->count();

            $this->lab_request_count = $labCparCount + $labResultCount;
            $this->concern_count = $this->request_count + $this->assigned_count + $this->nte_cpar + $this->cpar_count + $this->result_count + $this->acknowledgment_count + $this->hr_request_count + $this->acknowledged_cpar + $this->hr_decision_count + $this->memo_count + $this->lab_request_count;
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
