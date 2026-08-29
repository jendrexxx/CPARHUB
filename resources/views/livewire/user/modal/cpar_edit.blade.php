<div>
    <flux:modal
        name="EditCPARModal"
        class="w-full max-w-6xl">

        <div class="space-y-6">

            {{-- HEADER --}}
            <div>
                <flux:heading size="lg">
                    CPAR Details
                </flux:heading>

                <flux:text>
                    View CPAR request details below.
                </flux:text>
            </div>

            {{-- BASIC INFORMATION --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- CPAR NO --}}
                <flux:input
                    label="CPAR No."
                    wire:model="cpar_no"
                    readonly />

                {{-- DATE OPENED --}}
                <flux:input
                    label="Date Opened"
                    wire:model="date_open"
                    readonly />

            </div>

            {{-- SOURCE ORIGIN --}}
            <div>
                <flux:input
                    label="Source Origin"
                    wire:model="source_name"
                    readonly />
            </div>

            {{-- REPORTED BY --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <flux:input
                    label="Reported By"
                    wire:model="reported_by"
                    readonly />

                <flux:input
                    label="Department Name"
                    wire:model="department_name"
                    readonly />

            </div>

            {{-- COMPLAINANT --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <flux:input
                    label="Complainant Category"
                    wire:model="complain_name"
                    readonly />

                <flux:input
                    label="Complainant Name"
                    wire:model="complainant_name"
                    readonly />

            </div>

            {{-- CONCERN DESCRIPTION --}}
            <div>
                <flux:textarea
                    label="Concern Description"
                    wire:model="concern_description"
                    rows="5"
                    readonly />
            </div>

            {{-- ATTACHMENT + CONCERN CATEGORY + PRIORITY --}}
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
                            Download Attachment
                        </flux:button>
                    </div>

                    @else

                    <flux:text class="mt-2">
                        No attachment file
                    </flux:text>

                    @endif
                </div>

                {{-- CONCERN CATEGORY --}}
                <flux:input
                    label="Concern Category"
                    wire:model="concern_name"
                    readonly />

                {{-- PRIORITY --}}
                <flux:input
                    label="Priority"
                    wire:model="priority"
                    readonly />
            </div>
            {{-- FOOTER / CANCEL --}}
            <div class="flex justify-end gap-2 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button
                        type="button"
                        variant="danger"
                        icon="x-mark"
                        wire:click="cancelRequest({{ $assignment_id }})">
                        Cancel CPAR
                    </flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>
</div>