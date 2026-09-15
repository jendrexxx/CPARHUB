<?php

namespace App\Livewire\User\Result;

use App\Models\cpar_assignments;
use App\Models\cpar_attachments;
use App\Models\priority_level;
use App\Models\result_error_data_informations;
use App\Models\result_error_form;
use App\Models\result_error_quality_accuracies;
use App\Models\result_error_source_of_info;
use App\Models\result_error_technical_equipments;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use App\Models\result_complain_categories;
use Illuminate\Support\Facades\DB;

class ResultRequestForm extends Component
{
    public $result_no = '', $date_reported = '', $patient_name = '', $attending_physician = '', $actual_released_date = '', $complain_category_id = '', $complain_name = '', $concern_description = '', $employee_no = '', $reported_by = '', $test_procedure = '', $employeeCategoryId = '';
    public $source = [], $data = [], $quality = [], $technical = [], $data_information = [];
    public array $selectedData = [];
    public array $selectedTechnical = [];
    public array $selectedQuality = [];
    public $result_complain = [];
    public $complain_name_disabled = false;
    public $source_of_information = '', $branch_id = '', $id = '', $department_name = '', $department_id = '', $dept_head_assigned = '';
    public $employees = [];
    public $priority_level = [];
    public $priority = '', $concern_attachment = '';

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->employee_no = $info->employee_no;
            $this->reported_by = $info->first_name . ' ' . $info->last_name;
            $this->branch_id = $info->branch_id;
            $this->id = $info->id;
        }
        $this->date_reported = now()->format('M d, Y');
        $this->result_no = $this->generateResultNo();
        $this->source = result_error_source_of_info::select('id', 'source_name')
            ->orderBy('source_name')
            ->get();
        $this->data = result_error_data_informations::all();
        $this->quality = result_error_quality_accuracies::all();
        $this->technical = result_error_technical_equipments::all();
        $this->result_complain = result_complain_categories::select('id', 'complain_name')->get();
        $this->priority_level = priority_level::select('id', 'priority_name')->get();
        // Get Employee category ID
        $employeeCategory = result_complain_categories::where('complain_name', 'Employee')->first();
        $this->employeeCategoryId = $employeeCategory?->id;

        // Get employees except logged-in employee
        $this->employees = DB::table('employees as e')
            ->join('employees as h', 'e.dept_head', '=', 'h.employee_no')
            ->where('e.branch_id', $this->branch_id)
            ->where('e.email', '!=', $info->email)
            ->select(
                'h.id',
                'h.employee_no',
                'h.first_name',
                'h.last_name',
                'h.department_name',
                'h.department_id'
            )
            ->distinct()
            ->get();
    }

    protected function generateResultNo()
    {
        $year = now()->year;

        $lastNumber = result_error_form::whereYear('created_at', $year)
            ->selectRaw("MAX(CAST(SUBSTRING(result_no, -5) AS UNSIGNED)) as max_number")
            ->value('max_number');

        $nextNumber = ($lastNumber ?? 0) + 1;

        return "RC-{$year}-" . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function updatedComplainCategoryId($value = '')
    {
        if ($value == $this->employeeCategoryId) {
            $this->complain_name = $this->reported_by;
            $this->complain_name_disabled = true;
        } else {
            $this->complain_name = '';
            $this->complain_name_disabled = false;
        }
    }

    public function updatedDeptHeadAssigned($value = '')
    {
        $employee = $this->employees->firstWhere('id', $value);

        if ($employee) {
            $this->department_name = $employee->department_name;
            $this->department_id = $employee->department_id;
        } else {
            $this->department_name = '';
        }
    }

    public function save()
    {
        $this->validate([
            'patient_name' => 'required',
            'source_of_information' => 'required',
            'concern_description' => 'required',
            'attending_physician' => 'required',
            'actual_released_date' => 'required',
            'test_procedure' => 'required',
            'complain_category_id' => 'required',
            'complain_name' => 'required',
            'priority' => 'required'
        ]);

        $result = result_error_form::create([
            'result_no' => $this->result_no,
            'employee_no' => $this->employee_no,
            'reported_by' => $this->reported_by,
            'date_reported' => now(),
            'patient_name' => $this->patient_name,
            'attending_physician' => $this->attending_physician,
            'test_procedure' => $this->test_procedure,
            'actual_released_date' => $this->actual_released_date,
            'source_of_information' => $this->source_of_information,
            'data_information' => json_encode($this->selectedData),
            'technical_information' => json_encode($this->selectedTechnical),
            'quality_information' => json_encode($this->selectedQuality),
            'complainant_category' => $this->complain_category_id,
            'complain_name' => $this->complain_name,
            'concern_description' => $this->concern_description,
            'department_id'       => $this->department_id,
            'priority_level'      => $this->priority
        ]);
        cpar_assignments::create([
            'result_id'               => $result->id,
            'dept_head_assigned'    => $this->dept_head_assigned,
            'department_id'         => $this->department_id,
            'status_id'             => 1,
            'record_type'           => 10,
            'created_by'            => Auth::id(),
        ]);
        
        

        return redirect()->route('user_dashboard')->with('toast', ['type' => 'success', 'message' => 'Result Concern submitted successfully',]);
    }

    public function render()
    {
        return view('livewire.user.result.result_request_form')->layout('layouts.app');
    }
}
