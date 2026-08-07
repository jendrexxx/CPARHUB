<div>
    <flux:modal
        name="hr-decision-cpar"
        class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">
        <div class="space-y-6">
            <div>
                <flux:label class="text-xl font-bold">
                    HR Decision
                </flux:label>

                <flux:text class="mt-2 text-base text-zinc-500">
                    Review CPARs that are pending for HR decision.
                </flux:text>
            </div>

            <flux:separator />

            <div>
                <flux:heading class="text-xl font-bold">
                    CPAR Information
                </flux:heading>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <flux:input
                        label="CPAR No."
                        wire:model="cpar_no"
                        readonly />

                    <flux:input
                        label="Employee Assigned"
                        wire:model="employee_name"
                        readonly />

                    <flux:input
                        label="Department"
                        wire:model="department_name"
                        readonly />

                    <flux:input
                        label="Date Open"
                        wire:model="date_open"
                        readonly />

                </div>
            </div>

            <div class="space-y-4">

                <flux:heading class="text-xl font-bold">
                    Investigation Details
                </flux:heading>

                <flux:textarea
                    label="Identified Cause"
                    wire:model="identified_cause"
                    readonly />

                <flux:textarea
                    label="Provided Solution"
                    wire:model="provided_solution"
                    readonly />

                <flux:textarea
                    label="Recommendation"
                    wire:model="recommendation"
                    readonly />
            </div>

            <flux:separator />

            <div class="space-y-5">
                <div>
                    <flux:label class="text-xl font-bold">
                        HR Decision
                    </flux:label>

                    <flux:text class="mt-2 text-base text-zinc-500">
                        Select the applicable disciplinary category and HR action.
                    </flux:text>
                </div>
                {{-- =====================================================
                    SUPPORTING DOCUMENTS
                ====================================================== --}}
                <div class="space-y-3">

                    <flux:label>
                        Supporting Documents
                    </flux:label>

                    @if($nte_id)
                    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                        <div class="space-y-3">

                            <div>
                                <div class="font-medium text-zinc-900 dark:text-white">
                                    Notice to Explain (NTE)
                                </div>

                                <div class="text-sm text-zinc-500">
                                    Upload NTE document (PDF Only).
                                </div>
                            </div>


                            {{-- Display Old File --}}
                            @if($current_nte_attachment)

                            <div class="text-sm">
                                Current Attachment:

                                <a
                                    href="{{ asset('storage/' . $current_nte_attachment) }}"
                                    target="_blank"
                                    class="text-blue-600 underline">

                                    View NTE PDF
                                </a>

                            </div>

                            @endif


                            {{-- Upload New File --}}
                            <flux:input
                                type="file"
                                wire:model="nte_attachment"
                                accept="application/pdf" />


                            @error('nte_attachment')
                            <span class="text-sm text-red-600">
                                {{ $message }}
                            </span>
                            @enderror


                        </div>
                    </div>
                    @endif

                    @if($ir_id)
                    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">

                        <div class="space-y-3">

                            <div>
                                <div class="font-medium text-zinc-900 dark:text-white">
                                    Incident Report (IR)
                                </div>

                                <div class="text-sm text-zinc-500">
                                    Upload IR document (PDF Only).
                                </div>
                            </div>

                            <flux:input
                                type="file"
                                wire:model="ir_attachment"
                                accept="application/pdf" />

                            @error('ir_attachment')
                            <span class="text-sm text-red-600">
                                {{ $message }}
                            </span>
                            @enderror


                        </div>

                    </div>
                    @endif

                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- DISCIPLINARY CATEGORY --}}
                    <div class="space-y-3">

                        <flux:label>
                            Disciplinary Category
                        </flux:label>
                        @foreach($selectedCategories as $index => $category)
                        <div class="flex items-center gap-2">
                            <flux:select
                                class="flex-1"
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

                            {{-- Add --}}
                            @if($index == count($selectedCategories)-1)
                            <button
                                type="button"
                                wire:click="addCategory"
                                class="h-9 w-9 rounded-lg bg-zinc-800 text-white flex items-center justify-center">
                                +
                            </button>

                            @endif

                            {{-- Remove --}}
                            @if(count($selectedCategories) > 1)

                            <button
                                type="button"
                                wire:click="removeCategory({{ $index }})"
                                class="h-9 w-9 rounded-lg bg-red-600 text-white flex items-center justify-center">
                                -
                            </button>

                            @endif

                        </div>
                        @endforeach

                    </div>
                    {{-- OFFENSE LEVEL --}}
                    <div class="space-y-3">

                        <flux:label>
                            Offense Level
                        </flux:label>

                        @foreach($selectedOffenseLevels as $index => $offense)

                        <div class="flex items-center gap-2">

                            <flux:select
                                class="flex-1"
                                wire:model="selectedOffenseLevels.{{ $index }}">

                                <option value="">
                                    -- Select Offense Level --
                                </option>

                                @foreach($offenseLevels as $level)

                                <option value="{{ $level->id }}">
                                    {{ $level->offense_name }}
                                </option>

                                @endforeach

                            </flux:select>

                            {{-- Placeholder para pantay ang alignment --}}
                            <div class="flex gap-2">
                                <div class="h-9 w-9"></div>

                                @if(count($selectedOffenseLevels) > 1)
                                <div class="h-9 w-9"></div>
                                @endif
                            </div>

                        </div>

                        @endforeach

                    </div>
                    {{-- HR DECISION --}}
                    <div class="space-y-3">

                        <flux:label>
                            HR Decision
                        </flux:label>

                        @foreach($selectedHRDecisions as $index => $decision)

                        <div class="flex items-center gap-2">

                            <flux:select
                                class="flex-1"
                                wire:model="selectedHRDecisions.{{ $index }}">

                                <option value="">
                                    -- Select HR Decision --
                                </option>

                                @foreach($decisionCategories as $item)

                                <option value="{{ $item->id }}">
                                    {{ $item->decision_name }}
                                </option>

                                @endforeach

                            </flux:select>

                            {{-- Placeholder para pantay ang alignment --}}
                            <div class="flex gap-2">
                                <div class="h-9 w-9"></div>

                                @if(count($selectedHRDecisions) > 1)
                                <div class="h-9 w-9"></div>
                                @endif
                            </div>

                        </div>

                        @endforeach

                    </div>
                </div>

                {{-- Validation Message --}}
                @error('rows')
                <div class="mt-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-600">
                    {{ $message }}
                </div>
                @enderror

                {{-- =====================================================
                    HR REMARKS
                ====================================================== --}}
                <flux:textarea
                    label="HR Decision Remarks"
                    wire:model="hr_decision_remarks"
                    rows="5"
                    placeholder="Enter HR decision remarks..." />
            </div>
            <flux:separator />
            <div class="flex justify-end gap-2">

                {{-- SAVE DRAFT --}}
                <flux:button
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
                    variant="primary"
                    icon="lock-closed"
                    wire:click="saveHRDecision"
                    wire:loading.attr="disabled"
                    wire:target="saveHRDecision">

                    <span wire:loading.remove wire:target="saveHRDecision">
                        Save HR Decision
                    </span>

                    <span wire:loading wire:target="saveHRDecision">
                        Saving...
                    </span>

                </flux:button>

            </div>
        </div>
    </flux:modal>
</div>