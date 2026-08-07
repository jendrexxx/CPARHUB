<div>

    <flux:modal
        name="submission-cpar"
        class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">

        <div class="space-y-6">

            {{-- Header --}}
            <div>
                <flux:heading size="lg">
                    CPAR Submission
                </flux:heading>
                <flux:text>
                    Please review the CPAR submission requests assigned to you.
                </flux:text>
            </div>


            {{-- Main Content --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- ================= LEFT SIDE ================= --}}
                <div class="space-y-4">

                    <div class="rounded-lg p-5 space-y-2">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <flux:input
                                label="CPAR No."
                                wire:model="cpar_no"
                                readonly />

                            <flux:input
                                label="Date Open"
                                wire:model="date_open"
                                readonly />

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:input
                                label="Reported By"
                                wire:model="reported_by"
                                readonly />

                            <flux:input
                                label="Department"
                                wire:model="department_name"
                                readonly />
                        </div>
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
                        <flux:textarea
                            label="Remarks"
                            wire:model="remarks" />
                    </div>

                </div>

                {{-- ================= RIGHT SIDE ================= --}}
                <div class="space-y-4">

                    <div class="rounded-lg p-5 space-y-4">

                        <flux:textarea
                            label="Identified Cause"
                            wire:model="identified_cause"
                            readonly />

                        <flux:textarea
                            label="Provided Solution"
                            wire:model="provided_solution"
                            readonly />

                        <flux:textarea
                            label="Recommendation"
                            wire:model="recommendation"
                            readonly />
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <flux:input
                                label="Action Taken By"
                                wire:model="action_taken_by"
                                readonly />

                            <flux:input
                                label="Date Completed"
                                wire:model="date_completed"
                                readonly />

                            <flux:input
                                label="TAT"
                                wire:model="tat"
                                readonly />
                        </div>

                    </div>

                </div>

            </div>


            {{-- Footer --}}
            <div class="flex justify-end gap-2">

                <flux:modal.close>
                    <flux:button variant="ghost">
                        Close
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    variant="primary"
                    wire:click="submitCPAR({{ $this->id ?? 0 }})"
                    icon="paper-airplane">
                    Submit
                </flux:button>

            </div>

        </div>

    </flux:modal>

</div>