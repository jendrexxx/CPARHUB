<?php

namespace App\Livewire\User\Modal;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CparCancel extends Component
{
    public $cancelId;
    public $cancelReason = '';
    protected $listeners = [
        'view-Cancel' => 'viewCancel',
    ];

    public function viewCancel($id = '')
    {
        $this->cancelId = $id;
        $this->cancelReason = '';
        $this->modal('cancel-cpar')->show();
    }

    public function confirmCancel()
    {
        $this->validate([
            'cancelReason' => 'required|string|max:1000',
        ], [
            'cancelReason.required' => 'Please provide a reason for cancelling this CPAR.',
        ]);

        $assignment = DB::table('cpar_assignments')
            ->where('cpar_id', $this->cancelId)
            ->first();
            
        if (!$assignment) {
            $this->addError('cancelReason', 'CPAR assignment not found.');
            return;
        }

        DB::table('cpar_assignments')
            ->where('id', $this->cancelId)
            ->update([
                'status_id' => 60,
                'remarks'   => $this->cancelReason,
                'updated_at' => now(),
            ]);
        $this->modal('cancel-cpar')->close();
        $this->reset([
            'cancelId',
            'cancelReason',
        ]);

        session()->flash(
            'success',
            'CPAR has been cancelled successfully.'
        );
    }

    public function render()
    {
        return view('livewire.user.modal.cpar_cancel');
    }
}
