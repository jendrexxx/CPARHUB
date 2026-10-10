<div>

    <flux:modal
        name="HRDecisionModal"
        class="mt-4 w-[95vw] max-w-[1500px] sm:mt-6">

        <div class="flex max-h-[85vh] min-w-0 flex-col">

            <div class="shrink-0 space-y-1 pb-4">
                <flux:heading size="lg">
                    HR Decision
                </flux:heading>

                <flux:text>
                    Review CPAR and Result Error records that are pending for HR decision.
                </flux:text>
            </div>

            <flux:separator />

            <div
                class="min-w-0 max-w-full overflow-x-auto overflow-y-auto
                       overscroll-x-contain rounded-lg
                       border border-zinc-200 dark:border-zinc-700">

                <table class="min-w-[1200px] w-full text-center text-sm">

                    <thead class="bg-red-800 text-xs tracking-wider text-white">
                        <tr class="border-b-2 border-white/30">

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Request No.
                            </th>

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Reported By
                            </th>

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Date Reported
                            </th>

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Assigned To
                            </th>

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Priority Level
                            </th>

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Documents
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

                        @forelse ($hrDecisionList as $record)

                        <tr
                            wire:key="hr-decision-{{ $record->record_type }}-{{ $record->assignment_id }}"
                            class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                            <td class="whitespace-nowrap px-3 py-4 sm:px-4">

                                <div class="flex flex-col items-center gap-1">

                                    <div class="font-semibold text-zinc-900 dark:text-white">
                                        {{ $record->record_no }}
                                    </div>

                                    @if ($record->record_type === 'CPAR')

                                    <span
                                        class="inline-flex items-center rounded-full
                                                       bg-red-100 px-2.5 py-0.5
                                                       text-[10px] font-semibold text-red-700
                                                       dark:bg-red-950 dark:text-red-400">
                                        CPAR
                                    </span>

                                    @else

                                    <span
                                        class="inline-flex items-center rounded-full
                                                       bg-blue-100 px-2.5 py-0.5
                                                       text-[10px] font-semibold text-blue-700
                                                       dark:bg-blue-950 dark:text-blue-400">
                                        RESULT
                                    </span>

                                    @endif

                                </div>

                            </td>

                            <td
                                class="max-w-[220px] px-3 py-4 text-center sm:px-4">

                                <div
                                    class="truncate font-medium text-zinc-700 dark:text-zinc-300"
                                    title="{{ $record->reported_by }}">
                                    {{ $record->reported_by }}
                                </div>

                            </td>

                            <td
                                class="whitespace-nowrap px-3 py-4
                                           text-zinc-700 dark:text-zinc-300 sm:px-4">

                                @if ($record->record_date)

                                {{ \Carbon\Carbon::parse($record->record_date)->format('M d, Y') }}

                                @else

                                <span class="text-zinc-400 dark:text-zinc-500">
                                    N/A
                                </span>

                                @endif

                            </td>

                            <td
                                class="max-w-[240px] px-3 py-4 text-center sm:px-4">

                                <div
                                    class="truncate font-medium text-zinc-900 dark:text-white"
                                    title="{{ $record->employee_name ?? 'Unassigned' }}">
                                    {{ $record->employee_name ?? 'Unassigned' }}
                                </div>

                                @if (!empty($record->employee_no))

                                <button
                                    type="button"
                                    wire:click="viewOffenseHistory('{{ $record->employee_no }}')"
                                    class="mt-2 inline-flex items-center gap-1
                                                   rounded-md bg-zinc-100 px-2 py-1
                                                   text-xs font-medium text-zinc-600
                                                   transition hover:bg-red-50 hover:text-red-700
                                                   dark:bg-zinc-800 dark:text-zinc-400
                                                   dark:hover:bg-red-950 dark:hover:text-red-400">

                                    <flux:icon
                                        name="clock"
                                        class="size-3.5" />

                                    {{ $offenseHistoryCounts[$record->employee_no] ?? 0 }}
                                    Offense History

                                </button>

                                @endif

                            </td>

                            <td class="whitespace-nowrap px-3 py-4 sm:px-4">

                                @php
                                $priorityColor = match (
                                strtolower($record->priority_name ?? '')
                                ) {
                                'normal' =>
                                'bg-green-100 text-green-700 ring-green-600/20
                                dark:bg-green-950 dark:text-green-400',

                                'high' =>
                                'bg-orange-100 text-orange-700 ring-orange-600/20
                                dark:bg-orange-950 dark:text-orange-400',

                                'urgent' =>
                                'bg-red-100 text-red-700 ring-red-600/20
                                dark:bg-red-950 dark:text-red-400',

                                default =>
                                'bg-zinc-100 text-zinc-700 ring-zinc-600/20
                                dark:bg-zinc-700 dark:text-zinc-300',
                                };
                                @endphp

                                <span
                                    class="inline-flex items-center gap-1.5
                                               whitespace-nowrap rounded-full
                                               px-2.5 py-1 text-xs font-semibold
                                               ring-1 ring-inset
                                               {{ $priorityColor }}">
                                    {{ $record->priority_name ?? 'N/A' }}
                                </span>

                            </td>

                            <td class="px-3 py-4 sm:px-4">

                                <div class="flex flex-wrap justify-center gap-1.5">

                                    @if (!empty($record->ir_id))

                                    <flux:badge color="red">
                                        Submitted IR
                                    </flux:badge>

                                    @endif

                                    @if (!empty($record->nte_no))

                                    <flux:badge color="blue">
                                        Submitted NTE
                                    </flux:badge>

                                    @endif

                                    @if (
                                    empty($record->ir_id) &&
                                    empty($record->nte_no)
                                    )

                                    <span class="text-zinc-400 dark:text-zinc-500">
                                        —
                                    </span>

                                    @endif

                                </div>

                            </td>

                            <td class="whitespace-nowrap px-3 py-4 sm:px-4">

                                @if ($record->status_name === 'APPROVED')

                                <flux:badge color="green">
                                    {{ $record->status_name }}
                                </flux:badge>

                                @elseif ($record->status_name === 'FOR REVIEW')

                                <flux:badge color="yellow">
                                    {{ $record->status_name }}
                                </flux:badge>

                                @else

                                <flux:badge color="yellow">
                                    {{ $record->status_name }}
                                </flux:badge>

                                @endif

                            </td>

                            <td
                                class="sticky right-0 whitespace-nowrap
                                           bg-white px-3 py-4 text-center
                                           dark:bg-zinc-900 sm:px-4">

                                <flux:dropdown align="end">

                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="ellipsis-vertical"
                                        aria-label="Actions" />

                                    <flux:menu>

                                        @if ($record->record_type === 'CPAR')

                                        <flux:menu.item
                                            icon="eye"
                                            wire:click="viewCpar({{ $record->assignment_id }})">
                                            View CPAR
                                        </flux:menu.item>

                                        @elseif ($record->record_type === 'RESULT')

                                        <flux:menu.item
                                            icon="eye"
                                            wire:click="viewResult({{ $record->assignment_id }})">
                                            View Result Error
                                        </flux:menu.item>

                                        @endif

                                    </flux:menu>

                                </flux:dropdown>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="8"
                                class="px-4 py-12 text-center">

                                <flux:icon
                                    name="check-circle"
                                    class="mx-auto size-10 text-green-500" />

                                <flux:heading
                                    size="sm"
                                    class="mt-3">
                                    No Records Pending for HR Decision
                                </flux:heading>

                                <flux:text class="mt-1 text-zinc-500 dark:text-zinc-400">
                                    All records have been reviewed.
                                </flux:text>

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

</div>