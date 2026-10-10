<div>

    <flux:modal
        name="HROffenseModal"
        class="mt-4 w-[95vw] max-w-[1500px] sm:mt-6">

        <div class="flex max-h-[85vh] min-w-0 flex-col">

            <div class="shrink-0 space-y-1 border-b border-zinc-200 pb-4 dark:border-zinc-700">

                <flux:heading
                    size="lg"
                    class="font-bold">
                    Offense History
                </flux:heading>

                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                    Previous final disciplinary records
                </flux:text>

            </div>

            <div
                class="min-w-0 max-w-full overflow-x-auto overflow-y-auto
                       overscroll-x-contain rounded-lg
                       border border-zinc-200 dark:border-zinc-700">

                <table class="min-w-[1200px] w-full text-center text-sm">

                    <thead class="bg-red-800 text-xs tracking-wider text-white">
                        <tr class="border-b-2 border-white/30">

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Record No.
                            </th>

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Reported Date
                            </th>

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                HR Decision
                            </th>

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Disciplinary Category
                            </th>

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Offense
                            </th>

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Incident Date
                            </th>

                            <th class="whitespace-nowrap px-3 py-3 font-bold text-white sm:px-4">
                                Valid Until
                            </th>

                            <th class="sticky right-0 whitespace-nowrap bg-red-800 px-3 py-3 text-center font-bold text-white sm:px-4">
                                Status
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @forelse ($offenseList as $offense)

                        <tr
                            wire:key="offense-{{ $offense->record_type }}-{{ $offense->assignment_id ?? $offense->record_no }}"
                            class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                            <td class="whitespace-nowrap px-3 py-4 sm:px-4">

                                <div class="flex flex-col items-center gap-1">

                                    <div class="font-semibold text-zinc-900 dark:text-white">
                                        {{ $offense->record_no }}
                                    </div>

                                    @if ($offense->record_type === 'CPAR')

                                    <span
                                        class="inline-flex items-center rounded-full
                                                       bg-red-100 px-2.5 py-0.5
                                                       text-[10px] font-semibold text-red-700
                                                       dark:bg-red-950 dark:text-red-400">
                                        CPAR
                                    </span>

                                    @else

                                    <span
                                        class="inline-flex items-center rounded-full
                                                       bg-blue-100 px-2.5 py-0.5
                                                       text-[10px] font-semibold text-blue-700
                                                       dark:bg-blue-950 dark:text-blue-400">
                                        RESULT
                                    </span>

                                    @endif

                                </div>

                            </td>

                            <td
                                class="whitespace-nowrap px-3 py-4
                                           text-zinc-700 dark:text-zinc-300 sm:px-4">

                                @if ($offense->record_date)

                                {{ \Carbon\Carbon::parse($offense->record_date)->format('M d, Y') }}

                                @else

                                <span class="text-zinc-400 dark:text-zinc-500">
                                    N/A
                                </span>

                                @endif

                            </td>

                            <td class="whitespace-nowrap px-3 py-4 sm:px-4">

                                <flux:badge color="orange">
                                    {{ $offense->decision_name ?? 'N/A' }}
                                </flux:badge>

                            </td>

                            <td
                                class="max-w-[220px] px-3 py-4
                                           text-zinc-700 dark:text-zinc-300 sm:px-4">

                                <div
                                    class="truncate"
                                    title="{{ $offense->category_name ?? 'N/A' }}">
                                    {{ $offense->category_name ?? 'N/A' }}
                                </div>

                            </td>

                            <td
                                class="max-w-[250px] px-3 py-4
                                           text-zinc-700 dark:text-zinc-300 sm:px-4">

                                <div
                                    class="truncate font-medium"
                                    title="{{ $offense->offense_name ?? 'N/A' }}">
                                    {{ $offense->offense_name ?? 'N/A' }}
                                </div>

                            </td>

                            <td
                                class="whitespace-nowrap px-3 py-4
                                           text-zinc-700 dark:text-zinc-300 sm:px-4">

                                @if ($offense->incident_date)

                                {{ \Carbon\Carbon::parse($offense->incident_date)->format('M d, Y') }}

                                @else

                                <span class="text-zinc-400 dark:text-zinc-500">
                                    N/A
                                </span>

                                @endif

                            </td>

                            <td
                                class="whitespace-nowrap px-3 py-4
                                           text-zinc-700 dark:text-zinc-300 sm:px-4">

                                @if ($offense->valid_until)

                                {{ \Carbon\Carbon::parse($offense->valid_until)->format('M d, Y') }}

                                @else

                                <span class="text-zinc-400 dark:text-zinc-500">
                                    N/A
                                </span>

                                @endif

                            </td>

                            <td
                                class="sticky right-0 whitespace-nowrap
                                           bg-white px-3 py-4 text-center
                                           dark:bg-zinc-900 sm:px-4">

                                <flux:badge color="green">
                                    {{ $offense->status_name ?? 'N/A' }}
                                </flux:badge>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="8"
                                class="px-4 py-12 text-center">

                                <flux:icon
                                    name="check-circle"
                                    class="mx-auto size-10 text-green-500" />

                                <flux:heading
                                    size="sm"
                                    class="mt-3">
                                    No Previous Offenses
                                </flux:heading>

                                <flux:text class="mt-1 text-zinc-500 dark:text-zinc-400">
                                    No final disciplinary records were found for this employee.
                                </flux:text>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="pt-2 text-xs text-zinc-500 dark:text-zinc-400 sm:hidden">
                Swipe left or right to view all columns.
            </div>

        </div>

    </flux:modal>

</div>