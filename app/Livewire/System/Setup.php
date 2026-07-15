<?php

namespace App\Livewire\System;

use Livewire\Component;

class Setup extends Component
{
    public $tab = 'users';

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function render()
    {
        return view('livewire.system.setup')->layout('layouts.app');
    }
}
