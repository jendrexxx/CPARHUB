<div>
    <flux:modal
        name="lab-result"
        class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">

        <div class="space-y-6">

            {{-- ========================================================= --}}
            {{-- HEADER --}}
            {{-- ========================================================= --}}
            <div>
                <flux:heading size="xl" class="font-bold">
                    LAB Acknowledgement
                </flux:heading>

                <flux:text class="mt-2 text-base text-zinc-500">
                    Review result-related concerns that are pending for LAB acknowledgement.
                </flux:text>
            </div>

            <flux:separator />


            {{-- ========================================================= --}}
            {{-- MAIN CONTENT --}}
            {{-- ========================================================= --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- ===================================================== --}}
                {{-- LEFT: RESULT INFORMATION --}}
                {{-- ===================================================== --}}
                <div class="space-y-5">

                    <div>
                        <flux:label class="text-xl font-bold">
                            Result Information
                        </flux:label>

                        <flux:text class="mt-1">
                            Review the basic information of this result-related concern.
                        </flux:text>
                    </div>


                    {{-- RESULT NO + DATE --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <flux:input
                            label="Result No."
                            wire:model="result_no"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Date Reported"
                            wire:model="date_reported"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>


                    {{-- REPORTED BY + PATIENT --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

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

                    </div>


                    {{-- PHYSICIAN + RELEASED DATE --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <flux:input
                            label="Attending Physician"
                            wire:model="attending_physician"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Actual Released Date"
                            wire:model="actual_released_date"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>


                    {{-- SOURCE --}}
                    <flux:input
                        label="Source of Information"
                        wire:model="source_name"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- TEST PROCEDURE --}}
                    <flux:textarea
                        label="Test Procedure"
                        wire:model="test_procedure"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- ================================================= --}}
                    {{-- RESULT ERROR / CONCERN --}}
                    {{-- ================================================= --}}
                    <div class="space-y-4">

                        <div>
                            <flux:label>
                                Result Error / Concern
                            </flux:label>

                            <flux:text class="mt-1 text-sm text-zinc-500">
                                Review the reported result-related concerns.
                            </flux:text>
                        </div>


                        {{-- DATA + TECHNICAL --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- DATA AND INFORMATION --}}
                            <div
                                class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">

                                <div
                                    class="mb-4 border-b border-zinc-200 pb-2 dark:border-zinc-700">

                                    <flux:text class="font-medium">
                                        Data and Information Errors
                                    </flux:text>

                                </div>

                                <div class="space-y-3">

                                    @foreach ($data as $item)

                                    <label
                                        class="flex items-center gap-3 cursor-not-allowed">

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

                                        <span
                                            class="text-sm text-zinc-700 dark:text-zinc-300">

                                            {{ $item->data_name }}

                                        </span>

                                    </label>

                                    @endforeach

                                </div>

                            </div>


                            {{-- TECHNICAL --}}
                            <div
                                class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">

                                <div
                                    class="mb-4 border-b border-zinc-200 pb-2 dark:border-zinc-700">

                                    <flux:text class="font-medium">
                                        Technical and Equipment Issues
                                    </flux:text>

                                </div>

                                <div class="space-y-3">

                                    @foreach ($technical as $item)

                                    <label
                                        class="flex items-center gap-3 cursor-not-allowed">

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

                                        <span
                                            class="text-sm text-zinc-700 dark:text-zinc-300">

                                            {{ $item->technical_name }}

                                        </span>

                                    </label>

                                    @endforeach

                                </div>

                            </div>

                        </div>


                        {{-- QUALITY --}}
                        <div
                            class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">

                            <div
                                class="mb-4 border-b border-zinc-200 pb-2 dark:border-zinc-700">

                                <flux:text class="font-medium">
                                    Quality and Accuracy Issues
                                </flux:text>

                            </div>

                            <div class="space-y-3">

                                @foreach ($quality as $item)

                                <label
                                    class="flex items-center gap-3 cursor-not-allowed">

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

                                    <span
                                        class="text-sm text-zinc-700 dark:text-zinc-300">

                                        {{ $item->quality_name }}

                                    </span>

                                </label>

                                @endforeach

                            </div>

                        </div>

                    </div>


                    {{-- COMPLAINANT + PRIORITY --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

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

                        <flux:input
                            label="Priority"
                            wire:model="priority"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- RIGHT: INVESTIGATION DETAILS --}}
                {{-- ===================================================== --}}
                <div class="space-y-5">

                    <div>
                        <flux:label class="text-xl font-bold">
                            Investigation Details
                        </flux:label>

                        <flux:text class="mt-1">
                            Review the investigation findings and corrective actions.
                        </flux:text>
                    </div>


                    {{-- IDENTIFIED CAUSE --}}
                    <flux:textarea
                        label="Identified Cause"
                        wire:model="identified_cause"
                        rows="5"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- PROVIDED SOLUTION --}}
                    <flux:textarea
                        label="Provided Solution"
                        wire:model="provided_solution"
                        rows="5"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- RECOMMENDATION --}}
                    <flux:textarea
                        label="Recommendation"
                        wire:model="recommendation"
                        rows="5"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- COMPLETION DETAILS --}}
                    <div class="border-t border-zinc-200 pt-6 dark:border-zinc-700">

                        <flux:label class="text-lg font-bold">
                            Completion Details
                        </flux:label>

                        <flux:text class="mt-1">
                            Review the completion information before proceeding.
                        </flux:text>


                        {{-- DATE + TAT --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

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


                        {{-- CONCERN DESCRIPTION --}}
                        <div class="mt-4">

                            <flux:textarea
                                label="Concern Description"
                                wire:model="concern_description"
                                rows="5"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        </div>


                        {{-- ASSIGNED TO + DEPARTMENT --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

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


                        {{-- DEPT HEAD REMARKS --}}
                        <div class="mt-4">

                            <flux:textarea
                                label="Dept Head Remarks"
                                wire:model="dept_head_remarks"
                                rows="4"
                                readonly
                                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- SUPPORTING DOCUMENTS --}}
            {{-- ========================================================= --}}
            <flux:separator />

            <div class="space-y-5">

                <div>
                    <flux:label class="text-xl font-bold">
                        Supporting Documents
                    </flux:label>

                    <flux:text class="mt-1">
                        Review the submitted supporting documents.
                    </flux:text>
                </div>


                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                    {{-- NTE --}}
                    @if ($nte_id)

                    <div
                        class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">

                        <div class="space-y-4">

                            <div>
                                <flux:heading size="sm">
                                    Notice to Explain (NTE)
                                </flux:heading>

                                <flux:text class="mt-1">
                                    NTE document attachment.
                                </flux:text>
                            </div>


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

                            @else

                            <flux:text class="text-sm text-zinc-500">
                                No NTE attachment available.
                            </flux:text>

                            @endif

                        </div>

                    </div>

                    @endif


                    {{-- IR --}}
                    @if ($ir_id)

                    <div
                        class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">

                        <div class="space-y-4">

                            <div>
                                <flux:heading size="sm">
                                    Incident Report (IR)
                                </flux:heading>

                                <flux:text class="mt-1">
                                    Incident Report document attachment.
                                </flux:text>
                            </div>


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

                            @else

                            <flux:text class="text-sm text-zinc-500">
                                No IR attachment available.
                            </flux:text>

                            @endif

                        </div>

                    </div>

                    @endif

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- HR DECISION --}}
            {{-- ========================================================= --}}
            <flux:separator />

            <div class="space-y-5">

                <div>
                    <flux:label class="text-xl font-bold">
                        HR Decision
                    </flux:label>

                    <flux:text class="mt-1">
                        Review the HR decision, disciplinary category, and offense level.
                    </flux:text>
                </div>


                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- HR DECISION --}}
                    <div class="min-w-0 space-y-3">

                        <flux:label>
                            HR Decision
                        </flux:label>

                        <div class="space-y-2">

                            @foreach($selectedHRDecisions as $index => $decision)

                            <flux:select
                                class="w-full"
                                wire:model="selectedHRDecisions.{{ $index }}"
                                disabled>

                                <flux:select.option value="">
                                    -- Select HR Decision --
                                </flux:select.option>

                                @foreach($decisionCategories as $item)

                                <flux:select.option value="{{ $item->id }}">
                                    {{ $item->decision_name }}
                                </flux:select.option>

                                @endforeach

                            </flux:select>

                            @endforeach

                        </div>

                    </div>


                    {{-- DISCIPLINARY CATEGORY --}}
                    <div class="min-w-0 space-y-3">

                        <flux:label>
                            Disciplinary Category
                        </flux:label>

                        <div class="space-y-2">

                            @foreach($selectedCategories as $index => $category)

                            <flux:select
                                class="w-full"
                                wire:model="selectedCategories.{{ $index }}"
                                disabled>

                                <flux:select.option value="">
                                    -- Select Category --
                                </flux:select.option>

                                @foreach($disciplinaryCategories as $item)

                                <flux:select.option value="{{ $item->id }}">
                                    {{ $item->category_name }}
                                </flux:select.option>

                                @endforeach

                            </flux:select>

                            @endforeach

                        </div>

                    </div>


                    {{-- OFFENSE LEVEL --}}
                    <div class="min-w-0 space-y-3">

                        <flux:label>
                            Offense Level
                        </flux:label>

                        <div class="space-y-2">

                            @foreach($selectedOffenseLevels as $index => $offense)

                            <flux:select
                                class="w-full"
                                wire:model="selectedOffenseLevels.{{ $index }}"
                                disabled>

                                <flux:select.option value="">
                                    -- Select Offense Level --
                                </flux:select.option>

                                @foreach($offenseLevels as $level)

                                <flux:select.option value="{{ $level->id }}">
                                    {{ $level->offense_name }}
                                </flux:select.option>

                                @endforeach

                            </flux:select>

                            @endforeach

                        </div>

                    </div>

                </div>


                {{-- HR REMARKS --}}
                <flux:textarea
                    label="HR Decision Remarks"
                    wire:model="hr_decision_remarks"
                    rows="5"
                    readonly
                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

            </div>

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

                <div class="space-y-3">
                    <flux:textarea
                        label="Management Remarks"
                        wire:model="management_remarks"
                        rows="4"
                        placeholder="Enter management remarks..."
                        class="uppercase" />
                </div>

            </div>

            <flux:separator />

            <div class="flex justify-end items-center gap-3 pt-4">

                {{-- BACK TO HR --}}
                <flux:button
                    variant="primary"
                    color="orange"
                    icon="arrow-uturn-left"
                    wire:click="backToHR"
                    wire:loading.attr="disabled"
                    wire:target="backToHR">

                    <span
                        wire:loading.remove
                        wire:target="backToHR">

                        Revise HR Decision

                    </span>

                    <span
                        wire:loading
                        wire:target="backToHR">

                        Returning for Revision...

                    </span>

                </flux:button>


                {{-- SUBMIT --}}
                <flux:button
                    variant="primary"
                    icon="paper-airplane"
                    wire:click="verifiedLaboratory"
                    wire:loading.attr="disabled"
                    wire:target="verifiedLaboratory">

                    <span
                        wire:loading.remove
                        wire:target="verifiedLaboratory">

                        Submit

                    </span>

                    <span
                        wire:loading
                        wire:target="verifiedLaboratory">

                        Submitting...

                    </span>

                </flux:button>

            </div>

        </div>

    </flux:modal>
</div>