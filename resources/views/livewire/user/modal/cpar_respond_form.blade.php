<flux:modal
    name="respond-cpar"
    class="w-full max-w-7xl">
    <div class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-6">

            <div class="min-w-0">

                {{-- HEADER --}}
                <div
                    class="flex items-center justify-between border-b pb-3">
                    <div>
                        <flux:heading size="md">
                            CPAR Details
                        </flux:heading>

                        <flux:text>
                            View the CPAR request information below.
                        </flux:text>
                    </div>
                </div>


                {{-- DETAILS --}}
                <div class="mt-5 space-y-5">

                    {{-- CPAR NO + DATE --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <flux:input
                            label="CPAR No."
                            wire:model="cpar_no"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Date Opened"
                            wire:model="date_open"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>


                    {{-- SOURCE --}}
                    <flux:input
                        label="Source Origin"
                        wire:model="source_name"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- REPORTED BY + DEPARTMENT --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <flux:input
                            label="Reported By"
                            wire:model="reported_by"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Department Name"
                            wire:model="department_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>


                    {{-- COMPLAINANT --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <flux:input
                            label="Complainant Category"
                            wire:model="complain_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Complainant Name"
                            wire:model="complainant_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>


                    {{-- DESCRIPTION --}}
                    <flux:textarea
                        label="Concern Description"
                        wire:model="concern_description"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- ATTACHMENT + CATEGORY + STATUS --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div>
                            <flux:label>
                                Attachment
                            </flux:label>

                            @if ($attachment)

                            <div class="mt-2">
                                <flux:button
                                    icon="arrow-down-tray"
                                    variant="primary"
                                    size="sm"
                                    href="{{ Storage::url($attachment) }}"
                                    target="_blank">
                                    Download
                                </flux:button>
                            </div>

                            @else

                            <div class="mt-2 rounded-lg border border-zinc-200 bg-zinc-100 px-3 py-2.5 dark:border-zinc-700 dark:bg-zinc-800">
                                <flux:text>N/A</flux:text>
                            </div>

                            @endif
                        </div>


                        <flux:input
                            label="Concern Category"
                            wire:model="concern_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                        <flux:input
                            label="Priority"
                            wire:model="priority"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>

                    <flux:textarea
                        label="Remarks"
                        wire:model="remarks"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                </div>

            </div>

            <div class="min-w-0">

                {{-- HEADER --}}
                <div class="border-b pb-3">

                    <flux:heading size="md">
                        CPAR Response
                    </flux:heading>

                    <flux:text>
                        Provide the findings and actions taken for this CPAR.
                    </flux:text>

                </div>


                {{-- RESPONSE --}}
                <div class="mt-5 space-y-5">

                    <flux:textarea
                        label="Identified Cause"
                        wire:model="identified_cause"
                        rows="4"
                        placeholder="Enter the identified cause..." />


                    <flux:textarea
                        label="Provided Solution"
                        wire:model="provided_solution"
                        rows="4"
                        placeholder="Enter the provided solution..." />

                    <flux:textarea
                        label="Recommendation"
                        wire:model="recommendation"
                        rows="4"
                        placeholder="Enter recommendation..." />

                    <flux:input
                        type="file"
                        label="IR Attachment"
                        wire:model="ir_attachment"
                        accept=".pdf" />

                    @if ($existing_ir_attachment)
                    <div class="mt-2 flex items-center gap-2">

                        <flux:button
                            type="button"
                            icon="eye"
                            variant="primary"
                            size="sm"
                            href="{{ Storage::url($existing_ir_attachment) }}"
                            target="_blank">
                            View Existing IR
                        </flux:button>

                        <span class="text-sm text-zinc-500">
                            Existing attachment
                        </span>

                    </div>
                    @endif
                    {{-- COMPLETION DETAILS --}}
                    <div class="border-t pt-5">

                        <flux:heading size="sm">
                            Completion Details
                        </flux:heading>

                        <flux:text class="mt-1">
                            Select the final status and review the completion details.
                        </flux:text>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

                            <flux:input
                                label="Date Completed"
                                wire:model="date_completed"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                            <flux:input
                                label="TAT (Days)"
                                wire:model="tat"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <div class="flex justify-end gap-2 border-t pt-5">

            {{-- SAVE DRAFT --}}
            <flux:button
                variant="primary"
                icon="lock-open"
                wire:click="saveDraft"
                wire:loading.attr="disabled"
                wire:target="saveDraft"
                class="bg-orange-500 hover:bg-orange-600 text-white">

                <span
                    wire:loading.remove
                    wire:target="saveDraft">

                    Save Draft

                </span>

                <span
                    wire:loading
                    wire:target="saveDraft">

                    Saving...

                </span>

            </flux:button>

            {{-- SUBMIT RESPONSE --}}
            <flux:button
                variant="primary"
                icon="check"
                wire:click="saveResponse"
                wire:loading.attr="disabled"
                wire:target="saveResponse">

                <span wire:loading.remove wire:target="saveResponse">
                    Submit
                </span>

                <span wire:loading wire:target="saveResponse">
                    Submitting...
                </span>
            </flux:button>

        </div>
    </div>
</flux:modal>