<div>

    {{-- HR Decision Modal --}}
    <flux:modal
        name="HRDecisionModal"
        class="w-full max-w-[1500px] mt-6 top-0 z-50">

        <div class="w-full space-y-6">

            {{-- HEADER --}}
            <div class="px-1">
                <flux:heading size="lg">
                    HR Decision
                </flux:heading>

                <flux:text class="mt-1">
                    Review CPAR and Result Error records that are pending for HR decision.
                </flux:text>
            </div>

            <flux:separator />

            {{-- SEARCH --}}
            <div class="flex w-full flex-col gap-2 sm:flex-row sm:items-center">

                <div class="w-full flex-1">
                    <flux:input
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search CPAR No., Result No., employee, department..."
                        icon="magnifying-glass" />
                </div>

                @if ($search)

                <flux:button
                    variant="ghost"
                    icon="x-mark"
                    wire:click="$set('search', '')"
                    class="shrink-0">
                    Clear
                </flux:button>

                @endif

            </div>


            {{-- RECORDS --}}
            <div class="w-full overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">

                {{-- HORIZONTAL SCROLL --}}
                <div class="w-full overflow-x-auto overscroll-x-contain">

                    <table class="min-w-[1200px] w-full text-sm">

                        {{-- TABLE HEADER --}}
                        <thead
                            class="bg-zinc-100 text-xs tracking-wider text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">

                            <tr>

                                <th class="w-36 whitespace-nowrap px-4 py-3 text-center font-semibold">
                                    Request No.
                                </th>

                                <th class="min-w-[180px] px-4 py-3 text-center font-semibold">
                                    Reported By
                                </th>

                                <th class="w-32 whitespace-nowrap px-4 py-3 text-center font-semibold">
                                    Date Reported
                                </th>

                                <th class="min-w-[180px] px-4 py-3 text-center font-semibold">
                                    Assigned To
                                </th>

                                <th class="w-32 whitespace-nowrap px-4 py-3 text-center font-semibold">
                                    Priority Level
                                </th>

                                <th class="min-w-[180px] px-4 py-3 text-center font-semibold">
                                    Documents
                                </th>

                                <th class="w-32 whitespace-nowrap px-4 py-3 text-center font-semibold">
                                    Status
                                </th>

                                <th class="w-24 whitespace-nowrap px-4 py-3 text-center font-semibold">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        {{-- TABLE BODY --}}
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                            @forelse ($hrDecisionList as $record)

                            <tr
                                wire:key="hr-decision-{{ $record->record_type }}-{{ $record->assignment_id }}"
                                class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800">

                                {{-- REQUEST NO --}}
                                <td class="whitespace-nowrap px-4 py-4 text-center">

                                    <div class="flex flex-col items-center gap-1">

                                        <div class="font-semibold text-zinc-900 dark:text-white">
                                            {{ $record->record_no }}
                                        </div>

                                        <div class="mt-1 text-center">
                                            @if ($record->record_type === 'CPAR')
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold
                                            bg-red-100 text-red-700
                                            dark:bg-red-950 dark:text-red-400">
                                                CPAR
                                            </span>
                                            @else
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold
                                            bg-blue-100 text-blue-700
                                            dark:bg-blue-950 dark:text-blue-400">
                                                RESULT
                                            </span>
                                            @endif
                                        </div>

                                    </div>

                                </td>


                                {{-- REPORTED BY --}}
                                <td class="max-w-[220px] px-4 py-4 text-center">

                                    <div
                                        class="truncate font-medium text-zinc-700 dark:text-zinc-300"
                                        title="{{ $record->reported_by }}">
                                        {{ $record->reported_by }}
                                    </div>

                                </td>


                                {{-- DATE --}}
                                <td class="whitespace-nowrap px-4 py-4 text-center text-zinc-700 dark:text-zinc-300">

                                    @if ($record->record_date)

                                    {{ \Carbon\Carbon::parse($record->record_date)->format('M d, Y') }}

                                    @else

                                    <span class="text-zinc-400">
                                        N/A
                                    </span>

                                    @endif

                                </td>


                                {{-- ASSIGNED TO --}}
                                <td class="max-w-[220px] px-4 py-4 text-center">

                                    {{-- Employee Name --}}
                                    <div
                                        class="truncate font-medium text-zinc-900 dark:text-white"
                                        title="{{ $record->employee_name ?? 'Unassigned' }}">
                                        {{ $record->employee_name ?? 'Unassigned' }}
                                    </div>

                                    {{-- Offense History --}}
                                    @if (!empty($record->employee_no))
                                    <button
                                        type="button"
                                        wire:click="viewOffenseHistory('{{ $record->employee_no }}')"
                                        class="mt-2 inline-flex items-center gap-1 rounded-md
                                        bg-zinc-100 px-2 py-1 text-xs font-medium
                                        text-zinc-600 transition
                                        hover:bg-red-50 hover:text-red-700
                                        dark:bg-zinc-800 dark:text-zinc-400
                                        dark:hover:bg-red-950 dark:hover:text-red-400">
                                        <flux:icon name="clock" class="size-3.5" />

                                        {{ $offenseHistoryCounts[$record->employee_no] ?? 0 }}
                                        Offense History
                                    </button>
                                    @endif

                                </td>


                                {{-- PRIORITY --}}
                                <td class="whitespace-nowrap px-4 py-4 text-center">

                                    @php

                                    $priorityColor = match (
                                    strtolower($record->priority_name ?? '')
                                    ) {

                                    'normal' =>
                                    'bg-green-100 text-green-700 ring-green-600/20 dark:bg-green-950 dark:text-green-400',

                                    'high' =>
                                    'bg-orange-100 text-orange-700 ring-orange-600/20 dark:bg-orange-950 dark:text-orange-400',

                                    'urgent' =>
                                    'bg-red-100 text-red-700 ring-red-600/20 dark:bg-red-950 dark:text-red-400',

                                    default =>
                                    'bg-zinc-100 text-zinc-700 ring-zinc-600/20 dark:bg-zinc-700 dark:text-zinc-300',

                                    };

                                    @endphp

                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $priorityColor }}">

                                        {{ $record->priority_name ?? 'N/A' }}

                                    </span>

                                </td>


                                {{-- DOCUMENTS --}}
                                <td class="px-4 py-4 text-center">

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

                                        <span class="text-zinc-400">
                                            —
                                        </span>

                                        @endif

                                    </div>

                                </td>


                                {{-- STATUS --}}
                                <td class="whitespace-nowrap px-4 py-4 text-center">

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


                                {{-- ACTION --}}
                                <td class="whitespace-nowrap px-4 py-4 text-center">

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


                                            @if (!empty($record->employee_no))

                                            <!-- <flux:menu.item
                                                icon="clock"
                                                wire:click="viewOffenseHistory('{{ $record->employee_no }}')">
                                                View Offense History
                                            </flux:menu.item> -->

                                            @endif

                                        </flux:menu>

                                    </flux:dropdown>

                                </td>

                            </tr>


                            @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="px-4 py-12 text-center">

                                    <flux:icon
                                        name="check-circle"
                                        class="mx-auto size-10 text-green-500" />

                                    <flux:heading
                                        size="sm"
                                        class="mt-3">

                                        @if ($search)

                                        No Record Found

                                        @else

                                        No Records Pending for HR Decision

                                        @endif

                                    </flux:heading>

                                    <flux:text class="mt-1 text-zinc-500">

                                        @if ($search)

                                        No results found for
                                        <strong>"{{ $search }}"</strong>.

                                        @else

                                        All records have been reviewed.

                                        @endif

                                    </flux:text>

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </flux:modal>

</div>