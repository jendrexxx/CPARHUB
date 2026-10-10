<div>

    <flux:modal
        name="HRMemoModal"
        class="mt-4 w-[95vw] max-w-[1500px] sm:mt-6">

        <div class="flex max-h-[85vh] min-w-0 flex-col">

            <div class="shrink-0 space-y-1 pb-4">
                <flux:heading size="lg">
                    Employees for Memo
                </flux:heading>

                <flux:text>
                    Review employees who are subject to a memo based on the HR decision.
                </flux:text>
            </div>

            <flux:separator />

            <div
                class="min-w-0 max-w-full overflow-x-auto overflow-y-auto
                       overscroll-x-contain rounded-lg
                       border border-zinc-200 dark:border-zinc-700">

                <table class="min-w-[950px] w-full text-center text-sm">

                    <thead class="bg-red-800 text-xs tracking-wider text-white">
                        <tr class="border-b-2 border-white/30">

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Request No.
                            </th>

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Reported Employee
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

                        @forelse ($hrMemoList as $record)

                        <tr
                            wire:key="hr-memo-{{ $record->record_type }}-{{ $record->assignment_id }}"
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

                            <td class="px-3 py-4 sm:px-4">

                                <div
                                    class="font-medium text-zinc-900 dark:text-white"
                                    title="{{ $record->employee_name }}">
                                    {{ $record->employee_name }}
                                </div>

                                @if (!empty($record->employee_no))

                                <div class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ $record->employee_no }}
                                </div>

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

                                <div class="flex flex-wrap justify-center gap-2">

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

                                    <span class="text-zinc-400 dark:text-zinc-500">
                                        —
                                    </span>

                                    @endif

                                </div>

                            </td>

                            <td class="whitespace-nowrap px-3 py-4 sm:px-4">

                                <flux:badge color="yellow">
                                    {{ $record->status_name }}
                                </flux:badge>

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
                                            wire:click="viewMemo({{ $record->assignment_id }})">
                                            View Memo
                                        </flux:menu.item>

                                        @elseif ($record->record_type === 'RESULT')

                                        <flux:menu.item
                                            icon="eye"
                                            wire:click="viewResultMemo({{ $record->assignment_id }})">
                                            View Memo
                                        </flux:menu.item>

                                        @endif

                                    </flux:menu>

                                </flux:dropdown>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-4 py-12 text-center">

                                <flux:icon
                                    name="check-circle"
                                    class="mx-auto size-10 text-green-500" />

                                <flux:heading
                                    size="sm"
                                    class="mt-3">
                                    No Employees for Memo
                                </flux:heading>

                                <flux:text class="mt-1 text-zinc-500 dark:text-zinc-400">
                                    No employees are currently pending for HR memo.
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