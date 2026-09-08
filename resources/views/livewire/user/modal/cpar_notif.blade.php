<div>
    <flux:modal name="CPARModal" class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">
        <div class="space-y-6">

            <div>
                <flux:heading size="lg">Reported Concern</flux:heading>
                <flux:text>
                    Below is the list of your filed Reported Concern.
                </flux:text>
            </div>

            <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">

                <table class="w-full text-sm text-center">

                    <thead class="bg-zinc-100 dark:bg-zinc-800
                        text-zinc-600 dark:text-zinc-300
                        text-xs tracking-wider">

                        <tr>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Concern No.</th>
                            <th class="px-4 py-3">Reported By</th>
                            <th class="px-4 py-3">Date Reported</th>
                            <th class="px-4 py-3">Assigned To</th>
                            <th class="px-4 py-3">Priority Level</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @forelse ($requests as $request)

                        <tr wire:key="{{ strtolower($request->record_type) }}-{{ $request->id }}">

                            {{-- TYPE --}}
                            <td class="px-4 py-3">

                                @if ($request->record_type === 'CPAR')

                                <span class="inline-flex items-center rounded-full
                                            bg-blue-100 px-2.5 py-1 text-xs font-semibold
                                            text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                    CPAR
                                </span>

                                @else

                                <span class="inline-flex items-center rounded-full
                                            bg-purple-100 px-2.5 py-1 text-xs font-semibold
                                            text-purple-700 ring-1 ring-inset ring-purple-600/20">
                                    RESULT
                                </span>

                                @endif

                            </td>


                            {{-- CONCERN NO. --}}
                            <td class="px-4 py-3 font-medium
                                    text-zinc-900 dark:text-zinc-100">

                                {{ $request->record_no }}

                            </td>


                            {{-- REPORTED BY --}}
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">

                                {{ $request->reported_by }}

                            </td>


                            {{-- DATE --}}
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">

                                {{ \Carbon\Carbon::parse($request->date_reported)->format('M d, Y') }}

                            </td>


                            {{-- ASSIGNED TO --}}
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">

                                {{ $request->dept_head_name ?? 'Unassigned' }}

                            </td>


                            {{-- PRIORITY --}}
                            <td class="px-4 py-3">

                                @php
                                $priorityColor = match (
                                strtolower($request->priority_name ?? '')
                                ) {
                                'normal' =>
                                'bg-green-100 text-green-700 ring-green-600/20',

                                'high' =>
                                'bg-orange-100 text-orange-700 ring-orange-600/20',

                                'urgent' =>
                                'bg-red-100 text-red-700 ring-red-600/20',

                                default =>
                                'bg-zinc-100 text-zinc-700 ring-zinc-600/20',
                                };
                                @endphp

                                <span class="inline-flex items-center gap-1.5
                                        rounded-full px-2.5 py-1 text-xs font-semibold
                                        ring-1 ring-inset {{ $priorityColor }}">

                                    {{ $request->priority_name ?? 'N/A' }}

                                </span>

                            </td>


                            {{-- STATUS --}}
                            <td class="px-4 py-3">

                                @if ($request->status_name === 'PENDING')

                                <span class="inline-flex items-center
                                            px-2.5 py-0.5 rounded-full text-xs font-medium
                                            bg-yellow-100 text-yellow-800
                                            dark:bg-yellow-900 dark:text-yellow-200">

                                    {{ $request->status_name }}

                                </span>

                                @elseif ($request->status_name === 'APPROVED')

                                <span class="inline-flex items-center
                                            px-2.5 py-0.5 rounded-full text-xs font-medium
                                            bg-green-100 text-green-800
                                            dark:bg-green-900 dark:text-green-200">

                                    {{ $request->status_name }}

                                </span>

                                @elseif ($request->status_name === 'CLOSED')

                                <span class="inline-flex items-center
                                            px-2.5 py-0.5 rounded-full text-xs font-medium
                                            bg-blue-100 text-blue-800
                                            dark:bg-blue-900 dark:text-blue-200">

                                    {{ $request->status_name }}

                                </span>

                                @else

                                <span class="inline-flex items-center
                                            px-2.5 py-0.5 rounded-full text-xs font-medium
                                            bg-zinc-100 text-zinc-800
                                            dark:bg-zinc-700 dark:text-zinc-300">

                                    {{ $request->status_name }}

                                </span>

                                @endif

                            </td>


                            {{-- ACTION --}}
                            <td class="px-4 py-3 text-center">

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
                                colspan="9"
                                class="px-4 py-10 text-center
                                    text-zinc-500 dark:text-zinc-400">
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