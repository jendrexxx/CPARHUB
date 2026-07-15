<?php

namespace App\Livewire\System;
use App\Models\employee;
use App\Models\User;

use Livewire\Component;

class Statistic extends Component
{
    public $tab = 'users';

    public $branch = '';

    public $search = '';

    public $totalEmployees;

    public $activeUsers;

    public $inactiveUsers;

    public $newUsers;

    public function mount()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        $this->totalEmployees = employee::count();

        $this->activeUsers = User::where('status', 1)->count();

        $this->inactiveUsers = User::where('status', 0)->count();

        $this->newUsers = User::whereDate('created_at', today())->count();
    }

    public function render()
    {
        return view('livewire.system.statistic')->layout('layouts.app');
    }
}
