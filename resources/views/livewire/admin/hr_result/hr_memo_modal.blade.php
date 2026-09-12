<div>
    <flux:modal
        name="memo-result"
        class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">

        <div class="space-y-6">

            {{-- ===================================================== --}}
            {{-- HEADER --}}
            {{-- ===================================================== --}}
            <div>
                <flux:heading size="lg">
                    Employee Memo
                </flux:heading>

                <flux:text class="mt-1">
                    Review the RESULT details and HR decision before issuing the employee memo.
                </flux:text>
            </div>

            <flux:separator />


            {{-- ===================================================== --}}
            {{-- RESULT + INVESTIGATION --}}
            {{-- ===================================================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- ================================================= --}}
                {{-- RESULT INFORMATION --}}
                {{-- ================================================= --}}
                <div class="space-y-5">

                    <div>
                        <flux:label class="text-xl font-bold">
                            Result Information
                        </flux:label>

                        <flux:text class="mt-1">
                            Review the submitted result-related concern.
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
                        rows="5"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- ================================================= --}}
                    {{-- RESULT ERROR / CONCERN --}}
                    {{-- ================================================= --}}
                    <div class="space-y-3">

                        <flux:label>
                            Result Error / Concern
                        </flux:label>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- DATA --}}
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
                                            @checked(in_array(
                                            $item->id,
                                        is_array($data_information)
                                        ? $data_information
                                        : json_decode($data_information ?? '[]', true)
                                        ))
                                        disabled
                                        class="h-4 w-4 rounded border-zinc-300
                                        disabled:cursor-not-allowed
                                        disabled:opacity-70
                                        dark:border-zinc-600"
                                        >

                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">
                                            {{ $item->data_name }}
                                        </span>

                                    </label>

                                    @endforeach

                                </div>

                            </div>


                            {{-- TECHNICAL --}}
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
                                            @checked(in_array(
                                            $item->id,
                                        is_array($technical_information)
                                        ? $technical_information
                                        : json_decode($technical_information ?? '[]', true)
                                        ))
                                        disabled
                                        class="h-4 w-4 rounded border-zinc-300
                                        disabled:cursor-not-allowed
                                        disabled:opacity-70
                                        dark:border-zinc-600"
                                        >

                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">
                                            {{ $item->technical_name }}
                                        </span>

                                    </label>

                                    @endforeach

                                </div>

                            </div>

                        </div>


                        {{-- QUALITY --}}
                        <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">

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
                                        @checked(in_array(
                                        $item->id,
                                    is_array($quality_information)
                                    ? $quality_information
                                    : json_decode($quality_information ?? '[]', true)
                                    ))
                                    disabled
                                    class="h-4 w-4 rounded border-zinc-300
                                    disabled:cursor-not-allowed
                                    disabled:opacity-70
                                    dark:border-zinc-600"
                                    >

                                    <span class="text-sm text-zinc-700 dark:text-zinc-300">
                                        {{ $item->quality_name }}
                                    </span>

                                </label>

                                @endforeach

                            </div>

                        </div>

                    </div>


                    {{-- COMPLAINANT --}}
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


                {{-- ================================================= --}}
                {{-- RESULT RESPONSE / INVESTIGATION --}}
                {{-- ================================================= --}}
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
                    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-5">

                        <flux:heading size="sm">
                            Completion Details
                        </flux:heading>

                        <flux:text class="mt-1">
                            Review the completion information before issuing the memo.
                        </flux:text>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
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


                    {{-- CONCERN DESCRIPTION --}}
                    <flux:textarea
                        label="Concern Description"
                        wire:model="concern_description"
                        rows="5"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- ASSIGNED TO + DEPARTMENT --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <flux:input
                            label="Assigned To"
                            wire:model="employee_assigned_to"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Department"
                            wire:model="department_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>

                    {{-- DEPT HEAD REMARKS --}}
                    <flux:textarea
                        label="Dept Head Remarks"
                        wire:model="dept_head_remarks"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                </div>

            </div>

            <flux:separator />

            <div class="space-y-4">

                <div>
                    <flux:label class="text-xl font-bold">
                        Supporting Documents
                    </flux:label>

                    <flux:text class="mt-1">
                        Review the supporting NTE and IR documents.
                    </flux:text>
                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- NTE --}}
                    @if ($nte_id)

                    <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">

                        <div class="space-y-4">

                            <div>
                                <flux:heading size="sm">
                                    Notice to Explain (NTE)
                                </flux:heading>

                                <flux:text class="mt-1">
                                    Supporting Notice to Explain document.
                                </flux:text>
                            </div>


                            @if ($response_attachment)

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


                    {{-- IR --}}
                    @if ($ir_id)

                    <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">

                        <div class="space-y-4">

                            <div>
                                <flux:heading size="sm">
                                    Incident Report (IR)
                                </flux:heading>

                                <flux:text class="mt-1">
                                    Supporting Incident Report document.
                                </flux:text>
                            </div>


                            @if ($existing_ir_attachment)

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

            </div>


            {{-- ===================================================== --}}
            {{-- HR DECISION --}}
            {{-- ===================================================== --}}
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

                        <flux:heading size="sm">
                            HR Decision
                        </flux:heading>

                        @forelse($decisionCategories as $item)

                        <flux:input
                            value="{{ $item->decision_name }}"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        @empty

                        <flux:text class="text-zinc-500">
                            No HR decision recorded.
                        </flux:text>

                        @endforelse

                    </div>


                    {{-- DISCIPLINARY CATEGORY --}}
                    <div class="space-y-3">

                        <flux:heading size="sm">
                            Disciplinary Category
                        </flux:heading>

                        @forelse($disciplinaryCategories as $item)

                        <flux:input
                            value="{{ $item->category_name }}"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        @empty

                        <flux:text class="text-zinc-500">
                            No disciplinary category recorded.
                        </flux:text>

                        @endforelse

                    </div>


                    {{-- OFFENSE LEVEL --}}
                    <div class="space-y-3">

                        <flux:heading size="sm">
                            Offense Level
                        </flux:heading>

                        @forelse($offenseLevels as $item)

                        <flux:input
                            value="{{ $item->offense_name }}"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        @empty

                        <flux:text class="text-zinc-500">
                            No offense level recorded.
                        </flux:text>

                        @endforelse

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


            {{-- ===================================================== --}}
            {{-- MANAGEMENT --}}
            {{-- ===================================================== --}}
            @if (!empty($management_remarks))

            <flux:separator />

            <div class="space-y-4">

                <div>
                    <flux:label class="text-xl font-bold">
                        Management
                    </flux:label>

                    <flux:text class="mt-1">
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


            {{-- ===================================================== --}}
            {{-- MEMO DETAILS --}}
            {{-- ===================================================== --}}
            <flux:separator />

            <div class="space-y-5">

                <div>
                    <flux:label class="text-xl font-bold">
                        Memo Details
                    </flux:label>

                    <flux:text class="mt-1">
                        Prepare the employee memo based on the approved HR decision.
                    </flux:text>
                </div>


                {{-- MEMO NO + DATE --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <flux:input
                        label="Memo No."
                        wire:model="memo_no"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    <flux:input
                        label="Date"
                        wire:model="memo_date"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                </div>


                {{-- SUBJECT --}}
                <flux:input
                    label="Subject"
                    wire:model="memo_subject"
                    placeholder="Enter memo subject..." />


                {{-- CONTENT + PRINT --}}
                <div class="space-y-3">
                    <div class="flex items-end gap-3">

                        <div class="flex-1">
                            <flux:textarea
                                id="memo_content"
                                label="Content"
                                wire:model="memo_content"
                                rows="10"
                                placeholder="Enter memo content..." />
                        </div>

                        <div class="shrink-0">
                            <flux:button
                                type="button"
                                variant="primary"
                                icon="printer"
                                wire:click="printResultMemo">
                                Print
                            </flux:button>
                        </div>

                    </div>
                </div>

                {{-- MEMO ATTACHMENT --}}
                <div class="space-y-3">

                    <flux:label>
                        Memo Attachment
                    </flux:label>


                    {{-- CURRENT ATTACHMENT --}}
                    @if ($current_memo_attachment)

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


                    {{-- UPLOAD --}}
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


            {{-- ===================================================== --}}
            {{-- FOOTER BUTTONS --}}
            {{-- ===================================================== --}}
            <flux:separator />

            <div class="flex justify-end items-center gap-3">

                {{-- SAVE DRAFT --}}
                <flux:button
                    variant="primary"
                    icon="document"
                    wire:click="saveResultDraft"
                    wire:loading.attr="disabled"
                    wire:target="saveResultDraft"
                    class="w-auto bg-orange-500 hover:bg-orange-600 text-white">

                    <span
                        wire:loading.remove
                        wire:target="saveResultDraft">

                        Save Draft

                    </span>

                    <span
                        wire:loading
                        wire:target="saveResultDraft">

                        Saving...

                    </span>

                </flux:button>


                {{-- ISSUE MEMO --}}
                <flux:button
                    variant="primary"
                    icon="paper-airplane"
                    wire:click="issueResultMemo"
                    wire:loading.attr="disabled"
                    wire:target="issueResultMemo">

                    <span
                        wire:loading.remove
                        wire:target="issueResultMemo">

                        Submit

                    </span>

                    <span
                        wire:loading
                        wire:target="issueResultMemo">

                        Issuing...

                    </span>

                </flux:button>

            </div>

        </div>

    </flux:modal>

    @script
    <script>
        $wire.on('print-memo-result', ({
            content
        }) => {

            if (!content || !content.trim()) {
                return;
            }

            const printWindow = window.open(
                '',
                '_blank',
                'width=900,height=700'
            );

            if (!printWindow) {
                alert('Please allow pop-ups to print the memo.');
                return;
            }

            const escapeHtml = (text) => {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            };

            printWindow.document.open();

            printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">

                <title>Employee Memo</title>

                <style>
                    @page {
                        size: A4;
                        margin: 20mm;
                    }

                    * {
                        box-sizing: border-box;
                    }

                    body {
                        margin: 0;
                        padding: 0;

                        font-family: Arial, sans-serif;
                        font-size: 14px;
                        line-height: 1.6;

                        color: #000;
                        background: #fff;

                        white-space: pre-wrap;
                        overflow-wrap: break-word;
                        word-wrap: break-word;
                    }

                    .memo-content {
                        width: 100%;
                    }
                </style>
            </head>

            <body>
                <div class="memo-content">
                    ${escapeHtml(content)}
                </div>
            </body>
            </html>
        `);

            printWindow.document.close();

            // Wait for the print document to finish loading
            printWindow.onload = () => {

                printWindow.focus();

                setTimeout(() => {
                    printWindow.print();
                }, 300);
            };

        });
    </script>
    @endscript

</div>