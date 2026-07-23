<div>
    <flux class="max-w-5xl mx-auto">

        <div class="mb-6">
            <flux:heading size="xl" class="text-red-600">
                CPAR Request Form
            </flux:heading>

            <flux:text class="mt-1">
                Corrective and Preventive Action Request
            </flux:text>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">

            {{-- CPAR No & Date --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <flux:input
                    label="CPAR No."
                    wire:model="cpar_no"
                    readonly
                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                <flux:input
                    label="Date Opened"
                    wire:model="date_opened"
                    type="text"
                    readonly
                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
            </div>

            {{-- Source Origin --}}
            <flux:select wire:model="source_origin_id" label="Source Origin">
                <option>Select Source</option>
                @foreach ($source_origin as $origin)
                <option value="{{ $origin->id }}">
                    {{ $origin->source_name }}
                </option>
                @endforeach
            </flux:select>

            {{-- Reported By --}}
            <flux:input
                label="Reported By"
                wire:model="reported_by"
                placeholder="Juan Dela Cruz"
                readonly
                class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />

            {{-- Complainant --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:select
                    wire:model.live="complain_category_id"
                    label="Complainant Category">
                    <option value="">Select</option>
                    @foreach ($cpar_complain as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->complain_name }}
                    </option>
                    @endforeach
                </flux:select>

                <flux:input
                    wire:model="complain_name" value="{{ $complain_name }}"
                    label="Complainant Name" :readonly="$complain_name_disabled" />
            </div>

            {{-- Concern Description --}}
            <flux:textarea
                label="Concern Description"
                wire:model="concern_description"
                rows="5" />

            {{-- Attachment --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <flux:field>
                        <flux:label>Attachment</flux:label>
                        <input
                            wire:key="attachment-input"
                            type="file"
                            wire:model="attachment"
                            class="block w-full text-sm text-zinc-900 dark:text-zinc-100
                       border border-zinc-300 dark:border-zinc-700 rounded-lg
                       cursor-pointer bg-zinc-50 dark:bg-zinc-800
                       focus:outline-none file:mr-4 file:py-2 file:px-4
                       file:rounded-lg file:border-0 file:bg-zinc-200
                       dark:file:bg-zinc-700 file:text-sm file:font-medium
                       hover:file:bg-zinc-300 dark:hover:file:bg-zinc-600" />
                        <flux:error name="attachment" />
                    </flux:field>
                </div>

                {{-- Concern Category --}}
                <flux:select
                    wire:model.live="concern_category_id"
                    label="Concern Category">
                    <option value="">Select</option>
                    @foreach ($cpar_concern as $concern)
                    <option value="{{ $concern->id }}">
                        {{ $concern->concern_name }}
                    </option>
                    @endforeach
                </flux:select>
            </div>

            {{-- Assigned To --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:select
                    wire:model.live="assigned_to"
                    label="Assigned To">
                    <option value="">Select Employee</option>
                    @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}">
                        {{ strtoupper($employee->first_name . ' ' . $employee->last_name) }}
                    </option>
                    @endforeach
                </flux:select>

                <flux:input
                    wire:model="department_name"
                    label="Department Name" value="{{ $department_name }}"
                    readonly />
            </div>

            <div class="flex justify-end gap-3">
                <flux:button
                    type="submit"
                    variant="primary">
                    Submit Request
                </flux:button>

            </div>
        </form>
    </flux>
</div>