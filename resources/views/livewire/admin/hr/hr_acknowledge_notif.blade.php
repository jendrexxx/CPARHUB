<div>

    {{-- CPAR / Result Error Acknowledgment Modal --}}
    <flux:modal
        name="CPARAcknowledgeModal"
        class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">

        <div class="space-y-6">

            {{-- Header --}}
            <div>
                <flux:heading size="lg">
                    Acknowledgment Concern
                </flux:heading>
                <flux:text>
                    Please review the details of this concern and confirm your acknowledgment.
                </flux:text>
            </div>
            <div class="w-full overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                <table class="w-full text-sm text-center">
                    <thead class="bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-center">
                                Type
                            </th>

                            <th class="px-4 py-3 text-center">
                                Request No.
                            </th>

                            <th class="px-4 py-3 text-center">
                                Reported By
                            </th>

                            <th class="px-4 py-3 text-center">
                                Date Reported
                            </th>

                            <th class="px-4 py-3 text-center">
                                Assigned To
                            </th>

                            <th class="px-4 py-3 text-center">
                                Priority level
                            </th>

                            <th class="px-4 py-3 text-center">
                                Documents
                            </th>

                            <th class="px-4 py-3 text-center">
                                Status
                            </th>

                            <th class="px-4 py-3 text-center">
                                Action
                            </th>

                        </tr>

                    </thead>
                    {{-- TABLE BODY --}}
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse ($acknowledgment_requests as $request)
                        <tr wire:key="acknowledgment-{{ $request->record_type }}-{{ $request->assignment_id }}">

                            {{-- TYPE --}}
                            <td class="px-4 py-3">
                                @if ($request->record_type === 'CPAR')
                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-red-100 text-red-700
                                                   dark:bg-red-950 dark:text-red-400
                                                   px-2.5 py-1 text-xs font-semibold">
                                    CPAR
                                </span>
                                @elseif ($request->record_type === 'RESULT')
                                <span
                                    class="inline-flex items-center rounded-full
                                                   bg-purple-100 text-purple-700
                                                   dark:bg-purple-950 dark:text-purple-400
                                                   px-2.5 py-1 text-xs font-semibold">
                                    RESULT
                                </span>
                                @endif
                            </td>

                            {{-- REQUEST NO --}}
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $request->record_no }}
                            </td>

                            {{-- REPORTED BY --}}
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                {{ $request->reported_by }}
                            </td>


                            {{-- DATE --}}
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">

                                {{ \Carbon\Carbon::parse($request->record_date)->format('M d, Y') }}

                            </td>

                            {{-- ASSIGNED TO --}}
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">

                                {{ $request->first_name ?? '' }}
                                {{ $request->last_name ?? '' }}

                                @if (empty($request->first_name) && empty($request->last_name))
                                <span class="text-zinc-400">
                                    Unassigned
                                </span>
                                @endif

                            </td>


                            {{-- PRIORITY --}}
                            <td class="px-4 py-3">

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
                                               rounded-full px-2.5 py-1
                                               text-xs font-semibold
                                               ring-1 ring-inset
                                               {{ $priorityColor }}">
                                    {{ $request->priority_name ?? 'N/A' }}
                                </span>

                            </td>

                            {{-- DOCUMENTS --}}
                            <td class="px-4 py-4">

                                <div class="flex justify-center gap-2">
                                    {{-- IR --}}
                                    @if (!empty($request->ir_id))
                                    <span
                                        class="inline-flex items-center
                                                           rounded-full
                                                           px-2.5 py-1
                                                           text-xs font-medium
                                                           bg-red-100 text-red-700
                                                           dark:bg-red-950
                                                           dark:text-red-400">
                                        Submitted IR
                                    </span>
                                    @endif
                                    {{-- NTE --}}
                                    @if (!empty($request->nte_no))
                                    <span
                                        class="inline-flex items-center
                                                           rounded-full
                                                           px-2.5 py-1
                                                           text-xs font-medium
                                                           bg-blue-100 text-blue-700
                                                           dark:bg-blue-950
                                                           dark:text-blue-400">
                                        Submitted NTE
                                    </span>
                                    @endif

                                    {{-- NO DOCUMENT --}}
                                    @if (
                                    empty($request->ir_id) &&
                                    empty($request->nte_no)
                                    )
                                    <span class="text-zinc-400">
                                        —
                                    </span>

                                    @endif

                                </div>

                            </td>


                            {{-- STATUS --}}
                            <td class="px-4 py-3">

                                @if ($request->status_name === 'PENDING')

                                <span
                                    class="inline-flex items-center
                                                   rounded-full
                                                   px-2.5 py-0.5
                                                   text-xs font-medium
                                                   bg-yellow-100 text-yellow-800
                                                   dark:bg-yellow-900
                                                   dark:text-yellow-200">
                                    PENDING
                                </span>


                                @elseif ($request->status_name === 'ASSIGNED')

                                <span
                                    class="inline-flex items-center
                                                   rounded-full
                                                   px-2.5 py-0.5
                                                   text-xs font-medium
                                                   bg-blue-100 text-blue-800
                                                   dark:bg-blue-900
                                                   dark:text-blue-200">
                                    ASSIGNED
                                </span>


                                @elseif ($request->status_name === 'APPROVED')

                                <span
                                    class="inline-flex items-center
                                                   rounded-full
                                                   px-2.5 py-0.5
                                                   text-xs font-medium
                                                   bg-green-100 text-green-800
                                                   dark:bg-green-900
                                                   dark:text-green-200">
                                    APPROVED
                                </span>


                                @elseif ($request->status_name === 'CLOSED')

                                <span
                                    class="inline-flex items-center
                                                   rounded-full
                                                   px-2.5 py-0.5
                                                   text-xs font-medium
                                                   bg-indigo-100 text-indigo-800
                                                   dark:bg-indigo-900
                                                   dark:text-indigo-200">
                                    CLOSED
                                </span>


                                @else

                                <span
                                    class="inline-flex items-center
                                                   rounded-full
                                                   px-2.5 py-0.5
                                                   text-xs font-medium
                                                   bg-zinc-100 text-zinc-800
                                                   dark:bg-zinc-700
                                                   dark:text-zinc-300">
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
                                        icon="ellipsis-vertical"
                                        aria-label="Actions" />

                                    <flux:menu>

                                        {{-- CPAR --}}
                                        @if ($request->record_type === 'CPAR')

                                        @if (!empty($request->ir_request_id))

                                        <flux:menu.item
                                            icon="document-text"
                                            wire:click="createNoticeToExplain({{ $request->ir_request_id }})">
                                            Request NTE
                                        </flux:menu.item>

                                        @endif

                                        {{-- RESULT ERROR --}}
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
                                colspan="10"
                                class="px-4 py-10 text-center text-zinc-500 dark:text-zinc-400">
                                No acknowledgment requests found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </flux:modal>

</div>