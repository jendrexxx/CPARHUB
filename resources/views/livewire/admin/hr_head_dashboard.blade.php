<div>
    <div class="flex items-center justify-between">

        {{-- Left --}}
        <div>
            <h1 class="text-2xl font-bold text-zinc-800 dark:text-white">
                HR Dashboard
            </h1>

            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                Welcome back, {{ auth()->user()->name }}
            </p>
        </div>


        {{-- Right: Branch Filter --}}
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
    @include('toast')

    <div class="p-3 grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">

        {{-- Reported Concern --}}
        <flux:modal.trigger name="CPARHRModal">
            <div
                class="group flex h-full w-full items-center gap-4 p-5
                   bg-white dark:bg-zinc-900
                   rounded-2xl border border-gray-200 dark:border-zinc-700
                   shadow-sm cursor-pointer
                   transition-all duration-200
                   hover:-translate-y-1 hover:shadow-lg">

                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                       rounded-xl bg-yellow-100 text-yellow-600
                       group-hover:bg-yellow-500 group-hover:text-white
                       transition-colors duration-200">
                    <flux:icon.clipboard-document-list class="w-6 h-6" />
                </div>

                <div class="min-w-0">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $cpar_request_count }}
                    </div>

                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        Reported Concern
                    </div>
                </div>
            </div>
        </flux:modal.trigger>


        {{-- Acknowledgment Concern --}}
        <flux:modal.trigger name="CPARAcknowledgeModal">
            <div
                class="group flex h-full w-full items-center gap-4 p-5
                   bg-white dark:bg-zinc-900
                   rounded-2xl border border-gray-200 dark:border-zinc-700
                   shadow-sm cursor-pointer
                   transition-all duration-200
                   hover:-translate-y-1 hover:shadow-lg">

                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                       rounded-xl bg-blue-100 text-blue-600
                       group-hover:bg-blue-500 group-hover:text-white
                       transition-colors duration-200">
                    <flux:icon.paper-airplane class="w-6 h-6" />
                </div>

                <div class="min-w-0">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $acknowledged_cpar }}
                    </div>

                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        Acknowledgment Concern
                    </div>
                </div>
            </div>
        </flux:modal.trigger>

        {{-- Pending HR Decision --}}
        <flux:modal.trigger name="HRDecisionModal">
            <div
                class="group flex h-full w-full items-center gap-4 p-5
               bg-white dark:bg-zinc-900
               rounded-2xl border border-gray-200 dark:border-zinc-700
               shadow-sm cursor-pointer
               transition-all duration-200
               hover:-translate-y-1 hover:shadow-lg">

                {{-- Icon --}}
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                   rounded-xl bg-blue-100 text-blue-600
                   transition-all duration-200
                   group-hover:bg-blue-500 group-hover:text-white
                   group-hover:scale-105">

                    <flux:icon.scale class="h-6 w-6" />
                </div>

                {{-- Content --}}
                <div class="min-w-0">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $hr_decision_count }}
                    </div>

                    <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">
                        Pending HR Decision
                    </div>
                </div>

            </div>
        </flux:modal.trigger>


        {{-- Result Request --}}
        <flux:modal.trigger name="RESULTModal">
            <div
                class="group flex h-full w-full items-center gap-4 p-5
                   bg-white dark:bg-zinc-900
                   rounded-2xl border border-gray-200 dark:border-zinc-700
                   shadow-sm cursor-pointer
                   transition-all duration-200
                   hover:-translate-y-1 hover:shadow-lg">

                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                       rounded-xl bg-green-100 text-green-600
                       group-hover:bg-green-500 group-hover:text-white
                       transition-colors duration-200">
                    <flux:icon.chart-bar class="w-6 h-6" />
                </div>

                <div class="min-w-0">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $result_request_count }}
                    </div>

                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        Result Request
                    </div>
                </div>
            </div>
        </flux:modal.trigger>

    </div>

    <!-- CPAR -->
    <livewire:admin.hr.hr_notif />
    <livewire:admin.hr.hr_reassign />
    <livewire:admin.hr.hr_acknowledge_notif />
    <livewire:admin.hr.hr_acknowledge_modal />
    <livewire:admin.hr.hr_notice_explain_modal />
    <livewire:admin.hr.hr_decision_notif />
    <livewire:admin.hr.hr_decision_modal />
    <livewire:admin.hr.hr_ir_request />
    <livewire:user.modal.cpar_edit />

    <!-- RESULT -->
    <livewire:user.modal.result_notif />
</div>