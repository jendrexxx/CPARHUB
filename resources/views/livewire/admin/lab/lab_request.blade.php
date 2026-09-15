<div>
    <flux:modal name="LABrequest" class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">
        <div class="space-y-6">
            <div>
                <flux:heading size="xl" class="font-bold">
                    LAB Acknowledgement
                </flux:heading>

                <flux:text class="mt-2 text-base text-zinc-500">
                    Review CPARs that are pending for LAB acknowledgement.
                </flux:text>
            </div>

            <flux:separator />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="space-y-5">

                    <div>
                        <flux:label class="text-xl font-bold">
                            CPAR Information
                        </flux:label>

                        <flux:text class="mt-1">
                            Review the basic information of this CPAR.
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


                    {{-- REPORTED BY + DEPARTMENT --}}
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


                    {{-- SOURCE --}}
                    <flux:input
                        label="Source Origin"
                        wire:model="source_name"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


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


                    {{-- CONCERN DESCRIPTION --}}
                    <flux:textarea
                        label="Concern Description"
                        wire:model="concern_description"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    {{-- CATEGORY + STATUS --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <flux:label>
                                Attachment
                            </flux:label>

                            @if ($attachment)
                            <div class="mt-2">

                                <flux:button
                                    icon="arrow-down-tray"
                                    variant="primary"
                                    size="sm"
                                    href="{{ Storage::url($attachment) }}"
                                    target="_blank">

                                    Download Attachment

                                </flux:button>

                            </div>
                            @else
                            <div class="mt-2 rounded-lg border border-zinc-200 bg-zinc-100 px-3 py-2.5 dark:border-zinc-700 dark:bg-zinc-800">

                                <flux:text>
                                    No attachment available.
                                </flux:text>

                            </div>
                            @endif

                        </div>
                        <flux:input label="Concern Category" wire:model="concern_name" readonly class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:input
                            label="Reported Employee"
                            wire:model="employee_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                        <flux:input
                            label="Department"
                            wire:model="assigned_department_head"
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
                            Review the investigation findings and actions taken.
                        </flux:text>
                    </div>


                    {{-- IDENTIFIED CAUSE --}}
                    <flux:textarea
                        label="Identified Cause"
                        wire:model="identified_cause"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- PROVIDED SOLUTION --}}
                    <flux:textarea
                        label="Provided Solution"
                        wire:model="provided_solution"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- RECOMMENDATION --}}
                    <flux:textarea
                        label="Recommendation"
                        wire:model="recommendation"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- ACTION + DATE + TAT --}}
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
            <div class="lg:col-span-2 min-w-0">
                <div class="border-t border-zinc-200 pt-6 dark:border-zinc-700">
                    <flux:textarea
                        label="Dept Head Remarks"
                        wire:model="head_remarks"
                        rows="5"
                        readonly
                        class="w-full opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                </div>
            </div>
            <flux:separator />

            <div class="space-y-5">
                <div>
                    <flux:label class="text-xl font-bold">
                        Review HR Decision
                    </flux:label>
                    <flux:text class="mt-1">
                        Review the hr decision action, and supporting information before proceeding.
                    </flux:text>
                </div>
                <div class="space-y-3">
                    <flux:label>
                        Supporting Documents
                    </flux:label>
                    @if($ir_id)
                    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                        <div class="space-y-3">
                            <div>
                                <div class="font-medium text-zinc-900 dark:text-white">
                                    Incident Report (IR)
                                </div>
                                <div class="text-sm text-zinc-500">
                                    IR Document Attachment
                                </div>
                            </div>
                            @if($ir_attachment)
                            <div class="flex gap-3 items-center text-sm">
                                {{-- VIEW --}}
                                <a
                                    href="{{ asset('storage/'.$ir_attachment) }}"
                                    target="_blank"
                                    class="inline-flex items-center px-3 py-2 rounded-md
                                    bg-blue-100 text-blue-700
                                    hover:bg-blue-200">
                                    View IR PDF
                                </a>
                                {{-- DOWNLOAD --}}
                                <a
                                    href="{{ asset('storage/'.$ir_attachment) }}"
                                    download
                                    class="inline-flex items-center px-3 py-2 rounded-md
                                    bg-green-100 text-green-700
                                    hover:bg-green-200">
                                    Download IR PDF
                                </a>


                            </div>


                            @else

                            <span class="text-sm text-zinc-500">
                                No IR attachment available.
                            </span>


                            @endif



                        </div>


                    </div>
                    @endif
                    @if($nte_id)
                    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">

                        <div class="space-y-3">

                            <div>
                                <div class="font-medium text-zinc-900 dark:text-white">
                                    Notice to Explain (NTE)
                                </div>

                                <div class="text-sm text-zinc-500">
                                    NTE Document Attachment
                                </div>
                            </div>


                            @if($nte_attachment)

                            <div class="flex gap-3 items-center text-sm">
                                {{-- VIEW --}}
                                <a
                                    href="{{ asset('storage/'.$nte_attachment) }}"
                                    target="_blank"
                                    class="inline-flex items-center px-3 py-2 rounded-md
                                    bg-blue-100 text-blue-700
                                    hover:bg-blue-200">
                                    View NTE PDF
                                </a>
                                {{-- DOWNLOAD --}}
                                <a
                                    href="{{ asset('storage/'.$nte_attachment) }}"
                                    download
                                    class="inline-flex items-center px-3 py-2 rounded-md
                                    bg-green-100 text-green-700
                                    hover:bg-green-200">
                                    Download NTE PDF
                                </a>
                            </div>
                            @else
                            <span class="text-sm text-zinc-500">
                                No NTE attachment available.
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- ========================================================= --}}
                    {{-- HR DECISION --}}
                    {{-- ========================================================= --}}
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

                <flux:textarea
                    label="HR Decision Remarks"
                    wire:model="hr_decision_remarks"
                    rows="5" disabled
                    placeholder="Enter HR decision remarks..." />
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


                {{-- REMARKS --}}
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
            {{-- LAB Verification Action --}}
            <div class="flex justify-end items-center gap-3 pt-4">
                {{-- BACK TO HR --}}
                <flux:button
                    variant="primary"
                    color="orange"
                    icon="arrow-uturn-left"
                    wire:click="backToHR"
                    wire:loading.attr="disabled"
                    wire:target="backToHR">

                    <span wire:loading.remove wire:target="backToHR">
                        Revise HR Decision
                    </span>

                    <span wire:loading wire:target="backToHR">
                        Returning for Revision...
                    </span>

                </flux:button>


                {{-- SUBMIT FOR HR REVIEW --}}
                <flux:button
                    variant="primary"
                    icon="paper-airplane"
                    wire:click="verifiedLaboratory"
                    wire:loading.attr="disabled"
                    wire:target="verifiedLaboratory">

                    <span wire:loading.remove wire:target="verifiedLaboratory">
                        Submit
                    </span>

                    <span wire:loading wire:target="verifiedLaboratory">
                        Submitting...
                    </span>

                </flux:button>

            </div>
        </div>
    </flux:modal>
</div>