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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- ================= LEFT SIDE ================= --}}
            <div class="min-w-0">

                <div class="border-b pb-3">
                    <flux:heading size="md">
                        CPAR Details
                    </flux:heading>

                    <flux:text>
                        View the CPAR request information below.
                    </flux:text>
                </div>


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


                    {{-- SOURCE ORIGIN --}}
                    <flux:input
                        label="Source Origin"
                        wire:model="source_name"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


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

                    {{-- CONCERN DESCRIPTION --}}
                    <flux:textarea
                        label="Concern Description"
                        wire:model="concern_description"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- CATEGORY + STATUS --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- ATTACHMENT --}}
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

                            <div
                                class="mt-2 rounded-lg border border-zinc-200
                                       bg-zinc-100 px-3 py-2.5
                                       dark:border-zinc-700 dark:bg-zinc-800">

                                <flux:text>
                                    N/A
                                </flux:text>

                            </div>

                            @endif
                        </div>
                        <flux:input
                            label="Concern Category"
                            wire:model="concern_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                    </div>

                    <flux:textarea
                        label="Dept Head Remarks"
                        wire:model="remarks"
                        rows="5"
                        readonly
                        class="w-full opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                </div>

            </div>
            {{-- ================= RIGHT SIDE ================= --}}
            <div class="min-w-0">

                <div class="border-b pb-3">
                    <flux:heading size="md">
                        CPAR Response
                    </flux:heading>

                    <flux:text>
                        Review the findings and actions taken for this CPAR.
                    </flux:text>
                </div>


                <div class="mt-5 space-y-5">
                    {{-- IDENTIFIED CAUSE --}}
                    <flux:textarea
                        label="Identified Cause"
                        wire:model="identified_cause"
                        readonly
                        rows="4"
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                    {{-- PROVIDED SOLUTION --}}
                    <flux:textarea
                        label="Provided Solution"
                        wire:model="provided_solution"
                        readonly
                        rows="4"
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                    {{-- RECOMMENDATION --}}
                    <flux:textarea
                        label="Recommendation"
                        wire:model="recommendation"
                        readonly
                        rows="4"
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                    {{-- ACTION + DATE + TAT --}}
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
                        <div class="flex items-center justify-between rounded-lg border border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800">
                            <div class="flex items-center gap-3">
                                <flux:icon.document-text class="size-5 text-red-600" />
                                <div>
                                    <flux:text class="font-medium">
                                        IR Attachment
                                    </flux:text>
                                    <flux:text size="sm" class="text-zinc-500">
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
                    <flux:input type="file" label="NTE Attachment" wire:model="nte_attachment" accept=".pdf" />
                </div>
            </div>
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