<div>

    {{-- CPAR Submission Modal --}}
    <flux:modal name="CPARSubmissionModal" class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">
        <div class="space-y-6">
            {{-- Header --}}
            <div>
                <flux:heading size="lg">
                    Acknowledgment Concern
                </flux:heading>

                <flux:text>
                    Please review the Acknowledgment Concern requests.
                </flux:text>
            </div>


            {{-- Table --}}
            <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700 text-center">
                <div class="w-full overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                    <table class="w-full text-sm text-center">

                        <thead class="bg-zinc-100 dark:bg-zinc-800">
                            <tr>

                                <th class="px-4 py-3">
                                    CPAR No.
                                </th>

                                <th class="px-4 py-3">
                                    Reported By
                                </th>

                                <th class="px-4 py-3">
                                    Date Open
                                </th>

                                <th class="px-4 py-3">
                                    Department
                                </th>

                                <th class="px-4 py-3">
                                    Assigned To
                                </th>

                                <th class="px-4 py-3">
                                    Status
                                </th>

                                <th class="px-4 py-3">
                                    Action
                                </th>

                            </tr>
                        </thead>


                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                            @forelse ($cpar_requests as $request)

                            <tr wire:key="submission-{{ $request->assignment_id }}">

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

                                    @if (in_array($request->status_name, ['PENDING', 'UNRESOLVED']))

                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        {{ $request->status_name }}
                                    </span>

                                    @else

                                    <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300">
                                        {{ $request->status_name }}
                                    </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="px-4 py-3 text-center">
                                    <flux:dropdown align="end">

                                        <flux:button
                                            size="sm"
                                            variant="ghost"
                                            icon="ellipsis-vertical"
                                            aria-label="Actions">
                                        </flux:button>

                                        <flux:menu>

                                            <flux:menu.item
                                                icon="eye"
                                                wire:click="ViewSubmissionCPAR({{ $request->assignment_id }})">
                                                View Acknowledgment
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

                                    No CPAR submission requests found.

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