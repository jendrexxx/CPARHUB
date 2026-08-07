<flux:modal
    name="notice-to-explain"
    class="w-[120%] max-w-[1200px] mt-6 top-0 z-50">

    <div class="space-y-6">

        <div>
            <flux:heading size="lg">
                NTE request
            </flux:heading>

            <flux:text>
                Please request an NTE request from the employee assigned to this CPAR.
            </flux:text>
        </div>

        <flux:separator />

        {{-- Employee --}}
        <div class="grid grid-cols-4 gap-4">

            <flux:input
                label="Assigned Employee"
                wire:model="employee_name"
                readonly
                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

            <flux:input
                label="Empoloyee No."
                wire:model="employee_no"
                readonly
                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

            <flux:input
                label="CPAR No."
                wire:model="cpar_no"
                readonly
                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

            <flux:input
                label="NTE No."
                wire:model="nte_no"
                readonly
                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

        </div>
        <div class="flex justify-end gap-2">

            <flux:modal.close>
                <flux:button variant="ghost">
                    Cancel
                </flux:button>
            </flux:modal.close>

            <flux:button
                variant="primary"
                icon="paper-airplane"
                wire:click="sendNoticeToExplain"
                wire:loading.attr="disabled"
                :disabled="$hasNTERequest">

                <span wire:loading.remove wire:target="sendNoticeToExplain">
                     {{ $hasNTERequest ? 'NTE Request Already Sent' : 'Send NTE' }}
                </span>

                <span wire:loading wire:target="sendNoticeToExplain">
                    Sending...
                </span>

            </flux:button>

        </div>

    </div>

</flux:modal>