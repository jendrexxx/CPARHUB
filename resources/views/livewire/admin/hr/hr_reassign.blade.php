<div>

    <flux:modal name="reassign-cpar" class="w-full max-w-lg">

        <flux:heading size="lg">
            Re-Assign CPAR
        </flux:heading>


        <div class="mt-6 space-y-4">

            {{-- Main Assign To --}}
            <div class="flex items-end gap-2">

                <div class="flex-1">

                    <flux:select
                        label="Assign To"
                        wire:model="assigned">

                        <option value="">
                            -- Select Employee --
                        </option>

                        @foreach($employees as $employee)

                        <option value="{{ $employee->id }}">
                            {{ $employee->first_name }}
                            {{ $employee->last_name }}
                        </option>

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


            {{-- Additional Assign --}}
            @foreach(($assigned_to ?? []) as $index => $value)
            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <flux:select
                        label="Additional Assign"
                        wire:model="assigned_to.{{ $index }}">

                        <option value="">
                            -- Select Employee --
                        </option>

                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </option>
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

            <flux:textarea
                label="Remarks"
                wire:model="remarks" />

            <div class="flex justify-end gap-2 mt-6">
                <flux:button
                    variant="ghost"
                    x-on:click="$flux.modal('reassign-cpar').close()">
                    Cancel
                </flux:button>
                <flux:button
                    variant="primary"
                    wire:click="HRAssigned">
                    Save
                </flux:button>
            </div>
        </div>

    </flux:modal>

</div>