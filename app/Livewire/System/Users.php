<?php

namespace App\Livewire\System;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Users extends Component
{
    public $message = null;
    public $error = null;

    public function render()
    {
        return view('livewire.system.users', [
            'message' => $this->message,
            'error' => $this->error,
        ]);
    }
}