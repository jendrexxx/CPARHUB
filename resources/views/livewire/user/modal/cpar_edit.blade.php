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


            @if($cparRecord)

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <flux:input
                        label="CPAR No."
                        value="{{ $cparRecord->cpar_no }}"
                        readonly />


                    <flux:input
                        label="Reported By"
                        value="{{ $cparRecord->reported_by }}"
                        readonly />


                    <flux:input
                        label="Date Open"
                        value="{{ \Carbon\Carbon::parse($cparRecord->date_open)->format('M d, Y') }}"
                        readonly />


                    <flux:input
                        label="Department"
                        value="{{ $cparRecord->department_name }}"
                        readonly />


                    <flux:input
                        label="Source Origin"
                        value="{{ $cparRecord->source_name }}"
                        readonly />


                    <flux:input
                        label="Complaint Category"
                        value="{{ $cparRecord->complaint_category_name }}"
                        readonly />


                    <flux:input
                        label="Concern Category"
                        value="{{ $cparRecord->concern_category_name }}"
                        readonly />


                    <flux:input
                        label="Status"
                        value="{{ $cparRecord->status_name }}"
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


            @else

                <div class="text-center text-zinc-500 py-6">
                    No CPAR record selected.
                </div>

            @endif


        </div>

    </flux:modal>
</div>