<div>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <flux:modal name="master-file-modal" class="w-full max-w-7xl">

        <div
            x-data="{ step: 1 }"
            class="space-y-6">

            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    RESULT/OTHERS Master File
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    View and manage complete RESULT/OTHERS master records.
                </p>
            </div>

            <div class="flex items-center justify-between border-b border-gray-200 pb-5 dark:border-gray-700">

                <div class="flex flex-1 items-center">

                    <div class="flex items-center">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-semibold"
                            :class="step >= 1
                                ? 'bg-red-800 text-white'
                                : 'border border-gray-300 text-gray-500'">
                            1
                        </div>

                        <span
                            class="ml-2 hidden text-sm font-medium md:block"
                            :class="step >= 1 ? 'text-red-800' : 'text-gray-500'">
                            Employee
                        </span>
                    </div>

                    <div
                        class="mx-3 h-px flex-1"
                        :class="step > 1 ? 'bg-red-800' : 'bg-gray-300'"></div>

                    <div class="flex items-center">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-semibold"
                            :class="step >= 2
                                ? 'bg-red-800 text-white'
                                : 'border border-gray-300 text-gray-500'">
                            2
                        </div>

                        <span
                            class="ml-2 hidden text-sm font-medium md:block"
                            :class="step >= 2 ? 'text-red-800' : 'text-gray-500'">
                            Assignment
                        </span>
                    </div>

                    <div
                        class="mx-3 h-px flex-1"
                        :class="step > 2 ? 'bg-red-800' : 'bg-gray-300'"></div>

                    <div class="flex items-center">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-semibold"
                            :class="step >= 3
                                ? 'bg-red-800 text-white'
                                : 'border border-gray-300 text-gray-500'">
                            3
                        </div>

                        <span
                            class="ml-2 hidden text-sm font-medium md:block"
                            :class="step >= 3 ? 'text-red-800' : 'text-gray-500'">
                            Investigation
                        </span>
                    </div>

                    <div
                        class="mx-3 h-px flex-1"
                        :class="step > 3 ? 'bg-red-800' : 'bg-gray-300'"></div>

                    <div class="flex items-center">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-semibold"
                            :class="step >= 4
                                ? 'bg-red-800 text-white'
                                : 'border border-gray-300 text-gray-500'">
                            4
                        </div>

                        <span
                            class="ml-2 hidden text-sm font-medium md:block"
                            :class="step >= 4 ? 'text-red-800' : 'text-gray-500'">
                            Dept Head
                        </span>
                    </div>

                    <div
                        class="mx-3 h-px flex-1"
                        :class="step > 4 ? 'bg-red-800' : 'bg-gray-300'"></div>

                    <div class="flex items-center">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-semibold"
                            :class="step >= 5
                                ? 'bg-red-800 text-white'
                                : 'border border-gray-300 text-gray-500'">
                            5
                        </div>

                        <span
                            class="ml-2 hidden text-sm font-medium md:block"
                            :class="step >= 5 ? 'text-red-800' : 'text-gray-500'">
                            HR Decision
                        </span>
                    </div>

                    <div
                        class="mx-3 h-px flex-1"
                        :class="step > 5 ? 'bg-red-800' : 'bg-gray-300'"></div>

                    <div class="flex items-center">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-semibold"
                            :class="step >= 6
                                ? 'bg-red-800 text-white'
                                : 'border border-gray-300 text-gray-500'">
                            6
                        </div>

                        <span
                            class="ml-2 hidden text-sm font-medium md:block"
                            :class="step >= 6 ? 'text-red-800' : 'text-gray-500'">
                            Management
                        </span>
                    </div>

                    <div
                        class="mx-3 h-px flex-1"
                        :class="step > 6 ? 'bg-red-800' : 'bg-gray-300'"></div>

                    <div class="flex items-center">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-semibold"
                            :class="step >= 7
                                ? 'bg-red-800 text-white'
                                : 'border border-gray-300 text-gray-500'">
                            7
                        </div>

                        <span
                            class="ml-2 hidden text-sm font-medium md:block"
                            :class="step >= 7 ? 'text-red-800' : 'text-gray-500'">
                            Memo
                        </span>
                    </div>

                </div>

            </div>

            <div
                x-show="step === 1"
                x-cloak
                class="space-y-5">

                <div>
                    <flux:label class="text-xl font-bold">
                        Employee Information
                    </flux:label>

                    <flux:text class="mt-1">
                        Review the employee and CPAR information.
                    </flux:text>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    <flux:input
                        label="CPAR No."
                        wire:model="cpar_no"
                        readonly
                        class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                    <flux:input
                        label="Date Open"
                        wire:model="date_open"
                        readonly
                        class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                </div>

                <flux:select
                    label="Source Origin"
                    wire:model.live="source_name"
                    placeholder="Select Source Origin">
                    @foreach ($source_origin as $source)
                    <flux:select.option
                        value="{{ $source->source_name }}"
                        wire:key="source-origin-{{ $source->id }}">
                        {{ $source->source_name }}
                    </flux:select.option>
                    @endforeach
                </flux:select>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    <flux:select
                        label="Reported By"
                        wire:model.live="reported_by"
                        placeholder="Select Reported By">
                        @foreach ($employees as $employee)

                        @php
                        $fullName = trim(
                        $employee->first_name . ' ' . $employee->last_name
                        );
                        @endphp

                        <flux:select.option
                            value="{{ $fullName }}"
                            wire:key="reported-by-{{ $employee->id }}">
                            {{ $fullName }}
                        </flux:select.option>

                        @endforeach
                    </flux:select>

                    <flux:select
                        label="Branch"
                        wire:model.live="branch_id"
                        placeholder="Select Branch">
                        @foreach ($branch as $item)
                        <flux:select.option
                            value="{{ $item->id }}"
                            wire:key="branch-{{ $item->id }}">
                            {{ $item->branch_name }}
                        </flux:select.option>
                        @endforeach
                    </flux:select>

                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    <flux:select
                        label="Complainant Category"
                        wire:model.live="complain_name"
                        placeholder="Select Complainant Category">
                        @foreach ($cpar_complain as $complain)
                        <flux:select.option
                            value="{{ $complain->complain_name }}"
                            wire:key="complain-category-{{ $complain->id }}">
                            {{ $complain->complain_name }}
                        </flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:input
                        label="Complainant Name"
                        wire:model="complainant_name"
                        :disabled="$complain_name_disabled"
                        class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    <flux:select
                        label="Concern Category"
                        wire:model.live="concern_name"
                        placeholder="Select Concern Category"
                        required>
                        @foreach ($cpar_concern as $concern)
                        <flux:select.option
                            value="{{ $concern->concern_name }}"
                            wire:key="concern-category-{{ $concern->id }}">
                            {{ $concern->concern_name }}
                        </flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select
                        label="Priority"
                        wire:model.live="priority"
                        placeholder="Select Priority"
                        required>
                        @foreach ($priority_level as $priority)
                        <flux:select.option
                            value="{{ $priority->id }}"
                            wire:key="priority-level-{{ $priority->id }}">
                            {{ $priority->priority_name }}
                        </flux:select.option>
                        @endforeach
                    </flux:select>

                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    <flux:select
                        label="Reported Employee"
                        wire:model.live="employee_id"
                        placeholder="Select Reported Employee">
                        @foreach ($employees as $employee)

                        @php
                        $fullName = trim(
                        $employee->first_name . ' ' . $employee->last_name
                        );
                        @endphp

                        <flux:select.option
                            value="{{ $employee->id }}"
                            wire:key="reported-employee-{{ $employee->id }}">
                            {{ $fullName }}
                        </flux:select.option>

                        @endforeach
                    </flux:select>

                    <flux:input
                        label="Department"
                        wire:model="department_name"
                        readonly
                        class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                </div>

                <flux:textarea
                    label="Concern Description"
                    wire:model="concern_description"
                    rows="5" />

            </div>

            <div
                x-show="step === 2"
                x-cloak
                class="space-y-5">

                <div class="border-b border-gray-200 pb-4 dark:border-gray-700">

                    <h3 class="font-semibold">
                        Assignment
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Select the employee responsible for handling this CPAR.
                    </p>

                </div>

                <div class="flex items-end gap-3">
                    <div class="flex-1">
                        <flux:select
                            label="Assign To"
                            wire:model="assigned_to"
                            placeholder="-- Select Employee --">
                            @foreach ($employees as $employee)

                            @php
                            $fullName = trim(
                            $employee->first_name . ' ' . $employee->last_name
                            );
                            @endphp

                            <flux:select.option
                                value="{{ $employee->id }}"
                                wire:key="assigned-to-{{ $employee->id }}">
                                {{ $fullName }}
                            </flux:select.option>

                            @endforeach
                        </flux:select>
                    </div>
                </div>

                <flux:textarea
                    label="Remarks"
                    wire:model="assigned_remarks"
                    placeholder="Enter assignment remarks..."
                    rows="5" />

            </div>

            <div
                x-show="step === 3"
                x-cloak
                class="space-y-5">

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
                    rows="5" />

                <flux:textarea
                    label="Provided Solution"
                    wire:model="provided_solution"
                    rows="5" />

                <flux:textarea
                    label="Recommendation"
                    wire:model="recommendation"
                    rows="5" />

                <div class="space-y-5">

                    <div>
                        <flux:label class="text-xl font-bold">
                            Incident Report
                        </flux:label>

                        <flux:text class="mt-1">
                            Review and update the IR attachment.
                        </flux:text>
                    </div>

                    @if ($current_ir_attachment)

                    <div>

                        <flux:label>
                            Current IR Attachment
                        </flux:label>

                        <div class="mt-2 flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-zinc-800">

                            <span class="truncate text-sm text-gray-700 dark:text-gray-300">
                                {{ basename($current_ir_attachment) }}
                            </span>

                            <a
                                href="{{ Storage::url($current_ir_attachment) }}"
                                target="_blank"
                                download
                                class="ml-4 inline-flex items-center rounded-lg bg-gray-900 px-3 py-2 text-xs font-medium text-white hover:bg-gray-700 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200">
                                Download
                            </a>

                        </div>

                    </div>

                    @endif

                    <flux:input
                        type="file"
                        label="Replace IR Attachment"
                        wire:model="ir_attachment" />

                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                    <flux:input
                        label="Action Taken By"
                        wire:model="action_taken_by"
                        readonly
                        class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                    <flux:input
                        label="Date Completed"
                        type="text"
                        wire:model="date_completed"
                        readonly
                        class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                    <flux:input
                        label="TAT"
                        wire:model="tat"
                        readonly
                        class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                </div>

            </div>

            <div
                x-show="step === 4"
                x-cloak
                class="space-y-5">

                <div>
                    <flux:label class="text-xl font-bold">
                        Department Head Remarks
                    </flux:label>

                    <flux:text class="mt-1">
                        Review and update the remarks provided by the Department Head.
                    </flux:text>
                </div>

                <flux:textarea
                    label="Dept Head Remarks"
                    wire:model="head_remarks"
                    rows="10" />

            </div>

            <div
                x-show="step === 5"
                x-cloak
                class="space-y-6">

                <div>
                    <flux:label class="text-xl font-bold">
                        NTE and HR Decision
                    </flux:label>

                    <flux:text class="mt-1">
                        Review the Notice to Explain and HR decision details.
                    </flux:text>
                </div>

                <div class="space-y-5">

                    <flux:input
                        label="NTE No."
                        wire:model="nte_no"
                        readonly
                        class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                    @if ($current_nte_attachment)

                    <div>

                        <flux:label>
                            Current NTE Attachment
                        </flux:label>

                        <div class="mt-2 flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-zinc-800">

                            <span class="truncate text-sm text-gray-700 dark:text-gray-300">
                                {{ basename($current_nte_attachment) }}
                            </span>

                            <a
                                href="{{ Storage::url($current_nte_attachment) }}"
                                target="_blank"
                                download
                                class="ml-4 inline-flex items-center rounded-lg bg-gray-900 px-3 py-2 text-xs font-medium text-white hover:bg-gray-700 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200">
                                Download
                            </a>

                        </div>

                    </div>

                    @endif

                    <flux:input
                        type="file"
                        label="Replace NTE Attachment"
                        wire:model="nte_attachment" />

                </div>

                <div class="border-t border-gray-200 pt-6 dark:border-gray-700">

                    <div class="mb-5">
                        <flux:label class="text-xl font-bold">
                            HR Decision
                        </flux:label>

                        <flux:text class="mt-1">
                            Review and update the HR decision details.
                        </flux:text>
                    </div>

                    <div class="space-y-5">

                        <flux:select
                            label="Decision Category"
                            wire:model.live="selectedHRDecisions"
                            placeholder="Select Decision Category">
                            @foreach ($decision as $decisionCategory)
                            <flux:select.option
                                value="{{ $decisionCategory->id }}"
                                wire:key="decision-category-{{ $decisionCategory->id }}">
                                {{ $decisionCategory->decision_name }}
                            </flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:select
                            label="Disciplinary Category"
                            wire:model="selectedCategories"
                            placeholder="Select Disciplinary Category">
                            @foreach ($disciplinaryCategories as $category)
                            <flux:select.option
                                value="{{ $category->id }}"
                                wire:key="disciplinary-category-{{ $category->id }}">
                                {{ $category->category_name ?? '-' }}
                            </flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:select
                            label="Offense Level"
                            wire:model="selectedOffenseLevels"
                            placeholder="Select Offense Level">
                            @foreach ($offenseLevels as $offense)
                            <flux:select.option
                                value="{{ $offense->id }}"
                                wire:key="offense-level-{{ $offense->id }}">
                                {{ $offense->offense_name ?? $offense->name ?? '-' }}
                            </flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:textarea
                            label="HR Decision Remarks"
                            wire:model="hr_decision_remarks"
                            rows="5" />

                    </div>

                </div>

            </div>

            <div
                x-show="step === 6"
                x-cloak
                class="space-y-5">

                <div>
                    <flux:label class="text-xl font-bold">
                        Management Remarks
                    </flux:label>

                    <flux:text class="mt-1">
                        Review and update the final management remarks.
                    </flux:text>
                </div>

                <flux:textarea
                    label="Management Remarks"
                    wire:model="management_remarks"
                    rows="10"
                    placeholder="Enter management remarks..." />

            </div>

            <div
                x-show="step === 7"
                x-cloak
                class="space-y-5">

                <div>
                    <flux:label class="text-xl font-bold">
                        Memo
                    </flux:label>

                    <flux:text class="mt-1">
                        Review and update the memorandum details.
                    </flux:text>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    <flux:input
                        label="Memo No."
                        wire:model="memo_no"
                        readonly
                        class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                    <flux:input
                        type="text"
                        label="Memo Date"
                        wire:model="memo_date"
                        readonly
                        class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                </div>

                <flux:input
                    label="Subject"
                    wire:model="memo_subject"
                    class="uppercase"
                    oninput="this.value = this.value.toUpperCase()" />

                <flux:textarea
                    label="Memo Content"
                    wire:model="memo_content"
                    rows="10" />

                @if ($current_memo_attachment)

                <div>

                    <flux:label>
                        Current Memo Attachment
                    </flux:label>

                    <div class="mt-2 flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-zinc-800">

                        <span class="truncate text-sm text-gray-700 dark:text-gray-300">
                            {{ basename($current_memo_attachment) }}
                        </span>

                        <a
                            href="{{ Storage::url($current_memo_attachment) }}"
                            target="_blank"
                            download
                            class="ml-4 inline-flex items-center rounded-lg bg-gray-900 px-3 py-2 text-xs font-medium text-white hover:bg-gray-700 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200">
                            Download
                        </a>

                    </div>

                </div>

                @endif

                <flux:input
                    type="file"
                    label="Replace Memo Attachment"
                    wire:model="memo_attachment" />

            </div>

            <div class="flex items-center justify-between border-t border-gray-200 pt-6 dark:border-gray-700">

                <div>

                    <flux:button
                        type="button"
                        variant="ghost"
                        x-show="step > 1"
                        x-on:click="step--">
                        Back
                    </flux:button>

                </div>

                <div class="flex items-center gap-3">

                    <flux:button
                        type="button"
                        variant="ghost"
                        x-show="step === 1"
                        x-on:click="$dispatch('modal-close', { name: 'master-file-modal' })">
                        Cancel
                    </flux:button>

                    <flux:button
                        type="button"
                        variant="primary"
                        x-show="step < 7"
                        x-on:click="step++">
                        Next
                    </flux:button>

                    <flux:button
                        type="button"
                        variant="primary"
                        x-show="step === 7"
                        wire:click="update"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="update">
                            Finish
                        </span>

                        <span wire:loading wire:target="update">
                            Updating...
                        </span>
                    </flux:button>

                </div>

            </div>

        </div>

    </flux:modal>
</div>