<?php

namespace App\Livewire\System\Modal;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends Component
{
    public $userId;
    public $role;
    public $roles = [];
    public $permission = [];
    public $selectedPermissions = [];

    protected $listeners = [
        'permission-record' => 'open',
    ];


    public function mount()
    {
        $this->roles = Role::all();
        $this->permission = ModelsPermission::all();
    }

    public function open($id)
    {
        $this->userId = $id;
        $user = User::findOrFail($id);
        $this->role = optional($user->roles->first())->name;
        $this->selectedPermissions = $user->permissions
            ->pluck('name')
            ->toArray();
        $this->modal('user-permission')->show();
    }

    public function savePermission()
    {
        $this->validate([
            'role' => 'required',
        ]);
        $user = User::findOrFail($this->userId);
        // assign role to user
        $user->syncRoles([
            $this->role
        ]);
        // optional:
        // direct permissions sa user
        $user->syncPermissions(
            $this->selectedPermissions
        );
        $this->dispatch('modal-close', name: 'user-permission');
        $this->dispatch(
            'toast',
            type: 'success',
            message: 'Permission updated successfully.'
        );
        $this->dispatch('refreshUsers');

    }

    public function render()
    {
        return view('livewire.system.modal.permission');
    }
}
