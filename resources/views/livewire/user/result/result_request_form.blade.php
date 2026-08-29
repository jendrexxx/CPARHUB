<div>
    <flux class="max-w-5xl mx-auto">

        <div class="mb-6">
            <flux:heading size="xl" class="text-red-600">
                Result Concern
            </flux:heading>

            <flux:text class="mt-1">
                Report laboratory result concerns and initiate the corrective action process.
            </flux:text>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">

            {{-- CPAR No & Date --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input
                    label="Result No."
                    wire:model="result_no"
                    readonly
                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
                <flux:input
                    label="Date Reported"
                    wire:model="date_reported"
                    type="text"
                    readonly
                    class="opacity-60 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <flux:input
                    label="Reported By"
                    wire:model="reported_by"
                    class="opacity-60 cursor-not-allowed" readonly />
                <flux:input
                    label="Patient Name"
                    wire:model="patient_name" />
                <flux:input
                    label="Attending Physician"
                    wire:model="attending_physician"
                    type="text" />
                <flux:input
                    label="Actual Released Date"
                    wire:model="actual_released_date"
                    type="date" />
                <flux:select
                    wire:model="source_of_information"
                    label="Source of Information">
                    <option selected>Select Source</option>
                    @foreach ($source as $item)
                    <flux:select.option
                        :value="$item->id"
                        :label="$item->source_name" />
                    @endforeach
                </flux:select>
            </div>
            <!-- Test Procedure -->
            <flux:textarea
                label="Test Procedure"
                wire:model="test_procedure"
                rows="5" />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Data and Information Errors --}}
                <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">

                    <flux:label>Data and Information Errors</flux:label>

                    <div class="mt-4 ml-6 space-y-2">
                        <flux:checkbox.group wire:model="selectedData">
                            @foreach($data as $item)
                            <flux:checkbox
                                value="{{ $item->id }}"
                                label="{{ $item->data_name }}" />
                            @endforeach
                        </flux:checkbox.group>

                    </div>

                </div>

                {{-- Technical and Equipment Issues --}}
                <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">

                    <flux:label>Technical and Equipment Issues</flux:label>

                    <div class="mt-4 ml-6 space-y-2">
                        <flux:checkbox.group
                            wire:model.live="selectedTechnical">
                            @foreach($technical as $item)
                            <flux:checkbox
                                :value="$item->id"
                                :label="$item->technical_name" />
                            @endforeach
                        </flux:checkbox.group>
                    </div>
                </div>


                {{-- Quality and Accuracy Issues --}}
                <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">

                    <flux:label>Quality and Accuracy Issues</flux:label>

                    <div class="mt-4 ml-6 space-y-2">
                        <flux:checkbox.group
                            wire:model.live="selectedQuality">
                            @foreach($quality as $item)
                            <flux:checkbox
                                :value="$item->id"
                                :label="$item->quality_name" />
                            @endforeach
                        </flux:checkbox.group>
                    </div>
                </div>
            </div>

            {{-- Complainant --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <flux:select
                    wire:model.live="complain_category_id"
                    label="Complainant Category">
                    <option value="">Select</option>
                    @foreach ($result_complain as $result)
                    <option value="{{ $result->id }}">
                        {{ $result->complain_name }}
                    </option>
                    @endforeach
                </flux:select>

                <flux:input
                    wire:model="complain_name" value="{{ $complain_name }}"
                    label="Complainant Name" :readonly="$complain_name_disabled" />
                <div>
                    <flux:select
                        wire:model="priority"
                        label="Priority"
                        placeholder="Select Priority">
                        @foreach ($priority_level as $priority)
                        <flux:select.option value="{{ $priority->id }}">
                            {{ $priority->priority_name }}
                        </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

            </div>
            {{-- Concern Description --}}
            <flux:textarea
                label="Concern Description"
                wire:model="concern_description"
                rows="5" />

            {{-- Assigned To --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:select
                    wire:model.live="dept_head_assigned"
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