<flux:modal name="master-file-modal" class="w-full max-w-7xl">
    <div class="space-y-6">

        {{-- HEADER --}}
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                OTHERS Master File
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                View and manage complete RESULT/OTHERS master records.
            </p>
        </div>

        {{-- TWO-COLUMN LAYOUT --}}
        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-2">
            {{-- LEFT COLUMN: EMPLOYEE INFORMATION AND ATTACHMENTS --}}
            <div class="min-w-0 space-y-6">
                <section class="space-y-4 rounded-xl border-gray-200 p-4 dark:border-gray-700">

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <flux:input label="CPAR No." wire:model="cpar_no" readonly />
                        <flux:input label="Date Open" wire:model="date_open" readonly />
                    </div>

                    <flux:select
                        label="Reported By"
                        wire:model.live="reported_by"
                        placeholder="Select Reported By">
                        @foreach ($employees as $employee)
                        <flux:select.option
                            value="{{ trim($employee->first_name . ' ' . $employee->last_name) }}"
                            wire:key="reported-by-{{ $employee->id }}">
                            {{ trim($employee->first_name . ' ' . $employee->last_name) }}
                        </flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select
                        label="Source Origin"
                        wire:model.live="source_name"
                        placeholder="Select Source Origin">
                        @foreach ($source_origin as $source)
                        <flux:select.option
                            value="{{ $source->source_name }}"
                            wire:key="source-origin-{{ $source->id }}">
                            {{ $source->source_name }}
                        </flux:select.option>
                        @endforeach
                    </flux:select>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <flux:select
                            label="Complainant Category"
                            wire:model.live="complain_name"
                            placeholder="Select Complainant Category">
                            @foreach ($cpar_complain as $complain)
                            <flux:select.option
                                value="{{ $complain->complain_name }}"
                                wire:key="complain-category-{{ $complain->id }}">
                                {{ $complain->complain_name }}
                            </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:input label="Complainant Name" wire:model="complainant_name" :disabled="$complain_name_disabled" />
                    </div>


                    <div class="space-y-3">
                        <flux:label>Concern Attachment (Optional)</flux:label>

                        <flux:input
                            type="file"
                            wire:model="concern_attachment"
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" />

                        @error('concern_attachment')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div
                            wire:loading
                            wire:target="concern_attachment"
                            class="text-sm text-blue-600">
                            Uploading attachment...
                        </div>

                        @if ($existing_concern_attachment)
                        @php
                        $attachmentPath = preg_replace(
                        '#^storage/#',
                        '',
                        $existing_concern_attachment
                        );
                        @endphp

                        <div class="flex items-center justify-between gap-3 rounded-lg bg-gray-50 p-3 dark:bg-zinc-800">
                            <span class="min-w-0 truncate text-sm">
                                {{ basename($attachmentPath) }}
                            </span>

                            <a
                                href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($attachmentPath) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="shrink-0 text-sm font-medium text-blue-600 hover:underline">
                                View / Download
                            </a>
                        </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <flux:select
                            label="Concern Category"
                            wire:model.live="concern_name"
                            placeholder="Select Concern Category"
                            required>
                            @foreach ($cpar_concern as $concern)
                            <flux:select.option
                                value="{{ $concern->concern_name }}"
                                wire:key="concern-category-{{ $concern->id }}">
                                {{ $concern->concern_name }}
                            </flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:select
                            label="Priority"
                            wire:model.live="priority"
                            placeholder="Select Priority"
                            required>
                            @foreach ($priority_level as $priority)
                            <flux:select.option
                                value="{{ $priority->id }}"
                                wire:key="priority-level-{{ $priority->id }}">
                                {{ $priority->priority_name }}
                            </flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>

                    <flux:textarea
                        label="Concern Description"
                        wire:model="concern_description"
                        rows="4" />


                    <div class="mt-4 space-y-2">
                        <div class="flex items-center justify-between gap-3">
                            <flux:label>NTE Attachment</flux:label>

                            <flux:badge color="blue" variant="pill">
                                HR SENT TO USER
                            </flux:badge>
                        </div>

                        @if (!empty($current_nte_attachment))
                        <div class="flex min-w-0 items-center justify-between gap-3 rounded-lg bg-gray-50 p-3 dark:bg-zinc-800">
                            <span class="min-w-0 truncate text-sm">
                                {{ basename($current_nte_attachment) }}
                            </span>

                            <a
                                href="{{ asset('storage/' . ltrim($current_nte_attachment, '/')) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="shrink-0 text-sm font-medium text-blue-600 hover:underline">
                                View Existing NTE Attachment
                            </a>
                        </div>
                        @endif

                        <flux:input
                            type="file"
                            wire:model="nte_attachment"
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" />

                        <flux:description>
                            Upload a new file to replace the existing NTE attachment.
                        </flux:description>

                        @error('nte_attachment')
                        <flux:text color="red" size="sm">
                            {{ $message }}
                        </flux:text>
                        @enderror
                    </div>


                    <div class="mt-4 space-y-2">
                        <div class="flex items-center justify-between gap-3">
                            <flux:label>User NTE Response Attachment</flux:label>

                            <flux:badge color="amber" variant="pill">
                                USER RESPONSE TO NTE
                            </flux:badge>
                        </div>

                        @if (!empty($current_nte_attachment))
                        <div class="flex min-w-0 items-center justify-between gap-3 rounded-lg bg-gray-50 p-3 dark:bg-zinc-800">
                            <span class="min-w-0 truncate text-sm">
                                {{ basename($current_nte_attachment) }}
                            </span>

                            <a
                                href="{{ asset('storage/' . preg_replace('#^storage/#', '', ltrim($current_nte_attachment, '/'))) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="shrink-0 text-sm font-medium text-blue-600 hover:underline">
                                View Existing Response
                            </a>
                        </div>
                        @endif

                        <flux:input
                            type="file"
                            wire:model="nte_attachment"
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" />

                        <flux:description>
                            Upload your response to the NTE. Uploading a new file will replace the existing response attachment.
                        </flux:description>

                        @error('nte_attachment')
                        <flux:text color="red" size="sm">
                            {{ $message }}
                        </flux:text>
                        @enderror

                        <div
                            wire:loading
                            wire:target="nte_attachment"
                            class="text-sm text-blue-600">
                            Uploading response attachment...
                        </div>
                    </div>


                    <flux:field>
                        <flux:label>Status</flux:label>

                        <flux:select
                            wire:model="status"
                            placeholder="Select status">
                            @foreach ($statuses as $item)
                            <flux:select.option value="{{ $item->id }}">
                                {{ $item->status_name }}
                            </flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:error name="status" />
                    </flux:field>

                </section>
            </div>
            {{-- RIGHT COLUMN: ASSIGNMENT, INVESTIGATION AND DECISIONS --}}
            <div class="min-w-0 space-y-6">
                {{-- ASSIGNMENT --}}
                <section class="space-y-4 rounded-xl border-gray-200 dark:border-gray-700">
                    <flux:select
                        label="Assign To"
                        wire:model="assigned_to"
                        placeholder="Select Employee">
                        @foreach ($employees as $employee)
                        <flux:select.option
                            value="{{ $employee->id }}"
                            wire:key="assigned-to-{{ $employee->id }}">
                            {{ trim($employee->first_name . ' ' . $employee->last_name) }}
                        </flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:textarea
                        label="Assignment Remarks"
                        wire:model="assigned_remarks"
                        rows="4" />
                </section>
                {{-- INVESTIGATION --}}
                <section class="space-y-4 rounded-xl border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="font-bold">Investigation Details</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Investigation findings and recommended actions.
                        </p>
                    </div>

                    <flux:textarea
                        label="Identified Cause"
                        wire:model="identified_cause"
                        rows="4" />

                    <flux:textarea
                        label="Provided Solution"
                        wire:model="provided_solution"
                        rows="4" />

                    <flux:textarea
                        label="Recommendation"
                        wire:model="recommendation"
                        rows="4" />


                    <div class="space-y-3">
                        <flux:field>
                            <flux:label>IR Attachment</flux:label>

                            <flux:input
                                type="file"
                                wire:model="ir_attachment"
                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" />

                            <flux:error name="ir_attachment" />

                            <div
                                wire:loading
                                wire:target="ir_attachment"
                                class="text-sm text-blue-600">
                                Uploading attachment...
                            </div>

                            @if (!empty($existing_ir_attachment))
                            <div class="flex min-w-0 items-center justify-between gap-3 rounded-lg bg-gray-50 p-3 dark:bg-zinc-800">
                                <span class="min-w-0 truncate text-sm">
                                    {{ basename($existing_ir_attachment) }}
                                </span>

                                <a
                                    href="{{ asset('storage/' . ltrim($existing_ir_attachment, '/')) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="shrink-0 text-sm font-medium text-blue-600 hover:underline">
                                    View / Download
                                </a>
                            </div>
                            @endif

                            @if ($ir_attachment)
                            <p class="break-all text-sm text-green-600">
                                New file selected:
                                {{ $ir_attachment->getClientOriginalName() }}
                            </p>
                            @endif
                        </flux:field>
                    </div>

                </section>

                {{-- COMPLETION INFORMATION --}}
                <section class="space-y-4 rounded-xl border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <flux:input
                            label="Action Taken By"
                            wire:model="action_taken_by"
                            readonly />

                        <flux:input
                            label="Date Completed"
                            wire:model="date_completed"
                            readonly />
                    </div>

                    <flux:input
                        label="TAT"
                        wire:model="tat"
                        readonly />

                    <flux:textarea
                        label="Department Head Remarks"
                        wire:model="head_remarks"
                        rows="5" />
                </section>
            </div>
        </div>

        <div class="border-t border-zinc-200 pt-6 dark:border-zinc-700">
            <div class="mb-6">
                <flux:heading size="lg">HR Decision</flux:heading>
                <flux:text class="mt-1 text-sm text-zinc-500">
                    Select the applicable disciplinary category, offense level, and HR action.
                </flux:text>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- HR DECISION --}}
                <div class="space-y-3">

                    <flux:heading size="sm">
                        HR Decision
                    </flux:heading>

                    @foreach($selectedHRDecisions as $index => $decision)
                    <div class="flex items-end gap-2">

                        {{-- HR DECISION --}}
                        <div class="flex-1">

                            <flux:select
                                wire:model.live="selectedHRDecisions.{{ $index }}">

                                <flux:select.option value="">
                                    -- Select HR Decision --
                                </flux:select.option>

                                @foreach($decisionCategories as $item)

                                <flux:select.option value="{{ $item->id }}">
                                    {{ $item->decision_name }}
                                </flux:select.option>

                                @endforeach

                            </flux:select>

                        </div>

                        {{-- ADD --}}
                        @if($index == count($selectedHRDecisions) - 1)

                        <flux:button
                            type="button"
                            variant="primary"
                            icon="plus"
                            square
                            wire:click="addCategory" />

                        @endif

                        {{-- REMOVE --}}
                        @if(count($selectedHRDecisions) > 1)

                        <flux:button
                            type="button"
                            variant="danger"
                            icon="minus"
                            square
                            wire:click="removeCategory({{ $index }})" />

                        @endif

                    </div>
                    @endforeach
                </div>

                {{-- DISCIPLINARY CATEGORY --}}
                <div class="space-y-3">

                    <flux:heading size="sm">
                        Disciplinary Category
                    </flux:heading>

                    @foreach($selectedCategories as $index => $category)

                    <div class="flex items-end gap-2">

                        <div class="flex-1">

                            <flux:select
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

                        </div>
                    </div>

                    @endforeach

                </div>


                {{-- OFFENSE LEVEL --}}
                <div class="space-y-3">

                    <flux:heading size="sm">
                        Offense Level
                    </flux:heading>

                    @foreach($selectedOffenseLevels as $index => $offense)

                    <div class="flex items-end gap-2">

                        <div class="flex-1">

                            <flux:select
                                wire:model="selectedOffenseLevels.{{ $index }}">

                                <flux:select.option value="">
                                    -- Select Offense Level --
                                </flux:select.option>

                                @foreach($offenseLevels as $level)

                                <flux:select.option value="{{ $level->id }}">
                                    {{ $level->offense_name }}
                                </flux:select.option>

                                @endforeach

                            </flux:select>

                        </div>

                        <div class="w-9 shrink-0"></div>

                        @if(count($selectedOffenseLevels) > 1)
                        <div class="w-9 shrink-0"></div>
                        @endif

                    </div>

                    @endforeach

                </div>

            </div>

            {{-- VALIDATION --}}
            @error('rows')
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600 dark:border-red-900 dark:bg-red-950/30">
                {{ $message }}
            </div>
            @enderror

            <div class="mt-5">
                <flux:field>
                    <flux:label>HR Decision Remarks</flux:label>

                    <flux:textarea
                        wire:model="hr_decision_remarks"
                        rows="4"
                        placeholder="ENTER HR DECISION REMARKS..." />

                    <flux:error name="hr_decision_remarks" />
                </flux:field>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="flex justify-end gap-3 border-t border-gray-200 pt-5 dark:border-gray-700">
            <flux:button
                type="button"
                variant="ghost"
                x-on:click="$dispatch('modal-close', { name: 'master-file-modal' })">
                Cancel
            </flux:button>

            <flux:button
                type="button"
                variant="primary"
                wire:click="update"
                wire:loading.attr="disabled"
                wire:target="update">
                <span wire:loading.remove wire:target="update">
                    Update
                </span>

                <span wire:loading wire:target="update">
                    Updating...
                </span>
            </flux:button>
        </div>

    </div>
</flux:modal>