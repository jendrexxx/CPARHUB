<div>

    <flux:modal
        name="lab-reassign-cpar"
        class="mt-7 w-full max-w-7xl top-0 z-50">

        <div class="border-b pb-4">
            <flux:heading size="lg">
                Assign CPAR
            </flux:heading>

            <flux:text class="mt-1">
                Review the CPAR details and assign the appropriate employee.
            </flux:text>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-8 lg:grid-cols-2">

            <div class="min-w-0">

                <div class="flex items-center justify-between border-b pb-3">
                    <div>
                        <flux:heading size="md">
                            CPAR Details
                        </flux:heading>

                        <flux:text>
                            View the CPAR request information below.
                        </flux:text>
                    </div>
                </div>

                <div class="mt-5 space-y-5">

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        <flux:input
                            label="CPAR No."
                            wire:model="cpar_no"
                            readonly
                            class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                        <flux:input
                            label="Date Opened"
                            wire:model="date_open"
                            readonly
                            class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                    </div>

                    <flux:input
                        label="Source Origin"
                        wire:model="source_name"
                        readonly
                        class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        <flux:input
                            label="Reported By"
                            wire:model="reported_by"
                            readonly
                            class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                        <flux:input
                            label="Department Name"
                            wire:model="department_name"
                            readonly
                            class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        <flux:input
                            label="Complainant Category"
                            wire:model="complain_name"
                            readonly
                            class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                        <flux:input
                            label="Complainant Name"
                            wire:model="complainant_name"
                            readonly
                            class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                    </div>

                    <flux:textarea
                        label="Concern Description"
                        wire:model="concern_description"
                        rows="4"
                        readonly
                        class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

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

                        <flux:input
                            label="Concern Category"
                            wire:model="concern_name"
                            readonly
                            class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                        <flux:input
                            label="Priority"
                            wire:model="priority"
                            readonly
                            class="cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800" />

                    </div>

                </div>

            </div>

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

                    <div class="flex items-end gap-2">

                        <div class="min-w-0 flex-1">

                            <flux:select
                                label="Assign To"
                                wire:model="assigned"
                                disabled>
                                <flux:select.option value="">
                                    -- Select Employee --
                                </flux:select.option>

                                @foreach ($employees as $employee)

                                <flux:select.option value="{{ $employee->id }}">
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </flux:select.option>

                                @endforeach

                            </flux:select>

                        </div>

                        <flux:button
                            type="button"
                            variant="primary"
                            icon="plus"
                            wire:click="addAssignee" />

                    </div>

                    @foreach ($new_assignees as $index => $assignee)

                    <div
                        wire:key="additional-assignee-{{ $index }}"
                        class="flex items-end gap-2">

                        <div class="min-w-0 flex-1">

                            <flux:select
                                label="Additional Assignee"
                                wire:model="new_assignees.{{ $index }}">

                                <flux:select.option value="">
                                    -- Select Employee --
                                </flux:select.option>

                                @foreach ($employees as $employee)

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
                            wire:click="removeAssignee({{ $index }})" />

                    </div>

                    @endforeach

                    <flux:textarea
                        label="Remarks"
                        wire:model="remarks"
                        rows="4"
                        placeholder="Enter assignment remarks..." />

                </div>

            </div>

        </div>

        <div class="mt-8 flex justify-end gap-2 border-t pt-4">

            <flux:button
                variant="ghost"
                x-on:click="$flux.modal('lab-reassign-cpar').close()">
                Cancel
            </flux:button>

            <flux:button
                variant="primary"
                icon="check"
                wire:click="LabAssigned"
                wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="LabAssigned">
                    Assign
                </span>

                <span wire:loading wire:target="LabAssigned">
                    Assigning...
                </span>
            </flux:button>

        </div>

    </flux:modal>

</div>