<div>

    {{-- HR Decision Modal --}}
    <flux:modal
        name="HRDecisionModal"
        class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">

        <div class="space-y-6">

            {{-- Header --}}
            <div>
                <flux:heading size="lg">
                    HR Decision
                </flux:heading>

                <flux:text class="mt-1">
                    Review CPARs that are pending for HR decision.
                </flux:text>
            </div>

            <flux:separator />

            {{-- Search --}}
            <div class="flex items-center gap-2">

                <div class="flex-1">
                    <flux:input
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search CPAR No., employee, department..."
                        icon="magnifying-glass" />
                </div>

                @if ($search)
                <flux:button
                    variant="ghost"
                    icon="x-mark"
                    wire:click="$set('search', '')">
                    Clear
                </flux:button>
                @endif

            </div>

            {{-- Records --}}
            <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">

                <table class="w-full text-sm">

                    {{-- Table Header --}}
                    <thead class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">

                        <tr>

                            <th class="px-4 py-3 text-center font-semibold">
                                CPAR No.
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Reported Employee
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Department
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Disciplinary History
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Documents
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Status
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Action
                            </th>

                        </tr>

                    </thead>

                    {{-- Table Body --}}
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @forelse ($hrDecisionList as $cpar)

                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition text-center">

                            {{-- CPAR --}}
                            <td class="px-4 py-4 whitespace-nowrap">

                                <div class="font-semibold text-zinc-900 dark:text-white">
                                    {{ $cpar->cpar_no }}
                                </div>

                            </td>

                            {{-- Employee --}}
                            <td class="px-4 py-4">

                                <div class="font-medium text-zinc-900 dark:text-white">
                                    {{ $cpar->employee_name }}
                                </div>

                            </td>

                            {{-- Department --}}
                            <td class="px-4 py-4">

                                <span class="text-zinc-600 dark:text-zinc-400">
                                    {{ $cpar->department_name }}
                                </span>

                            </td>

                            {{-- Disciplinary History --}}
                            <td class="px-4 py-4 text-center">

                                @if ($cpar->offense_count == 0)

                                <flux:badge
                                    color="green"
                                    icon="check-circle">
                                    No Previous Offense
                                </flux:badge>

                                @elseif ($cpar->offense_count == 1)

                                <flux:badge
                                    color="yellow"
                                    icon="exclamation-triangle">
                                    1 Previous
                                </flux:badge>

                                @elseif ($cpar->offense_count == 2)

                                <flux:badge
                                    color="orange"
                                    icon="exclamation-triangle">
                                    2 Previous
                                </flux:badge>

                                @else

                                <flux:badge
                                    color="red"
                                    icon="exclamation-triangle">
                                    {{ $cpar->offense_count }} Previous
                                </flux:badge>

                                @endif

                            </td>

                            {{-- Documents --}}
                            <td class="px-4 py-4">

                                <div class="flex justify-center gap-2">

                                    @if (!empty($cpar->ir_id))

                                    <flux:badge
                                        color="red">
                                        submitted IR
                                    </flux:badge>

                                    @endif

                                    @if (!empty($cpar->nte_no))

                                    <flux:badge
                                        color="blue">
                                        submitted NTE
                                    </flux:badge>

                                    @endif

                                    @if (empty($cpar->ir_id) && empty($cpar->nte_no))

                                    <span class="text-zinc-400">
                                        —
                                    </span>

                                    @endif

                                </div>

                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-4 text-center">

                                <flux:badge color="yellow">
                                    {{ $cpar->status_name }}
                                </flux:badge>

                            </td>

                            <td class="px-4 py-3 text-center">

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
                                            wire:click="viewCpar({{ $cpar->assignment_id }})">
                                            View
                                        </flux:menu.item>

                                    </flux:menu>
                                </flux:dropdown>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="9" class="px-4 py-12 text-center">

                                <flux:icon
                                    name="check-circle"
                                    class="mx-auto size-10 text-green-500" />

                                <flux:heading
                                    size="sm"
                                    class="mt-3">

                                    @if ($search)
                                    No CPAR Found
                                    @else
                                    No CPAR Pending for HR Decision
                                    @endif

                                </flux:heading>

                                <flux:text class="mt-1 text-zinc-500">

                                    @if ($search)

                                    No results found for
                                    <strong>"{{ $search }}"</strong>.

                                    @else

                                    All CPAR requests have been reviewed.

                                    @endif

                                </flux:text>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </flux:modal>

</div>