<flux:modal
    name="NoticeToExplainModal"
    class="w-[95%] max-w-[1500px] mt-6 top-0 z-50">
    <div class="space-y-6">

        <div>
            <flux:heading size="lg">
                Notice to Explain
            </flux:heading>

            <flux:text>
                Below are the Notice to Explain requests that require your response.
            </flux:text>
        </div>

        {{-- NTE LIST --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200">

            <table class="w-full text-sm text-center">

                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-3 font-semibold">NTE No.</th>
                        <th class="px-4 py-3 font-semibold">CPAR No.</th>
                        <th class="px-4 py-3 font-semibold">Issued Date</th>
                        <th class="px-4 py-3 font-semibold">Due Date</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold text-center">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($nteList as $nte)

                    <tr class="border-b hover:bg-gray-50">

                        <td class="px-4 py-3 font-medium">
                            {{ $nte->nte_no }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $nte->cpar_no ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($nte->issued_at)->format('M d, Y') }}
                        </td>

                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($nte->due_date)->format('M d, Y') }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                {{ $nte->status }}
                            </span>
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
                                        wire:click="viewNTE({{ $nte->id }})">
                                        Request IR
                                    </flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            No Notice to Explain found.
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>
    </div>
</flux:modal>