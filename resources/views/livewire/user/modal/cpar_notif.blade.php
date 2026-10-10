<div>
    <flux:modal name="CPARModal" class="w-[95vw] max-w-[1500px] mt-6 top-0 z-50">

        <div class="space-y-6">

            <div>
                <flux:heading size="lg">Reported Concern</flux:heading>

                <flux:text>
                    Below is the list of your filed Reported Concern.
                </flux:text>
            </div>

            <div class="w-full overflow-x-auto overflow-y-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">

                <table class="min-w-[1100px] w-full table-auto text-sm text-center">

                    <thead class="bg-red-800 text-xs tracking-wider text-white">
                        <tr class="border-b-2 border-white/30">

                            <th class="whitespace-nowrap px-4 py-3 font-bold text-white">
                                Type
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-bold text-white">
                                Concern No.
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-bold text-white">
                                Reported By
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-bold text-white">
                                Date Reported
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-bold text-white">
                                Assigned To
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-bold text-white">
                                Priority Level
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-bold text-white">
                                Status
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-bold text-white">
                                Action
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @forelse ($requests as $request)

                        <tr
                            wire:key="{{ strtolower($request->record_type) }}-{{ $request->id }}"
                            class="transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                            <td class="whitespace-nowrap px-4 py-3">

                                @if ($request->record_type === 'CPAR')

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-blue-100 px-2.5 py-1 text-xs font-semibold
                                                   text-blue-700 ring-1 ring-inset ring-blue-600/20
                                                   dark:bg-blue-900/40 dark:text-blue-300">
                                    CPAR
                                </span>

                                @else

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-purple-100 px-2.5 py-1 text-xs font-semibold
                                                   text-purple-700 ring-1 ring-inset ring-purple-600/20
                                                   dark:bg-purple-900/40 dark:text-purple-300">
                                    RESULT
                                </span>

                                @endif

                            </td>


                            <td class="whitespace-nowrap px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $request->record_no }}
                            </td>


                            <td class="whitespace-nowrap px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                {{ $request->reported_by }}
                            </td>


                            <td class="whitespace-nowrap px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                {{ \Carbon\Carbon::parse($request->date_reported)->format('M d, Y') }}
                            </td>


                            <td class="whitespace-nowrap px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                {{ $request->dept_head_name ?? 'Unassigned' }}
                            </td>


                            <td class="whitespace-nowrap px-4 py-3">

                                @php
                                $priorityColor = match (
                                strtolower($request->priority_name ?? '')
                                ) {
                                'normal' =>
                                'bg-green-100 text-green-700 ring-green-600/20 dark:bg-green-900/40 dark:text-green-300',

                                'high' =>
                                'bg-orange-100 text-orange-700 ring-orange-600/20 dark:bg-orange-900/40 dark:text-orange-300',

                                'urgent' =>
                                'bg-red-100 text-red-700 ring-red-600/20 dark:bg-red-900/40 dark:text-red-300',

                                default =>
                                'bg-zinc-100 text-zinc-700 ring-zinc-600/20 dark:bg-zinc-700 dark:text-zinc-300',
                                };
                                @endphp

                                <span
                                    class="inline-flex items-center gap-1.5
                                               rounded-full px-2.5 py-1 text-xs font-semibold
                                               ring-1 ring-inset {{ $priorityColor }}">
                                    {{ $request->priority_name ?? 'N/A' }}
                                </span>

                            </td>


                            <td class="whitespace-nowrap px-4 py-3">

                                @if ($request->status_name === 'PENDING')

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-yellow-100 px-2.5 py-0.5 text-xs font-medium
                                                   text-yellow-800
                                                   dark:bg-yellow-900/40 dark:text-yellow-200">
                                    {{ $request->status_name }}
                                </span>

                                @elseif ($request->status_name === 'APPROVED')

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-green-100 px-2.5 py-0.5 text-xs font-medium
                                                   text-green-800
                                                   dark:bg-green-900/40 dark:text-green-200">
                                    {{ $request->status_name }}
                                </span>

                                @elseif ($request->status_name === 'CLOSED')

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-blue-100 px-2.5 py-0.5 text-xs font-medium
                                                   text-blue-800
                                                   dark:bg-blue-900/40 dark:text-blue-200">
                                    {{ $request->status_name }}
                                </span>

                                @else

                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-zinc-100 px-2.5 py-0.5 text-xs font-medium
                                                   text-zinc-800
                                                   dark:bg-zinc-700 dark:text-zinc-300">
                                    {{ $request->status_name }}
                                </span>

                                @endif

                            </td>


                            <td class="whitespace-nowrap px-4 py-3 text-center">

                                <flux:dropdown align="end">

                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="ellipsis-vertical" />

                                    <flux:menu>

                                        @if ($request->record_type === 'CPAR')

                                        <flux:menu.item
                                            icon="eye"
                                            wire:click="viewCparDetails({{ $request->id }})">
                                            View CPAR Details
                                        </flux:menu.item>

                                        @elseif ($request->record_type === 'RESULT')

                                        <flux:menu.item
                                            icon="eye"
                                            wire:click="viewResultDetails({{ $request->id }})">
                                            View Result Details
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
                                class="px-4 py-10 text-center text-zinc-500 dark:text-zinc-400">
                                No requests found.
                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </flux:modal>
</div>