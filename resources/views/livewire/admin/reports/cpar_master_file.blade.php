<div class="p-6">
    @include('toast')
    {{-- PAGE HEADER --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
            Master File
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            View and manage the complete master records of Corrective and Preventive Action Reports.
        </p>
    </div>

    {{-- SEARCH --}}
    <div class="mb-4 flex items-center justify-between">
        <div class="w-full max-w-md">
            <flux:input
                wire:model.live.debounce.300ms="search"
                placeholder="Search CPAR..."
                icon="magnifying-glass" />
        </div>
    </div>

    {{-- CPAR MASTER FILE TABLE --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">

        <table class="w-full text-left text-sm">

            <thead
                class="bg-gray-50 text-xs uppercase text-gray-600 dark:bg-gray-800 dark:text-gray-300">

                <tr>

                    <th class="px-4 py-3 font-semibold">
                        No.
                    </th>

                    <th class="px-4 py-3 font-semibold">
                        Type
                    </th>

                    <th class="px-4 py-3 font-semibold">
                        Reported By
                    </th>

                    <th class="px-4 py-3 font-semibold">
                        Reported Employee
                    </th>

                    <th class="whitespace-nowrap px-6 py-3">
                        Branch
                    </th>

                    <th class="whitespace-nowrap px-6 py-3">
                        Date
                    </th>

                    <th class="whitespace-nowrap px-6 py-3">
                        Status
                    </th>

                    <th class="whitespace-nowrap px-6 py-3 text-center">
                        Action
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                @forelse ($cpars as $cpar)

                <tr
                    wire:key="master-{{ $cpar->record_type }}-{{ $cpar->assignment_id }}"
                    class="hover:bg-gray-50 dark:hover:bg-gray-800">

                    {{-- RECORD NO --}}
                    <td class="whitespace-nowrap px-4 py-4">

                        <span class="font-semibold text-zinc-900 dark:text-white">
                            {{ $cpar->record_no }}
                        </span>

                    </td>


                    {{-- TYPE --}}
                    <td class="whitespace-nowrap px-4 py-4">

                        @if ($cpar->record_type === 'CPAR')

                        <span
                            class="inline-flex items-center rounded-full
                                       bg-red-100 px-2.5 py-1
                                       text-xs font-medium text-red-700
                                       dark:bg-red-950 dark:text-red-400">

                            CPAR

                        </span>

                        @else

                        <span
                            class="inline-flex items-center rounded-full
                                       bg-blue-100 px-2.5 py-1
                                       text-xs font-medium text-blue-700
                                       dark:bg-blue-950 dark:text-blue-400">

                            RESULT

                        </span>

                        @endif

                    </td>


                    {{-- REPORTED BY --}}
                    <td class="whitespace-nowrap px-4 py-4">

                        <span class="font-semibold text-zinc-900 dark:text-white">
                            {{ $cpar->reported_by ?? '-' }}
                        </span>

                    </td>


                    {{-- REPORTED EMPLOYEE --}}
                    <td class="whitespace-nowrap px-4 py-4">

                        <span class="font-semibold text-zinc-900 dark:text-white">

                            @if (!empty($cpar->assigned_employee))

                            {{ $cpar->assigned_employee }}

                            @elseif (!empty($cpar->dept_head))

                            {{ $cpar->dept_head }}

                            @else

                            -

                            @endif

                        </span>

                    </td>


                    {{-- BRANCH --}}
                    <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-300">

                        {{ $cpar->branch_name ?? '-' }}

                    </td>


                    {{-- DATE --}}
                    <td class="whitespace-nowrap px-6 py-4 text-gray-600 dark:text-gray-400">

                        {{
                            $cpar->record_date
                                ? \Carbon\Carbon::parse($cpar->record_date)->format('M d, Y')
                                : '-'
                        }}

                    </td>


                    {{-- STATUS --}}
                    <td class="px-4 py-4">

                        <span
                            class="inline-flex items-center rounded-full
                                   bg-yellow-100 px-2.5 py-1
                                   text-xs font-medium text-yellow-700
                                   dark:bg-yellow-950 dark:text-yellow-400">

                            {{ $cpar->status_name ?? '—' }}

                        </span>

                    </td>


                    {{-- ACTION --}}
                    <td class="px-4 py-3 text-center">

                        <flux:dropdown align="end">

                            <flux:button
                                size="sm"
                                variant="ghost"
                                icon="ellipsis-vertical" />

                            <flux:menu>

                                <flux:menu.item
                                    wire:click="view({{ $cpar->assignment_id }})">

                                    View

                                </flux:menu.item>

                            </flux:menu>

                        </flux:dropdown>

                    </td>

                </tr>

                @empty

                <tr>

                    <td
                        colspan="8"
                        class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">

                        No CPAR or RESULT records found.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <div class="mt-4">
        {{ $cpars->links() }}
    </div>

    <livewire:admin.reports.modal.edit />
</div>