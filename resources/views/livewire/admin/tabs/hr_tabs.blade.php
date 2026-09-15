<div>

    <div class="flex flex-col gap-4 mb-4 lg:flex-row lg:items-center lg:justify-between">

        <div class="flex items-center gap-2 shrink-0">

            <span class="text-sm text-zinc-500 dark:text-zinc-400 whitespace-nowrap">
                Rows per page
            </span>

            <flux:select
                wire:model.live="perPage"
                class="w-24">

                <flux:select.option value="10">10</flux:select.option>
                <flux:select.option value="25">25</flux:select.option>
                <flux:select.option value="50">50</flux:select.option>
                <flux:select.option value="100">100</flux:select.option>

            </flux:select>

        </div>

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end w-full lg:w-auto">

            <div class="w-full sm:w-80 lg:w-96">

                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="CPAR / RESULT No., employee, reported by..."
                    icon="magnifying-glass" />

            </div>

            @if ($search)

                <flux:button
                    type="button"
                    variant="ghost"
                    icon="x-mark"
                    wire:click="clearSearch"
                    class="shrink-0">

                    Clear Search

                </flux:button>

            @endif

        </div>

    </div>

    <div class="w-full overflow-x-auto">

        <div class="mt-4 overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">

            <table class="w-full min-w-[1200px] text-sm">

                <thead class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">

                    <tr>

                        <th class="px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-300">
                            Type
                        </th>

                        <th class="px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-300">
                            Reported By
                        </th>

                        <th class="px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-300">
                            Employee Reported
                        </th>

                        <th class="px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-300">
                            Date Reported
                        </th>

                        <th class="px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-300">
                            Valid Until
                        </th>

                        <th class="px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-300">
                            Offense
                        </th>

                        <th class="px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-300">
                            Category
                        </th>

                        <th class="px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-300">
                            Documents
                        </th>

                        <th class="px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-300">
                            Status
                        </th>

                        <th class="px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-300">
                            Tracker
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                    @forelse ($cpar_offense as $cpar)

                        <tr class="text-center hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">


                            {{-- =================================================
                                RECORD NO. + RECORD TYPE
                            ================================================== --}}

                            <td class="px-4 py-4 whitespace-nowrap">

                                <div class="flex flex-col items-center gap-1">

                                    <span class="font-semibold text-zinc-900 dark:text-white">
                                        {{ $cpar->record_no ?? '—' }}
                                    </span>

                                    @if (($cpar->record_type ?? '') === 'CPAR')

                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold
                                            bg-red-100 text-red-700
                                            dark:bg-red-950 dark:text-red-400">

                                            CPAR

                                        </span>

                                    @elseif (($cpar->record_type ?? '') === 'RESULT')

                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold
                                            bg-blue-100 text-blue-700
                                            dark:bg-blue-950 dark:text-blue-400">

                                            RESULT

                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- =================================================
                                REPORTED BY
                            ================================================== --}}

                            <td class="px-4 py-4">

                                <span class="font-medium text-zinc-900 dark:text-white">

                                    {{ $cpar->reported_by ?? '—' }}

                                </span>

                            </td>


                            {{-- =================================================
                                EMPLOYEE
                            ================================================== --}}

                            <td class="px-4 py-4">

                                <span class="font-medium text-zinc-900 dark:text-white">

                                    {{ $cpar->employee_name ?? '—' }}

                                </span>

                            </td>


                            {{-- =================================================
                                DATE
                            ================================================== --}}

                            <td class="px-4 py-4 whitespace-nowrap">

                                @if (!empty($cpar->record_date))

                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                                        bg-zinc-100 text-zinc-700
                                        dark:bg-zinc-800 dark:text-zinc-300">

                                        {{ \Carbon\Carbon::parse($cpar->record_date)->format('M d, Y') }}

                                    </span>

                                @else

                                    <span class="text-zinc-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                VALID UNTIL
                            ================================================== --}}

                            <td class="px-4 py-4 whitespace-nowrap">

                                @if (!empty($cpar->valid_until))

                                    @php
                                        $validUntil = \Carbon\Carbon::parse($cpar->valid_until);
                                    @endphp

                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                                        {{ $validUntil->isFuture()
                                            ? 'bg-green-100 text-green-700 dark:bg-green-950 dark:text-green-400'
                                            : 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-400'
                                        }}">

                                        {{ $validUntil->format('M d, Y') }}

                                    </span>

                                @else

                                    <span class="text-zinc-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                OFFENSE
                            ================================================== --}}

                            <td class="px-4 py-4">

                                <div class="flex flex-wrap justify-center gap-1">

                                    @if (!empty($cpar->decision_name))

                                        @foreach (explode(', ', $cpar->decision_name) as $decision)

                                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                                                bg-red-100 text-red-700
                                                dark:bg-red-950 dark:text-red-400">

                                                {{ $decision }}

                                            </span>

                                        @endforeach

                                    @else

                                        <span class="text-zinc-400">
                                            —
                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- =================================================
                                CATEGORY
                            ================================================== --}}

                            <td class="px-4 py-4">

                                <div class="flex flex-wrap justify-center gap-1">

                                    @if (!empty($cpar->category_name))

                                        @foreach (explode(', ', $cpar->category_name) as $category)

                                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                                                bg-red-100 text-red-700
                                                dark:bg-red-950 dark:text-red-400">

                                                {{ $category }}

                                            </span>

                                        @endforeach

                                    @else

                                        <span class="text-zinc-400">
                                            —
                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- =================================================
                                DOCUMENTS
                            ================================================== --}}

                            <td class="px-4 py-4">

                                <div class="flex flex-wrap justify-center gap-2">


                                    {{-- IR --}}

                                    @if (!empty($cpar->ir_id))

                                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                                            bg-red-100 text-red-700
                                            dark:bg-red-950 dark:text-red-400">

                                            IR Submitted

                                        </span>

                                    @endif


                                    {{-- NTE --}}

                                    @if (!empty($cpar->nte_no))

                                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                                            bg-blue-100 text-blue-700
                                            dark:bg-blue-950 dark:text-blue-400">

                                            NTE Submitted

                                        </span>

                                    @endif


                                    {{-- NONE --}}

                                    @if (empty($cpar->ir_id) && empty($cpar->nte_no))

                                        <span class="text-zinc-400">
                                            —
                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- =================================================
                                STATUS
                            ================================================== --}}

                            <td class="px-4 py-4 whitespace-nowrap">

                                @if (!empty($cpar->status_name))

                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                                        bg-yellow-100 text-yellow-700
                                        dark:bg-yellow-950 dark:text-yellow-400">

                                        {{ $cpar->status_name }}

                                    </span>

                                @else

                                    <span class="text-zinc-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                TRACKER
                            ================================================== --}}

                            <td class="px-4 py-4 whitespace-nowrap">

                                @php

                                    $tracker = match ((int) $cpar->status_id) {

                                        1, 15 => [
                                            'label' => 'Department Head',
                                            'color' => 'blue'
                                        ],

                                        5, 20, 25, 30, 40 => [
                                            'label' => 'HR Head',
                                            'color' => 'purple'
                                        ],

                                        10, 23 => [
                                            'label' => 'Reported Employee',
                                            'color' => 'orange'
                                        ],

                                        35 => [
                                            'label' => 'LAB Supervisor',
                                            'color' => 'green'
                                        ],

                                        50, 55 => [
                                            'label' => 'Done',
                                            'color' => 'green'
                                        ],

                                        default => [
                                            'label' => 'No Tracker',
                                            'color' => 'gray'
                                        ],

                                    };


                                    $trackerClass = match ($tracker['color']) {

                                        'blue' =>
                                            'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',

                                        'purple' =>
                                            'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',

                                        'orange' =>
                                            'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300',

                                        'green' =>
                                            'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',

                                        default =>
                                            'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-300',

                                    };

                                @endphp


                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $trackerClass }}">

                                    {{ $tracker['label'] }}

                                </span>

                            </td>

                        </tr>


                    @empty


                        {{-- =================================================
                            EMPTY
                        ================================================== --}}

                        <tr>

                            <td colspan="10" class="px-4 py-12 text-center">

                                <div class="flex flex-col items-center justify-center">

                                    <div class="flex items-center justify-center w-12 h-12 rounded-full
                                        bg-green-100 dark:bg-green-950">

                                        <svg
                                            class="w-6 h-6 text-green-600 dark:text-green-400"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="m5 12 4 4L19 6" />

                                        </svg>

                                    </div>


                                    <h3 class="mt-3 text-sm font-semibold text-zinc-900 dark:text-white">

                                        No CPAR / RESULT Found

                                    </h3>


                                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">

                                        @if ($search)

                                            No CPAR or RESULT found for

                                            <span class="font-medium">
                                                "{{ $search }}"
                                            </span>.

                                        @else

                                            There are no CPAR or RESULT concerns.

                                        @endif

                                    </p>

                                </div>

                            </td>

                        </tr>


                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($cpar_offense->hasPages())

            <div class="mt-4 pt-2">

                {{ $cpar_offense->links() }}

            </div>

        @endif

    </div>

</div>