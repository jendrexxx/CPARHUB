<div class="hr-memo-print-root">
    <flux:modal
        name="hr-memo-modal"
        class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">
        <div class="space-y-6">

            <div>
                <flux:label class="text-xl font-bold">
                    Employee Memo
                </flux:label>

                <flux:text class="mt-1">
                    Review the CPAR details and HR decision before issuing the employee memo.
                </flux:text>
            </div>

            <flux:separator />

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

                    {{-- REPORTED BY + SOURCE --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <flux:input
                            label="Reported By"
                            wire:model="reported_by"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Department"
                            wire:model="reported_by_department"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>

                    {{-- COMPLAINANT --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

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

                    {{-- CONCERN --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:input
                            label="Source Origin"
                            wire:model="source_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Concern Category"
                            wire:model="concern_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>
                    {{-- EMPLOYEE + DEPARTMENT --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <flux:input
                            label="Reported Employee"
                            wire:model="full_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Department"
                            wire:model="department_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>
                    <flux:textarea
                        label="Concern Description"
                        wire:model="concern_description"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
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
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    <flux:textarea
                        label="Provided Solution"
                        wire:model="provided_solution"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    <flux:textarea
                        label="Recommendation"
                        wire:model="recommendation"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

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

            </div>

            <div>

                <flux:textarea
                    label="Dept Head Remarks"
                    wire:model="head_remarks"
                    rows="4"
                    readonly
                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

            </div>
            <!-- SUPPORTING DOCUMENTS -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                {{-- NTE --}}
                @if ($nte_id)
                <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                    <div class="space-y-4">

                        <div>
                            <flux:heading size="sm">
                                Notice to Explain (NTE)
                            </flux:heading>

                            <flux:text class="mt-1">
                                Upload the NTE document in PDF format.
                            </flux:text>
                        </div>

                        @if ($current_nte_attachment)
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

                                    <flux:text size="sm" class="text-zinc-500">
                                        Notice to Explain PDF is already uploaded.
                                    </flux:text>
                                </div>

                            </div>

                            <flux:button
                                size="sm"
                                variant="ghost"
                                icon="eye"
                                href="{{ Storage::url($current_nte_attachment) }}"
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
                                Upload the Incident Report document in PDF format.
                            </flux:text>
                        </div>

                        @if ($current_ir_attachment)
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

                                    <flux:text size="sm" class="text-zinc-500">
                                        Incident Report PDF is already uploaded.
                                    </flux:text>
                                </div>

                            </div>

                            <flux:button
                                size="sm"
                                variant="ghost"
                                icon="eye"
                                href="{{ Storage::url($current_ir_attachment) }}"
                                target="_blank">

                                View PDF

                            </flux:button>

                        </div>
                        @endif

                    </div>
                </div>
                @endif

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
                        @foreach($decisionCategories as $item)
                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <flux:input
                                    label="HR Decision"
                                    value="{{ $item->decision_name }}"
                                    readonly
                                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- DISCIPLINARY CATEGORY --}}
                    <div class="space-y-3">
                        @foreach($disciplinaryCategories as $item)
                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <flux:input
                                    label="Disciplinary Category"
                                    value="{{ $item->category_name }}"
                                    readonly
                                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- OFFENSE LEVEL --}}
                    <div class="space-y-3">
                        @foreach($offenseLevels as $item)
                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <flux:input
                                    label="Offense Level"
                                    value="{{ $item->offense_name }}"
                                    readonly
                                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- HR DECISION REMARKS --}}
                <flux:textarea
                    label="HR Decision Remarks"
                    wire:model="hr_decision_remarks"
                    rows="5"
                    readonly
                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

            </div>

            @if(!empty($management_remarks))
            <flux:separator />
            <div class="space-y-4">

                <div>
                    <flux:label class="text-xl font-bold">
                        Management
                    </flux:label>

                    <flux:text class="mt-1 text-sm text-zinc-500">
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

                    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                        {{-- SUBJECT --}}
                        <div class="flex items-center">

                            <div class="w-28 shrink-0 font-semibold">
                                Subject :
                            </div>

                            <div class="flex-1">
                                <flux:input
                                    wire:model="memo_subject"
                                    placeholder="Enter subject..."
                                    @input="$el.value = $el.value.toUpperCase()"
                                    class="uppercase " />
                            </div>

                        </div>


                        {{-- DATE --}}
                        <div class="mt-4 flex items-center">

                            <div class="w-28 shrink-0 font-semibold">
                                Date :
                            </div>

                            <div class="flex-1">
                                <flux:input
                                    wire:model="memo_date"
                                    readonly
                                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                            </div>

                        </div>


                        {{-- TO --}}
                        <div class="mt-4 flex items-center">

                            <div class="w-28 shrink-0 font-semibold">
                                To :
                            </div>

                            <div class="flex-1">
                                <flux:input
                                    wire:model="full_name"
                                    readonly
                                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                            </div>

                        </div>


                        {{-- FROM --}}
                        <div class="mt-4 flex items-center">

                            <div class="w-28 shrink-0 font-semibold">
                                From :
                            </div>

                            <div class="flex-1">
                                <flux:input
                                    wire:model="from"
                                    readonly
                                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                            </div>

                        </div>


                        {{-- RE --}}
                        <div class="mt-4 flex items-center">

                            <div class="w-28 shrink-0 font-semibold">
                                RE :
                            </div>

                            <div class="flex-1">
                                <flux:input
                                    wire:model="memo_re"
                                    placeholder="Enter memo reference..."
                                    @input="$el.value = $el.value.toUpperCase()"
                                    class="uppercase" />
                            </div>

                        </div>


                        {{-- MEMO CONTENT --}}
                        <div class="mt-6">
                            <flux:textarea
                                id="memo_content"
                                label="MEMO CONTENT"
                                wire:model="memo_content"
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
                                CONFORME :
                            </div>

                            <div class="w-80 border-b border-zinc-900 pb-1 dark:border-zinc-100">
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
                            onclick="HRprintMemo({{ $assignment_id }})">
                            Print Memo
                        </flux:button>

                    </div>


                </div>


                <style>
                    .memo-print-wrapper-sample {
                        display: none;
                    }

                    .memo-content-separator2 {
                        display: none;
                    }

                    .memo-text-field {
                        margin: 0 !important;
                        padding: 0 !important;
                        width: 120% !important;
                        text-align: left !important;
                        text-indent: 0 !important;
                        white-space: pre-wrap !important;
                        overflow-wrap: break-word !important;
                        word-break: normal !important;
                        font-family: Arial, Helvetica, sans-serif !important;
                        font-size: 10pt !important;
                        line-height: 1.3 !important;
                    }

                    /* EDITABLE MEMO CONTENT */
                    .memo-content {
                        display: block !important;
                        width: 120% !important;
                        max-width: 120% !important;
                        margin: 18px 0 0 0 !important;
                        padding: 0 !important;
                        box-sizing: border-box !important;
                    }

                    .memo-textarea {
                        display: block !important;
                        width: 120% !important;
                        min-width: 120% !important;
                        max-width: 120% !important;
                        min-height: 250px !important;
                        height: auto;
                        margin: 0 !important;
                        padding: 12px !important;

                        font-family: Arial, Helvetica, sans-serif !important;
                        font-size: 10pt !important;
                        line-height: 1.5 !important;
                        text-align: justify !important;

                        white-space: pre-wrap !important;
                        overflow-wrap: break-word !important;
                        word-break: normal !important;

                        border: 1px solid #d1d5db !important;
                        border-radius: 5px !important;
                        background: #fff !important;
                        color: #000 !important;

                        resize: vertical;
                        outline: none;
                        box-sizing: border-box !important;
                    }

                    .memo-textarea:focus {
                        border-color: #9F0712 !important;
                        box-shadow: 0 0 0 1px #9F0712 !important;
                    }

                    @media print {

                        @page {
                            size: A4 portrait;
                            margin: 12mm 15mm 12mm 15mm;
                        }

                        html,
                        body {
                            width: 210mm !important;
                            min-height: 297mm !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            background: #fff !important;
                        }

                        body.hr-memo-printing * {
                            visibility: hidden !important;
                        }

                        body.hr-memo-printing .memo-print-wrapper-sample,
                        body.hr-memo-printing .memo-print-wrapper-sample * {
                            visibility: visible !important;
                        }

                        body.hr-memo-printing .memo-print-wrapper-sample {
                            display: block !important;
                            position: absolute !important;
                            top: 0 !important;
                            left: 0 !important;
                            width: 180mm !important;
                            height: auto !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            background: #fff !important;
                            overflow: visible !important;
                        }

                        body.hr-memo-printing .memo-form-sample {
                            display: block !important;
                            width: 180mm !important;
                            max-width: 180mm !important;
                            min-height: 0 !important;
                            height: auto !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            background: #fff !important;
                            color: #000 !important;
                            font-family: Arial, Helvetica, sans-serif !important;
                            font-size: 10pt !important;
                            line-height: 1.25 !important;
                            box-sizing: border-box !important;
                        }

                        /* LOGO */
                        body.hr-memo-printing .memo-logo {
                            display: flex !important;
                            justify-content: center !important;
                            align-items: center !important;
                            width: 100% !important;
                            max-width: 100% !important;
                            margin: 0 0 0 55px !important;
                            padding: 0 !important;
                            text-align: center !important;
                            box-sizing: border-box !important;
                        }

                        body.hr-memo-printing .memo-logo img {
                            display: block !important;
                            width: 300px !important;
                            max-width: 300px !important;
                            height: auto !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            object-fit: contain !important;
                        }

                        /* MEMO TITLE */
                        body.hr-memo-printing .memo-title {
                            display: block !important;
                            text-align: center !important;
                            font-family: Arial, Helvetica, sans-serif !important;
                            font-size: 16pt !important;
                            font-weight: bold !important;
                            letter-spacing: 1px !important;
                            margin: 15px 0 25px 0 !important;
                            padding: 0 !important;
                            color: #000 !important;
                        }

                        /* MEMO FIELDS */
                        body.hr-memo-printing .memo-field {
                            display: grid !important;
                            grid-template-columns: 70px 1fr !important;
                            column-gap: 12px !important;
                            width: 100% !important;
                            margin: 0 0 7px 0 !important;
                            padding: 0 !important;
                            align-items: start !important;
                            box-sizing: border-box !important;
                            break-inside: avoid !important;
                            page-break-inside: avoid !important;
                        }

                        body.hr-memo-printing .memo-label {
                            display: block !important;
                            width: auto !important;
                            min-width: 0 !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            font-weight: 700 !important;
                            font-size: 10pt !important;
                            line-height: 1.25 !important;
                            color: #000 !important;
                            white-space: nowrap !important;
                        }

                        body.hr-memo-printing .memo-input {
                            display: block !important;
                            width: 100% !important;
                            min-height: 0 !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            border: 0 !important;
                            border-radius: 0 !important;
                            background: transparent !important;
                            color: #000 !important;
                            font-size: 10pt !important;
                            line-height: 1.25 !important;
                            text-transform: uppercase !important;
                            overflow-wrap: break-word !important;
                            word-break: normal !important;
                            box-sizing: border-box !important;
                        }

                        /* MEMO CONTENT */
                        body.hr-memo-printing .memo-content {
                            display: block !important;
                            width: 100% !important;
                            margin: 18px 0 0 0 !important;
                            padding: 0 !important;
                            break-inside: auto !important;
                            page-break-inside: auto !important;
                        }

                        body.hr-memo-printing .memo-content-label {
                            display: block !important;
                            margin: 0 0 6px 0 !important;
                            padding: 0 !important;
                            font-weight: 700 !important;
                            font-size: 10pt !important;
                            color: #000 !important;
                        }

                        body.hr-memo-printing .memo-content .memo-input {
                            display: block !important;
                            width: 100% !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            line-height: 1.3 !important;
                            white-space: pre-wrap !important;
                            text-transform: uppercase !important;
                        }

                        /* HIDE EDITABLE TEXTAREA WHEN PRINTING */
                        body.hr-memo-printing .memo-textarea {
                            display: none !important;
                        }

                        /* PRINTED MEMO TEXT */
                        body.hr-memo-printing .memo-text-field {
                            display: block !important;
                            width: 120% !important;
                            max-width: 120% !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            text-align: justify !important;
                            text-indent: 0 !important;
                            white-space: pre-wrap !important;
                            overflow-wrap: break-word !important;
                            word-break: normal !important;
                            font-family: Arial, Helvetica, sans-serif !important;
                            font-size: 10pt !important;
                            line-height: 1.3 !important;
                            color: #000 !important;
                            text-transform: uppercase !important;
                            box-sizing: border-box !important;
                        }

                        /* SEPARATOR */
                        body.hr-memo-printing .memo-content-separator2 {
                            display: block !important;
                            width: 120% !important;
                            height: 1px !important;
                            margin: 8px 0 12px 0 !important;
                            padding: 0 !important;
                            border: 0 !important;
                            border-top: 1px solid #000 !important;
                            box-sizing: border-box !important;
                        }

                        /* SIGNATORY */
                        body.hr-memo-printing .memo-signatory {
                            display: block !important;
                            width: 100% !important;
                            margin: 20px 0 0 0 !important;
                            padding: 0 !important;
                            break-inside: avoid !important;
                            page-break-inside: avoid !important;
                        }

                        body.hr-memo-printing .memo-signatory .memo-label {
                            display: block !important;
                            width: auto !important;
                            margin: 0 0 6px 0 !important;
                            padding: 0 !important;
                        }

                        body.hr-memo-printing .memo-signature1 {
                            display: block !important;
                            width: 245px !important;
                            max-width: 245px !important;
                            min-height: 14px !important;
                            height: auto !important;
                            margin: 0 !important;
                            padding: 0 0 2px 0 !important;
                            border: 0 !important;
                            border-bottom: 1px solid #000 !important;
                            border-radius: 0 !important;
                            background: transparent !important;
                            color: #000 !important;
                            font-family: Arial, Helvetica, sans-serif !important;
                            font-size: 10pt !important;
                            font-weight: 700 !important;
                            line-height: 1.2 !important;
                            text-align: left !important;
                            text-transform: uppercase !important;
                            white-space: normal !important;
                            overflow-wrap: break-word !important;
                            word-break: normal !important;
                            box-sizing: border-box !important;
                        }

                        body.hr-memo-printing .memo-signatory>.memo-input {
                            display: block !important;
                            width: 245px !important;
                            max-width: 245px !important;
                            margin: 3px 0 0 0 !important;
                            padding: 0 !important;
                            font-size: 10pt !important;
                            line-height: 1.2 !important;
                            color: #000 !important;
                            text-transform: uppercase !important;
                        }

                        /* CONFORME */
                        body.hr-memo-printing .memo-conforme {
                            display: block !important;
                            width: 100% !important;
                            margin: 28px 0 0 0 !important;
                            padding: 0 !important;
                            break-inside: avoid !important;
                            page-break-inside: avoid !important;
                        }

                        body.hr-memo-printing .memo-conforme-label {
                            display: block !important;
                            margin: 0 0 25px 0 !important;
                            padding: 0 !important;
                            font-weight: 700 !important;
                            font-size: 10pt !important;
                            line-height: 1.2 !important;
                            color: #000 !important;
                        }

                        body.hr-memo-printing .memo-signature {
                            display: block !important;
                            width: 245px !important;
                            max-width: 245px !important;
                            margin: 0 !important;
                            padding: 0 0 2px 0 !important;
                            border-bottom: 1px solid #000 !important;
                            font-weight: 700 !important;
                            font-size: 10pt !important;
                            line-height: 1.2 !important;
                            color: #000 !important;
                            box-sizing: border-box !important;
                        }

                        body.hr-memo-printing .memo-position {
                            display: block !important;
                            width: 245px !important;
                            max-width: 245px !important;
                            margin: 3px 0 0 0 !important;
                            padding: 0 !important;
                            font-size: 10pt !important;
                            line-height: 1.2 !important;
                            color: #000 !important;
                            box-sizing: border-box !important;
                        }

                        /* PRINT PAGE BREAKS */
                        body.hr-memo-printing .memo-logo,
                        body.hr-memo-printing .memo-field,
                        body.hr-memo-printing .memo-signatory,
                        body.hr-memo-printing .memo-conforme {
                            break-inside: avoid !important;
                            page-break-inside: avoid !important;
                        }

                        /* FORCE BLACK TEXT AND WHITE BACKGROUND */
                        body.hr-memo-printing .memo-print-wrapper-sample,
                        body.hr-memo-printing .memo-print-wrapper-sample * {
                            color: #000 !important;
                            background: #fff !important;
                            box-sizing: border-box !important;
                        }
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
                    wire:click="saveMemoDraft"
                    wire:loading.attr="disabled"
                    wire:target="saveMemoDraft"
                    class="w-auto bg-orange-500 hover:bg-orange-600 text-white">

                    <span
                        wire:loading.remove
                        wire:target="saveMemoDraft">

                        Save Draft

                    </span>

                    <span
                        wire:loading
                        wire:target="saveMemoDraft">

                        Saving...

                    </span>

                </flux:button>


                {{-- ISSUE MEMO --}}
                <flux:button
                    variant="primary"
                    icon="paper-airplane"
                    wire:click="issueMemo"
                    wire:loading.attr="disabled"
                    wire:target="issueMemo"
                    class="w-auto">

                    <span
                        wire:loading.remove
                        wire:target="issueMemo">
                        Submit
                    </span>

                    <span
                        wire:loading
                        wire:target="issueMemo">

                        Issuing...

                    </span>

                </flux:button>

            </div>
        </div>
    </flux:modal>

    <div
        id="memo-print-test-{{ $assignment_id }}"
        class="memo-print-wrapper-sample">

        <div class="memo-form-sample">

            <div class="memo-logo">
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

            <div class="memo-content-separator2"></div>

            <div class="memo-content">
                <div class="memo-text-field">{{ strtoupper($memo_content ?? '') }}</div>
            </div>

            <div class="memo-signatory">

                <div class="memo-label">
                    SIGNATORY:
                </div>

                <div class="memo-signature1">
                    {{ strtoupper($signatory ?? '') }}
                </div>

                <div class="memo-input">
                    {{ strtoupper($signatory_position ?? '') }}
                </div>

            </div>

            <div class="memo-conforme">

                <div class="memo-conforme-label">
                    CONFORME:
                </div>

                <div class="memo-signature">
                    {{ strtoupper($full_name ?? '') }}
                </div>

                <div class="memo-position">
                    {{ strtoupper($position_name ?? '') }}
                </div>

            </div>

        </div>
    </div>

    <script>
        function HRprintMemo(memoId) {
            const memo = document.getElementById('memo-print-test-' + memoId);

            if (!memo) {
                console.error('Memo print element not found:', memoId);
                return;
            }

            document.body.classList.add('hr-memo-printing');

            window.print();

            setTimeout(() => {
                document.body.classList.remove('hr-memo-printing');
            }, 500);
        }
    </script>

</div>