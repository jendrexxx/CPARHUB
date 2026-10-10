<div>
    <flux:modal
        name="result-file"
        class="w-full max-w-7xl">

        <div class="mb-6 border-b border-zinc-200 pb-4 dark:border-zinc-700">
            <flux:heading size="lg">
                Edit Result
            </flux:heading>

            <flux:text class="mt-1 text-zinc-500">
                Review and update the result information and assignment.
            </flux:text>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">

            <div>

                <div class="mb-5 border-b border-zinc-200 pb-3 dark:border-zinc-700">
                    <flux:heading size="sm">
                        Result Details
                    </flux:heading>

                    <flux:text class="mt-1 text-sm text-zinc-500">
                        Update the result concern information below.
                    </flux:text>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <flux:label>Result No.</flux:label>

                        <flux:input
                            class="mt-2 opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800"
                            wire:model="result_no"
                            readonly />
                    </div>

                    <div>
                        <flux:label>Date Reported</flux:label>
                        <flux:input
                            type="date"
                            class="mt-2 opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800"
                            wire:model="date_reported"
                            readonly />
                    </div>

                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <flux:select
                        label="Reported By"
                        wire:model.live="reported_by"
                        wire:change="updateEmployeeDetails"
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
                            {{ $employee->employee_no }} - {{ $fullName }}
                        </flux:select.option>
                        @endforeach
                    </flux:select>

                    <div>
                        <flux:label>Patient Name</flux:label>

                        <flux:input
                            class="mt-2"
                            wire:model="patient_name"
                            oninput="this.value = this.value.toUpperCase()" />
                    </div>

                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <flux:label>Attending Physician</flux:label>

                        <flux:input
                            class="mt-2"
                            wire:model="attending_physician"
                            oninput="this.value = this.value.toUpperCase()" />
                    </div>

                    <div>
                        <flux:label>Actual Released Date</flux:label>

                        <flux:input
                            type="date"
                            class="mt-2"
                            wire:model="actual_released_date" />
                    </div>

                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <flux:label>Source of Information</flux:label>
                        <flux:select
                            class="mt-2"
                            wire:model="source_id">
                            <flux:select.option value="">
                                -- Select Source --
                            </flux:select.option>
                            @foreach ($sources as $source)
                            <flux:select.option value="{{ $source->id }}">
                                {{ $source->source_name }}
                            </flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>

                    <div>
                        <flux:label>Complainant Category</flux:label>
                        <flux:select
                            class="mt-2"
                            wire:model="complainant_category_id">
                            <flux:select.option value="">
                                -- Select Category --
                            </flux:select.option>
                            @foreach ($complainants as $complainant)
                            <flux:select.option value="{{ $complainant->id }}">
                                {{ $complainant->complain_name }}
                            </flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>

                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <flux:label>Complainant Name</flux:label>

                        <flux:input
                            class="mt-2"
                            wire:model="complainant_name"
                            oninput="this.value = this.value.toUpperCase()" />
                    </div>

                    <div>
                        <flux:label>Priority Level</flux:label>

                        <flux:select
                            class="mt-2"
                            wire:model="priority_id">

                            <flux:select.option value="">
                                -- Select Priority --
                            </flux:select.option>

                            @foreach ($priorities as $item)
                            <flux:select.option value="{{ $item->id }}">
                                {{ $item->priority_name }}
                            </flux:select.option>
                            @endforeach

                        </flux:select>
                    </div>

                </div>

                <div class="mt-4">
                    <flux:label>Test Procedure</flux:label>

                    <flux:textarea
                        class="mt-2"
                        rows="4"
                        wire:model="test_procedure"
                        oninput="this.value = this.value.toUpperCase()"
                        placeholder="Enter test procedure..." />
                </div>

                <div class="mt-4">
                    <flux:label>Concern Description</flux:label>
                    <flux:textarea
                        class="mt-2"
                        rows="4"
                        wire:model="concern_description"
                        oninput="this.value = this.value.toUpperCase()"
                        placeholder="Enter concern description..." />
                </div>

                <div class="mt-4">
                    <flux:label>Status</flux:label>

                    <flux:select
                        class="mt-2"
                        wire:model="status_id"
                        placeholder="Select Status">
                        @foreach ($cpar_status as $status)
                        <flux:select.option value="{{ $status->id }}">
                            {{ $status->status_name }}
                        </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div class="mb-5 border-b border-zinc-200 pb-3 dark:border-zinc-700 mt-5">

                    <flux:heading size="md">
                        Result Response
                    </flux:heading>

                    <flux:text class="mt-1">
                        Provide the investigation findings and corrective actions.
                    </flux:text>

                </div>


                <div class="mt-5 space-y-5">

                    {{-- IDENTIFIED CAUSE --}}
                    <flux:textarea
                        label="Identified Cause"
                        wire:model="identified_cause"
                        rows="5"
                        placeholder="ENTER THE IDENTIFIED CAUSE..."
                        class="uppercase" />


                    {{-- PROVIDED SOLUTION --}}
                    <flux:textarea
                        label="Provided Solution"
                        wire:model="provided_solution"
                        rows="5"
                        placeholder="ENTER THE PROVIDED SOLUTION..."
                        class="uppercase" />


                    {{-- RECOMMENDATION --}}
                    <flux:textarea
                        label="Recommendation"
                        wire:model="recommendation"
                        rows="5"
                        placeholder="ENTER RECOMMENDATION..."
                        class="uppercase" />

                    {{-- IR ATTACHMENT --}}
                    <div>

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
                            <flux:text class="text-sm text-zinc-500">
                                Existing attachment
                            </flux:text>
                        </div>

                        @endif

                    </div>

                    {{-- COMPLETION DETAILS --}}
                    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-5">
                        <flux:heading size="sm">
                            Completion Details
                        </flux:heading>

                        <flux:text class="mt-1">
                            Review the completion information before submitting.
                        </flux:text>

                        {{-- DATE / TAT --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
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

                        {{-- Dept Head Remarks --}}
                        <flux:textarea
                            label="Dept Head Remarks"
                            wire:model="remarks"
                            rows="5"
                            class="uppercase" />

                    </div>

                </div>

            </div>

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

                    <div class="flex items-end gap-2">
                        <div class="min-w-0 flex-1">
                            <flux:select
                                label="Assign To"
                                wire:model.live="assigned_to"
                                placeholder="-- Select Employee --">

                                <flux:select.option value="">
                                    -- Select Employee --
                                </flux:select.option>

                                @foreach ($employees as $employee)
                                <flux:select.option
                                    value="{{ $employee->id }}"
                                    wire:key="assigned-employee-{{ $employee->id }}">
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>
                        <div class="min-w-0 flex-1">
                            <flux:input
                                label="Department Name"
                                wire:model="department_name"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                        </div>
                    </div>

                    @foreach (($new_assignees ?? []) as $index => $value)

                    <div class="flex items-end gap-2">

                        <div class="min-w-0 flex-1">

                            <flux:select
                                label="Additional Assignee"
                                wire:model="new_assignees.{{ $index }}">

                                <flux:select.option value="">
                                    -- Select Employee --
                                </flux:select.option>

                                @foreach ($employees as $employee)

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

                    <flux:textarea
                        label="Remarks"
                        wire:model="assignment_remarks"
                        rows="4"
                        placeholder="Enter assignment remarks..." />

                </div>

                <div class="mt-6">

                    <flux:label>
                        Result Error / Concern
                    </flux:label>

                    <div class="mt-3 grid grid-cols-1 gap-4 md:grid-cols-2">

                        <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">

                            <div class="mb-4 border-b border-zinc-200 pb-2 dark:border-zinc-700">

                                <flux:text class="font-medium">
                                    Data and Information Errors
                                </flux:text>

                            </div>

                            <div class="space-y-3">

                                @foreach ($data as $item)

                                <label class="flex cursor-pointer items-center gap-3">

                                    <input
                                        type="checkbox"
                                        value="{{ $item->id }}"
                                        wire:model="data_information"
                                        class="h-4 w-4 rounded border-zinc-300 text-primary-600 dark:border-zinc-600">

                                    <span class="text-sm text-zinc-700 dark:text-zinc-300">
                                        {{ $item->data_name }}
                                    </span>

                                </label>

                                @endforeach

                            </div>

                        </div>

                        <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">

                            <div class="mb-4 border-b border-zinc-200 pb-2 dark:border-zinc-700">

                                <flux:text class="font-medium">
                                    Technical and Equipment Issues
                                </flux:text>

                            </div>

                            <div class="space-y-3">

                                @foreach ($technical as $item)

                                <label class="flex cursor-pointer items-center gap-3">

                                    <input
                                        type="checkbox"
                                        value="{{ $item->id }}"
                                        wire:model="technical_information"
                                        class="h-4 w-4 rounded border-zinc-300 text-primary-600 dark:border-zinc-600">

                                    <span class="text-sm text-zinc-700 dark:text-zinc-300">
                                        {{ $item->technical_name }}
                                    </span>

                                </label>

                                @endforeach

                            </div>

                        </div>

                    </div>

                    <div class="mt-2 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">

                        <div class="mb-4 border-b border-zinc-200 pb-2 dark:border-zinc-700">

                            <flux:text class="font-medium">
                                Quality and Accuracy Issues
                            </flux:text>

                        </div>

                        <div class="space-y-3">

                            @foreach ($quality as $item)

                            <label class="flex cursor-pointer items-center gap-3">

                                <input
                                    type="checkbox"
                                    value="{{ $item->id }}"
                                    wire:model="quality_information"
                                    class="h-4 w-4 rounded border-zinc-300 text-primary-600 dark:border-zinc-600">

                                <span class="text-sm text-zinc-700 dark:text-zinc-300">
                                    {{ $item->quality_name }}
                                </span>

                            </label>

                            @endforeach

                        </div>

                    </div>
                    <div class="space-y-6 mt-5">

                        <div class="mb-5 border-b border-zinc-200 pb-3 dark:border-zinc-700">
                            <flux:heading size="md">
                                HR Send NTE Attachment
                            </flux:heading>

                            <flux:text class="mt-1">
                                Upload or view the Notice to Explain (NTE) PDF attachment.
                            </flux:text>
                        </div>

                        <div>
                            <flux:input
                                type="file"
                                label="NTE Attachment"
                                wire:model="nte_attachment"
                                accept=".pdf,application/pdf" />

                            @if ($existing_nte_attachment)
                            <div class="mt-2 flex items-center gap-2">
                                <flux:button
                                    type="button"
                                    icon="eye"
                                    variant="primary"
                                    size="sm"
                                    href="{{ Storage::url($existing_nte_attachment) }}"
                                    target="_blank">
                                    View Existing NTE
                                </flux:button>

                                <flux:text class="text-sm text-zinc-500">
                                    Existing attachment
                                </flux:text>
                            </div>
                            @endif

                            @error('nte_attachment')
                            <flux:text class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </flux:text>
                            @enderror
                        </div>

                        <div class="mt-6 border-b border-zinc-200 pb-3 dark:border-zinc-700">
                            <flux:heading size="md">
                                NTE Response Attachment
                            </flux:heading>

                            <flux:text class="mt-1">
                                Upload or view your response to the Notice to Explain (NTE).
                            </flux:text>
                        </div>

                        <div class="mt-4">
                            <flux:input
                                type="file"
                                label="NTE Employee Response Attachment"
                                wire:model="response_attachment"
                                accept=".pdf,application/pdf" />

                            @if ($existing_response_attachment)
                            <div class="mt-2 flex items-center gap-2">
                                <flux:button
                                    type="button"
                                    icon="eye"
                                    variant="primary"
                                    size="sm"
                                    href="{{ Storage::url($existing_response_attachment) }}"
                                    target="_blank">
                                    View Existing Response
                                </flux:button>

                                <flux:text class="text-sm text-zinc-500">
                                    Existing response attachment
                                </flux:text>
                            </div>
                            @endif

                            <flux:text class="mt-1 text-xs text-zinc-500">
                                Upload your NTE response in PDF format. Maximum file size: 10MB.
                            </flux:text>

                            @error('response_attachment')
                            <flux:text class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </flux:text>
                            @enderror
                        </div>

                        <flux:separator />

                        <div class="space-y-6">
                            <div>
                                <flux:label class="text-xl font-bold">
                                    HR Decision
                                </flux:label>

                                <flux:text class="mt-1">
                                    Select the applicable disciplinary category, offense level, and HR action.
                                </flux:text>
                            </div>

                            <div class="grid grid-cols-1 gap-6">

                                <div class="space-y-3">
                                    <flux:heading size="sm">
                                        HR Decision
                                    </flux:heading>
                                    @foreach ($selectedHRDecisions as $index => $decision)
                                    <div wire:key="hr-decision-{{ $index }}" class="flex items-end gap-2">
                                        <div class="min-w-0 flex-1">
                                            <flux:select
                                                wire:model.live="selectedHRDecisions.{{ $index }}">

                                                <flux:select.option value="">
                                                    -- Select HR Decision --
                                                </flux:select.option>

                                                @foreach ($decisionCategories as $item)
                                                <flux:select.option value="{{ $item->id }}">
                                                    {{ $item->decision_name }}
                                                </flux:select.option>
                                                @endforeach
                                            </flux:select>
                                        </div>

                                        @if ($index == count($selectedHRDecisions) - 1)
                                        <flux:button
                                            type="button"
                                            variant="primary"
                                            icon="plus"
                                            square
                                            wire:click="addCategory" />
                                        @endif

                                        @if (count($selectedHRDecisions) > 1)
                                        <flux:button
                                            type="button"
                                            variant="danger"
                                            icon="minus"
                                            square
                                            wire:click="removeCategory({{ $index }})" />
                                        @endif
                                    </div>
                                    @endforeach
                                </div>

                                <div class="space-y-3">
                                    <flux:heading size="sm">
                                        Disciplinary Category
                                    </flux:heading>

                                    @foreach ($selectedCategories as $index => $category)
                                    <div wire:key="disciplinary-category-{{ $index }}">
                                        <flux:select
                                            wire:model="selectedCategories.{{ $index }}">

                                            <flux:select.option value="">
                                                -- Select Category --
                                            </flux:select.option>

                                            @foreach ($disciplinaryCategories as $item)
                                            <flux:select.option value="{{ $item->id }}">
                                                {{ $item->category_name }}
                                            </flux:select.option>
                                            @endforeach
                                        </flux:select>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="space-y-3">
                                    <flux:heading size="sm">
                                        Offense Level
                                    </flux:heading>

                                    @foreach ($selectedOffenseLevels as $index => $offense)
                                    <div wire:key="offense-level-{{ $index }}">
                                        <flux:select
                                            wire:model="selectedOffenseLevels.{{ $index }}">

                                            <flux:select.option value="">
                                                -- Select Offense Level --
                                            </flux:select.option>

                                            @foreach ($offenseLevels as $level)
                                            <flux:select.option value="{{ $level->id }}">
                                                {{ $level->offense_name }}
                                            </flux:select.option>
                                            @endforeach
                                        </flux:select>
                                    </div>
                                    @endforeach
                                </div>

                            </div>

                            @error('rows')
                            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600 dark:border-red-900 dark:bg-red-950/30">
                                {{ $message }}
                            </div>
                            @enderror

                            <flux:textarea
                                label="HR Decision Remarks"
                                wire:model="hr_decision_remarks"
                                rows="5"
                                class="uppercase"
                                placeholder="Enter HR decision remarks..." />

                            <flux:textarea
                                label="Lab Supervisor Remarks"
                                wire:model="management_remarks"
                                rows="5"
                                class="uppercase"
                                placeholder="Enter Lab Supervisor remarks..." />
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="mt-6 flex justify-end gap-2 border-t border-zinc-200 pt-4 dark:border-zinc-700">

            <flux:button
                variant="ghost"
                x-on:click="$flux.modal('result-file').close()">
                Cancel
            </flux:button>

            <flux:button
                variant="primary"
                wire:click="saveResult"
                wire:loading.attr="disabled">

                <span wire:loading.remove>
                    ✓ Save Changes
                </span>

                <span wire:loading>
                    Saving...
                </span>

            </flux:button>

        </div>

    </flux:modal>
</div>