<?php

namespace App\Livewire\System;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Rolepermission extends Component
{
    use WithPagination;
    public $search = '';
    public $perPage = 10;

    public function render()
    {
        return view('livewire.system.rolepermission');
    }
}
