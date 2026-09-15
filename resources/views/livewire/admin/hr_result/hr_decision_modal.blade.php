<flux:modal
    name="decision-result"
    class="w-full max-w-7xl">

    <div class="space-y-6">

        {{-- HEADER --}}
        <div>
            <flux:heading size="lg">
                HR Decision
            </flux:heading>

            <flux:text class="mt-1">
                Review RESULT details, supporting documents, and provide the appropriate HR decision.
            </flux:text>
        </div>

        {{-- MAIN GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="min-w-0 rounded-lg border-zinc-200 dark:border-zinc-700 p-5">

                <div class="border-b border-zinc-200 dark:border-zinc-700 pb-4">

                    <flux:heading size="md">
                        Result Details
                    </flux:heading>

                    <flux:text class="mt-1">
                        View the submitted result-related concern.
                    </flux:text>

                </div>


                <div class="mt-5 space-y-5">

                    {{-- RESULT NO + DATE --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <flux:input
                            label="Result No."
                            wire:model="result_no"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Date Reported"
                            wire:model="date_reported"
                            type="text"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-4">

                        <flux:input
                            label="Reported By"
                            wire:model="reported_by"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Patient Name"
                            wire:model="patient_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Attending Physician"
                            wire:model="attending_physician"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Actual Released Date"
                            wire:model="actual_released_date"
                            type="date"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                    </div>

                    <flux:input
                        label="Source of Information"
                        wire:model="source_name"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    {{-- TEST PROCEDURE --}}
                    <flux:textarea
                        label="Test Procedure"
                        wire:model="test_procedure"
                        rows="5"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    <div class="mt-6">
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

                    {{-- COMPLAINANT --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <flux:input
                            label="Complainant Category"
                            wire:model="complain_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Complainant Name"
                            wire:model="complain_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Priority"
                            wire:model="priority"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>
                </div>

            </div>

            <div class="min-w-0 rounded-lg border-zinc-200 dark:border-zinc-700 p-5">

                <div class="border-b border-zinc-200 dark:border-zinc-700 pb-4">

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
                        placeholder="Enter the identified cause..."
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- PROVIDED SOLUTION --}}
                    <flux:textarea
                        label="Provided Solution"
                        wire:model="provided_solution"
                        rows="5"
                        placeholder="Enter the provided solution..."
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- RECOMMENDATION --}}
                    <flux:textarea
                        label="Recommendation"
                        wire:model="recommendation"
                        rows="5"
                        placeholder="Enter recommendation..."
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

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

                        {{-- CONCERN DESCRIPTION --}}
                        <div class="mt-4">
                        <flux:textarea
                            label="Concern Description"
                            wire:model="concern_description"
                            rows="5"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                        </div>

                        {{-- ASSIGNED TO / DEPARTMENT --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <flux:input
                                label="Assigned To"
                                wire:model="employee_assigned_to"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                            <flux:input
                                label="Department Name"
                                wire:model="department_name"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                        </div>


                        {{-- REMARKS --}}
                        <div class="mt-4">
                            <flux:textarea label="Dept Head Remarks" wire:model="dept_head_remarks" rows="4"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                        </div>
                    </div>
                </div>

            </div>

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

            <!-- SUPPORTING DOCUMENTS -->
            <div class="space-y-4">
                @if ($nte_id)
                <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="space-y-4">
                        {{-- Header --}}
                        <div>
                            <flux:heading size="sm">
                                Notice to Explain (NTE)
                            </flux:heading>

                            <flux:text class="mt-1">
                                Upload the NTE document in PDF format.
                            </flux:text>
                        </div>
                        {{-- Current NTE Attachment --}}
                        @if ($response_attachment)
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

                                        Notice to Explain PDF is already uploaded.

                                    </flux:text>

                                </div>

                            </div>

                            <flux:button
                                size="sm"
                                variant="ghost"
                                icon="eye"
                                href="{{ Storage::url($response_attachment) }}"
                                target="_blank">
                                View PDF
                            </flux:button>

                        </div>
                        @endif
                    </div>
                </div>
                @endif
                @if ($ir_id)
                <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="space-y-4">
                        {{-- Header --}}
                        <div>
                            <flux:heading size="sm">
                                Incident Report (IR)
                            </flux:heading>

                            <flux:text class="mt-1">
                                Upload the Incident Report document in PDF format.
                            </flux:text>
                        </div>
                        {{-- Current IR Attachment --}}
                        @if ($existing_ir_attachment)
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

                                        Incident Report PDF is already uploaded.

                                    </flux:text>

                                </div>

                            </div>


                            <flux:button
                                size="sm"
                                variant="ghost"
                                icon="eye"
                                href="{{ Storage::url($existing_ir_attachment) }}"
                                target="_blank">

                                View PDF

                            </flux:button>

                        </div>
                        @endif

                    </div>

                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- HR DECISION --}}
                <div class="space-y-3">

                    <flux:heading size="sm">
                        HR Decision
                    </flux:heading>

                    @foreach($selectedHRDecisions as $index => $decision)
                    <div class="flex items-end gap-2">

                        {{-- HR DECISION --}}
                        <div class="flex-1">

                            <flux:select
                                wire:model.live="selectedHRDecisions.{{ $index }}">

                                <flux:select.option value="">
                                    -- Select HR Decision --
                                </flux:select.option>

                                @foreach($decisionCategories as $item)

                                <flux:select.option value="{{ $item->id }}">
                                    {{ $item->decision_name }}
                                </flux:select.option>

                                @endforeach

                            </flux:select>

                        </div>

                        {{-- ADD --}}
                        @if($index == count($selectedHRDecisions) - 1)

                        <flux:button
                            type="button"
                            variant="primary"
                            icon="plus"
                            square
                            wire:click="addCategory" />

                        @endif

                        {{-- REMOVE --}}
                        @if(count($selectedHRDecisions) > 1)

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
                    @foreach($selectedCategories as $index => $category)
                    <div class="flex items-end gap-2">

                        <div class="flex-1">

                            <flux:select
                                wire:model="selectedCategories.{{ $index }}">

                                <flux:select.option value="">
                                    -- Select Category --
                                </flux:select.option>

                                @foreach($disciplinaryCategories as $item)

                                <flux:select.option value="{{ $item->id }}">
                                    {{ $item->category_name }}
                                </flux:select.option>

                                @endforeach

                            </flux:select>

                        </div>
                    </div>
                    @endforeach
                </div>


                {{-- OFFENSE LEVEL --}}
                <div class="space-y-3">

                    <flux:heading size="sm">
                        Offense Level
                    </flux:heading>

                    @foreach($selectedOffenseLevels as $index => $offense)

                    <div class="flex items-end gap-2">

                        <div class="flex-1">

                            <flux:select
                                wire:model="selectedOffenseLevels.{{ $index }}">

                                <flux:select.option value="">
                                    -- Select Offense Level --
                                </flux:select.option>

                                @foreach($offenseLevels as $level)

                                <flux:select.option value="{{ $level->id }}">
                                    {{ $level->offense_name }}
                                </flux:select.option>

                                @endforeach

                            </flux:select>

                        </div>

                        <div class="w-9 shrink-0"></div>

                        @if(count($selectedOffenseLevels) > 1)
                        <div class="w-9 shrink-0"></div>
                        @endif

                    </div>

                    @endforeach

                </div>

            </div>

            {{-- VALIDATION --}}
            @error('rows')
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600 dark:border-red-900 dark:bg-red-950/30">
                {{ $message }}
            </div>
            @enderror

            {{-- HR REMARKS --}}
            <flux:textarea
                label="HR Decision Remarks"
                wire:model="hr_decision_remarks"
                rows="5"
                placeholder="Enter HR decision remarks..." />
        </div>
        @if(!empty($management_remarks))
        <div class="space-y-5">

            {{-- HEADER --}}
            <div>
                <flux:label class="text-xl font-bold">
                    Management
                </flux:label>

                <flux:text class="mt-1 text-sm text-zinc-500">
                    Review the remarks or comments provided by management.
                </flux:text>
            </div>

            {{-- MANAGEMENT REMARKS --}}
            <div class="space-y-3">

                <flux:label>
                    Management Remarks
                </flux:label>

                <flux:textarea
                    wire:model="management_remarks"
                    rows="6"
                    disabled
                    class="bg-zinc-100 dark:bg-zinc-800" />

            </div>

        </div>
        @endif
        <flux:separator />
        <div class="flex justify-end items-center gap-3">
            {{-- SAVE DRAFT --}}
            <flux:button
                variant="primary"
                icon="lock-open"
                wire:click="saveDraft"
                wire:loading.attr="disabled"
                wire:target="saveDraft"
                class="w-auto bg-orange-500 hover:bg-orange-600 text-white">
                <span wire:loading.remove wire:target="saveDraft">
                    Save Draft
                </span>
                <span wire:loading wire:target="saveDraft">
                    Saving...
                </span>
            </flux:button>
            {{-- SAVE HR DECISION --}}
            <flux:button
                variant="primary"
                icon="lock-closed"
                wire:click="saveHRDecision"
                wire:loading.attr="disabled"
                wire:target="saveHRDecision"
                class="w-auto">

                <span
                    wire:loading.remove
                    wire:target="saveHRDecision">

                    Save HR Decision

                </span>

                <span
                    wire:loading
                    wire:target="saveHRDecision">

                    Saving...

                </span>

            </flux:button>
        </div>
    </div>
</flux:modal>