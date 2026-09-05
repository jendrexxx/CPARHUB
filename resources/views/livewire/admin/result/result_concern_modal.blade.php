<flux:modal
    name="acknowledge-result"
    class="w-full max-w-7xl">

    <div class="space-y-6">

        {{-- HEADER --}}
        <div>
            <flux:heading size="lg">
                Respond to Result-Related Concern
            </flux:heading>

            <flux:text class="mt-1">
                Review the result concern and provide the necessary findings and actions.
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


                    {{-- IR ATTACHMENT --}}
                    <div>
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

                        {{-- CONCERN DESCRIPTION --}}
                        <flux:textarea
                            label="Concern Description"
                            wire:model="concern_description"
                            rows="5"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


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
                            <flux:textarea label="Dept Head Remarks" wire:model="dept_head_remarks" rows="4" />
                        </div>
                    </div>

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
                wire:click="submitRESULT({{ $this->id ?? 0 }})"
                icon="paper-airplane">
                Submit
            </flux:button>
        </div>
    </div>
</flux:modal>