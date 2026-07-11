<?php

namespace App\Livewire\User\Cpar;

use Livewire\Component;

class CparRequestForm extends Component
{
    public function render()
    {
        return view('livewire.user.cpar.cpar_request_form')->layout('layouts.app');
    }
}
