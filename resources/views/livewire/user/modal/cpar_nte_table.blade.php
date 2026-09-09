<flux:modal
    name="NoticeToExplainModal"
    class="w-[95%] max-w-[1500px] mt-6 top-0 z-50">
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <flux:heading size="lg">
                Notice to Explain
            </flux:heading>

            <flux:text>
                Below are the Notice to Explain requests that require your response.
            </flux:text>
        </div>


        {{-- NTE LIST --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700">

            <table class="w-full text-sm text-center">

                <thead class="bg-gray-100 dark:bg-zinc-800 border-b border-gray-200 dark:border-zinc-700">

                    <tr>

                        <th class="px-4 py-3 font-semibold">
                            Type
                        </th>

                        <th class="px-4 py-3 font-semibold">
                            NTE No.
                        </th>

                        <th class="px-4 py-3 font-semibold">
                            Request No.
                        </th>

                        <th class="px-4 py-3 font-semibold">
                            Issued Date
                        </th>

                        <th class="px-4 py-3 font-semibold">
                            Due Date
                        </th>

                        <th class="px-4 py-3 font-semibold">
                            Status
                        </th>

                        <th class="px-4 py-3 font-semibold text-center">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">

                    @forelse ($nteList as $nte)

                    <tr
                        wire:key="nte-{{ $nte->record_type }}-{{ $nte->id }}"
                        class="border-b hover:bg-gray-50 dark:hover:bg-zinc-800">

                        {{-- TYPE --}}
                        <td class="px-4 py-3">

                            @if ($nte->record_type === 'CPAR')

                            <flux:badge
                                color="red"
                                icon="document-text">
                                CPAR
                            </flux:badge>

                            @else

                            <flux:badge
                                color="purple"
                                icon="document-text">
                                RESULT ERROR
                            </flux:badge>

                            @endif

                        </td>


                        {{-- NTE NO --}}
                        <td class="px-4 py-3 font-medium">

                            {{ $nte->nte_no }}

                        </td>


                        {{-- REQUEST NO --}}
                        <td class="px-4 py-3">

                            {{ $nte->record_no ?? 'N/A' }}

                        </td>


                        {{-- ISSUED DATE --}}
                        <td class="px-4 py-3">

                            @if (!empty($nte->issued_at))

                            {{ \Carbon\Carbon::parse($nte->issued_at)->format('M d, Y') }}

                            @else

                            <span class="text-gray-400">
                                N/A
                            </span>

                            @endif

                        </td>


                        {{-- DUE DATE --}}
                        <td class="px-4 py-3">

                            @if (!empty($nte->due_date))

                            {{ \Carbon\Carbon::parse($nte->due_date)->format('M d, Y') }}

                            @else

                            <span class="text-gray-400">
                                N/A
                            </span>

                            @endif

                        </td>


                        {{-- STATUS --}}
                        <td class="px-4 py-3">

                            @php
                            $statusColor = match (strtoupper($nte->status ?? '')) {
                            'PENDING' => 'yellow',
                            'SUBMITTED' => 'green',
                            'APPROVED' => 'green',
                            'CLOSED' => 'zinc',
                            default => 'red',
                            };
                            @endphp

                            <flux:badge color="{{ $statusColor }}">
                                {{ $nte->status ?? 'N/A' }}
                            </flux:badge>

                        </td>


                        {{-- ACTION --}}
                        <td class="px-4 py-3 text-center">

                            <flux:dropdown align="end">

                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="ellipsis-vertical"
                                    aria-label="Actions" />

                                <flux:menu>

                                    <flux:menu.item
                                        icon="eye"
                                        wire:click="viewNTE({{ $nte->id }})">
                                        View NTE
                                    </flux:menu.item>

                                </flux:menu>

                            </flux:dropdown>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="7"
                            class="px-4 py-8 text-center text-gray-500 dark:text-zinc-400">

                            <flux:icon
                                name="document-text"
                                class="mx-auto size-10 text-zinc-400" />

                            <div class="mt-2">
                                No Notice to Explain found.
                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
</flux:modal>