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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <flux:input
                    label="Reported By"
                    wire:model="reported_by"
                    class="opacity-60 cursor-not-allowed" readonly />
                <flux:input
                    label="Patient Name"
                    wire:model="patient_name"
                    class="uppercase"
                    x-on:input="$el.value = $el.value.toUpperCase(); $wire.set('patient_name', $el.value)" />

                <flux:input
                    label="Attending Physician"
                    wire:model="attending_physician"
                    type="text"
                    class="uppercase"
                    x-on:input="$el.value = $el.value.toUpperCase(); $wire.set('attending_physician', $el.value)" />
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
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                {{-- Test Procedure - 8/12 --}}
                <div class="md:col-span-8">
                    <flux:input
                        label="Test Procedure"
                        wire:model="test_procedure"
                        class="uppercase"
                        x-on:input="$el.value = $el.value.toUpperCase(); $wire.set('test_procedure', $el.value)" />
                </div>

                {{-- Actual Released Date - 4/12 --}}
                <div
                    class="md:col-span-4"
                    x-data="{
                        date: @entangle('actual_released_date'),

                        isSunday(date) {
                            if (!date) return false;

                            const selectedDate = new Date(date + 'T00:00:00');
                            return selectedDate.getDay() === 0;
                        },

                        validateDate(event) {
                            const value = event.target.value;

                            if (!value) {
                                this.date = '';
                                return;
                            }

                            // Prevent future dates
                            const today = new Date();

                            const todayString =
                                today.getFullYear() + '-' +
                                String(today.getMonth() + 1).padStart(2, '0') + '-' +
                                String(today.getDate()).padStart(2, '0');

                            if (value > todayString) {
                                alert('Future dates are not allowed.');

                                event.target.value = todayString;
                                this.date = todayString;

                                return;
                            }

                            // Prevent Sunday
                            if (this.isSunday(value)) {
                                alert('Sunday is not allowed.');

                                event.target.value = '';
                                this.date = '';

                                return;
                            }

                            this.date = value;
                        }
                    }">
                    <flux:input
                        label="Actual Released Date"
                        wire:model="actual_released_date"
                        type="date"
                        x-bind:max="new Date().toISOString().split('T')[0]"
                        x-on:change="validateDate($event)" />
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

                <div
                    x-data
                    x-on:input="
                        $event.target.value = $event.target.value.toUpperCase();
                        $wire.set('complain_name', $event.target.value);
                    ">
                    <flux:input
                        wire:model="complain_name"
                        label="Complainant Name"
                        :readonly="$complain_name_disabled"
                        class="uppercase" />
                </div>
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Data and Information Errors --}}
                <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">

                    <flux:label class="font-bold uppercase">
                        Data and Information Errors
                    </flux:label>

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

                    <flux:label class="font-bold uppercase">
                        Technical and Equipment Issues
                    </flux:label>

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

                    <flux:label class="font-bold uppercase">
                        Quality and Accuracy Issues
                    </flux:label>

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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <flux:field>
                        <flux:label>Concern Attachment</flux:label>
                        <input
                            wire:key="attachment-input"
                            type="file"
                            wire:model="concern_attachment"
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
                <flux:input
                    label="Concern Description"
                    wire:model="concern_description"
                    type="text"
                    class="uppercase"
                    x-on:input="$el.value = $el.value.toUpperCase(); $wire.set('concern_description', $el.value)" />
            </div>

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