<?php

namespace App\Livewire\User\Modal;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ResultCancel extends Component
{
    public $cancelId = '';
    public $cancelReason = '';
    protected $listeners = [
        'view-result-Cancel' => 'viewCancel',
    ];

    public function viewCancel($id = '')
    {
        $this->cancelId = $id;
        $this->cancelReason = '';
        $this->modal('cancel-result')->show();
    }

    public function confirmCancel()
    {
        $this->validate([
            'cancelReason' => 'required|string|max:1000',
        ], [
            'cancelReason.required' => 'Please provide a reason for cancelling this RESULT.',
        ]);

        $assignment = DB::table('cpar_assignments')
            ->where('id', $this->cancelId)
            ->first();

        if (!$assignment) {
            $this->addError('cancelReason', 'RESULT assignment not found.');
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

        $this->dispatch('refreshCparData');
        $this->dispatch('refreshCparCount');
        $this->dispatch('modal-close',name: 'cancel-result');
        $this->dispatch('modal-close',name: 'result-form');

        session()->flash('success','RESULT has been cancelled successfully.'
        );
    }

    public function render()
    {
        return view('livewire.user.modal.result_cancel');
    }
}
