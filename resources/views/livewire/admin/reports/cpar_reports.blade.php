<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">
            CPAR Reports
        </h2>

        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
            View and analyze Corrective & Preventive Action Reports.
        </p>
    </div>

    {{-- FILTER CARD --}}
    <div class="rounded-xl border border-zinc-200 bg-white p-4 sm:p-5 dark:border-zinc-700 dark:bg-zinc-900">

        {{-- FILTER GRID --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- SEARCH --}}
            <div class="sm:col-span-2 lg:col-span-2">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    icon="magnifying-glass"
                    label="Search"
                    placeholder="CPAR No., employee, reported by..." />
            </div>

            {{-- BRANCH --}}
            <div class="sm:col-span-1">

                <flux:select
                    wire:model.live="branchFilter"
                    label="Branch">

                    <flux:select.option value="ALL">
                        All Branches
                    </flux:select.option>

                    @foreach ($branches as $branch)

                    <flux:select.option value="{{ $branch->id }}">
                        {{ $branch->branch_name }}
                    </flux:select.option>

                    @endforeach

                </flux:select>

            </div>


            {{-- DEPARTMENT --}}
            <div class="sm:col-span-1">

                <flux:select
                    wire:model.live="departmentFilter"
                    label="Department">

                    <flux:select.option value="ALL">
                        All Departments
                    </flux:select.option>

                    @foreach ($departments as $department)

                    <flux:select.option value="{{ $department->id }}">
                        {{ $department->department_name }}
                    </flux:select.option>

                    @endforeach

                </flux:select>

            </div>


            {{-- STATUS --}}
            <div class="sm:col-span-1">

                <flux:select
                    wire:model.live="statusFilter"
                    label="Status">

                    <flux:select.option value="ALL">
                        All Statuses
                    </flux:select.option>

                    @foreach ($statuses as $status)

                    <flux:select.option value="{{ $status->id }}">
                        {{ $status->status_name }}
                    </flux:select.option>

                    @endforeach

                </flux:select>

            </div>


            {{-- DECISION --}}
            <div class="sm:col-span-1">

                <flux:select
                    wire:model.live="categoryFilter"
                    label="Decision">

                    <flux:select.option value="ALL">
                        All Decisions
                    </flux:select.option>

                    @foreach ($decisions as $decision)

                    <flux:select.option value="{{ $decision->id }}">
                        {{ $decision->decision_name }}
                    </flux:select.option>

                    @endforeach

                </flux:select>

            </div>


            {{-- DATE FROM --}}
            <div class="sm:col-span-1">

                <flux:input
                    type="date"
                    wire:model.live="dateFrom"
                    label="Date From" />

            </div>


            {{-- DATE TO --}}
            <div class="sm:col-span-1">

                <flux:input
                    type="date"
                    wire:model.live="dateTo"
                    label="Date To" />

            </div>

        </div>


        {{-- ACTIONS --}}
        @if ($search || $branchFilter !== 'ALL' || $departmentFilter !== 'ALL' || $statusFilter !== 'ALL' || $categoryFilter !== 'ALL' || $dateFrom || $dateTo)
        <div class="mt-5 flex flex-col gap-3 border-t border-zinc-200 pt-4 dark:border-zinc-700 sm:flex-row sm:items-center sm:justify-between">

            {{-- ACTIVE FILTER MESSAGE --}}
            <span
                class="
                    text-sm
                    text-zinc-500
                    dark:text-zinc-400
                ">
                Filters are currently applied.
            </span>

            {{-- CLEAR FILTERS --}}
            <flux:button
                type="button"
                variant="ghost"
                icon="x-mark"
                wire:click="clearFilters">
                Clear Filters
            </flux:button>

        </div>

        @endif

    </div>

    {{-- TABLE CONTROLS --}}
    <div class="flex items-center justify-between">

        {{-- ROWS PER PAGE --}}
        <div class="flex items-center gap-2">

            <span class="text-sm text-zinc-500 dark:text-zinc-400">
                Rows per page
            </span>

            <flux:select
                wire:model.live="perPage"
                class="w-24">

                <flux:select.option value="10">
                    10
                </flux:select.option>

                <flux:select.option value="20">
                    20
                </flux:select.option>

                <flux:select.option value="50">
                    50
                </flux:select.option>

                <flux:select.option value="100">
                    100
                </flux:select.option>

            </flux:select>

        </div>

        {{-- TOTAL RECORDS --}}
        <div class="text-sm text-zinc-500 dark:text-zinc-400">

            Total:
            <span class="font-semibold text-zinc-900 dark:text-white">
                {{ $cparReports->total() }}
            </span>

        </div>

    </div>
    {{-- REPORT TABLE --}}
    <div class="overflow-x-auto
               rounded-xl
               border border-zinc-200
               dark:border-zinc-700">

        <table class="w-full text-left text-sm">

            {{-- HEADER --}}
            <thead
                class="border-b
                       border-zinc-200
                       bg-zinc-50
                       dark:border-zinc-700
                       dark:bg-zinc-900">

                <tr>

                    <th class="px-4 py-3 text-center font-semibold">
                        CPAR No.
                    </th>

                    <th class="px-4 py-3 text-center font-semibold">
                        Reported By
                    </th>

                    <th class="px-4 py-3 text-center font-semibold">
                        Reported Employee
                    </th>

                    <th class="px-4 py-3 text-center font-semibold">
                        Branch
                    </th>

                    <th class="px-4 py-3 text-center font-semibold">
                        Department
                    </th>

                    <th class="px-4 py-3 text-center font-semibold">
                        Date Open
                    </th>

                    <th class="px-4 py-3 text-center font-semibold">
                        Decision Category
                    </th>

                    <th class="px-4 py-3 text-center font-semibold">
                        Valid Until
                    </th>

                    <th class="px-4 py-3 text-center font-semibold">
                        Status
                    </th>

                    <th class="px-4 py-3 text-center font-semibold">
                        Actions
                    </th>

                </tr>

            </thead>


            {{-- BODY --}}
            <tbody
                class="divide-y
                       divide-zinc-200
                       dark:divide-zinc-700">

                @forelse ($cparReports as $cpar)

                <tr
                    class="text-center
                           transition
                           hover:bg-zinc-50
                           dark:hover:bg-zinc-800">


                    {{-- CPAR NO --}}
                    <td
                        class="whitespace-nowrap
                               px-4 py-4">

                        <span
                            class="font-semibold
                                   text-zinc-900
                                   dark:text-white">

                            {{ $cpar->cpar_no }}

                        </span>

                    </td>


                    {{-- REPORTED BY --}}
                    <td
                        class="whitespace-nowrap
                               px-4 py-4">

                        <span
                            class="font-semibold
                                   text-zinc-900
                                   dark:text-white">

                            {{ $cpar->reported_by }}

                        </span>

                    </td>


                    {{-- EMPLOYEE --}}
                    <td class="px-4 py-4">

                        <span
                            class="font-semibold
                                   text-zinc-900
                                   dark:text-white">

                            {{ $cpar->employee_name ?? '—' }}

                        </span>

                    </td>


                    {{-- BRANCH --}}
                    <td class="px-4 py-4">

                        {{ $cpar->branch_name ?? '—' }}

                    </td>


                    {{-- DEPARTMENT --}}
                    <td class="px-4 py-4">

                        {{ $cpar->department_name ?? '—' }}

                    </td>


                    {{-- DATE OPEN --}}
                    <td
                        class="whitespace-nowrap
                               px-4 py-4">

                        @if ($cpar->date_open)

                        {{ \Carbon\Carbon::parse($cpar->date_open)->format('M d, Y') }}

                        @else

                        —

                        @endif

                    </td>


                    {{-- Decision Category --}}
                    <td class="px-4 py-4">

                        <div
                            class="flex
                                   flex-wrap
                                   justify-center
                                   gap-1">

                            @if ($cpar->decision_name)

                            @foreach (
                            explode(', ', $cpar->decision_name)
                            as $decision
                            )

                            <span
                                class="inline-flex
                                       items-center
                                       rounded-full
                                       bg-red-100
                                       px-2.5
                                       py-1
                                       text-xs
                                       font-medium
                                       text-red-700
                                       dark:bg-red-950
                                       dark:text-red-400">

                                {{ $decision }}

                            </span>

                            @endforeach

                            @else

                            <span
                                class="text-zinc-400">

                                —

                            </span>

                            @endif

                        </div>

                    </td>


                    {{-- VALID UNTIL --}}
                    <td
                        class="whitespace-nowrap
                               px-4 py-4">

                        @if ($cpar->valid_until)

                        @php
                        $validUntil = \Carbon\Carbon::parse(
                        $cpar->valid_until
                        );
                        @endphp

                        <span
                            class="inline-flex
                                   items-center
                                   rounded-full
                                   px-2.5
                                   py-1
                                   text-xs
                                   font-medium
                                   {{ $validUntil->isFuture()
                                        ? 'bg-green-100 text-green-700 dark:bg-green-950 dark:text-green-400'
                                        : 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-400'
                                   }}">

                            {{ $validUntil->format('M d, Y') }}

                        </span>

                        @else

                        <span
                            class="text-zinc-400">

                            —

                        </span>

                        @endif

                    </td>


                    {{-- STATUS --}}
                    <td class="px-4 py-4">

                        <span
                            class="inline-flex
                                   items-center
                                   rounded-full
                                   bg-yellow-100
                                   px-2.5
                                   py-1
                                   text-xs
                                   font-medium
                                   text-yellow-700
                                   dark:bg-yellow-950
                                   dark:text-yellow-400">
                            {{ $cpar->status_name ?? '—' }}

                        </span>

                    </td>


                    {{-- ACTIONS --}}
                    <td
                        class="px-4 py-3
                               text-center">

                        <flux:dropdown align="end">

                            <flux:button
                                size="sm"
                                variant="ghost"
                                icon="ellipsis-vertical">
                            </flux:button>


                            <flux:menu>

                                <flux:menu.item
                                    icon="document-text"
                                    href="{{ route('cpar.pdf', $cpar->assignment_id) }}"
                                    target="_blank">

                                    PDF

                                </flux:menu.item>

                            </flux:menu>

                        </flux:dropdown>

                    </td>

                </tr>


                @empty

                {{-- EMPTY --}}
                <tr>

                    <td
                        colspan="10"
                        class="px-4 py-12 text-center">

                        <div
                            class="flex
                                   flex-col
                                   items-center
                                   justify-center">

                            <div
                                class="flex
                                       h-12
                                       w-12
                                       items-center
                                       justify-center
                                       rounded-full
                                       bg-zinc-100
                                       dark:bg-zinc-800">

                                <svg
                                    class="h-6 w-6
                                           text-zinc-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 13h6m-3-3v6m9-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />

                                </svg>

                            </div>


                            <h3
                                class="mt-3
                                       text-sm
                                       font-semibold
                                       text-zinc-900
                                       dark:text-white">

                                No CPAR Records Found

                            </h3>


                            <p
                                class="mt-1
                                       text-sm
                                       text-zinc-500
                                       dark:text-zinc-400">

                                @if ($search)

                                No records match your search:
                                <span class="font-medium">
                                    "{{ $search }}"
                                </span>

                                @else

                                There are currently no CPAR records available.

                                @endif

                            </p>

                        </div>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- PAGINATION --}}
    @if ($cparReports->hasPages())
    <div class="pt-2">
        {{ $cparReports->links() }}
    </div>
    @endif

</div>