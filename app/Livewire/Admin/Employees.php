<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class Employees extends Component
{

    public $loading = false;
    public $message = null;
    public $error = null;

    protected $listeners = [
        'refresh' => 'refreshEmployees',
    ];

    public function refreshEmployees()
    {
        $this->loading = true;

        try {

            $response = Http::timeout(5)
                ->withHeaders([
                    'X-API-KEY1' => env('API_KEY1'),
                    'X-API-KEY2' => env('API_KEY2'),
                ])
                ->get('http://127.0.0.1:8000/api/api_testing');

            if ($response->successful()) {

                $employees = $response->json()['data'];

                foreach ($employees as $emp) {

                    Employee::updateOrCreate(

                        [
                            'employee_no' => $emp['employee_no'] ?? null,
                        ],

                        [

                            'first_name' => $emp['first_name'] ?? null,
                            'middle_name' => $emp['middle_name'] ?? null,
                            'last_name' => $emp['last_name'] ?? null,
                            'birth_date' => $emp['birth_date'] ?? null,

                            'department_name' => $emp['department'] ?? null,
                            'department_id' => $emp['department_id'] ?? null,

                            'date_hired' => $emp['date_hired'] ?? null,
                            'regularization_date' => $emp['regularization_date'] ?? null,
                            'probationary_date' => $emp['probationary_date'] ?? null,

                            'dept_head' => $emp['dept_head'] ?? null,

                            'branch_id' => $emp['branch_id'] ?? null,
                            'branch_name' => $emp['branch_name'] ?? null,

                            'position_id' => $emp['position_id'] ?? null,
                            'position_name' => $emp['position'] ?? null,

                            'status' => $emp['status'] ?? null,
                            'email' => $emp['email'] ?? null,

                        ]

                    );
                }

                $this->message = "Employees synced successfully.";
                $this->error = null;
            } else {

                $this->message = null;
                $this->error = "API not available.";
            }
        } catch (\Exception $e) {

            Log::error($e->getMessage());

            $this->message = null;
            $this->error = "API connection failed.";
        }
        $this->dispatch('refreshDataTable');
        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.admin.employees')->layout('layouts.app');
    }
}
