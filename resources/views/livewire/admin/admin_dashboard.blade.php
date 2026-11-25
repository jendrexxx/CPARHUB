<div>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-800 dark:text-white">
                Admin Dashboard
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                Welcome back, {{ auth()->user()->name }}
            </p>
        </div>
        <div class="w-64">

            <flux:select
                label="Branch"
                wire:model.live="branch_id">

                @foreach ($branches as $branch)
                <option value="{{ $branch->id }}">
                    {{ $branch->branch_name }}
                </option>
                @endforeach

            </flux:select>

        </div>
    </div>
    <div class="grid grid-cols-1 gap-4 p-3 md:grid-cols-3 md:gap-6">
        <!-- Reported Concerns -->
        <flux:modal.trigger name="ReportedConcernsModal">
            <div
                class="group flex h-full w-full cursor-pointer items-center gap-4
                   rounded-2xl border border-gray-200 bg-white p-5 shadow-sm
                   transition-all duration-200
                   hover:-translate-y-1 hover:shadow-lg
                   dark:border-zinc-700 dark:bg-zinc-900">

                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                       rounded-xl bg-yellow-100 text-yellow-600
                       transition-all duration-200
                       group-hover:scale-105 group-hover:bg-yellow-500
                       group-hover:text-white
                       dark:bg-yellow-900/30 dark:text-yellow-400
                       dark:group-hover:bg-yellow-500 dark:group-hover:text-white">

                    <flux:icon.calendar class="h-6 w-6" />
                </div>

                <div class="min-w-0">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $reported_count }}
                    </div>

                    <div class="truncate text-[15px] font-bold text-gray-500 dark:text-zinc-400">
                        Reported Concerns
                    </div>
                </div>
            </div>
        </flux:modal.trigger>
        <!-- Assigned Concern -->
        <flux:modal.trigger name="AssignedConcernModal">
            <div
                class="group flex h-full w-full cursor-pointer items-center gap-4
                   rounded-2xl border border-gray-200 bg-white p-5 shadow-sm
                   transition-all duration-200
                   hover:-translate-y-1 hover:shadow-lg
                   dark:border-zinc-700 dark:bg-zinc-900">

                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                       rounded-xl bg-blue-100 text-blue-600
                       transition-all duration-200
                       group-hover:scale-105 group-hover:bg-blue-500
                       group-hover:text-white
                       dark:bg-blue-900/30 dark:text-blue-400
                       dark:group-hover:bg-blue-500 dark:group-hover:text-white">

                    <flux:icon.user class="h-6 w-6" />
                </div>

                <div class="min-w-0">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $assigned_count }}
                    </div>

                    <div class="truncate text-[15px] font-bold text-gray-500 dark:text-zinc-400">
                        Assigned Concern
                    </div>
                </div>
            </div>
        </flux:modal.trigger>
        <!-- NTE Explanation -->
        <flux:modal.trigger name="NTEExplanationModal">
            <div
                class="group flex h-full w-full cursor-pointer items-center gap-4
                   rounded-2xl border border-gray-200 bg-white p-5 shadow-sm
                   transition-all duration-200
                   hover:-translate-y-1 hover:shadow-lg
                   dark:border-zinc-700 dark:bg-zinc-900">

                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                       rounded-xl bg-purple-100 text-purple-600
                       transition-all duration-200
                       group-hover:scale-105 group-hover:bg-purple-500
                       group-hover:text-white
                       dark:bg-purple-900/30 dark:text-purple-400
                       dark:group-hover:bg-purple-500 dark:group-hover:text-white">

                    <flux:icon.document-text class="h-6 w-6" />
                </div>

                <div class="min-w-0">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $nte_count }}
                    </div>

                    <div class="truncate text-[15px] font-bold text-gray-500 dark:text-zinc-400">
                        NTE Explanation
                    </div>
                </div>
            </div>
        </flux:modal.trigger>
        <!-- Acknowledge Concern -->
        <flux:modal.trigger name="AcknowledgeConcernModal">
            <div
                class="group flex h-full w-full cursor-pointer items-center gap-4
                   rounded-2xl border border-gray-200 bg-white p-5 shadow-sm
                   transition-all duration-200
                   hover:-translate-y-1 hover:shadow-lg
                   dark:border-zinc-700 dark:bg-zinc-900">

                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                       rounded-xl bg-green-100 text-green-600
                       transition-all duration-200
                       group-hover:scale-105 group-hover:bg-green-500
                       group-hover:text-white
                       dark:bg-green-900/30 dark:text-green-400
                       dark:group-hover:bg-green-500 dark:group-hover:text-white">

                    <flux:icon.check-circle class="h-6 w-6" />
                </div>

                <div class="min-w-0">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $acknowledge_count }}
                    </div>

                    <div class="truncate text-[15px] font-bold text-gray-500 dark:text-zinc-400">
                        Acknowledge Concern
                    </div>
                </div>
            </div>
        </flux:modal.trigger>
        <!-- Pending HR Decision -->
        <flux:modal.trigger name="HRDecisionModal">
            <div
                class="group flex h-full w-full cursor-pointer items-center gap-4
                   rounded-2xl border border-gray-200 bg-white p-5 shadow-sm
                   transition-all duration-200
                   hover:-translate-y-1 hover:shadow-lg
                   dark:border-zinc-700 dark:bg-zinc-900">

                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                       rounded-xl bg-orange-100 text-orange-600
                       transition-all duration-200
                       group-hover:scale-105 group-hover:bg-orange-500
                       group-hover:text-white
                       dark:bg-orange-900/30 dark:text-orange-400
                       dark:group-hover:bg-orange-500 dark:group-hover:text-white">

                    <flux:icon.clock class="h-6 w-6" />
                </div>

                <div class="min-w-0">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $hr_decision_count }}
                    </div>

                    <div class="truncate text-[15px] font-bold text-gray-500 dark:text-zinc-400">
                        Pending HR Decision
                    </div>
                </div>
            </div>
        </flux:modal.trigger>
        <!-- Employees Subject to Memo -->
        <flux:modal.trigger name="HRMemoModal">
            <div
                class="group flex h-full w-full cursor-pointer items-center gap-4
                   rounded-2xl border border-gray-200 bg-white p-5 shadow-sm
                   transition-all duration-200
                   hover:-translate-y-1 hover:shadow-lg
                   dark:border-zinc-700 dark:bg-zinc-900">

                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                       rounded-xl bg-blue-100 text-blue-600
                       transition-all duration-200
                       group-hover:scale-105 group-hover:bg-blue-500
                       group-hover:text-white
                       dark:bg-blue-900/30 dark:text-blue-400
                       dark:group-hover:bg-blue-500 dark:group-hover:text-white">

                    <flux:icon.document-text class="h-6 w-6" />
                </div>

                <div class="min-w-0">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $memo_count }}
                    </div>

                    <div class="truncate text-[15px] font-bold text-gray-500 dark:text-zinc-400">
                        Employees Subject to Memo
                    </div>
                </div>
            </div>
        </flux:modal.trigger>
    </div>
    <livewire:admin.dashboard.reported_list />
    <livewire:admin.dashboard.assigned_list />
    <livewire:admin.dashboard.modal.reported />
    <livewire:admin.dashboard.modal.assigned  :branch_id="$branch_id" lazy />
</div>