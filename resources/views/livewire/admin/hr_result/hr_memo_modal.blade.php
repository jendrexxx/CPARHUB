<div class="memo-print-root">
    <flux:modal
        name="memo-result"
        class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">

        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Employee Memo
                </flux:heading>

                <flux:text class="mt-1">
                    Review the Result details and HR decision before issuing the employee memo.
                </flux:text>
            </div>

            <flux:separator />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

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

            <flux:separator />

            <div class="space-y-6">

                {{-- HEADER --}}
                <div class="print-hidden">
                    <flux:label class="text-xl font-bold">
                        Memo Details
                    </flux:label>

                    <flux:text class="mt-1 text-zinc-500">
                        Prepare the employee memo based on the approved HR decision.
                    </flux:text>
                </div>

                <div>

                    <div
                        class="rounded-xl border border-zinc-200 bg-white p-6
                         dark:border-zinc-700 dark:bg-zinc-900">

                        {{-- SUBJECT --}}
                        <div class="flex items-center">

                            <div class="w-28 shrink-0 font-semibold">
                                Subject:
                            </div>

                            <div class="flex-1">

                                <flux:input
                                    wire:model="memo_subject"
                                    placeholder="Enter subject..."
                                    @input="$el.value = $el.value.toUpperCase()"
                                    class="uppercase" />

                            </div>

                        </div>

                        {{-- DATE --}}
                        <div class="mt-4 flex items-center">

                            <div class="w-28 shrink-0 font-semibold">
                                Date:
                            </div>

                            <div class="flex-1">

                                <flux:input
                                    wire:model="memo_date"
                                    readonly
                                    class="cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                            </div>

                        </div>

                        {{-- TO --}}
                        <div class="mt-4 flex items-center">

                            <div class="w-28 shrink-0 font-semibold">
                                To:
                            </div>

                            <div class="flex-1">

                                <flux:input
                                    wire:model="full_name"
                                    readonly
                                    class="cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                            </div>

                        </div>

                        {{-- FROM --}}
                        <div class="mt-4 flex items-center">
                            <div class="w-28 shrink-0 font-semibold">
                                From:
                            </div>
                            <div class="flex-1">
                                <flux:input
                                    wire:model="from"
                                    readonly
                                    class="cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                            </div>
                        </div>

                        {{-- RE --}}
                        <div class="mt-4 flex items-center">

                            <div class="w-28 shrink-0 font-semibold">
                                RE:
                            </div>

                            <div class="flex-1">

                                <flux:input
                                    wire:model="memo_re"
                                    placeholder="Enter memo reference..."
                                    class="uppercase" />

                            </div>

                        </div>
                        {{-- MEMO CONTENT --}}
                        <div class="mt-6">
                            <div class="memo-content-separator"></div>
                            <flux:textarea
                                id="memo_content"
                                label="Memo Content"
                                wire:model.live="memo_content"
                                rows="18"
                                class="uppercase"
                                placeholder="Enter memo content..." />
                        </div>

                        {{-- SIGNATORY --}}
                        <div class="mt-6">
                            <div class="mb-2 text-sm font-semibold">
                                Signatory
                            </div>
                            <div class="w-80 border-b border-zinc-900 pb-1 dark:border-zinc-100">
                                {{ $signatory }}
                            </div>
                            <div class="text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $signatory_position }}
                            </div>

                        </div>

                        {{-- CONFORME --}}
                        <div class="mt-8">

                            <div class="mb-8 text-sm font-semibold">
                                CONFORME:
                            </div>

                            <div
                                class="w-80 border-b border-zinc-900 pb-1 dark:border-zinc-100">
                                {{ $full_name }}
                            </div>

                            <div class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $position_name }}
                            </div>

                        </div>

                    </div>

                    <div class="mt-6 flex justify-end gap-2 print-hidden">
                        <flux:button
                            type="button"
                            icon="printer"
                            onclick="printMemo({{ $assignment_id }})">
                            Print Memo
                        </flux:button>
                    </div>

                </div>

                <style>
                    .memo-print-wrapper {
                        display: none;
                    }

                    @media print {

                        @page {
                            size: A4;
                            margin: 12mm 15mm 12mm 15mm;
                        }

                        body * {
                            visibility: hidden !important;
                        }

                        .memo-print-wrapper,
                        .memo-print-wrapper * {
                            visibility: visible !important;
                        }

                        .memo-print-wrapper {
                            display: block !important;

                            position: absolute !important;

                            top: 0 !important;
                            left: 0 !important;

                            width: 180mm !important;
                            max-width: 180mm !important;

                            margin: 0 !important;
                            padding: 0 !important;

                            background: white !important;
                        }

                        .memo-form {
                            display: block !important;

                            width: 180mm !important;
                            max-width: 180mm !important;

                            margin: 0 !important;
                            padding: 0 !important;

                            background: white !important;
                            color: black !important;

                            font-family: Arial, Helvetica, sans-serif !important;
                            font-size: 10pt !important;
                            line-height: 1.25 !important;
                        }

                        .memo-logo-result {
                            display: block !important;
                            width: 100% !important;
                            max-width: 100% !important;
                            margin: 0 0 0px 55px !important;
                            padding: 0 !important;
                            text-align: center !important;
                        }

                        .memo-logo-result img {
                            display: inline-block !important;
                            width: 300px !important;
                            max-width: 300px !important;
                            height: auto !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            object-fit: contain !important;
                            vertical-align: top !important;
                        }

                        .memo-field {
                            display: grid !important;

                            grid-template-columns: 75px 1fr !important;
                            column-gap: 10px !important;

                            align-items: start !important;

                            width: 100% !important;

                            margin: 0 0 7px 0 !important;
                            padding: 0 !important;

                            break-inside: avoid !important;
                            page-break-inside: avoid !important;
                        }

                        .memo-label {
                            display: block !important;

                            width: auto !important;
                            min-width: 0 !important;

                            margin: 0 !important;
                            padding: 0 !important;

                            font-weight: 700 !important;
                            font-size: 10pt !important;
                            line-height: 1.25 !important;

                            color: black !important;

                            white-space: nowrap !important;
                        }

                        .memo-input {
                            display: block !important;

                            width: 100% !important;
                            min-width: 0 !important;
                            min-height: 0 !important;

                            margin: 0 !important;
                            padding: 0 !important;

                            border: 0 !important;
                            border-radius: 0 !important;

                            background: transparent !important;
                            color: black !important;

                            font-size: 10pt !important;
                            line-height: 1.25 !important;

                            text-transform: uppercase !important;

                            word-break: normal !important;
                            overflow-wrap: break-word !important;
                        }

                        .memo-content {
                            display: block !important;

                            width: 100% !important;

                            margin: 18px 0 0 0 !important;
                            padding: 0 !important;

                            break-inside: avoid !important;
                            page-break-inside: avoid !important;
                        }

                        .memo-content-label {
                            display: block !important;

                            margin: 0 0 6px 0 !important;
                            padding: 0 !important;

                            font-weight: 700 !important;
                            font-size: 10pt !important;
                            line-height: 1.25 !important;

                            color: black !important;
                        }

                        .memo-content .memo-input {
                            display: block !important;

                            width: 100% !important;

                            margin: 0 !important;
                            padding: 0 !important;

                            line-height: 1.3 !important;

                            white-space: pre-wrap !important;

                            text-transform: uppercase !important;

                            word-break: normal !important;
                            overflow-wrap: break-word !important;
                        }

                        .memo-signatory {
                            display: block !important;

                            width: 100% !important;

                            margin: 20px 0 0 0 !important;
                            padding: 0 !important;

                            break-inside: avoid !important;
                            page-break-inside: avoid !important;
                        }

                        .memo-signatory .memo-label {
                            margin: 0 0 6px 0 !important;
                        }

                        .memo-signatory .memo-input {
                            width: 100% !important;
                        }

                        .memo-conforme {
                            display: block !important;

                            width: 100% !important;

                            margin: 28px 0 0 0 !important;
                            padding: 0 !important;

                            break-inside: avoid !important;
                            page-break-inside: avoid !important;
                        }

                        .memo-conforme-label {
                            display: block !important;

                            margin: 0 0 25px 0 !important;
                            padding: 0 !important;

                            font-weight: 700 !important;
                            font-size: 10pt !important;

                            color: black !important;
                        }

                        .memo-signature {
                            display: block !important;

                            width: 245px !important;

                            margin: 0 !important;
                            padding: 0 0 2px 0 !important;

                            border-bottom: 1px solid black !important;

                            font-weight: 700 !important;
                            font-size: 10pt !important;

                            color: black !important;
                        }

                        .memo-position {
                            display: block !important;

                            width: 245px !important;

                            margin-top: 3px !important;
                            padding: 0 !important;

                            font-size: 10pt !important;

                            color: black !important;
                        }

                        .memo-logo-result,
                        .memo-field,
                        .memo-content,
                        .memo-signatory,
                        .memo-conforme {
                            break-inside: avoid !important;
                            page-break-inside: avoid !important;
                        }

                        .memo-print-wrapper,
                        .memo-print-wrapper * {
                            color: black !important;
                            background: white !important;
                            box-sizing: border-box !important;
                        }

                        .memo-content-separator {
                            display: block !important;
                            width: 100% !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            border-bottom: 1px solid black !important;
                            height: 1px !important;
                        }

                        .memo-content-separator {
                            display: block !important;

                            width: 120% !important;
                            height: 1px !important;

                            margin: 8px 0 12px 0 !important;
                            padding: 0 !important;

                            border-top: 1px solid black !important;
                        }
                    }

                    .memo-text-field1 {
                        margin: 0 !important;
                        padding: 0 !important;

                        width: 100% !important;

                        text-align: left !important;
                        text-indent: 0 !important;

                        white-space: pre-wrap !important;
                        overflow-wrap: break-word !important;
                        word-break: normal !important;

                        font-family: Arial, Helvetica, sans-serif !important;
                        font-size: 10pt !important;
                        line-height: 1.3 !important;
                    }

                    @media print {
                        .memo-text-field1 {
                            margin: 0 !important;
                            padding: 0 !important;

                            width: 100% !important;
                            max-width: 100% !important;

                            text-align: left !important;
                            text-indent: 0 !important;

                            white-space: pre-wrap !important;
                            overflow-wrap: break-word !important;
                            word-break: normal !important;

                            line-height: 1.3 !important;
                        }
                    }

                    .memo-content-separator {
                        display: none;
                    }
                </style>

                {{-- MEMO ATTACHMENT --}}
                <div
                    id="memo-attachment"
                    class="rounded-xl border border-zinc-200 bg-white p-5
                 dark:border-zinc-700 dark:bg-zinc-900">

                    <flux:label class="mb-4 text-base font-semibold">
                        Memo Attachment
                    </flux:label>

                    {{-- CURRENT ATTACHMENT --}}
                    @if ($current_memo_attachment)
                    <div class="mb-4 flex items-center justify-between rounded-lg
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

    <div
        id="memo-print-{{ $assignment_id }}"
        class="memo-print-wrapper">

        <div class="memo-form">

            <div class="memo-logo-result">
                <img
                    src="{{ asset('logo/premierelaboratory_cover.jpg') }}"
                    alt="Premiere Medical Logo"
                    class="mx-auto h-auto">
            </div>

            <div class="memo-field">
                <div class="memo-label">
                    SUBJECT:
                </div>

                <div class="memo-input">
                    {{ strtoupper($memo_subject ?? '') }}
                </div>
            </div>

            <div class="memo-field">
                <div class="memo-label">
                    DATE:
                </div>

                <div class="memo-input">
                    {{ $memo_date ?? '' }}
                </div>
            </div>

            <div class="memo-field">
                <div class="memo-label">
                    TO:
                </div>

                <div class="memo-input">
                    {{ $full_name ?? '' }}
                </div>
            </div>

            <div class="memo-field">
                <div class="memo-label">
                    FROM:
                </div>

                <div class="memo-input">
                    {{ $from ?? '' }}
                </div>
            </div>

            <div class="memo-field">
                <div class="memo-label">
                    RE:
                </div>

                <div class="memo-input">
                    {{ strtoupper($memo_re ?? '') }}
                </div>
            </div>

            <div class="memo-content-separator"></div>

            <div class="memo-content">
                <div class="memo-text-field1">{{ strtoupper($memo_content ?? '') }}</div>
            </div>

            <div class="memo-signatory">
                <div class="memo-label">
                    SIGNATORY:
                </div>
                <div class="memo-signature">
                    {{ $signatory ?? '' }}
                </div>
                <div class="memo-input">
                    {{ $signatory_position ?? '' }}
                </div>
            </div>

            <div class="memo-conforme">

                <div class="memo-conforme-label">
                    CONFORME:
                </div>

                <div class="memo-signature">
                    {{ $full_name ?? '' }}
                </div>

                <div class="memo-position">
                    {{ $position_name ?? '' }}
                </div>

            </div>

        </div>
    </div>


    <script>
        function printMemo(memoId) {
            const memo = document.getElementById('memo-print-' + memoId);

            if (!memo) {
                console.error('Memo print element not found:', memoId);
                return;
            }

            document.body.classList.add('result-memo-printing');

            window.print();

            setTimeout(() => {
                document.body.classList.remove('result-memo-printing');
            }, 500);
        }
    </script>

</div>