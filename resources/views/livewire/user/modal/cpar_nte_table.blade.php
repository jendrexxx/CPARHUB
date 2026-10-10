<flux:modal
    name="NoticeToExplainModal"
    class="mt-4 w-[95vw] max-w-[1500px] sm:mt-6">

    <div class="flex max-h-[85vh] min-w-0 flex-col">

        <div class="shrink-0 space-y-1 pb-4">
            <flux:heading size="lg">
                Notice to Explain
            </flux:heading>

            <flux:text>
                Below are the Notice to Explain requests that require your response.
            </flux:text>
        </div>

        <div
            class="min-w-0 max-w-full overflow-x-auto overflow-y-auto
                   overscroll-x-contain rounded-lg
                   border border-zinc-200 dark:border-zinc-700">

            <table class="min-w-[950px] w-full text-center text-sm">

                <thead class="bg-red-800 text-xs tracking-wider text-white">
                    <tr class="border-b-2 border-white/30">

                        <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                            Type
                        </th>

                        <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                            NTE No.
                        </th>

                        <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                            Request No.
                        </th>

                        <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                            Issued Date
                        </th>

                        <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                            Due Date
                        </th>

                        <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                            Status
                        </th>

                        <th class="sticky right-0 whitespace-nowrap bg-red-800 px-3 py-3 text-center font-bold text-white sm:px-4">
                            Action
                        </th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                    @forelse ($nteList as $nte)

                    <tr
                        wire:key="nte-{{ $nte->record_type }}-{{ $nte->id }}"
                        class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                        <td class="whitespace-nowrap px-3 py-3 sm:px-4">

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

                        <td
                            class="whitespace-nowrap px-3 py-3 font-medium
                                       text-zinc-900 dark:text-zinc-100 sm:px-4">
                            {{ $nte->nte_no }}
                        </td>

                        <td
                            class="whitespace-nowrap px-3 py-3
                                       text-zinc-700 dark:text-zinc-300 sm:px-4">
                            {{ $nte->record_no ?? 'N/A' }}
                        </td>

                        <td
                            class="whitespace-nowrap px-3 py-3
                                       text-zinc-700 dark:text-zinc-300 sm:px-4">

                            @if (!empty($nte->issued_at))

                            {{ \Carbon\Carbon::parse($nte->issued_at)->format('M d, Y') }}

                            @else

                            <span class="text-zinc-400 dark:text-zinc-500">
                                N/A
                            </span>

                            @endif

                        </td>

                        <td
                            class="whitespace-nowrap px-3 py-3
                                       text-zinc-700 dark:text-zinc-300 sm:px-4">

                            @if (!empty($nte->due_date))

                            {{ \Carbon\Carbon::parse($nte->due_date)->format('M d, Y') }}

                            @else

                            <span class="text-zinc-400 dark:text-zinc-500">
                                N/A
                            </span>

                            @endif

                        </td>

                        <td class="whitespace-nowrap px-3 py-3 sm:px-4">

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

                        <td
                            class="sticky right-0 whitespace-nowrap
                                       bg-white px-3 py-3 text-center
                                       dark:bg-zinc-900 sm:px-4">

                            <flux:dropdown align="end">

                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="ellipsis-vertical"
                                    aria-label="Actions" />

                                <flux:menu>

                                    @if ($nte->record_type === 'CPAR')

                                    <flux:menu.item
                                        icon="eye"
                                        wire:click="viewNTE({{ $nte->assignment_id }})">
                                        View NTE
                                    </flux:menu.item>

                                    @elseif ($nte->record_type === 'RESULT')

                                    <flux:menu.item
                                        icon="document-text"
                                        wire:click="viewResultNTE({{ $nte->assignment_id }})">
                                        Respond to Result Error
                                    </flux:menu.item>

                                    @endif

                                </flux:menu>

                            </flux:dropdown>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="7"
                            class="px-4 py-10 text-center
                                       text-zinc-500 dark:text-zinc-400">

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

        <div class="pt-2 text-xs text-zinc-500 dark:text-zinc-400 sm:hidden">
            Swipe left or right to view all columns.
        </div>

    </div>

</flux:modal>