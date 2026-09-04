<flux:modal
    name="respond-result"
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


            {{-- ====================================================== --}}
            {{-- LEFT : RESULT DETAILS --}}
            {{-- ====================================================== --}}
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


                    {{-- ERROR CATEGORIES --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- DATA AND INFORMATION --}}
                        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">

                            <flux:label>
                                Data and Information Errors
                            </flux:label>

                            <div class="mt-4 space-y-2">

                                @foreach($data as $item)

                                <div class="flex items-center gap-2">

                                    <flux:checkbox
                                        :checked="in_array($item->id, $selectedData ?? [])"
                                        label="{{ $item->data_name }}"
                                        disabled />

                                </div>

                                @endforeach

                            </div>

                        </div>


                        {{-- TECHNICAL --}}
                        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">

                            <flux:label>
                                Technical and Equipment Issues
                            </flux:label>

                            <div class="mt-4 space-y-2">

                                @foreach($technical as $item)

                                <div class="flex items-center gap-2">

                                    <flux:checkbox
                                        :checked="in_array($item->id, $selectedTechnical ?? [])"
                                        label="{{ $item->technical_name }}"
                                        disabled />

                                </div>

                                @endforeach

                            </div>

                        </div>

                    </div>

                    {{-- QUALITY --}}
                    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">

                        <flux:label>
                            Quality and Accuracy Issues
                        </flux:label>

                        <div class="mt-4 space-y-2">

                            @foreach($quality as $item)

                            <div class="flex items-center gap-2">

                                <flux:checkbox
                                    :checked="in_array($item->id, $selectedQuality ?? [])"
                                    label="{{ $item->quality_name }}"
                                    disabled />

                            </div>

                            @endforeach

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


                    {{-- CONCERN DESCRIPTION --}}
                    <flux:textarea
                        label="Concern Description"
                        wire:model="concern_description"
                        rows="5"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- ASSIGNED TO --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

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
                    <flux:textarea
                        label="Dept Head/HR Remarks"
                        wire:model="assignment_remarks"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                </div>

            </div>


            {{-- ====================================================== --}}
            {{-- RIGHT : RESULT RESPONSE --}}
            {{-- ====================================================== --}}
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
                        placeholder="Enter the identified cause..." />


                    {{-- PROVIDED SOLUTION --}}
                    <flux:textarea
                        label="Provided Solution"
                        wire:model="provided_solution"
                        rows="5"
                        placeholder="Enter the provided solution..." />


                    {{-- RECOMMENDATION --}}
                    <flux:textarea
                        label="Recommendation"
                        wire:model="recommendation"
                        rows="5"
                        placeholder="Enter recommendation..." />


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


                    {{-- COMPLETION --}}
                    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-5">

                        <flux:heading size="sm">
                            Completion Details
                        </flux:heading>

                        <flux:text class="mt-1">
                            Review the completion information before submitting.
                        </flux:text>


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

                    </div>

                </div>

            </div>

        </div>


        {{-- ====================================================== --}}
        {{-- FOOTER --}}
        {{-- ====================================================== --}}
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-zinc-200 dark:border-zinc-700 pt-5">

            <flux:button
                type="button"
                variant="primary"
                icon="lock-open"
                wire:click="saveDraft"
                wire:loading.attr="disabled"
                wire:target="saveDraft"
                class="bg-orange-500 hover:bg-orange-600 text-white">

                <span wire:loading.remove wire:target="saveDraft">
                    Save Draft
                </span>

                <span wire:loading wire:target="saveDraft">
                    Saving...
                </span>

            </flux:button>


            <flux:button
                type="button"
                variant="primary"
                icon="check"
                wire:click="saveResponse"
                wire:loading.attr="disabled"
                wire:target="saveResponse">

                <span wire:loading.remove wire:target="saveResponse">
                    Submit Response
                </span>

                <span wire:loading wire:target="saveResponse">
                    Submitting...
                </span>

            </flux:button>

        </div>

    </div>

</flux:modal>