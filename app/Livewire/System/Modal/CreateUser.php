<?php

namespace App\Livewire\System\Modal;

use App\Livewire\System\Branches;
use App\Models\branch;
use App\Models\department;
use App\Models\employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Component
{
    public $name = '';
    public $email = '';
    public $username = '';
    public $password = '';
    public $user_id = null;
    public $employee_no;
    public $confirm_password = '';
    public $department_name = '';
    public $branch_name = '';
    public $role = '';
    public string $status = 'Active';
    public $userId = '';
    public $department_list = [];
    public $branch_list = [];
    public $roles = [];

    protected $listeners = [
        'open-modal' => 'createUser',
        'edit-record' => 'editRecord',
    ];

    protected function rules()
    {
        $rules = [
            'employee_no' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user_id),
            ],
            'username' => [
                'required',
                Rule::unique('users', 'username')->ignore($this->user_id),
            ],
            'department_name' => ['required'],
            'branch_name' => ['required'],
            'role' => ['required'],
            'status' => ['required'],
        ];
        // Password required only when creating
        if (!$this->user_id) {
            $rules['password'] = ['required', 'min:7'];
            $rules['confirm_password'] = ['required', 'same:password'];
        } else {
            // Optional when editing
            $rules['password'] = ['nullable', 'min:7'];
            $rules['confirm_password'] = ['nullable', 'same:password'];
        }

        return $rules;
    }

    public function updatedEmployeeNo($value)
    {
        $value = trim($value);

        if ($value === '') {
            $this->resetEmployeeFields();

            return;
        }

        $employee = employee::query()
            ->leftJoin('branches', 'employees.branch_id', '=', 'branches.id')
            ->where('employees.employee_no', $value)
            ->select(
                'employees.*',
                'branches.branch_name'
            )
            ->first();

        if (!$employee) {
            $this->resetEmployeeFields();

            return;
        }

        $firstName = trim($employee->first_name ?? '');
        $lastName = trim($employee->last_name ?? '');

        // Full Name
        $this->name = trim($firstName . ' ' . $lastName);

        // Email
        $this->email = $employee->email ?? '';

        // Username = first 3 letters of first name + first 3 letters of last name
        $this->username = strtoupper(
            substr($firstName, 0, 3) .
                substr($lastName, 0, 3)
        );

        // Department
        $this->department_name = $employee->department_name ?? '';

        // Branch
        $this->branch_name = $employee->branch_name ?? '';
    }

    private function resetEmployeeFields()
    {
        $this->name = '';
        $this->email = '';
        $this->username = '';
        $this->department_name = '';
        $this->branch_name = '';
    }

    protected function messages()
    {
        return [
            'employee_no.required' => 'Employee No is required.',
            'name.required' => 'Full Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email address.',
            'email.unique' => 'Email already exists.',
            'username.required' => 'Username is required.',
            'username.unique' => 'Username already exists.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'confirm_password.required' => 'Confirm Password is required.',
            'confirm_password.same' => 'Passwords do not match.',
            'department_name.required' => 'Department is required.',
            'branch_name.required' => 'Branch is required.',
            'role.required' => 'Role is required.',
            'status.required' => 'Status is required.',
        ];
    }

    public function mount()
    {
        $this->department_list = department::pluck('department_name')->toArray();
        $this->branch_list = branch::pluck('branch_name')->toArray();
        $this->roles = Role::pluck('name')->toArray();
    }

    public function createUser()
    {
        $this->resetForm();
        $this->modal('user-create')->show();
    }

    public function editRecord($id)
    {
        $user = User::query()
            ->leftJoin('employees as a', 'a.email', '=', 'users.email')
            ->leftJoin('model_has_roles as mhr', function ($join) {
                $join->on('users.id', '=', 'mhr.model_id')
                    ->where('mhr.model_type', User::class);
            })
            ->leftJoin('roles as r', 'r.id', '=', 'mhr.role_id')
            ->select(
                'users.*',
                'a.employee_no',
                'a.department_name',
                'a.branch_name',
                'r.name as role_name'
            )
            ->where('users.id', $id)
            ->firstOrFail();

        $this->user_id = $user->id;
        $this->employee_no = $user->employee_no;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->department_name = $user->department_name;
        $this->branch_name = $user->branch_name;
        $this->role = $user->role_name ?? '';
        $this->modal('user-create')->show();
    }

    public function resetForm()
    {
        $this->user_id = null;
        $this->name = '';
        $this->email = '';
        $this->username = '';
        $this->password = '';
        $this->confirm_password = '';
        $this->employee_no = '';
        $this->department_name = '';
        $this->branch_name = '';
        $this->role = '';
        $this->status = 'Active';
    }

    #[On('permission-record')]
    public function permissionRecord($id)
    {
        $this->userId = $id;
        $user = User::findOrFail($id);
        $this->role = optional($user->roles->first())->name;
        $this->modal('user-permission')->show();
    }

    public function save()
    {
        if ($this->user_id) {

            $user = User::findOrFail($this->user_id);

            $oldEmail = $user->email;

            $data = [
                'name'     => $this->name,
                'email'    => $this->email,
                'username' => $this->username,
                'status'   => $this->status,
            ];

            // Update password only if entered
            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }

            $user->update($data);

            DB::table('employees')
                ->where('email', $oldEmail)
                ->update([
                    'email'            => $this->email,
                    'employee_no'      => $this->employee_no,
                    'department_name'  => $this->department_name,
                    'branch_name'      => $this->branch_name,
                ]);

            if ($this->role) {
                $user->syncRoles([$this->role]);
            }

            $this->dispatch(
                'modal-close',
                name: 'user-create'
            );

            $this->dispatch('refreshUsers');

            $this->dispatch(
                'toast',
                type: 'success',
                message: 'User successfully updated.'
            );
        } else {

            $user = User::create([
                'name'     => $this->name,
                'email'    => $this->email,
                'username' => $this->username,
                'password' => Hash::make($this->password),
                'status'   => $this->status,
            ]);

            DB::table('employees')->insert([
                'employee_no'     => $this->employee_no,
                'email'           => $this->email,
                'department_name' => $this->department_name,
                'branch_name'     => $this->branch_name,
            ]);

            if ($this->role) {
                $user->assignRole($this->role);
            }

            $this->dispatch(
                'modal-close',
                name: 'user-create'
            );

            $this->dispatch('refreshUsers');

            $this->dispatch(
                'toast',
                type: 'success',
                message: 'User successfully created.'
            );
        }
    }

    public function render()
    {
        return view('livewire.system.modal.create_user');
    }
}
