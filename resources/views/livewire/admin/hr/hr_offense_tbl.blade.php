<div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">

    <flux:modal
        name="HROffenseModal"
        class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">

        {{-- MODAL HEADER --}}
        <div class="mb-5 border-b border-zinc-200 pb-4 dark:border-zinc-700">

            <flux:heading
                size="lg"
                class="font-bold">
                Offense History
            </flux:heading>

            <div class="mt-1 text-sm font-semibold text-zinc-700 dark:text-zinc-200">
                
            </div>

            <div class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                Previous final disciplinary records
            </div>

        </div>

        {{-- TABLE --}}
        <div class="w-full overflow-x-auto">

            <table class="min-w-[1200px] w-full text-sm">

                <thead
                    class="bg-zinc-100 text-xs tracking-wider text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">

                    <tr>

                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase">
                            Record No.
                        </th>

                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase">
                            Reported Date
                        </th>

                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase">
                            HR Decision
                        </th>

                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase">
                            Disciplinary Category
                        </th>

                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase">
                            Offense
                        </th>

                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase">
                            Incident Date
                        </th>

                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase">
                            Valid Until
                        </th>

                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                    @forelse ($offenseList as $offense)

                    <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800">

                        {{-- RECORD NO --}}
                        <td class="whitespace-nowrap px-4 py-4 text-center">

                            <div class="font-semibold">
                                {{ $offense->record_no }}
                            </div>

                            <div class="mt-1 text-center">

                                @if ($offense->record_type === 'CPAR')

                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold
                                    bg-red-100 text-red-700
                                    dark:bg-red-950 dark:text-red-400">
                                    CPAR
                                </span>

                                @else

                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold
                                    bg-blue-100 text-blue-700
                                    dark:bg-blue-950 dark:text-blue-400">
                                    RESULT
                                </span>

                                @endif

                            </div>

                        </td>

                        {{-- REPORTED DATE --}}
                        <td class="max-w-[250px] px-4 py-4 text-center">

                            <div class="font-semibold">
                                {{ $offense->record_date
                                    ? \Carbon\Carbon::parse($offense->record_date)->format('M d, Y')
                                    : 'N/A'
                                }}
                            </div>

                        </td>


                        {{-- HR DECISION --}}
                        <td class="px-4 py-4 text-center">

                            <flux:badge color="orange">
                                {{ $offense->decision_name ?? 'N/A' }}
                            </flux:badge>

                        </td>


                        {{-- CATEGORY --}}
                        <td class="px-4 py-4 text-center">
                            {{ $offense->category_name ?? 'N/A' }}
                        </td>


                        {{-- OFFENSE --}}
                        <td class="max-w-[250px] px-4 py-4 text-center">

                            <div class="font-medium">
                                {{ $offense->offense_name ?? 'N/A' }}
                            </div>

                        </td>


                        {{-- INCIDENT DATE --}}
                        <td class="whitespace-nowrap px-4 py-4 text-center">

                            {{ $offense->incident_date
                                ? \Carbon\Carbon::parse($offense->incident_date)->format('M d, Y')
                                : 'N/A'
                            }}

                        </td>


                        {{-- VALID UNTIL --}}
                        <td class="whitespace-nowrap px-4 py-4 text-center">

                            {{ $offense->valid_until
                                ? \Carbon\Carbon::parse($offense->valid_until)->format('M d, Y')
                                : 'N/A'
                            }}

                        </td>


                        {{-- STATUS --}}
                        <td class="px-4 py-4 text-center">

                            <flux:badge color="green">
                                {{ $offense->status_name }}
                            </flux:badge>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="9" class="px-4 py-12 text-center">

                            <flux:icon
                                name="check-circle"
                                class="mx-auto size-10 text-green-500" />

                            <flux:heading size="sm" class="mt-3">
                                No Previous Offenses
                            </flux:heading>

                            <flux:text class="mt-1 text-zinc-500">
                                No final disciplinary records were found for this employee.
                            </flux:text>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </flux:modal>

</div>