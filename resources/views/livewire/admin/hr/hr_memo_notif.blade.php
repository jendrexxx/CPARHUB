<div>
    <flux:modal
        name="HRMemoModal"
        class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">

        <div class="space-y-6">

            {{-- HEADER --}}
            <div>
                <flux:heading size="lg">
                    Employees for Memo
                </flux:heading>

                <flux:text class="mt-1">
                    Review employees who are subject to a memo based on the HR decision.
                </flux:text>
            </div>

            <flux:separator />

            {{-- SEARCH --}}
            <div class="flex items-center gap-2">

                <div class="flex-1">
                    <flux:input
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search CPAR / Result No., employee, department..."
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

            {{-- TABLE --}}
            <div class="w-full overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                <table class="w-full text-sm">

                    {{-- TABLE HEADER --}}
                    <thead class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">

                        <tr>

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

                    {{-- TABLE BODY --}}
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @forelse ($hrMemoList as $record)

                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition text-center">

                            {{-- RECORD NO --}}
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="font-semibold text-zinc-900 dark:text-white">
                                        {{ $record->record_no }}
                                    </div>

                                    @if ($record->record_type === 'CPAR')
                                    <flux:badge color="blue">
                                        CPAR
                                    </flux:badge>
                                    @else
                                    <flux:badge color="purple">
                                        RESULT
                                    </flux:badge>
                                    @endif
                                </div>
                            </td>


                            {{-- EMPLOYEE --}}
                            <td class="px-4 py-4">

                                <div class="font-medium text-zinc-900 dark:text-white">
                                    {{ $record->employee_name }}
                                </div>

                                @if (!empty($record->employee_no))
                                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ $record->employee_no }}
                                </div>
                                @endif

                            </td>


                            {{-- DEPARTMENT --}}
                            <td class="px-4 py-4">

                                <span class="text-zinc-600 dark:text-zinc-400">
                                    {{ $record->department_name }}
                                </span>

                            </td>

                            <td class="px-4 py-4 text-center">
                                @php
                                $priority = strtoupper(trim($record->priority_name ?? ''));

                                $priorityColor = match ($priority) {
                                'NORMAL' => 'blue',
                                'HIGH' => 'orange',
                                'URGENT' => 'red',
                                default => 'zinc',
                                };
                                @endphp

                                <flux:badge color="{{ $priorityColor }}">
                                    {{ $priority ?: 'N/A' }}
                                </flux:badge>
                            </td>


                            {{-- DOCUMENTS --}}
                            <td class="px-4 py-4">

                                <div class="flex justify-center gap-2">

                                    @if (!empty($record->ir_id))

                                    <flux:badge color="red">
                                        IR Submitted
                                    </flux:badge>

                                    @endif

                                    @if (!empty($record->nte_no))

                                    <flux:badge color="blue">
                                        NTE Submitted
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
                            <td class="px-4 py-4 text-center">

                                <flux:badge color="yellow">
                                    {{ $record->status_name }}
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

                                        @if ($record->record_type === 'CPAR')

                                        <flux:menu.item
                                            icon="eye"
                                            wire:click="viewMemo({{ $record->assignment_id }})">
                                            View Other Memo
                                        </flux:menu.item>

                                        @elseif ($record->record_type === 'RESULT')

                                        <flux:menu.item
                                            icon="eye"
                                            wire:click="viewResultMemo({{ $record->assignment_id }})">
                                            View Result
                                        </flux:menu.item>

                                        @endif

                                    </flux:menu>

                                </flux:dropdown>
                            </td>

                        </tr>

                        @empty

                        {{-- EMPTY STATE --}}
                        <tr>

                            <td colspan="8" class="px-4 py-12 text-center">

                                <flux:icon
                                    name="check-circle"
                                    class="mx-auto size-10 text-green-500" />

                                <flux:heading
                                    size="sm"
                                    class="mt-3">

                                    @if ($search)
                                    No Records Found
                                    @else
                                    No Employees for Memo
                                    @endif

                                </flux:heading>

                                <flux:text class="mt-1 text-zinc-500">

                                    @if ($search)

                                    No results found for
                                    <strong>"{{ $search }}"</strong>.

                                    @else

                                    No employees are currently pending
                                    for memo issuance.

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