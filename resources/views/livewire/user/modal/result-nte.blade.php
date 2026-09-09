<flux:modal
    name="view-result-nte"
    class="w-[120%] max-w-[1500px] mt-6 top-0 z-50"
    wire:model="showViewNte">

    <div class="space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="flex items-start justify-between border-b pb-4">
            <div>
                <flux:heading size="lg">
                    Result Error - Notice to Explain
                </flux:heading>

                <flux:text class="mt-1">
                    View the issued Notice to Explain and submit your response.
                </flux:text>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- ========================================================= --}}
            {{-- LEFT SIDE - RESULT ERROR INFORMATION --}}
            {{-- ========================================================= --}}
            <div class="space-y-4">

                <div>
                    <flux:label class="text-xl font-bold">
                        Result Error Information
                    </flux:label>

                    <flux:text class="mt-1">
                        Review the basic information of this Result Error.
                    </flux:text>
                </div>


                <div class="rounded-lg p-5 space-y-4">

                    {{-- RESULT NO / DATE REPORTED --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <flux:input
                            label="Result Error No."
                            wire:model="result_no"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Date Reported"
                            wire:model="date_reported"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>


                    {{-- REPORTED BY / DEPARTMENT --}}
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:input
                            label="Actual Released Date"
                            wire:model="actual_released_date"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Source of Information"
                            wire:model="source_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                    </div>

                    <flux:textarea
                        label="Test Procedure"
                        wire:model="test_procedure"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    <div>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- COMPLAINANT CATEGORY --}}
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- PRIORITY --}}
                        <flux:input
                            label="Priority"
                            wire:model="priority"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                        {{-- STATUS --}}
                        <flux:input
                            label="Status"
                            wire:model="status"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- IR ATTACHMENT --}}
                        <div>
                            <flux:label>
                                IR Attachment
                            </flux:label>

                            @if ($existing_ir_attachment)

                            <div class="mt-2 flex items-center gap-2">

                                <flux:button
                                    icon="eye"
                                    variant="primary"
                                    size="sm"
                                    href="{{ Storage::url($ir_attachment) }}"
                                    target="_blank">
                                    View
                                </flux:button>

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
                    </div>

                    {{-- DEPARTMENT HEAD REMARKS --}}
                    <flux:textarea
                        label="Dept Head Remarks"
                        wire:model="dept_head_remarks"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                </div>

            </div>

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

        <div class="flex justify-end gap-2 pt-4 border-t">

            <flux:button
                type="button"
                variant="ghost"
                x-on:click="$dispatch('modal-close', { name: 'view-result-nte' })">
                Cancel
            </flux:button>

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