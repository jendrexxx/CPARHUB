<div>
    <flux:modal
        name="hr-reassign-result"
        class="w-full max-w-7xl">

        {{-- HEADER --}}
        <div class="mb-6 border-b border-zinc-200 pb-4 dark:border-zinc-700">
            <flux:heading size="lg">
                Reassign Result
            </flux:heading>

            <flux:text class="mt-1 text-zinc-500">
                Review the result details and assign the appropriate employee.
            </flux:text>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">

            {{-- ========================================= --}}
            {{-- LEFT : RESULT DETAILS --}}
            {{-- ========================================= --}}
            <div>

                <div class="mb-5 border-b border-zinc-200 pb-3 dark:border-zinc-700">
                    <flux:heading size="sm">
                        Result Details
                    </flux:heading>

                    <flux:text class="mt-1 text-sm text-zinc-500">
                        View the result concern information below.
                    </flux:text>
                </div>

                {{-- Result No + Date --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <flux:label>Result No.</flux:label>

                        <flux:input
                            class="mt-2"
                            value="{{ $result_no ?? 'N/A' }}"
                            disabled />
                    </div>

                    <div>
                        <flux:label>Date Reported</flux:label>

                        <flux:input
                            class="mt-2"
                            value="{{ $date_reported ?? 'N/A' }}"
                            disabled />
                    </div>

                </div>

                {{-- Reported By + Department --}}
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <flux:label>Reported By</flux:label>

                        <flux:input
                            class="mt-2"
                            value="{{ $reported_by ?? 'N/A' }}"
                            disabled />
                    </div>

                    <div>
                        <flux:label>Patient Name</flux:label>

                        <flux:input
                            class="mt-2"
                            value="{{ $patient_name ?? 'N/A' }}"
                            disabled />
                    </div>

                </div>

                {{-- Patient + Physician --}}
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <flux:label>Attending Physician</flux:label>

                        <flux:input
                            class="mt-2"
                            value="{{ $attending_physician ?? 'N/A' }}"
                            disabled />
                    </div>

                    <div>
                        <flux:label>Actual Released Date</flux:label>

                        <flux:input
                            class="mt-2"
                            value="{{ $actual_released_date ?? 'N/A' }}"
                            disabled />
                    </div>

                </div>

                {{-- Released Date + Source --}}
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <flux:label>Source of Information</flux:label>

                        <flux:input
                            class="mt-2"
                            value="{{ $source_name ?? 'N/A' }}"
                            disabled />
                    </div>

                    <div>
                        <flux:label>Complainant Category</flux:label>

                        <flux:input
                            class="mt-2"
                            value="{{ $complain_name ?? 'N/A' }}"
                            disabled />
                    </div>

                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <flux:label>Complainant Name</flux:label>

                        <flux:input
                            class="mt-2"
                            value="{{ $complainant_name ?? 'N/A' }}"
                            disabled />
                    </div>

                    <div>
                        <flux:label>Priority</flux:label>

                        <flux:input
                            class="mt-2"
                            value="{{ $priority ?? 'N/A' }}"
                            disabled />
                    </div>

                </div>

                <div class="mt-4">
                    <flux:label>Test Procedure</flux:label>

                    <flux:textarea
                        class="mt-2"
                        rows="4"
                        disabled>{{ $test_procedure ?? 'N/A' }}</flux:textarea>
                </div>

                {{-- Concern Description --}}
                <div class="mt-4">
                    <flux:label>Concern Description</flux:label>

                    <flux:textarea
                        class="mt-2"
                        rows="4"
                        disabled>{{ $concern_description ?? 'N/A' }}</flux:textarea>
                </div>
            </div>


            {{-- ========================================= --}}
            {{-- RIGHT : ASSIGNMENT --}}
            {{-- ========================================= --}}
            <div>

                <div class="mb-5 border-b border-zinc-200 pb-3 dark:border-zinc-700">
                    <flux:heading size="sm">
                        Assignment
                    </flux:heading>

                    <flux:text class="mt-1 text-sm text-zinc-500">
                        Select the employee responsible for handling this result.
                    </flux:text>
                </div>

                <div class="mt-5 space-y-5">

                    {{-- MAIN ASSIGN --}}
                    <div class="flex items-end gap-2">

                        <div class="flex-1 min-w-0">

                            <flux:select
                                label="Assign To"
                                wire:model="assigned_to">

                                <flux:select.option value="">
                                    -- Select Employee --
                                </flux:select.option>

                                @foreach($employees as $employee)

                                <flux:select.option value="{{ $employee->id }}">
                                    {{ $employee->first_name }}
                                    {{ $employee->last_name }}
                                </flux:select.option>

                                @endforeach

                            </flux:select>

                        </div>

                        <flux:button
                            type="button"
                            variant="primary"
                            icon="plus"
                            wire:click="addAssignee">
                        </flux:button>

                    </div>


                    {{-- ADDITIONAL ASSIGNEES --}}
                    @foreach(($new_assignees ?? []) as $index => $value)

                    <div class="flex items-end gap-2">

                        <div class="flex-1 min-w-0">

                            <flux:select
                                label="Additional Assignee"
                                wire:model="new_assignees.{{ $index }}">

                                <flux:select.option value="">
                                    -- Select Employee --
                                </flux:select.option>

                                @foreach($employees as $employee)

                                <flux:select.option value="{{ $employee->id }}">
                                    {{ $employee->first_name }}
                                    {{ $employee->last_name }}
                                </flux:select.option>

                                @endforeach

                            </flux:select>

                        </div>

                        <flux:button
                            type="button"
                            variant="danger"
                            icon="minus"
                            wire:click="removeAssignee({{ $index }})">
                        </flux:button>

                    </div>

                    @endforeach

                    {{-- REMARKS --}}
                    <flux:textarea
                        label="Remarks"
                        wire:model="assignment_remarks"
                        rows="4"
                        placeholder="Enter assignment remarks..." />

                </div>

                {{-- Result Error Information --}}
                <div class="mt-6">
                    {{-- Result Error / Concern --}}
                    <div class="mt-6">
                        <flux:label>
                            Result Error / Concern
                        </flux:label>
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-4">
                            {{-- Data and Information Errors --}}
                            <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                                <div class="mb-4 border-b border-zinc-200 pb-2 dark:border-zinc-700">
                                    <flux:text class="font-medium">
                                        Data and Information Errors
                                    </flux:text>
                                </div>
                                <div class="space-y-3">
                                    @foreach ($data as $item)
                                    <label class="flex items-center gap-3 cursor-not-allowed">
                                        <input
                                            type="checkbox"
                                            value="{{ $item->id }}"
                                            wire:model="data_information"
                                            disabled
                                            class="h-4 w-4 rounded border-zinc-300
                                            text-primary-600
                                            disabled:cursor-not-allowed
                                            disabled:opacity-70
                                            dark:border-zinc-600">
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">
                                            {{ $item->data_name }}
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Technical and Equipment Issues --}}
                            <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                                <div class="mb-4 border-b border-zinc-200 pb-2 dark:border-zinc-700">
                                    <flux:text class="font-medium">
                                        Technical and Equipment Issues
                                    </flux:text>
                                </div>
                                <div class="space-y-3">
                                    @foreach ($technical as $item)
                                    <label class="flex items-center gap-3 cursor-not-allowed">
                                        <input
                                            type="checkbox"
                                            value="{{ $item->id }}"
                                            wire:model="technical_information"
                                            disabled
                                            class="h-4 w-4 rounded border-zinc-300
                                            text-primary-600
                                            disabled:cursor-not-allowed
                                            disabled:opacity-70
                                            dark:border-zinc-600">

                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">
                                            {{ $item->technical_name }}
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        {{-- Quality and Accuracy Issues --}}
                        <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700 mt-2">
                            <div class="mb-4 border-b border-zinc-200 pb-2 dark:border-zinc-700">
                                <flux:text class="font-medium">
                                    Quality and Accuracy Issues
                                </flux:text>
                            </div>
                            <div class="space-y-3">
                                @foreach ($quality as $item)
                                <label class="flex items-center gap-3 cursor-not-allowed">

                                    <input
                                        type="checkbox"
                                        value="{{ $item->id }}"
                                        wire:model="quality_information"
                                        disabled
                                        class="h-4 w-4 rounded border-zinc-300
                                    text-primary-600
                                    disabled:cursor-not-allowed
                                    disabled:opacity-70
                                    dark:border-zinc-600">
                                    <span class="text-sm text-zinc-700 dark:text-zinc-300">
                                        {{ $item->quality_name }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="mt-6 flex justify-end gap-2 border-t border-zinc-200 pt-4 dark:border-zinc-700">

            <flux:button
                variant="ghost"
                x-on:click="$flux.modal('reassign-result').close()">
                Cancel
            </flux:button>

            <flux:button
                variant="primary"
                wire:click="reassignResult">
                <span class="mr-1">✓</span>
                Assign
            </flux:button>

        </div>

    </flux:modal>
</div>