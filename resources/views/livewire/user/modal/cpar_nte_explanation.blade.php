<flux:modal
    name="view-notice-to-explain"
    class="w-[120%] max-w-[1500px] mt-6 top-0 z-50"
    wire:model="showViewNte">

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-start justify-between border-b pb-4">

            <div>
                <flux:heading size="lg">
                    Notice to Explain
                </flux:heading>

                <flux:text class="mt-1">
                    View the issued Notice to Explain.
                </flux:text>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- ================= LEFT SIDE ================= --}}
            <div class="space-y-4">
                <div>
                    <flux:label class="text-xl font-bold">
                        CPAR Information
                    </flux:label>

                    <flux:text class="mt-1">
                        Review the basic information of this CPAR.
                    </flux:text>
                </div>
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

                    <flux:input
                        label="Complaint Category"
                        wire:model="complain_name"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    <flux:input
                        label="Concern Category"
                        wire:model="concern_name"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    <flux:input
                        label="Status"
                        wire:model="status_name"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    {{-- IR ATTACHMENT --}}
                    <div>
                        <flux:label>
                            IR Attachment
                        </flux:label>

                        @if ($ir_attachment)

                        <div class="mt-2 flex items-center gap-2">

                            {{-- View --}}
                            <flux:button
                                icon="eye"
                                variant="primary"
                                size="sm"
                                href="{{ Storage::url($ir_attachment) }}"
                                target="_blank">
                                View
                            </flux:button>

                            {{-- Download --}}
                            <flux:button
                                icon="arrow-down-tray"
                                variant="primary"
                                size="sm"
                                href="{{ Storage::url($ir_attachment) }}"
                                download>
                                Download
                            </flux:button>

                        </div>

                        @else

                        <div class="mt-2 rounded-lg border border-gray-200 bg-gray-100 px-3 py-2.5 dark:border-zinc-700 dark:bg-zinc-800">

                            <flux:text>
                                N/A
                            </flux:text>

                        </div>

                        @endif
                    </div>


                    {{-- NTE ATTACHMENT --}}
                    {{-- NTE ISSUED BY HR --}}
                    <div>

                        <flux:label>
                            NTE Issued by HR
                        </flux:label>

                        @if ($nte_attachment)

                        <div class="mt-2 flex items-center gap-2">

                            <flux:button
                                icon="eye"
                                variant="primary"
                                size="sm"
                                href="{{ Storage::url($nte_attachment) }}"
                                target="_blank">

                                View

                            </flux:button>

                            <flux:button
                                icon="arrow-down-tray"
                                variant="primary"
                                size="sm"
                                href="{{ Storage::url($nte_attachment) }}"
                                download>

                                Download

                            </flux:button>

                        </div>

                        @else

                        <div class="mt-2 rounded-lg border border-zinc-200 bg-zinc-100 px-3 py-2.5 dark:border-zinc-700 dark:bg-zinc-800">

                            <flux:text>
                                No NTE attachment available.
                            </flux:text>

                        </div>

                        @endif

                    </div>
                    <flux:textarea label="Dept Head Remarks" wire:model="remarks" readonly class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                </div>

            </div>

            {{-- ================= RIGHT SIDE ================= --}}
            <div class="space-y-4">

                <div>
                    <flux:label class="text-xl font-bold">
                        Investigation Details
                    </flux:label>

                    <flux:text class="mt-1">
                        Review the investigation findings and actions taken.
                    </flux:text>
                </div>

                <div class="rounded-lg p-5 space-y-4">

                    {{-- IDENTIFIED CAUSE --}}
                    <flux:textarea
                        label="Identified Cause"
                        wire:model="identified_cause"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    {{-- PROVIDED SOLUTION --}}
                    <flux:textarea
                        label="Provided Solution"
                        wire:model="provided_solution"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    {{-- RECOMMENDATION --}}
                    <flux:textarea
                        label="Recommendation"
                        wire:model="recommendation"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    {{-- ACTION TAKEN BY --}}
                    <flux:input
                        label="Action Taken By"
                        wire:model="action_taken_by"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    {{-- DATE COMPLETED / TAT --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

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

                    {{-- IR NO. / NTE NO. --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <flux:input
                            label="IR No."
                            wire:model="ir_id"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="NTE No."
                            wire:model="nte_no"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>
                    {{-- STAFF NTE RESPONSE --}}
                    <div>
                        <flux:label>
                            NTE Response Attachment
                        </flux:label>
                        <div class="mt-2">
                            <flux:input
                                type="file"
                                wire:model="response_attachment"
                                accept=".pdf,application/pdf" />
                        </div>
                        <flux:text class="mt-1 text-xs text-zinc-500">
                            Upload your NTE response in PDF format. Maximum file size: 10MB.
                        </flux:text>
                        @error('response_attachment')
                        <div class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
        {{-- Footer --}}
        <div class="flex justify-end gap-2 pt-4 border-t">
            <flux:button
                variant="primary"
                icon="paper-airplane"
                wire:click="submitNteResponse"
                wire:loading.attr="disabled"
                wire:target="submitNteResponse">
                <span
                    wire:loading.remove
                    wire:target="submitNteResponse">
                    Submit
                </span>
                <span
                    wire:loading
                    wire:target="submitNteResponse">
                    Submitting...
                </span>
            </flux:button>
        </div>

    </div>

</flux:modal>