<div>
    <flux:modal
        name="hr-memo-modal"
        class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">
        <div class="space-y-6">

            <div>
                <flux:label class="text-xl font-bold">
                    Employee Memo
                </flux:label>

                <flux:text class="mt-1">
                    Review the CPAR details and HR decision before issuing the employee memo.
                </flux:text>
            </div>

            <flux:separator />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="space-y-5">

                    <div>
                        <flux:label class="text-xl font-bold">
                            Employee Information
                        </flux:label>

                        <flux:text class="mt-1">
                            Review the employee and CPAR information.
                        </flux:text>
                    </div>

                    {{-- CPAR NO + DATE --}}
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

                    {{-- REPORTED BY + SOURCE --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <flux:input
                            label="Reported By"
                            wire:model="reported_by"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Department"
                            wire:model="reported_by_department"
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

                    {{-- CONCERN --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:input
                            label="Source Origin"
                            wire:model="source_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Concern Category"
                            wire:model="concern_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>
                    {{-- EMPLOYEE + DEPARTMENT --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <flux:input
                            label="Reported Employee"
                            wire:model="employee_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Department"
                            wire:model="department_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>
                    <flux:textarea
                        label="Concern Description"
                        wire:model="concern_description"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                </div>

                <div class="space-y-5">

                    <div>
                        <flux:label class="text-xl font-bold">
                            Investigation Details
                        </flux:label>

                        <flux:text class="mt-1">
                            Review the investigation findings before issuing the memo.
                        </flux:text>
                    </div>

                    <flux:textarea
                        label="Identified Cause"
                        wire:model="identified_cause"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    <flux:textarea
                        label="Provided Solution"
                        wire:model="provided_solution"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    <flux:textarea
                        label="Recommendation"
                        wire:model="recommendation"
                        rows="4"
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

                </div>

            </div>

            <div>

                <flux:textarea
                    label="Dept Head Remarks"
                    wire:model="head_remarks"
                    rows="4"
                    readonly
                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

            </div>
            <!-- SUPPORTING DOCUMENTS -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                {{-- NTE --}}
                @if ($nte_id)
                <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="space-y-4">

                        <div>
                            <flux:heading size="sm">
                                Notice to Explain (NTE)
                            </flux:heading>

                            <flux:text class="mt-1">
                                Upload the NTE document in PDF format.
                            </flux:text>
                        </div>

                        @if ($current_nte_attachment)
                        <div
                            class="flex items-center justify-between rounded-lg
                       border border-zinc-200 bg-zinc-50 px-4 py-3
                       dark:border-zinc-700 dark:bg-zinc-800">

                            <div class="flex min-w-0 items-center gap-3">

                                <flux:icon.document-text
                                    class="size-5 shrink-0 text-red-600" />

                                <div class="min-w-0">
                                    <flux:text class="font-medium">
                                        Current Attachment
                                    </flux:text>

                                    <flux:text size="sm" class="text-zinc-500">
                                        Notice to Explain PDF is already uploaded.
                                    </flux:text>
                                </div>

                            </div>

                            <flux:button
                                size="sm"
                                variant="ghost"
                                icon="eye"
                                href="{{ Storage::url($current_nte_attachment) }}"
                                target="_blank">

                                View PDF

                            </flux:button>

                        </div>
                        @endif

                    </div>
                </div>
                @endif


                {{-- IR --}}
                @if ($ir_id)
                <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="space-y-4">

                        <div>
                            <flux:heading size="sm">
                                Incident Report (IR)
                            </flux:heading>

                            <flux:text class="mt-1">
                                Upload the Incident Report document in PDF format.
                            </flux:text>
                        </div>

                        @if ($current_ir_attachment)
                        <div
                            class="flex items-center justify-between rounded-lg
                       border border-zinc-200 bg-zinc-50 px-4 py-3
                       dark:border-zinc-700 dark:bg-zinc-800">

                            <div class="flex min-w-0 items-center gap-3">

                                <flux:icon.document-text
                                    class="size-5 shrink-0 text-red-600" />

                                <div class="min-w-0">
                                    <flux:text class="font-medium">
                                        Current Attachment
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
                                href="{{ Storage::url($current_ir_attachment) }}"
                                target="_blank">

                                View PDF

                            </flux:button>

                        </div>
                        @endif

                    </div>
                </div>
                @endif

            </div>
            <flux:separator />

            <div class="space-y-5">

                <div>
                    <flux:label class="text-xl font-bold">
                        HR Decision
                    </flux:label>

                    <flux:text class="mt-1">
                        Review the final HR decision applicable to this employee.
                    </flux:text>
                </div>


                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- HR DECISION --}}
                    <div class="space-y-3">
                        @foreach($decisionCategories as $item)
                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <flux:input
                                    label="HR Decision"
                                    value="{{ $item->decision_name }}"
                                    readonly
                                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- DISCIPLINARY CATEGORY --}}
                    <div class="space-y-3">
                        @foreach($disciplinaryCategories as $item)
                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <flux:input
                                    label="Disciplinary Category"
                                    value="{{ $item->category_name }}"
                                    readonly
                                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- OFFENSE LEVEL --}}
                    <div class="space-y-3">
                        @foreach($offenseLevels as $item)
                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <flux:input
                                    label="Offense Level"
                                    value="{{ $item->offense_name }}"
                                    readonly
                                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- HR DECISION REMARKS --}}
                <flux:textarea
                    label="HR Decision Remarks"
                    wire:model="hr_decision_remarks"
                    rows="5"
                    readonly
                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

            </div>

            @if(!empty($management_remarks))
            <flux:separator />
            <div class="space-y-4">

                <div>
                    <flux:label class="text-xl font-bold">
                        Management
                    </flux:label>

                    <flux:text class="mt-1 text-sm text-zinc-500">
                        Review the remarks or comments provided by management.
                    </flux:text>
                </div>

                <flux:textarea
                    label="Management Remarks"
                    wire:model="management_remarks"
                    rows="5"
                    disabled
                    class="bg-zinc-100 dark:bg-zinc-800" />

            </div>
            @endif

            <div class="space-y-5">

                <div>
                    <flux:label class="text-xl font-bold">
                        Details
                    </flux:label>

                    <flux:text class="mt-1">
                        Prepare the memo for the employee based on the approved HR decision.
                    </flux:text>
                </div>

                {{-- MEMO NO + DATE --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input
                        readonly
                        label="Memo No."
                        wire:model="memo_no"
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                    <flux:input
                        readonly
                        type="text"
                        label="Date"
                        wire:model="memo_date"
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                </div>

                {{-- MEMO SUBJECT --}}
                <flux:input
                    label="Subject"
                    wire:model="memo_subject"
                    placeholder="Enter memo subject..." />

                {{-- MEMO CONTENT --}}
                <flux:textarea
                    label="Content"
                    wire:model="memo_content"
                    rows="8"
                    placeholder="Enter memo content..." />

                {{-- MEMO ATTACHMENT --}}
                <div class="space-y-3">

                    <flux:label>
                        Attachment
                    </flux:label>

                    @if ($current_memo_attachment)
                    <div
                        class="flex items-center justify-between rounded-lg
                            border border-zinc-200 bg-zinc-50 px-4 py-3
                            dark:border-zinc-700 dark:bg-zinc-800">

                        <div class="flex items-center gap-3">

                            <flux:icon.document-text
                                class="size-5 text-red-600" />

                            <div>

                                <flux:text class="font-medium">
                                    Current Attachment
                                </flux:text>

                                <flux:text
                                    size="sm"
                                    class="text-zinc-500">

                                    Memo document is already uploaded.

                                </flux:text>

                            </div>

                        </div>

                        <flux:button
                            size="sm"
                            variant="ghost"
                            icon="eye"
                            href="{{ Storage::url($current_memo_attachment) }}"
                            target="_blank">

                            View PDF

                        </flux:button>

                    </div>
                    @endif

                    <flux:input
                        type="file"
                        wire:model="memo_attachment"
                        accept="application/pdf" />

                    @error('memo_attachment')

                    <span class="text-sm text-red-600">
                        {{ $message }}
                    </span>

                    @enderror

                </div>

            </div>

            <flux:separator />
            <div class="flex justify-end items-center gap-3">

                {{-- SAVE DRAFT --}}
                <flux:button
                    variant="primary"
                    icon="document"
                    wire:click="saveMemoDraft"
                    wire:loading.attr="disabled"
                    wire:target="saveMemoDraft"
                    class="w-auto bg-orange-500 hover:bg-orange-600 text-white">

                    <span
                        wire:loading.remove
                        wire:target="saveMemoDraft">

                        Save Draft

                    </span>

                    <span
                        wire:loading
                        wire:target="saveMemoDraft">

                        Saving...

                    </span>

                </flux:button>


                {{-- ISSUE MEMO --}}
                <flux:button
                    variant="primary"
                    icon="paper-airplane"
                    wire:click="issueMemo"
                    wire:loading.attr="disabled"
                    wire:target="issueMemo"
                    class="w-auto">

                    <span
                        wire:loading.remove
                        wire:target="issueMemo">
                        Submit
                    </span>

                    <span
                        wire:loading
                        wire:target="issueMemo">

                        Issuing...

                    </span>

                </flux:button>

            </div>
        </div>
    </flux:modal>
</div>