<flux:modal
    name="incident-report-request"
    class="w-[120%] max-w-[1200px] mt-6 top-0 z-50">

    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <flux:heading size="lg">
                Incident Report Request
            </flux:heading>

            <flux:text>
                Please create an Incident Report request for the employee assigned to this CPAR.
            </flux:text>
        </div>

        <flux:separator />

        {{-- Employee Information --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

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
                label="IR No"
                wire:model="ir_id"
                readonly
                placeholder="Auto-generated"
                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-2">

            <flux:modal.close>
                <flux:button variant="ghost">
                    Cancel
                </flux:button>
            </flux:modal.close>

            <flux:button
                variant="primary"
                icon="paper-airplane"
                wire:click="sendIncidentReportRequest"
                wire:loading.attr="disabled"
                :disabled="$hasIrRequest">

                <span
                    wire:loading.remove
                    wire:target="sendIncidentReportRequest">
                    {{ $hasIrRequest ? 'IR Request Already Sent' : 'Send IR Request' }}
                </span>

                <span
                    wire:loading
                    wire:target="sendIncidentReportRequest">
                    Sending...
                </span>

            </flux:button>

        </div>

    </div>

</flux:modal>