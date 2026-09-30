<div>
    <flux:modal
        name="LABModal"
        class="mt-6 w-[95vw] max-w-[1500px] top-0 z-50 sm:w-[92vw] lg:w-[90vw]">
        <div class="flex max-h-[90vh] flex-col">

            <div class="shrink-0 space-y-1">
                <flux:heading size="lg">
                    LAB Acknowledgment
                </flux:heading>

                <flux:text>
                    Review assigned CPAR concerns and acknowledge the details provided.
                </flux:text>
            </div>

            <flux:separator class="my-5 shrink-0" />

            <div class="min-h-0 flex-1">

                <div
                    class="w-full overflow-x-auto overflow-y-auto rounded-xl border border-zinc-200 dark:border-zinc-700"
                    style="max-height: 55vh;">
                    <table class="min-w-[1150px] w-full table-auto text-sm">

                        <thead class="sticky top-0 z-10 border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                            <tr>

                                <th class="w-[100px] whitespace-nowrap px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-200">
                                    Type
                                </th>

                                <th class="w-[160px] whitespace-nowrap px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-200">
                                    Request No.
                                </th>

                                <th class="w-[220px] whitespace-nowrap px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-200">
                                    Reported Employee
                                </th>

                                <th class="w-[180px] whitespace-nowrap px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-200">
                                    Department
                                </th>

                                <th class="w-[240px] whitespace-nowrap px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-200">
                                    Decision Name
                                </th>

                                <th class="w-[220px] whitespace-nowrap px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-200">
                                    Documents
                                </th>

                                <th class="w-[150px] whitespace-nowrap px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-200">
                                    Status
                                </th>

                                <th class="w-[100px] whitespace-nowrap px-4 py-3 text-center font-semibold text-zinc-700 dark:text-zinc-200">
                                    Action
                                </th>

                            </tr>
                        </thead>

                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                            @forelse ($cpar_reviews as $cpar)

                            <tr class="text-center transition hover:bg-zinc-50 dark:hover:bg-zinc-800">

                                <td class="whitespace-nowrap px-4 py-4">
                                    @if ($cpar->record_type === 'CPAR')

                                    <flux:badge
                                        color="red"
                                        icon="document-text">
                                        CPAR
                                    </flux:badge>

                                    @else

                                    <flux:badge
                                        color="purple"
                                        icon="document-text">
                                        RESULT ERROR
                                    </flux:badge>

                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-4 py-4">
                                    <span class="font-semibold text-zinc-900 dark:text-white">
                                        {{ $cpar->record_type === 'CPAR'
                                                ? ($cpar->cpar_no ?? '—')
                                                : ($cpar->result_no ?? '—') }}
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <div
                                        class="mx-auto max-w-[200px] truncate font-medium text-zinc-900 dark:text-white"
                                        title="{{ $cpar->employee_name ?? '' }}">
                                        {{ $cpar->employee_name ?: '—' }}
                                    </div>
                                </td>

                                <td class="px-4 py-4">
                                    <div
                                        class="mx-auto max-w-[160px] truncate text-zinc-600 dark:text-zinc-400"
                                        title="{{ $cpar->department_name ?? '' }}">
                                        {{ $cpar->department_name ?: '—' }}
                                    </div>
                                </td>

                                <td class="px-4 py-4">
                                    <div
                                        class="mx-auto max-w-[220px] truncate text-zinc-600 dark:text-zinc-400"
                                        title="{{ $cpar->decision_name ?? '' }}">
                                        {{ $cpar->decision_name ?: '—' }}
                                    </div>
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap items-center justify-center gap-2">

                                        @if (!empty($cpar->ir_id))
                                        <flux:badge color="red">
                                            Submitted IR
                                        </flux:badge>
                                        @endif

                                        @if (!empty($cpar->nte_no))
                                        <flux:badge color="blue">
                                            Submitted NTE
                                        </flux:badge>
                                        @endif

                                        @if (
                                        empty($cpar->ir_id) &&
                                        empty($cpar->nte_no)
                                        )
                                        <span class="text-zinc-400">
                                            —
                                        </span>
                                        @endif

                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-4 py-4">
                                    <flux:badge color="yellow">
                                        {{ $cpar->status_name ?: '—' }}
                                    </flux:badge>
                                </td>

                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="flex justify-center">

                                        <flux:dropdown align="end">

                                            <flux:button
                                                size="sm"
                                                variant="ghost"
                                                icon="ellipsis-vertical"
                                                aria-label="Actions" />

                                            <flux:menu>

                                                @if ($cpar->record_type === 'CPAR')

                                                <flux:menu.item
                                                    icon="eye"
                                                    wire:click="viewDetails({{ $cpar->assignment_id }})">
                                                    View CPAR
                                                </flux:menu.item>

                                                @elseif ($cpar->record_type === 'RESULT')

                                                <flux:menu.item
                                                    icon="eye"
                                                    wire:click="viewLabResult({{ $cpar->assignment_id }})">
                                                    View Result Error
                                                </flux:menu.item>

                                                @endif

                                            </flux:menu>

                                        </flux:dropdown>

                                    </div>
                                </td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="8" class="px-4 py-14 text-center">

                                    <flux:icon
                                        name="check-circle"
                                        class="mx-auto size-10 text-green-500" />

                                    <flux:heading
                                        size="sm"
                                        class="mt-3">
                                        No CPAR Pending for Acknowledgment
                                    </flux:heading>

                                    <flux:text class="mt-1 text-zinc-500">
                                        All assigned CPAR concerns have been acknowledged.
                                    </flux:text>

                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>
                </div>

            </div>

            @if ($labReviewTotal > 0)

            <div class="mt-5 shrink-0 border-t border-zinc-200 pt-4 dark:border-zinc-700">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div class="text-center text-sm text-zinc-500 dark:text-zinc-400 sm:text-left">
                        Showing

                        <span class="font-semibold text-zinc-700 dark:text-zinc-200">
                            {{ (($labReviewPage - 1) * $perPage) + 1 }}
                        </span>

                        to

                        <span class="font-semibold text-zinc-700 dark:text-zinc-200">
                            {{ min($labReviewPage * $perPage, $labReviewTotal) }}
                        </span>

                        of

                        <span class="font-semibold text-zinc-700 dark:text-zinc-200">
                            {{ $labReviewTotal }}
                        </span>

                        records
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-1">

                        <flux:button
                            size="sm"
                            variant="ghost"
                            icon="chevron-left"
                            wire:click="previousLABReviewPage"
                            wire:loading.attr="disabled"
                            :disabled="$labReviewPage <= 1"
                            aria-label="Previous page" />

                        @for ($page = 1; $page <= $labReviewLastPage; $page++)

                            @if (
                            $page===1 ||
                            $page===$labReviewLastPage ||
                            abs($page - $labReviewPage) <=2
                            )

                            <flux:button
                            size="sm"
                            wire:click="goToLABReviewPage({{ $page }})"
                            wire:loading.attr="disabled"
                            :variant="$page === $labReviewPage ? 'primary' : 'ghost'">
                            {{ $page }}
                            </flux:button>

                            @elseif (
                            ($page === 2 && $labReviewPage > 4) ||
                            ($page === $labReviewLastPage - 1 && $labReviewPage < $labReviewLastPage - 3)
                                )

                                <span class="px-2 text-sm text-zinc-400">
                                ...
                                </span>

                                @endif

                                @endfor

                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="chevron-right"
                                    wire:click="nextLABReviewPage"
                                    wire:loading.attr="disabled"
                                    :disabled="$labReviewPage >= $labReviewLastPage"
                                    aria-label="Next page" />

                    </div>

                </div>

            </div>

            @endif

        </div>
    </flux:modal>
</div>