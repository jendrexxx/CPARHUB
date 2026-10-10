<div>

    {{-- SEARCH + ROWS --}}

    <div class="mb-4 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">

        <div class="flex flex-col gap-4 md:flex-row md:items-end">

            <div class="flex-1">

                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search CPAR No., Result No., reported by, employee..."
                    icon="magnifying-glass" />

            </div>

            <div class="w-full md:w-40">

                <flux:select
                    wire:model.live="perPage"
                    label="Rows">

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

    </div>


    {{-- TABLE --}}

    <div class="w-full overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">

        <table class="w-full text-center text-sm">
            <thead class="bg-red-800 text-xs tracking-wider text-white">

                <tr class="border-b-2 border-white/30">

                    <th class="px-4 py-3 text-center font-bold uppercase text-white">
                        Request No.
                    </th>

                    <th class="px-4 py-3 text-center font-bold uppercase text-white">
                        Reported By
                    </th>

                    <th class="px-4 py-3 text-center font-bold uppercase text-white">
                        Reported Employee
                    </th>

                    <th class="px-4 py-3 text-center font-bold uppercase text-white">
                        Status
                    </th>

                    <th class="px-4 py-3 text-center font-bold uppercase text-white">
                        Documents
                    </th>

                    <th class="px-4 py-3 text-center font-bold uppercase text-white">
                        Status Tracker
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                @forelse($this->cparOffense as $cpar)

                <tr
                    wire:key="cpar-record-{{ $cpar->record_type }}-{{ $cpar->assignment_id }}"
                    class="border-b border-zinc-100 dark:border-zinc-800">

                    {{-- REQUEST NO. --}}

                    <td class="px-4 py-3 font-medium">

                        {{ $cpar->cpar_no }}

                        @if($cpar->record_type === 'RESULT')

                        <div class="mt-1">

                            <span
                                class="inline-flex items-center rounded-full
                                        bg-purple-100 px-2 py-0.5
                                        text-[10px] font-semibold text-purple-700
                                        dark:bg-purple-900/30 dark:text-purple-300">
                                RESULT
                            </span>

                        </div>

                        @else

                        <div class="mt-1">

                            <span
                                class="inline-flex items-center rounded-full
                                        bg-blue-100 px-2 py-0.5
                                        text-[10px] font-semibold text-blue-700
                                        dark:bg-blue-900/30 dark:text-blue-300">
                                CPAR
                            </span>

                        </div>

                        @endif

                    </td>


                    {{-- REPORTED BY --}}

                    <td class="px-4 py-3">

                        {{ $cpar->reported_by }}

                        @if($cpar->employee_no)

                        <div class="text-xs text-zinc-500 dark:text-zinc-400">

                            {{ $cpar->employee_no }}

                        </div>

                        @endif

                    </td>


                    {{-- REPORTED EMPLOYEE --}}

                    <td class="px-4 py-3">

                        <div class="font-medium">

                            {{ $cpar->employee_name ?: ($cpar->dept_employee_name ?: 'Unassigned') }}

                        </div>

                    </td>


                    {{-- STATUS --}}

                    <td class="px-4 py-3">

                        <span
                            class="inline-flex items-center rounded-full
                                bg-zinc-100 px-3 py-1
                                text-xs font-semibold text-zinc-700
                                dark:bg-zinc-800 dark:text-zinc-300">

                            {{ $cpar->status_name ?? 'No Status' }}

                        </span>

                    </td>


                    {{-- DOCUMENTS --}}

                    <td class="px-4 py-3 text-center">

                        <div class="flex flex-wrap justify-center gap-2">

                            @if($cpar->ir_submitted)

                            <span
                                class="inline-flex items-center gap-1 rounded-full
                                        bg-blue-100 px-3 py-1
                                        text-xs font-semibold text-blue-700
                                        dark:bg-blue-900/30 dark:text-blue-300">

                                <flux:icon.document-text class="size-3.5" />

                                SUBMITTED IR

                            </span>

                            @endif


                            @if($cpar->nte_submitted)

                            <span
                                class="inline-flex items-center gap-1 rounded-full
                                        bg-purple-100 px-3 py-1
                                        text-xs font-semibold text-purple-700
                                        dark:bg-purple-900/30 dark:text-purple-300">

                                <flux:icon.document-text class="size-3.5" />

                                SUBMITTED NTE

                            </span>

                            @endif


                            @if(
                            !$cpar->ir_submitted &&
                            !$cpar->nte_submitted
                            )

                            <span
                                class="inline-flex items-center rounded-full
                                        bg-zinc-100 px-3 py-1
                                        text-xs font-semibold text-zinc-500
                                        dark:bg-zinc-800 dark:text-zinc-400">

                                N/A

                            </span>

                            @endif

                        </div>

                    </td>


                    {{-- STATUS TRACKER --}}

                    <td class="px-4 py-3 text-center">

                        @php

                        $tracker = match ((int) $cpar->status_id) {

                        1 => [
                        'label' => 'Department Head',
                        'color' => 'blue',
                        ],

                        5 => [
                        'label' => 'HR Head',
                        'color' => 'purple',
                        ],

                        10 => [
                        'label' => 'Reported Employee',
                        'color' => 'orange',
                        ],

                        15 => [
                        'label' => 'Department Head',
                        'color' => 'blue',
                        ],

                        20 => [
                        'label' => 'HR Head',
                        'color' => 'purple',
                        ],

                        23 => [
                        'label' => 'Reported Employee',
                        'color' => 'orange',
                        ],

                        25 => [
                        'label' => 'HR Head',
                        'color' => 'purple',
                        ],

                        30 => [
                        'label' => 'HR Head',
                        'color' => 'purple',
                        ],

                        35 => [
                        'label' => 'LAB Supervisor',
                        'color' => 'green',
                        ],

                        40 => [
                        'label' => 'HR Head',
                        'color' => 'purple',
                        ],

                        50, 55 => [
                        'label' => 'Done',
                        'color' => 'green',
                        ],

                        60 => [
                        'label' => 'User Cancelled',
                        'color' => 'red',
                        ],

                        default => [
                        'label' => 'No Tracker',
                        'color' => 'gray',
                        ],

                        };

                        @endphp


                        <span
                            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                {{ match($tracker['color']) {

                                    'blue' =>
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',

                                    'purple' =>
                                        'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',

                                    'orange' =>
                                        'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300',

                                    'green' =>
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',

                                    'red' =>
                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',

                                    default =>
                                        'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-300',

                                } }}">

                            {{ $tracker['label'] }}

                        </span>

                    </td>

                </tr>

                @empty

                <tr>

                    <td
                        colspan="6"
                        class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">

                        No CPAR or RESULT records found.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}

    <div class="mt-4 flex flex-col items-center justify-between gap-3 sm:flex-row">

        <div class="text-sm text-zinc-500 dark:text-zinc-400">

            @if($this->cparOffense->total() > 0)

            Showing

            <span class="font-medium text-zinc-700 dark:text-zinc-200">
                {{ $this->cparOffense->firstItem() }}
            </span>

            to

            <span class="font-medium text-zinc-700 dark:text-zinc-200">
                {{ $this->cparOffense->lastItem() }}
            </span>

            of

            <span class="font-medium text-zinc-700 dark:text-zinc-200">
                {{ $this->cparOffense->total() }}
            </span>

            records

            @else

            @endif

        </div>


        @if($this->cparOffense->hasPages())

        <div>

            {{ $this->cparOffense->links() }}

        </div>

        @endif

    </div>

</div>