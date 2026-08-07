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

            <div>
                <flux:heading class="text-xl font-bold">
                    CPAR Information
                </flux:heading>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <flux:input
                        label="CPAR No."
                        wire:model="cpar_no"
                        disabled
                        class="opacity-60 cursor-not-allowed" />

                    <flux:input
                        label="Employee Assigned"
                        wire:model="employee_name"
                        disabled
                        class="opacity-60 cursor-not-allowed" />

                    <flux:input
                        label="Department"
                        wire:model="department_name"
                        disabled
                        class="opacity-60 cursor-not-allowed" />

                    <flux:input
                        label="Date Open"
                        wire:model="date_open"
                        disabled
                        class="opacity-60 cursor-not-allowed" />

                </div>
            </div>

            <div class="space-y-4">

                <flux:heading class="text-xl font-bold">
                    Investigation Details
                </flux:heading>

                <flux:textarea
                    label="Identified Cause"
                    wire:model="identified_cause"
                    disabled
                    class="opacity-60 cursor-not-allowed" />

                <flux:textarea
                    label="Provided Solution"
                    wire:model="provided_solution"
                    disabled
                    class="opacity-60 cursor-not-allowed" />

                <flux:textarea
                    label="Recommendation"
                    wire:model="recommendation"
                    disabled
                    class="opacity-60 cursor-not-allowed" />
            </div>

            <flux:separator />

            <div class="space-y-5">
                <div>
                    <flux:label class="text-xl font-bold">
                        HR Decision
                    </flux:label>
                </div>
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
                                wire:model="selectedCategories.{{ $index }}" disabled>

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
                                wire:model="selectedOffenseLevels.{{ $index }}" disabled>

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
                                wire:model="selectedHRDecisions.{{ $index }}" disabled>

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
                <flux:textarea
                    label="HR Decision Remarks"
                    wire:model="hr_decision_remarks"
                    rows="5" disabled
                    placeholder="Enter HR decision remarks..." />
            </div>
            <flux:separator />
            {{-- LAB Verification Action --}}
            <div class="flex justify-end gap-3 pt-4">
                <flux:button
                    variant="primary"
                    icon="check-circle"
                    wire:click="verifiedLaboratory">
                    Verified Laboratory
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>