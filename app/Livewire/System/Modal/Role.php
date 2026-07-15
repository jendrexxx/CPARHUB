<?php

namespace App\Livewire\System\Modal;

use Spatie\Permission\Models\Role as RoleModel;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Role extends Component
{
    public $role_id;
    public $name = '';
    public $message;
    public $permissions = [];
    public $selectedPermissions = [];

    protected $listeners = [
        'role-record' => 'edit',
        'open-modal' => 'createRole',
        'refreshRoles' => 'refreshRoleTable',
    ];

    public function mount()
    {
        $this->permissions = Permission::orderBy('name')->get();
    }

    public function createRole()
    {
        $this->resetForm();
        $this->modal('role-modal')->show();
    }

    public function edit($id)
    {
        $role = RoleModel::findOrFail($id);
        $this->role_id = $role->id;
        $this->name = $role->name;
        // Existing permissions
        $this->selectedPermissions = $role->permissions
            ->pluck('name')
            ->toArray();
        $this->modal('role-modal')->show();
        // or
        // $this->dispatch('open-modal', name: 'role-modal');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->role_id,
        ]);

        if ($this->role_id) {

            $role = RoleModel::findOrFail($this->role_id);

            $role->update([
                'name' => $this->name,
            ]);
        } else {

            $role = RoleModel::create([
                'name' => $this->name,
            ]);
        }

        // Save permissions
        $role->syncPermissions($this->selectedPermissions);

        $this->dispatch('refreshRoles');

        $this->resetForm();

        $this->modal('role-modal')->close();
    }

    public function resetForm()
    {
        $this->reset([
            'role_id',
            'name',
            'selectedPermissions',
        ]);
    }

    public function render()
    {
        return view('livewire.system.modal.role');
    }
}
