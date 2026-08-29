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
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                            <flux:input
                                label="Date Open"
                                wire:model="date_open"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:input
                                label="Reported By"
                                wire:model="reported_by"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                            <flux:input
                                label="Department"
                                wire:model="department_name"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                        </div>
                        <flux:input
                            label="Source Origin"
                            wire:model="source_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:input
                                label="Complaint Category"
                                wire:model="complain_name"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                            <flux:input
                                label="Complaint Name"
                                wire:model="complainant_name"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                        </div>
                        <flux:textarea
                            readonly
                            label="Concern Description"
                            wire:model="concern_description"
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                        <flux:input
                            label="Concern Category"
                            wire:model="concern_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:input
                                label="Priority"
                                wire:model="priority"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                            <flux:input
                                label="Status"
                                wire:model="status_name"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                        </div>
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
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:textarea
                            label="Provided Solution"
                            wire:model="provided_solution"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:textarea
                            label="Recommendation"
                            wire:model="recommendation"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <flux:input
                                label="Action Taken By"
                                wire:model="action_taken_by"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                            <flux:input
                                label="Date Completed"
                                wire:model="date_completed"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                            <flux:input
                                label="TAT"
                                wire:model="tat"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                        </div>
                        @if($ir_attachment)
                        <div class="mt-4">
                            <flux:text class="mb-2 font-semibold">
                                Incident Report (IR)
                            </flux:text>
                            <div
                                class="flex items-center justify-between rounded-lg
                                border border-zinc-200 bg-zinc-50 px-4 py-3
                                dark:border-zinc-700 dark:bg-zinc-800">

                                <div class="flex items-center gap-3">

                                    <flux:icon.document-text
                                        class="size-5 text-red-600" />

                                    <div>

                                        <flux:text class="font-medium">
                                            IR Attachment
                                        </flux:text>

                                        <flux:text
                                            size="sm"
                                            class="text-zinc-500">

                                            Incident Report PDF is already uploaded.

                                        </flux:text>

                                    </div>

                                </div>

                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="eye"
                                    href="{{ Storage::url($ir_attachment) }}"
                                    target="_blank">

                                    View PDF

                                </flux:button>

                            </div>
                        </div>
                        @endif
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