<?php

namespace App\Livewire\System;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Auditlogs extends Component
{
    public function render()
    {
        return view('livewire.system.auditlogs');
    }
}
