<div>

    {{-- CPAR / Result Error Submission Modal --}}
    <flux:modal
        name="CPARSubmissionModal"
        class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">

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
            <div class="w-full overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">

                <table class="w-full text-sm text-center">

                    <thead class="bg-zinc-100 dark:bg-zinc-800">

                        <tr>

                            <th class="px-4 py-3">
                                Type
                            </th>

                            <th class="px-4 py-3">
                                Request No.
                            </th>

                            <th class="px-4 py-3">
                                Reported By
                            </th>

                            <th class="px-4 py-3">
                                Date Reported
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

                        @forelse ($acknowledgment_requests as $request)

                        <tr wire:key="acknowledgment-{{ $request->record_type }}-{{ $request->assignment_id }}">

                            {{-- Type --}}
                            <td class="px-4 py-3">

                                @if ($request->record_type === 'CPAR')

                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700 dark:bg-red-900 dark:text-red-200">
                                    CPAR
                                </span>

                                @else

                                <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-1 text-xs font-semibold text-purple-700 dark:bg-purple-900 dark:text-purple-200">
                                    RESULT ERROR
                                </span>

                                @endif

                            </td>


                            {{-- Request No --}}
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">

                                {{ $request->record_no }}

                            </td>


                            {{-- Reported By --}}
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">

                                {{ $request->reported_by }}

                            </td>


                            {{-- Date Reported --}}
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">

                                {{ \Carbon\Carbon::parse($request->record_date)->format('M d, Y') }}

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

                                @elseif ($request->status_name === 'APPROVED')

                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
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
                                        aria-label="Actions" />

                                    <flux:menu>

                                        @if ($request->record_type === 'CPAR')

                                        <flux:menu.item
                                            icon="eye"
                                            wire:click="ViewSubmissionCPAR({{ $request->assignment_id }})">
                                            View CPAR Acknowledgment
                                        </flux:menu.item>

                                        @elseif ($request->record_type === 'RESULT')

                                        <flux:menu.item
                                            icon="eye"
                                            wire:click="ViewSubmissionResult({{ $request->assignment_id }})">
                                            View Result Acknowledgment
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