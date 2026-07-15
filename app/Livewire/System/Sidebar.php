<?php

namespace App\Livewire\System;

use Livewire\Component;

class Sidebar extends Component
{
    public function render()
    {
        return view('livewire.system.sidebar')->layout('layouts.app');
    }
}
