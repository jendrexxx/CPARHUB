<div>

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
                    Please review the details of this CPAR concern and confirm your acknowledgment.
                </flux:text>
            </div>
            {{-- Table --}}
            <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                <table class="w-full text-sm text-center">
                    <thead class="bg-zinc-100 dark:bg-zinc-800
                                  text-zinc-600 dark:text-zinc-300
                                  uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-3">CPAR No.</th>
                            <th class="px-4 py-3">Reported By</th>
                            <th class="px-4 py-3">Date Open</th>
                            <th class="px-4 py-3">Department</th>
                            <th class="px-4 py-3">Assigned To</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse ($cpar_acknowledge as $request)
                        <tr wire:key="assignment-{{ $request->assignment_id }}">
                            {{-- CPAR No --}}
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $request->cpar_no }}
                            </td>
                            {{-- Reported By --}}
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                {{ $request->reported_by }}
                            </td>
                            {{-- Date Open --}}
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                {{ \Carbon\Carbon::parse($request->date_open)->format('M d, Y') }}
                            </td>
                            {{-- Department --}}
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                {{ $request->department_name }}
                            </td>
                            {{-- Assigned Employee --}}
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                {{ $request->first_name }}
                                {{ $request->last_name }}
                            </td>
                            {{-- Status --}}
                            <td class="px-4 py-3">
                                @if ($request->status_name === 'PENDING')
                                <span class="inline-flex items-center px-2.5 py-0.5
                                                 rounded-full text-xs font-medium
                                                 bg-yellow-100 text-yellow-800
                                                 dark:bg-yellow-900 dark:text-yellow-200">
                                    PENDING
                                </span>

                                @elseif ($request->status_name === 'ASSIGNED')

                                <span class="inline-flex items-center px-2.5 py-0.5
                                                 rounded-full text-xs font-medium
                                                 bg-blue-100 text-blue-800
                                                 dark:bg-blue-900 dark:text-blue-200">
                                    ASSIGNED
                                </span>

                                @elseif ($request->status_name === 'APPROVED')

                                <span class="inline-flex items-center px-2.5 py-0.5
                                                 rounded-full text-xs font-medium
                                                 bg-green-100 text-green-800
                                                 dark:bg-green-900 dark:text-green-200">
                                    APPROVED
                                </span>

                                @elseif ($request->status_name === 'CLOSED')

                                <span class="inline-flex items-center px-2.5 py-0.5
                                                 rounded-full text-xs font-medium
                                                 bg-indigo-100 text-indigo-800
                                                 dark:bg-indigo-900 dark:text-indigo-200">
                                    CLOSED
                                </span>

                                @else

                                <span class="inline-flex items-center px-2.5 py-0.5
                                                 rounded-full text-xs font-medium
                                                 bg-zinc-100 text-zinc-800
                                                 dark:bg-zinc-700 dark:text-zinc-300">
                                    {{ $request->status_name }}
                                </span>

                                @endif

                            </td>
                            {{-- Action --}}
                            <td class="px-4 py-3">

                                <flux:dropdown align="end">

                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="ellipsis-vertical">
                                    </flux:button>

                                    <flux:menu>

                                        {{-- View Details --}}
                                        <flux:menu.item
                                            icon="eye"
                                            wire:click="viewDetails({{ $request->assignment_id }})">
                                            View Details
                                        </flux:menu.item>

                                        {{-- Notice to Explain --}}
                                        <flux:menu.item
                                            icon="document-text"
                                            wire:click="createRequestIR({{ $request->assignment_id }})">
                                            Request IR
                                        </flux:menu.item>

                                        <flux:menu.item
                                            icon="document-text"
                                            wire:click="createNoticeToExplain({{ $request->assignment_id }})">
                                            Request NTE
                                        </flux:menu.item>

                                    </flux:menu>

                                </flux:dropdown>

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td
                                colspan="7"
                                class="px-4 py-10 text-center text-zinc-500 dark:text-zinc-400">

                                No CPAR requests found.

                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </flux:modal>

</div>