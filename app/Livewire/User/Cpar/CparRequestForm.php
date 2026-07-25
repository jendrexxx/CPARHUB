<?php

namespace App\Livewire\User\Cpar;

use App\Models\cpar_assignments;
use App\Models\cpar_attachments;
use App\Models\cpar_complain_categories;
use App\Models\cpar_concern_categories;
use Livewire\Component;
use App\Models\cpar_request_forms;
use App\Models\cpar_source_origins;
use App\Models\employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class CparRequestForm extends Component
{
    use WithFileUploads;

    // Parent toggles (kasabay ng "Data and Information Errors" checkbox mismo)
    public bool $error_type_1 = false;
    public bool $error_type_2 = false;
    public bool $error_type_3 = false;

    // Selected IDs mula sa lookup tables (dynamic checkboxes)
    public array $data_information = [];
    public array $technical_information = [];
    public array $quality_information = [];

    public $source_origin = [], $cpar_complain = [], $cpar_concern = [], $employees = [], $employees_data = [], $data_informations = [], $quality_accuracies = [], $technical_equipments = [];
    public $employee = '';
    public $complain_name_disabled = false;
    public $attachment ='';
    public $cpar_no = '', $date_opened = '', $source_origin_id = '', $complain_category_id = '', $complain_name = '', $reported_by = '', $employeeCategoryId = '', $concern_category_id = '', $branch_id = '', $dept_head_assigned = '', $department_name = '', $concern_description = '', $department_id = '', $attending_physician = '', $test_procedure = '', $patient_name = '';
    public $resultsCategoryId = '';
    public $actual_released_date = '';
    public $employee_no ='';

    public function mount()
    {
        $user = Auth::user();
        $info = employee::where('email', $user->email)->first();
        if ($info) {
            $this->reported_by = $info->first_name . ' ' . $info->last_name;
            $this->branch_id = $info->branch_id;
            $this->employee_no = $info->employee_no;
        }
        $this->date_opened = now()->format('M d, Y');
        $this->cpar_no = $this->generateCparNo();
        $this->source_origin = cpar_source_origins::select('id', 'source_name')->get();
        $this->cpar_complain = cpar_complain_categories::select('id', 'complain_name')->get();
        $this->cpar_concern = cpar_concern_categories::select('id', 'concern_name')->get();
        $resultsCategory = cpar_concern_categories::where('concern_name', 'Results')->first();
        $this->resultsCategoryId = $resultsCategory?->id;
        // Get Employee category ID
        $employeeCategory = cpar_complain_categories::where('complain_name', 'Employee')->first();
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

    protected function rules()
    {
        return [
            'source_origin_id' => 'required',
            'complain_category_id' => 'required',
            'complain_name' => 'required',
            'concern_description' => 'required',
            'concern_category_id' => 'required',
            'dept_head_assigned' => 'required',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'department_name' => 'required'
        ];
    }

    public function reset_form()
    {
        $this->reset([
            'source_origin_id',
            'complain_category_id',
            'complain_name',
            'concern_description',
            'attachment',
            'concern_category_id',
            'dept_head_assigned',
            'department_name',
        ]);

        $this->cpar_no = $this->generateCparNo();
    }

    public function save()
    {
        $this->validate();
        $attachmentPath = null;

        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('cpar', 'public');
        }

        DB::transaction(function () use ($attachmentPath) {
            $request = cpar_request_forms::create([
                'cpar_no'               => $this->cpar_no,
                'employee_no'           => $this->employee_no,
                'reported_by'           => $this->reported_by,
                'date_open'             => now(),
                'source_id'             => $this->source_origin_id,
                'complaint_category_id' => $this->complain_category_id,
                'concern_category_id'   => $this->concern_category_id,
                'complainant_name'      => $this->complain_name,
                'concern_description'   => $this->concern_description,
                'department_id'         => $this->department_id,
                'created_by'            => Auth::id(),
            ]);

            if ($attachmentPath) {
                cpar_attachments::create([
                    'cpar_id'     => $request->id,
                    'file_name'   => $this->attachment->getClientOriginalName(),
                    'file_path'   => $attachmentPath,
                    'file_type'   => $this->attachment->getMimeType(),
                    'uploaded_by' => Auth::id(),
                ]);
            }

            cpar_assignments::create([
                'cpar_id'        => $request->id,
                'dept_head_assigned'    => $this->dept_head_assigned,
                'department_id'  => $this->department_id,
                'status_id'             => 1,
                'created_by'     => Auth::id(),
            ]);
        });

        $this->reset_form();
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'CPAR application submitted successfully!',
        ]);
        return redirect()->route('user_dashboard');
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

    protected function generateCparNo()
    {
        $year = now()->year;

        // Find the last CPAR number for this year
        $lastRecord = cpar_request_forms::where('cpar_no', 'LIKE', "CPAR-{$year}-%")
            ->orderByDesc('cpar_no')
            ->first();

        if ($lastRecord) {
            // Extract the last 3 digits and increment
            $lastNumber = (int) substr($lastRecord->cpar_no, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return "CPAR-{$year}-" . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        return view('livewire.user.cpar.cpar_request_form')->layout('layouts.app');
    }
}
