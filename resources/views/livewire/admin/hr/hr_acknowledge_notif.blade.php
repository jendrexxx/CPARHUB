<div>

    <flux:modal
        name="CPARAcknowledgeModal"
        class="mt-4 w-[95vw] max-w-[1500px] sm:mt-6">

        <div class="flex max-h-[85vh] min-w-0 flex-col">

            <div class="shrink-0 space-y-1 pb-4">
                <flux:heading size="lg">
                    Acknowledgment Concern
                </flux:heading>

                <flux:text>
                    Please review the details of this concern and confirm your acknowledgment.
                </flux:text>
            </div>

            <div
                class="min-w-0 max-w-full overflow-x-auto overflow-y-auto
                       overscroll-x-contain rounded-lg
                       border border-zinc-200 dark:border-zinc-700">

                <table class="min-w-[1150px] w-full text-center text-sm">

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

                        @forelse ($acknowledgment_requests as $request)

                        <tr
                            wire:key="acknowledgment-{{ $request->record_type }}-{{ $request->assignment_id }}"
                            class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                            <td class="whitespace-nowrap px-3 py-4 sm:px-4">

                                <div class="flex flex-col items-center gap-1">

                                    <div class="font-semibold text-zinc-900 dark:text-white">
                                        {{ $request->record_no }}
                                    </div>

                                    @if ($request->record_type === 'CPAR')

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
                                class="whitespace-nowrap px-3 py-3
                                           text-zinc-700 dark:text-zinc-300 sm:px-4">
                                {{ $request->reported_by }}
                            </td>

                            <td
                                class="whitespace-nowrap px-3 py-3
                                           text-zinc-700 dark:text-zinc-300 sm:px-4">
                                {{ \Carbon\Carbon::parse($request->record_date)->format('M d, Y') }}
                            </td>

                            <td
                                class="whitespace-nowrap px-3 py-3
                                           text-zinc-700 dark:text-zinc-300 sm:px-4">

                                @if (!empty($request->first_name) || !empty($request->last_name))

                                {{ $request->first_name ?? '' }}
                                {{ $request->last_name ?? '' }}

                                @else

                                <span class="text-zinc-400 dark:text-zinc-500">
                                    Unassigned
                                </span>

                                @endif

                            </td>

                            <td class="whitespace-nowrap px-3 py-3 sm:px-4">

                                @php
                                $priorityColor = match (
                                strtolower($request->priority_name ?? '')
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
                                    {{ $request->priority_name ?? 'N/A' }}
                                </span>

                            </td>

                            <td class="px-3 py-4 sm:px-4">

                                <div class="flex flex-wrap items-center justify-center gap-2">

                                    @if (!empty($request->ir_id))

                                    <span
                                        class="inline-flex items-center whitespace-nowrap
                                                       rounded-full bg-red-100 px-2.5 py-1
                                                       text-xs font-medium text-red-700
                                                       dark:bg-red-950 dark:text-red-400">
                                        Submitted IR
                                    </span>

                                    @endif

                                    @if (!empty($request->nte_no))

                                    <span
                                        class="inline-flex items-center whitespace-nowrap
                                                       rounded-full bg-blue-100 px-2.5 py-1
                                                       text-xs font-medium text-blue-700
                                                       dark:bg-blue-950 dark:text-blue-400">
                                        Submitted NTE
                                    </span>

                                    @endif

                                    @if (
                                    empty($request->ir_id) &&
                                    empty($request->nte_no)
                                    )

                                    <span class="text-zinc-400 dark:text-zinc-500">
                                        —
                                    </span>

                                    @endif

                                </div>

                            </td>

                            <td class="whitespace-nowrap px-3 py-3 sm:px-4">

                                @if ($request->status_name === 'PENDING')

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-yellow-100 px-2.5 py-0.5
                                                   text-xs font-medium text-yellow-800
                                                   dark:bg-yellow-900 dark:text-yellow-200">
                                    PENDING
                                </span>

                                @elseif ($request->status_name === 'ASSIGNED')

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-blue-100 px-2.5 py-0.5
                                                   text-xs font-medium text-blue-800
                                                   dark:bg-blue-900 dark:text-blue-200">
                                    ASSIGNED
                                </span>

                                @elseif ($request->status_name === 'APPROVED')

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-green-100 px-2.5 py-0.5
                                                   text-xs font-medium text-green-800
                                                   dark:bg-green-900 dark:text-green-200">
                                    APPROVED
                                </span>

                                @elseif ($request->status_name === 'CLOSED')

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-indigo-100 px-2.5 py-0.5
                                                   text-xs font-medium text-indigo-800
                                                   dark:bg-indigo-900 dark:text-indigo-200">
                                    CLOSED
                                </span>

                                @else

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-zinc-100 px-2.5 py-0.5
                                                   text-xs font-medium text-zinc-800
                                                   dark:bg-zinc-700 dark:text-zinc-300">
                                    {{ $request->status_name }}
                                </span>

                                @endif

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

                                        @if ($request->record_type === 'CPAR')

                                        @if (!empty($request->ir_request_id))

                                        <flux:menu.item
                                            icon="document-text"
                                            wire:click="createNoticeToExplain({{ $request->ir_request_id }})">
                                            Request NTE
                                        </flux:menu.item>

                                        @endif

                                        @elseif ($request->record_type === 'RESULT')

                                        <flux:menu.item
                                            icon="document-text"
                                            wire:click="createNTE({{ $request->assignment_id }})">
                                            Request NTE
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
                                class="px-4 py-10 text-center
                                           text-zinc-500 dark:text-zinc-400">
                                No acknowledgment requests found.
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