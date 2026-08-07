<flux:modal
    name="IRRequestModal"
    class="w-[95%] max-w-[1500px] mt-6 top-0 z-50">

    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <flux:heading size="lg">
                Incident Report Request
            </flux:heading>

            <flux:text>
                Below are the Incident Report requests that require your response.
            </flux:text>
        </div>


        {{-- Table --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200">

            <table class="w-full text-sm text-center">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3">CPAR No</th>
                        <th class="px-4 py-3">Employee</th>
                        <th class="px-4 py-3">Department</th>
                        <th class="px-4 py-3">IR No</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($irRequests as $ir)

                    <tr class="border-t">

                        <td class="px-4 py-3">
                            {{ $ir->cpar_no }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $ir->first_name }} {{ $ir->last_name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $ir->department_name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $ir->ir_id }}
                        </td>

                        <td class="px-4 py-3">
                            <flux:badge color="yellow">
                                {{ $ir->status }}
                            </flux:badge>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <flux:dropdown align="end">
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="ellipsis-vertical">
                                </flux:button>
                                <flux:menu>
                                    <flux:menu.item
                                        icon="eye"
                                        wire:click="viewIR({{ $ir->id }})">
                                        Request IR
                                    </flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="8" class="py-5 text-gray-500">
                            No Incident Report Request found.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</flux:modal>