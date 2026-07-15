<?php

namespace App\Livewire\System\Modal;

use App\Livewire\System\Branches;
use App\Models\branch;
use App\Models\department;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class CreateUser extends Component
{
    public $name;
    public $email;
    public $username;
    public $password;
    public $user_id = null;
    public $employee_no;
    public $confirm_password;
    public $department_name;
    public $branch_name;
    public $role;
    public $status = 'Active';
    public $userId;
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
            $rules['password'] = ['required', 'min:8'];
            $rules['confirm_password'] = ['required', 'same:password'];
        } else {
            // Optional when editing
            $rules['password'] = ['nullable', 'min:8'];
            $rules['confirm_password'] = ['nullable', 'same:password'];
        }

        return $rules;
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
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->status = $user->status;
        $this->role = optional($user->roles->first())->name;
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
        $this->validate();
        if ($this->user_id) {

            // Update User

        } else {
            // Create User
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'password' => bcrypt($this->password),
            ]);
            $this->dispatch('close-modal', name: 'user-create');
            $this->dispatch('refreshUsers');
        }
    }

    public function render()
    {
        return view('livewire.system.modal.create_user');
    }
}
