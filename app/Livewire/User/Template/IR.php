<?php

namespace App\Livewire\User\Template;

use Livewire\Component;

class IR extends Component
{

    public $show = false;

    protected $listeners = [
        'printIncidentReport' => 'showIncidentReport',
    ];

    public function showIncidentReport()
    {
        $this->show = true;
        $this->dispatch('start-print');
    }

    public function render()
    {
        return view('livewire.user.template.i-r');
    }
}
