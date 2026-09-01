<div class="p-6">

    {{-- PAGE HEADER --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
            CPAR Master File
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
            <thead class="bg-gray-50 text-xs uppercase text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                <tr>

                    <th class="px-4 py-3 font-semibold">
                        CPAR No.
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
                        Date Open
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
                    wire:key="cpar-master-{{ $cpar->assignment_id }}"
                    class="hover:bg-gray-50 dark:hover:bg-gray-800">

                    {{-- CPAR NO --}}
                    <td class="whitespace-nowrap px-4 py-4">
                        <span class="font-semibold text-zinc-900 dark:text-white">
                            {{ $cpar->cpar_no }}
                        </span>
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
                            {{ $cpar->reported_employee ?? '-' }}
                        </span>
                    </td>

                    {{-- BRANCH --}}
                    <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-300">
                        {{ $cpar->branch_name ?? '-' }}
                    </td>

                    {{-- DATE OPEN --}}
                    <td class="whitespace-nowrap px-6 py-4 text-gray-600 dark:text-gray-400">
                        {{
                                $cpar->date_open
                                    ? \Carbon\Carbon::parse($cpar->date_open)->format('M d, Y')
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
                        colspan="7"
                        class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        No CPAR records found.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
    </div>
    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $cpars->links() }}
    </div>
    {{-- MODAL --}}
    <livewire:admin.reports.modal.edit />
</div>