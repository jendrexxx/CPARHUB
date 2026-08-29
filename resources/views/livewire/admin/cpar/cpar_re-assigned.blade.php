<div>

    <flux:modal
        name="reassign-cpar"
        class="w-full max-w-7xl mt-7 top-0 z-50">

        <div class="border-b pb-4">
            <flux:heading size="lg">
                Assign CPAR
            </flux:heading>

            <flux:text class="mt-1">
                Review the CPAR details and assign the appropriate employee.
            </flux:text>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-6">

            <div class="min-w-0">
                {{-- HEADER --}}
                <div
                    class="flex items-center justify-between cursor-pointer border-b pb-3">

                    <div>
                        <flux:heading size="md">
                            CPAR Details
                        </flux:heading>

                        <flux:text>
                            View the CPAR request information below.
                        </flux:text>
                    </div>
                </div>


                {{-- CPAR DETAILS CONTENT --}}
                <div class="mt-5 space-y-5">

                    {{-- CPAR NO + DATE --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <flux:input
                            label="CPAR No."
                            wire:model="cpar_no"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Date Opened"
                            wire:model="date_open"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    </div>


                    {{-- SOURCE ORIGIN --}}
                    <flux:input
                        label="Source Origin"
                        wire:model="source_name"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                    {{-- REPORTED BY + DEPARTMENT --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <flux:input
                            label="Reported By"
                            wire:model="reported_by"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        <flux:input
                            label="Department Name"
                            wire:model="department_name"
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


                    {{-- CONCERN DESCRIPTION --}}
                    <flux:textarea
                        label="Concern Description"
                        wire:model="concern_description"
                        rows="4"
                        readonly
                        class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />


                    {{-- ATTACHMENT + CATEGORY + STATUS --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        {{-- ATTACHMENT --}}
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

                        {{-- CONCERN CATEGORY --}}
                        <flux:input
                            label="Concern Category"
                            wire:model="concern_name"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

                        {{-- STATUS --}}
                        <flux:input
                            label="Priority"
                            wire:model="priority"
                            readonly
                            class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                    </div>

                </div>

            </div>


            {{-- =========================
                RIGHT SIDE - ASSIGNMENT
            ========================== --}}
            <div class="min-w-0">

                <div class="border-b pb-3">

                    <flux:heading size="md">
                        Assignment
                    </flux:heading>

                    <flux:text>
                        Select the employee responsible for handling this CPAR.
                    </flux:text>

                </div>


                <div class="mt-5 space-y-5">

                    {{-- MAIN ASSIGN --}}
                    <div class="flex items-end gap-2">

                        <div class="flex-1 min-w-0">

                            <flux:select
                                label="Assign To"
                                wire:model="assigned">

                                <flux:select.option value="">
                                    -- Select Employee --
                                </flux:select.option>

                                @foreach($employees as $employee)

                                <flux:select.option value="{{ $employee->id }}">
                                    {{ $employee->first_name }}
                                    {{ $employee->last_name }}
                                </flux:select.option>

                                @endforeach

                            </flux:select>

                        </div>

                        <flux:button
                            type="button"
                            variant="primary"
                            icon="plus"
                            wire:click="addAssignee">
                        </flux:button>

                    </div>


                    {{-- ADDITIONAL ASSIGNEES --}}
                    @foreach(($new_assignees ?? []) as $index => $value)

                    <div class="flex items-end gap-2">

                        <div class="flex-1 min-w-0">

                            <flux:select
                                label="Additional Assignee"
                                wire:model="new_assignees.{{ $index }}">

                                <flux:select.option value="">
                                    -- Select Employee --
                                </flux:select.option>

                                @foreach($employees as $employee)

                                <flux:select.option value="{{ $employee->id }}">
                                    {{ $employee->first_name }}
                                    {{ $employee->last_name }}
                                </flux:select.option>

                                @endforeach

                            </flux:select>

                        </div>

                        <flux:button
                            type="button"
                            variant="danger"
                            icon="minus"
                            wire:click="removeAssignee({{ $index }})">
                        </flux:button>

                    </div>

                    @endforeach


                    {{-- REMARKS --}}
                    <flux:textarea
                        label="Remarks"
                        wire:model="remarks"
                        rows="4"
                        placeholder="Enter assignment remarks..." />

                </div>

            </div>

        </div>

        <div class="flex justify-end gap-2 mt-8 border-t pt-4">

            <flux:button
                variant="ghost"
                x-on:click="$flux.modal('reassign-cpar').close()">

                Cancel

            </flux:button>

            <flux:button
                variant="primary"
                icon="check"
                wire:click="save">

                Assign

            </flux:button>

        </div>

    </flux:modal>

</div>