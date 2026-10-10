<div>
    <flux:modal
        name="AssignedConcernModal"
        class="mt-4 w-[95vw] max-w-[1500px] sm:mt-6">

        <div class="flex max-h-[85vh] min-w-0 flex-col">

            <div class="shrink-0 space-y-1 pb-4">
                <flux:heading size="lg">
                    Reported Concern
                </flux:heading>

                <flux:text>
                    Below is the list of reported concerns assigned to you.
                </flux:text>
            </div>

            <div
                class="min-w-0 max-w-full overflow-x-auto overflow-y-auto overscroll-x-contain rounded-lg border border-zinc-200 dark:border-zinc-700">
                <table class="min-w-[1100px] w-full text-center text-sm">

                    <thead class="bg-red-800 text-xs tracking-wider text-white">
                        <tr class="border-b-2 border-white/30">

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Type
                            </th>

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
                                Reported Employee
                            </th>

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Department Name
                            </th>

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Status
                            </th>

                            <th class="sticky right-0 whitespace-nowrap bg-red-800 px-3 py-3 font-bold text-white sm:px-4">
                                Action
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @forelse ($assigned_requests as $request)

                        <tr
                            wire:key="{{ strtolower($request->record_type) }}-{{ $request->id }}"
                            class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                            <td class="whitespace-nowrap px-3 py-3 sm:px-4">

                                @if ($request->record_type === 'CPAR')

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-blue-100 px-2.5 py-1
                                                   text-xs font-semibold text-blue-700
                                                   dark:bg-blue-900/40 dark:text-blue-300">
                                    CPAR
                                </span>

                                @else

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-purple-100 px-2.5 py-1
                                                   text-xs font-semibold text-purple-700
                                                   dark:bg-purple-900/40 dark:text-purple-300">
                                    RESULT ERROR
                                </span>

                                @endif

                            </td>

                            <td
                                class="whitespace-nowrap px-3 py-3 font-medium
                                           text-zinc-900 dark:text-zinc-100 sm:px-4">
                                {{ $request->record_no }}
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
                                class="max-w-[220px] whitespace-nowrap px-3 py-3
                                           text-zinc-700 dark:text-zinc-300 sm:px-4">
                                {{ $request->assigned_full_name ?? 'Unassigned' }}
                            </td>

                            <td
                                class="max-w-[220px] whitespace-nowrap px-3 py-3
                                           text-zinc-700 dark:text-zinc-300 sm:px-4">
                                {{ $request->department_name }}
                            </td>

                            <td class="whitespace-nowrap px-3 py-3 sm:px-4">

                                @if ($request->status_name === 'PENDING')

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-yellow-100 px-2.5 py-0.5
                                                   text-xs font-medium text-yellow-800
                                                   dark:bg-yellow-900 dark:text-yellow-200">
                                    {{ $request->status_name }}
                                </span>

                                @elseif ($request->status_name === 'APPROVED')

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-green-100 px-2.5 py-0.5
                                                   text-xs font-medium text-green-800
                                                   dark:bg-green-900 dark:text-green-200">
                                    {{ $request->status_name }}
                                </span>

                                @elseif ($request->status_name === 'CLOSED')

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-blue-100 px-2.5 py-0.5
                                                   text-xs font-medium text-blue-800
                                                   dark:bg-blue-900 dark:text-blue-200">
                                    {{ $request->status_name }}
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
                                        icon="ellipsis-vertical" />

                                    <flux:menu>

                                        @if ($request->record_type === 'CPAR')

                                        <flux:menu.item
                                            icon="arrow-path"
                                            wire:click="Assigncpar({{ $request->assignment_id }})">
                                            Assign
                                        </flux:menu.item>

                                        @elseif ($request->record_type === 'RESULT')

                                        <flux:menu.item
                                            icon="arrow-path"
                                            wire:click="Assignresult({{ $request->assignment_id }})">
                                            Assign
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
                                No requests found.
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