<flux:modal name="cancel-cpar" class="w-full max-w-lg">

    <div class="space-y-6">
        {{-- HEADER --}}
        <div>
            <flux:heading size="lg">
                Cancel CPAR Request
            </flux:heading>
            <flux:text class="mt-1">
                Please provide a reason for cancelling this CPAR request.
            </flux:text>
        </div>

        {{-- REASON --}}
        <div>
            <flux:textarea
                wire:model="cancelReason"
                label="Reason for Cancellation"
                placeholder="Enter the reason for cancelling this CPAR..."
                rows="5" />
        </div>

        {{-- ACTIONS --}}
        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">
                    Close
                </flux:button>
            </flux:modal.close>

            <flux:button
                variant="danger"
                icon="x-mark"
                wire:click="confirmCancel">
                Cancel CPAR
            </flux:button>
        </div>
    </div>
</flux:modal>