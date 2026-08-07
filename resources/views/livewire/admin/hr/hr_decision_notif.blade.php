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
            <div class="space-y-4">

                @forelse ($hrDecisionList as $cpar)

                <div
                    class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-5
                               hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">

                    <div class="flex justify-between items-start gap-8">

                        {{-- LEFT --}}
                        <div class="flex-1 space-y-2">

                            <div class="text-lg font-semibold text-zinc-900 dark:text-white">
                                {{ $cpar->cpar_no }}
                            </div>

                            <div class="text-sm text-zinc-500">
                                <strong>Reported by:</strong>
                                {{ $cpar->reported_by }}
                            </div>

                            <div class="text-sm text-zinc-500">
                                <strong>Assigned to:</strong>
                                {{ $cpar->employee_name }}
                            </div>

                            <div class="text-sm text-zinc-500">
                                <strong>Employee No.:</strong>
                                {{ $cpar->employee_no }}
                            </div>

                            <div class="text-sm text-zinc-500">
                                <strong>Department:</strong>
                                {{ $cpar->department_name }}
                            </div>

                            <div class="flex items-center gap-2 pt-2">

                                <span class="text-sm text-zinc-500">
                                    Disciplinary History:
                                </span>

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
                                    1 Previous Offense
                                </flux:badge>

                                @elseif ($cpar->offense_count == 2)

                                <flux:badge
                                    color="orange"
                                    icon="exclamation-triangle">
                                    2 Previous Offenses
                                </flux:badge>

                                @else

                                <flux:badge
                                    color="red"
                                    icon="exclamation-triangle">
                                    {{ $cpar->offense_count }} Previous Offenses
                                </flux:badge>

                                @endif

                            </div>

                        </div>

                        {{-- RIGHT --}}
                        <div class="w-64 flex flex-col items-end gap-3">

                            <flux:badge color="yellow">
                                PENDING HR DECISION
                            </flux:badge>

                            @if($cpar->nte_no || $cpar->ir_id)

                            <div class="w-full rounded-lg border border-zinc-200 dark:border-zinc-700 p-3 bg-zinc-50 dark:bg-zinc-900">

                                @if(!empty($cpar->nte_no))
                                <div class="flex justify-between text-sm">
                                    <span class="text-zinc-500">
                                        NTE No.
                                    </span>

                                    <span class="font-semibold">
                                        {{ $cpar->nte_no }}
                                    </span>
                                </div>
                                @endif

                                @if(!empty($cpar->ir_id))
                                <div class="flex justify-between text-sm @if(!empty($cpar->nte_no)) mt-3 @endif">
                                    <span class="text-zinc-500">
                                        IR No.
                                    </span>

                                    <span class="font-semibold">
                                        {{ $cpar->ir_id }}
                                    </span>
                                </div>
                                @endif

                            </div>

                            @endif

                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="mt-5 flex justify-end">

                        <flux:button
                            variant="primary"
                            icon="eye"
                            wire:click="viewCpar({{ $cpar->assignment_id }})">
                            View
                        </flux:button>

                    </div>

                </div>

                @empty

                <div class="py-10 text-center">

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

                </div>

                @endforelse

            </div>

        </div>

    </flux:modal>

</div>