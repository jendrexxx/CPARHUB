<div>
    <flux:modal name="EditCPARModal" class="w-full max-w-5xl">

        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Edit CPAR Request
                </flux:heading>

                <flux:text>
                    Update CPAR information below.
                </flux:text>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <flux:input
                    label="CPAR No."
                    wire:model="cpar_no"
                    readonly />

                <flux:input
                    label="Reported By"
                    wire:model="reported_by"
                    readonly />

                <flux:input
                    label="Date Open"
                    wire:model="date_open"
                    readonly />

                <flux:input
                    label="Department"
                    wire:model="department_name"
                    readonly />

                <flux:input
                    label="Source Origin"
                    wire:model="source_name"
                    readonly />

                <flux:input
                    label="Complaint Category"
                    wire:model="complain_name"
                    readonly />

                <flux:input
                    label="Concern Category"
                    wire:model="concern_name"
                    readonly />

                <flux:input
                    label="Status"
                    wire:model="status_name"
                    readonly />
            </div>


            <div class="flex justify-end gap-2">

                <flux:button
                    variant="ghost"
                    x-on:click="$flux.modal('EditCPARModal').close()">
                    Cancel
                </flux:button>


                <flux:button
                    variant="primary"
                    wire:click="update">
                    Save Changes
                </flux:button>

            </div>





        </div>

    </flux:modal>
</div>