<div>
    <div class="mb-4 rounded-xl border border-zinc-200 dark:border-zinc-700
            bg-white dark:bg-zinc-900 p-4">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">

            {{-- SEARCH --}}
            <div class="lg:col-span-2">

                <flux:input
                    wire:model.live.debounce.300ms="search"
                    icon="magnifying-glass"
                    placeholder="Search employee, employee no., CPAR no..." />

            </div>


            {{-- OFFENSE --}}
            <div>

                <flux:select
                    wire:model.live="offenseFilter"
                    placeholder="Offense">

                    <flux:select.option value="ALL">
                        All Offenses
                    </flux:select.option>

                    @foreach ($offenseCategories as $offense)

                    <flux:select.option value="{{ $offense->id }}">
                        {{ $offense->decision_name }}
                    </flux:select.option>

                    @endforeach

                </flux:select>

            </div>


            {{-- CATEGORY --}}
            <div>

                <flux:select
                    wire:model.live="categoryFilter"
                    placeholder="Category">

                    <flux:select.option value="ALL">
                        All Categories
                    </flux:select.option>

                    @foreach ($disciplinaryCategories as $category)

                    <flux:select.option value="{{ $category->id }}">
                        {{ $category->category_name }}
                    </flux:select.option>

                    @endforeach

                </flux:select>

            </div>


            {{-- VALIDITY --}}
            <div>

                <flux:select
                    wire:model.live="validityFilter"
                    placeholder="Validity">

                    <flux:select.option value="ALL">
                        All
                    </flux:select.option>

                    <flux:select.option value="VALID">
                        Valid
                    </flux:select.option>

                    <flux:select.option value="EXPIRED">
                        Expired
                    </flux:select.option>

                </flux:select>

            </div>

        </div>


        {{-- SECOND ROW --}}
        <div class="flex items-center justify-between mt-4">

            <div class="text-sm text-zinc-500 dark:text-zinc-400">

                Showing
                <span class="font-semibold text-zinc-900 dark:text-white">
                    {{ $cpar_offense->count() }}
                </span>
                record(s)

            </div>


            {{-- CLEAR --}}
            @if (
            $search ||
            $offenseFilter !== 'ALL' ||
            $categoryFilter !== 'ALL' ||
            $statusFilter !== 'ALL' ||
            $validityFilter !== 'ALL'
            )

            <button
                type="button"
                wire:click="clearFilters"
                class="inline-flex items-center gap-2
                       rounded-lg px-3 py-2
                       text-sm font-medium
                       text-zinc-600 dark:text-zinc-300
                       hover:bg-zinc-100
                       dark:hover:bg-zinc-800
                       transition">

                <svg
                    class="w-4 h-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18 18 6M6 6l12 12" />

                </svg>

                Clear Filters

            </button>

            @endif

        </div>

    </div>
    <div class="w-full overflow-x-auto">
        <div
            class="mt-4 overflow-x-auto rounded-xl
           border border-zinc-200
           dark:border-zinc-700">
            <table class="w-full text-sm">
                {{-- TABLE HEADER --}}
                <thead
                    class="bg-zinc-50 dark:bg-zinc-900
                   border-b border-zinc-200
                   dark:border-zinc-700">
                    <tr>
                        <th class="px-4 py-3 text-center font-semibold
                           text-zinc-700 dark:text-zinc-300">
                            Employee
                        </th>
                        <th class="px-4 py-3 text-center font-semibold
                           text-zinc-700 dark:text-zinc-300">
                            Employee No.
                        </th>
                        <th class="px-4 py-3 text-center font-semibold
                           text-zinc-700 dark:text-zinc-300">
                            Department
                        </th>
                        <th class="px-4 py-3 text-center font-semibold
                           text-zinc-700 dark:text-zinc-300">
                            CPAR No.
                        </th>
                        <th class="px-4 py-3 text-center font-semibold
                           text-zinc-700 dark:text-zinc-300">
                            Date Open
                        </th>
                        <th class="px-4 py-3 text-center font-semibold
                           text-zinc-700 dark:text-zinc-300">
                            Offense
                        </th>
                        <th class="px-4 py-3 text-center font-semibold
                           text-zinc-700 dark:text-zinc-300">
                            Category
                        </th>
                        <th class="px-4 py-3 text-center font-semibold
                           text-zinc-700 dark:text-zinc-300">
                            Documents
                        </th>
                        <th class="px-4 py-3 text-center font-semibold
                           text-zinc-700 dark:text-zinc-300">
                            Status
                        </th>
                    </tr>
                </thead>

                {{-- TABLE BODY --}}
                <tbody
                    class="divide-y divide-zinc-200
                   dark:divide-zinc-700">
                    @forelse ($cpar_offense as $cpar)
                    <tr
                        class="text-center
                           hover:bg-zinc-50
                           dark:hover:bg-zinc-800
                           transition">

                        {{-- ASSIGNED EMPLOYEE --}}
                        <td class="px-4 py-4">
                            <span
                                class="font-medium
                                   text-zinc-900
                                   dark:text-white">
                                {{ $cpar->employee_name }}
                            </span>
                        </td>

                        {{-- EMPLOYEE NO --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span
                                class="text-zinc-600
                                   dark:text-zinc-400">
                                {{ $cpar->employee_no }}
                            </span>
                        </td>

                        {{-- DEPARTMENT --}}
                        <td class="px-4 py-4">
                            <span
                                class="text-zinc-600
                                   dark:text-zinc-400">
                                {{ $cpar->department_name }}
                            </span>
                        </td>

                        {{-- CPAR NO --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span
                                class="font-semibold
                                   text-zinc-900
                                   dark:text-white">
                                {{ $cpar->cpar_no }}
                            </span>
                        </td>

                        {{-- DATE OPEN --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span
                                class="text-zinc-600
                                   dark:text-zinc-400">
                                {{ \Carbon\Carbon::parse($cpar->date_open)->format('M d, Y') }}
                            </span>
                        </td>

                        {{-- OFFENSE --}}
                        <td class="px-4 py-4">
                            <div class="flex flex-wrap justify-center gap-1">
                                @if ($cpar->decision_name)
                                @foreach (explode(', ', $cpar->decision_name) as $decision)
                                <span
                                    class="inline-flex items-center
                                               rounded-full
                                               px-2.5 py-1
                                               text-xs font-medium
                                               bg-red-100 text-red-700
                                               dark:bg-red-950
                                               dark:text-red-400">

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

                        <td class="px-4 py-4">
                            <div class="flex flex-wrap justify-center gap-1">
                                @if ($cpar->category_name)
                                @foreach (explode(', ', $cpar->category_name) as $category)
                                <span
                                    class="inline-flex items-center
                                               rounded-full
                                               px-2.5 py-1
                                               text-xs font-medium
                                               bg-red-100 text-red-700
                                               dark:bg-red-950
                                               dark:text-red-400">

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

                        {{-- DOCUMENTS --}}
                        <td class="px-4 py-4">
                            <div class="flex justify-center gap-2">
                                @if (!empty($cpar->ir_id))
                                <span
                                    class="inline-flex items-center
                                           rounded-full
                                           px-2.5 py-1
                                           text-xs font-medium
                                           bg-red-100 text-red-700
                                           dark:bg-red-950
                                           dark:text-red-400">
                                    IR Request
                                </span>
                                @endif

                                @if (!empty($cpar->nte_no))
                                <span
                                    class="inline-flex items-center
                                           rounded-full
                                           px-2.5 py-1
                                           text-xs font-medium
                                           bg-blue-100 text-blue-700
                                           dark:bg-blue-950
                                           dark:text-blue-400">
                                    NTE Request
                                </span>
                                @endif


                                @if (
                                empty($cpar->ir_id)
                                && empty($cpar->nte_no)
                                )

                                <span class="text-zinc-400">
                                    —
                                </span>

                                @endif

                            </div>
                        </td>

                        {{-- STATUS --}}
                        <td class="px-4 py-4">
                            <span
                                class="inline-flex items-center
                                   rounded-full
                                   px-2.5 py-1
                                   text-xs font-medium
                                   bg-yellow-100 text-yellow-700
                                   dark:bg-yellow-950
                                   dark:text-yellow-400">

                                {{ $cpar->status_name }}

                            </span>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td
                            colspan="10"
                            class="px-4 py-12 text-center">
                            <div
                                class="flex flex-col
                                   items-center
                                   justify-center">
                                <div
                                    class="flex items-center
                                       justify-center
                                       w-12 h-12
                                       rounded-full
                                       bg-green-100
                                       dark:bg-green-950">
                                    <svg
                                        class="w-6 h-6
                                           text-green-600
                                           dark:text-green-400"
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
                                <h3
                                    class="mt-3 text-sm
                                       font-semibold
                                       text-zinc-900
                                       dark:text-white">
                                    No CPAR Found
                                </h3>
                                <p
                                    class="mt-1 text-sm
                                       text-zinc-500
                                       dark:text-zinc-400">
                                    There are no CPAR concerns.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>