<?php

namespace App\Livewire\User\Modal;

use Livewire\Component;
use Livewire\Attributes\On;

class CparEdit extends Component
{
    public $cparRecord = null;


    #[On('edit-cpar-open')]
    public function open($record)
    {
        dd($record);

        $this->cparRecord = $record;
    }


    public function render()
    {
        return view('livewire.user.modal.cpar_edit');
    }
}
