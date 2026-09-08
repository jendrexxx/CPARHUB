<div>
    <flux:modal
        name="CPARHRModal"
        class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">
        <div class="space-y-6">

            {{-- Header --}}
            <div>
                <flux:heading size="lg">
                    HR Requests
                </flux:heading>

                <flux:text>
                    Below is the list of CPAR and Result Error requests assigned to HR.
                </flux:text>
            </div>


            {{-- Table --}}
            <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">

                <table class="w-full text-sm text-center">

                    {{-- Header --}}
                    <thead class="bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 uppercase text-xs tracking-wider">

                        <tr>
                            <th class="px-4 py-3">
                                Type
                            </th>

                            <th class="px-4 py-3">
                                No.
                            </th>

                            <th class="px-4 py-3">
                                Reported By
                            </th>

                            <th class="px-4 py-3">
                                Date Reported
                            </th>

                            <th class="px-4 py-3">
                                Reported Employee
                            </th>

                            <th class="px-4 py-3">
                                Status
                            </th>

                            <th class="px-4 py-3">
                                Action
                            </th>
                        </tr>

                    </thead>


                    {{-- Body --}}
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @forelse ($hr_requests as $request)

                        <tr
                            wire:key="{{ strtolower($request->record_type) }}-{{ $request->id }}"
                            class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                            {{-- TYPE --}}
                            <td class="px-4 py-3">

                                @if ($request->record_type === 'CPAR')

                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    CPAR
                                </span>

                                @else

                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                    RESULT ERROR
                                </span>

                                @endif

                            </td>


                            {{-- NUMBER --}}
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


                            {{-- ASSIGNED EMPLOYEE --}}
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">

                                {{ $request->dept_head_name ?? 'N/A' }}

                            </td>


                            {{-- STATUS --}}
                            <td class="px-4 py-3">

                                @if ($request->status_name === 'PENDING')

                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    {{ $request->status_name }}
                                </span>

                                @elseif ($request->status_name === 'APPROVED')

                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ $request->status_name }}
                                </span>

                                @elseif ($request->status_name === 'CLOSED')

                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $request->status_name }}
                                </span>

                                @elseif ($request->status_name === 'ASSIGNED')

                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                    {{ $request->status_name }}
                                </span>

                                @else

                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300">
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

                                        {{-- CPAR --}}
                                        @if ($request->record_type === 'CPAR')
                                        <flux:menu.item
                                            icon="arrow-path"
                                            wire:click="UpdateAssign({{ $request->record_id }})">
                                            Re-Assign
                                        </flux:menu.item>
                                        {{-- RESULT ERROR --}}
                                        @elseif ($request->record_type === 'RESULT')
                                        <flux:menu.item
                                            icon="arrow-path"
                                            wire:click="viewResultDetails({{ $request->result_id }})">
                                            Re-Assign
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
                                No HR requests found.
                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </flux:modal>
</div>