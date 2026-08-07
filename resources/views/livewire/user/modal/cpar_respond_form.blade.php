<flux:modal name="respond-cpar" class="w-full max-w-5xl">
    <div class="space-y-6">

        <div>
            <flux:heading size="lg">
                Respond to CPAR
            </flux:heading>

            <flux:text>
                Complete the assigned CPAR form.
            </flux:text>
        </div>

        {{-- Basic Information --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <flux:input
                label="CPAR No."
                wire:model="cpar_no"
                readonly
                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

            <flux:input
                label="Date Opened"
                wire:model="date_opened"
                readonly
                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
        </div>

        {{-- Response --}}
        <div class="grid grid-cols-1 gap-6">

            <flux:textarea
                label="Identified Cause"
                wire:model="identified_cause"
                rows="3"
                placeholder="Enter the identified cause..." />

            <flux:textarea
                label="Provided Solution"
                wire:model="provided_solution"
                rows="3"
                placeholder="Enter the provided solution..." />

            <flux:textarea
                label="Recommendation"
                wire:model="recommendation"
                rows="3"
                placeholder="Enter recommendation..." />

            <flux:textarea
                label="Action Taken"
                wire:model="action_taken_by"
                rows="3"
                placeholder="Describe the action taken..." />
        </div>

        {{-- Completion --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <flux:select
                label="Status"
                wire:model="status">
                <option value="">
                    -- Select Status --
                </option>
                <option value="15">
                    RESOLVED
                </option>
                <option value="20">
                    UNRESOLVED
                </option>
            </flux:select>

            <flux:input
                type="text"
                label="Date Completed"
                wire:model.live="date_completed"
                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" readonly />

            <flux:input
                label="TAT (Days)"
                wire:model="tat"
                readonly
                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

        </div>

        <div class="flex justify-end gap-2">
            <flux:button
                variant="ghost"
                x-on:click="$flux.modal('respond-cpar').close()">
                Cancel
            </flux:button>

            <flux:button
                variant="primary"
                wire:click="saveResponse">
                Submit
            </flux:button>
        </div>

    </div>
</flux:modal>