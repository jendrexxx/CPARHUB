<div>
    <flux:modal
        name="LABAssignedModal"
        class="w-[150%] max-w-[1500px] mt-6 top-0 z-50">
        <div class="space-y-6">

            {{-- Header --}}
            <div>
                <flux:heading size="lg">
                    LAB Requests
                </flux:heading>

                <flux:text>
                    Below is the list of CPAR and Result Error requests assigned to MANAGEMENT.
                </flux:text>
            </div>
            <div class="flex items-center justify-between mb-4">

                <div class="flex items-center gap-2">

                    <span class="text-sm text-zinc-600 dark:text-zinc-400">
                        Rows:
                    </span>

                    <flux:select
                        wire:model.live="perPage"
                        class="w-24">

                        <flux:select.option value="5">
                            5
                        </flux:select.option>

                        <flux:select.option value="10">
                            10
                        </flux:select.option>

                        <flux:select.option value="50">
                            50
                        </flux:select.option>

                        <flux:select.option value="100">
                            100
                        </flux:select.option>

                    </flux:select>

                </div>

            </div>

            <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">

                <table class="w-full text-sm text-center">

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
                                Department Name
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

                        @forelse ($lab_requests as $request)

                        <tr
                            wire:key="lab-{{ $request->record_type }}-{{ $request->assignment_id }}"
                            class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

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

                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $request->record_no }}
                            </td>

                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                {{ $request->reported_by }}
                            </td>

                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                {{ \Carbon\Carbon::parse($request->record_date)->format('M d, Y') }}
                            </td>

                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                {{ $request->employee_name ?: $request->emp_dept_name }}
                            </td>

                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                {{ $request->employee_department ?: $request->dept_department }}
                            </td>

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

                            <td class="px-4 py-3 text-center">

                                <flux:dropdown align="end">

                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="ellipsis-vertical" />

                                    <flux:menu>

                                        @if ($request->record_type === 'CPAR')

                                        <flux:menu.item
                                            icon="arrow-path"
                                            wire:click="UpdateAssign({{ $request->assignment_id }})">

                                            Re-Assign

                                        </flux:menu.item>

                                        @elseif ($request->record_type === 'RESULT')

                                        <flux:menu.item
                                            icon="arrow-path"
                                            wire:click="viewResultDetails({{ $request->assignment_id }})">

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

            @if ($labTotal > 0)

            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div class="text-sm text-zinc-600 dark:text-zinc-400">

                    Showing
                    {{ (($labPage - 1) * $perPage) + 1 }}
                    -
                    {{ min($labPage * $perPage, $labTotal) }}
                    of
                    {{ $labTotal }}

                </div>

                <div class="flex items-center gap-1">

                    <flux:button
                        size="sm"
                        variant="ghost"
                        wire:click="previousLabPage"
                        :disabled="$labPage <= 1">

                        Previous

                    </flux:button>

                    @for ($page = 1; $page <= $labLastPage; $page++)

                        <flux:button
                        size="sm"
                        wire:click="goToLabPage({{ $page }})"
                        :variant="$labPage === $page ? 'primary' : 'ghost'">

                        {{ $page }}

                        </flux:button>

                        @endfor

                        <flux:button
                            size="sm"
                            variant="ghost"
                            wire:click="nextLabPage"
                            :disabled="$labPage >= $labLastPage">

                            Next

                        </flux:button>

                </div>

            </div>

            @endif
        </div>
    </flux:modal>
</div>