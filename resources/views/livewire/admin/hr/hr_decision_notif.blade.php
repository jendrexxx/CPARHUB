<div>

    {{-- HR Decision Modal --}}
    <flux:modal
        name="HRDecisionModal"
        class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">

        <div class="space-y-6">

            {{-- Header --}}
            <div>
                <flux:heading size="lg">
                    HR Decision
                </flux:heading>

                <flux:text class="mt-1">
                    Review CPAR and Result Error records that are pending for HR decision.
                </flux:text>
            </div>

            <flux:separator />

            {{-- Search --}}
            <div class="flex items-center gap-2">

                <div class="flex-1">
                    <flux:input
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search CPAR No., Result No., employee, department..."
                        icon="magnifying-glass" />
                </div>

                @if ($search)
                <flux:button
                    variant="ghost"
                    icon="x-mark"
                    wire:click="$set('search', '')">
                    Clear
                </flux:button>
                @endif

            </div>


            {{-- Records --}}
            <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">

                <table class="w-full text-sm">

                    {{-- Table Header --}}
                    <thead class="bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 text-xs tracking-wider">

                        <tr>

                            <th class="px-4 py-3 text-center font-semibold">
                                Type
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Request No.
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Reported Employee
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Department
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Priority level
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Documents
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Status
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Action
                            </th>

                        </tr>

                    </thead>


                    {{-- Table Body --}}
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @forelse ($hrDecisionList as $record)

                        <tr
                            wire:key="hr-decision-{{ $record->record_type }}-{{ $record->assignment_id }}"
                            class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition text-center">

                            {{-- Type --}}
                            <td class="px-4 py-3">
                                @if ($record->record_type === 'CPAR')
                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-red-100 text-red-700
                                                   dark:bg-red-950 dark:text-red-400
                                                   px-2.5 py-1 text-xs font-semibold">
                                    CPAR
                                </span>
                                @elseif ($record->record_type === 'RESULT')
                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-purple-100 text-purple-700
                                                   dark:bg-purple-950 dark:text-purple-400
                                                   px-2.5 py-1 text-xs font-semibold">
                                    RESULT
                                </span>
                                @endif
                            </td>

                            {{-- Request No. --}}
                            <td class="px-4 py-4 whitespace-nowrap">

                                <div class="font-semibold text-zinc-900 dark:text-white">
                                    {{ $record->record_no }}
                                </div>

                                <div class="text-xs text-zinc-500 mt-1">
                                    {{ \Carbon\Carbon::parse($record->record_date)->format('M d, Y') }}
                                </div>

                            </td>


                            {{-- Employee --}}
                            <td class="px-4 py-4">
                                <div class="font-medium text-zinc-900 dark:text-white">
                                    {{ $record->employee_name ?? 'Unassigned' }}
                                </div>

                                @if (!empty($record->employee_no))
                                <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                    {{ $record->employee_no }}
                                </div>
                                @endif
                            </td>


                            {{-- Department --}}
                            <td class="px-4 py-4">

                                <span class="text-zinc-600 dark:text-zinc-400">
                                    {{ $record->department_name ?? 'N/A' }}
                                </span>

                            </td>

                            {{-- Priority level --}}
                            <td class="px-4 py-4 text-center">
                                <span class="text-zinc-600 dark:text-zinc-400">
                                    {{ $record->department_name ?? 'N/A' }}
                                </span>
                            </td>

                            {{-- Documents --}}
                            <td class="px-4 py-4">

                                @if ($record->record_type === 'CPAR')

                                <div class="flex justify-center gap-2">

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
                                    empty($record->nte_no))

                                    <span class="text-zinc-400">
                                        —
                                    </span>

                                    @endif

                                </div>

                                @else
                                <div class="flex justify-center gap-2">

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
                                    empty($record->nte_no))

                                    <span class="text-zinc-400">
                                        —
                                    </span>

                                    @endif

                                </div>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td class="px-4 py-4">

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


                            {{-- Action --}}
                            <td class="px-4 py-3 text-center">
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

    </flux:modal>

</div>