<div>
    <flux:modal name="master-file-modal" class="w-full max-w-7xl">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                CPAR Master File
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                View and manage complete CPAR master records.
            </p>
        </div>

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

                {{-- REPORTED BY + SOURCE --}}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    {{-- Reported By --}}
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

                    {{-- Branch --}}
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

                {{-- COMPLAINANT --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

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
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                </div>

                {{-- CONCERN --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Concern Category --}}
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

                    {{-- Priority --}}
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
                {{-- EMPLOYEE + DEPARTMENT --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                </div>

                <flux:textarea
                    label="Assigned Remarks"
                    wire:model="assigned_remarks"
                    rows="3" />

                <flux:select
                    label="Status"
                    wire:model.live="status_id"
                    placeholder="Select Status">

                    @foreach ($status as $item)
                    <flux:select.option
                        value="{{ $item->id }}"
                        wire:key="status-{{ $item->id }}">
                        {{ $item->status_name }}
                    </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:textarea
                    label="Concern Description"
                    wire:model="concern_description"
                    rows="3" />
                <div>
                    <flux:label class="text-xl font-bold">
                        Notice to Explain
                    </flux:label>

                    <flux:text class="mt-1">
                        Review and update the NTE attachment.
                    </flux:text>
                </div>
                <flux:input
                    label="NTE No."
                    wire:model="nte_no"
                    readonly
                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                {{-- CURRENT NTE ATTACHMENT --}}
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

                {{-- REPLACE NTE --}}
                <flux:input
                    type="file"
                    label="Replace NTE Attachment"
                    wire:model="nte_attachment" />
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
                    rows="4" />

                <flux:textarea
                    label="Provided Solution"
                    wire:model="provided_solution"
                    rows="4" />

                <flux:textarea
                    label="Recommendation"
                    wire:model="recommendation"
                    rows="4" />

                <div class="space-y-5">

                    <div>
                        <flux:label class="text-xl font-bold">
                            Incident Report
                        </flux:label>

                        <flux:text class="mt-1">
                            Review and update the IR attachment.
                        </flux:text>
                    </div>
                    {{-- CURRENT IR ATTACHMENT --}}
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

                    {{-- REPLACE IR --}}
                    <flux:input
                        type="file"
                        label="Replace IR Attachment"
                        wire:model="ir_attachment" />

                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <flux:input
                        label="Action Taken By"
                        wire:model="action_taken_by"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

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
                <flux:textarea
                    label="Dept Head Remarks"
                    wire:model="head_remarks"
                    rows="4" />
            </div>

        </div>

        {{-- HR DECISION + MEMO --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- HR DECISION --}}
            <div class="space-y-5">

                <div>
                    <flux:label class="text-xl font-bold">
                        HR Decision
                    </flux:label>

                    <flux:text class="mt-1">
                        Review and update the HR decision details.
                    </flux:text>
                </div>

                {{-- DECISION CATEGORY --}}
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

                {{-- DISCIPLINARY CATEGORY --}}
                <flux:select
                    label="Disciplinary Category"
                    wire:model="selectedCategories"
                    placeholder="Select Disciplinary Category">
                    @foreach ($disciplinaryCategories as $category)
                    <flux:select.option value="{{ $category->id }}"
                        wire:key="disciplinary-category-{{ $category->id }}">
                        {{ $category->category_name ?? '-' }}
                    </flux:select.option>

                    @endforeach

                </flux:select>

                {{-- OFFENSE LEVEL --}}
                <flux:select
                    label="Offense Level"
                    wire:model="selectedOffenseLevels"
                    placeholder="Select Offense Level">

                    @foreach ($offenseLevels as $offense)

                    <flux:select.option value="{{ $offense->id }}">
                        {{ $offense->offense_name ?? $offense->name ?? '-' }}
                    </flux:select.option>

                    @endforeach

                </flux:select>

                {{-- HR DECISION REMARKS --}}
                <flux:textarea
                    label="HR Decision Remarks"
                    wire:model="hr_decision_remarks"
                    rows="4" />

                {{-- MANAGEMENT REMARKS --}}
                <flux:textarea
                    label="Management Remarks"
                    wire:model="management_remarks"
                    rows="4" />

            </div>


            {{-- MEMO --}}
            <div class="space-y-5">

                <div>
                    <flux:label class="text-xl font-bold">
                        Memo
                    </flux:label>

                    <flux:text class="mt-1">
                        Review and update the memorandum details.
                    </flux:text>
                </div>

                {{-- MEMO NO + DATE --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input label="Memo No." wire:model="memo_no" readonly class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                    <flux:input type="text" label="Memo Date" readonly class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" wire:model="memo_date" />
                </div>

                {{-- SUBJECT --}}
                <flux:input
                    label="Subject"
                    wire:model="memo_subject" />

                {{-- CONTENT --}}
                <flux:textarea
                    label="Memo Content"
                    wire:model="memo_content"
                    rows="6" />

                {{-- CURRENT MEMO ATTACHMENT --}}
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

        </div>
        {{-- FOOTER --}}
        <div class="flex items-center justify-end gap-4 border-t border-gray-200 pt-6 dark:border-gray-700">

            <flux:button
                variant="ghost"
                x-on:click="$dispatch('modal-close', { name: 'master-file-modal' })">

                Cancel

            </flux:button>

            <flux:button
                variant="primary"
                wire:click="update"
                wire:loading.attr="disabled">

                <span wire:loading.remove wire:target="update">
                    Save Changes
                </span>

                <span wire:loading wire:target="update">
                    Updating...
                </span>

            </flux:button>

        </div>
    </flux:modal>
</div>