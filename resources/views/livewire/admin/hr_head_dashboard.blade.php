<div>

    {{-- =========================================================
        FULL PAGE LOADING
    ========================================================== --}}
    <div
        wire:loading
        wire:target="loadDashboard,branch_id"
        class="fixed inset-0 z-[9999] flex items-center justify-center
               bg-white/90 dark:bg-zinc-950/90 backdrop-blur-sm"
    >
        <div class="flex flex-col items-center gap-4">

            {{-- Spinner --}}
            <div
                class="h-14 w-14 animate-spin rounded-full
                       border-4 border-zinc-200
                       border-t-red-800"
            ></div>

            <div class="text-base font-semibold text-zinc-700 dark:text-zinc-200">
                Loading HR Dashboard...
            </div>

            <div class="text-sm text-zinc-500 dark:text-zinc-400">
                Please wait...
            </div>

        </div>
    </div>


    {{-- =========================================================
        DASHBOARD CONTENT
    ========================================================== --}}
    <div wire:init="loadDashboard">

        {{-- HEADER --}}
        <div class="flex items-center justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold text-zinc-800 dark:text-white">
                    HR Dashboard
                </h1>

                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    Welcome back, {{ auth()->user()->name }}
                </p>
            </div>


            {{-- BRANCH --}}
            <div class="w-64">

                <flux:select
                    label="Branch"
                    wire:model.live="branch_id"
                >

                    @foreach ($branches as $branch)

                        <option value="{{ $branch->id }}">
                            {{ $branch->branch_name }}
                        </option>

                    @endforeach

                </flux:select>

            </div>

        </div>


        @include('toast')


        {{-- =====================================================
            CARDS
        ====================================================== --}}
        <div class="p-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">

            {{-- REPORTED CONCERN --}}
            <flux:modal.trigger name="CPARHRModal">

                <div
                    class="group flex h-full w-full items-center gap-4 p-5
                           bg-white dark:bg-zinc-900
                           rounded-2xl border border-gray-200 dark:border-zinc-700
                           shadow-sm cursor-pointer
                           transition-all duration-200
                           hover:-translate-y-1 hover:shadow-lg"
                >

                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center
                               rounded-xl bg-yellow-100 text-yellow-600
                               group-hover:bg-yellow-500 group-hover:text-white
                               transition-colors duration-200"
                    >
                        <flux:icon.clipboard-document-list class="w-6 h-6" />
                    </div>

                    <div class="min-w-0">

                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $hr_request_count }}
                        </div>

                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                            Reported Concern
                        </div>

                    </div>

                </div>

            </flux:modal.trigger>


            {{-- ACKNOWLEDGMENT --}}
            <flux:modal.trigger name="CPARAcknowledgeModal">

                <div
                    class="group flex h-full w-full items-center gap-4 p-5
                           bg-white dark:bg-zinc-900
                           rounded-2xl border border-gray-200 dark:border-zinc-700
                           shadow-sm cursor-pointer
                           transition-all duration-200
                           hover:-translate-y-1 hover:shadow-lg"
                >

                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center
                               rounded-xl bg-blue-100 text-blue-600
                               group-hover:bg-blue-500 group-hover:text-white
                               transition-colors duration-200"
                    >
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


            {{-- HR DECISION --}}
            <flux:modal.trigger name="HRDecisionModal">

                <div
                    class="group flex h-full w-full items-center gap-4 p-5
                           bg-white dark:bg-zinc-900
                           rounded-2xl border border-gray-200 dark:border-zinc-700
                           shadow-sm cursor-pointer
                           transition-all duration-200
                           hover:-translate-y-1 hover:shadow-lg"
                >

                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center
                               rounded-xl bg-blue-100 text-blue-600
                               transition-all duration-200
                               group-hover:bg-blue-500 group-hover:text-white
                               group-hover:scale-105"
                    >
                        <flux:icon.scale class="h-6 w-6" />
                    </div>

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


            {{-- MEMO --}}
            <flux:modal.trigger name="HRMemoModal">

                <div
                    class="group flex h-full w-full items-center gap-4 p-5
                           bg-white dark:bg-zinc-900
                           rounded-2xl border border-gray-200 dark:border-zinc-700
                           shadow-sm cursor-pointer
                           transition-all duration-200
                           hover:-translate-y-1 hover:shadow-lg"
                >

                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center
                               rounded-xl bg-blue-100 text-blue-600
                               transition-all duration-200
                               group-hover:bg-blue-500 group-hover:text-white
                               group-hover:scale-105"
                    >
                        <flux:icon.document-text class="h-6 w-6" />
                    </div>

                    <div class="min-w-0">

                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $memo_count }}
                        </div>

                        <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">
                            Employees Subject to Memo
                        </div>

                    </div>

                </div>

            </flux:modal.trigger>

        </div>

        <!-- cpar -->
        <livewire:admin.tabs.hr_tabs :branch_id="$branch_id" :key="'hr-tabs-'.$branch_id" lazy/>
        <livewire:admin.hr.hr_acknowledge_modal />
        <livewire:admin.hr.hr_notice_explain_modal />
        <livewire:admin.hr.hr_decision_modal />
        <livewire:admin.hr.hr_memo_modal />
        <livewire:admin.hr.hr_ir_request />
        <livewire:user.modal.cpar_edit />
        <livewire:admin.hr.hr_notif :branch_id="$branch_id" :key="'hr-notif-'.$branch_id" lazy/>
        <livewire:admin.hr.hr_reassign :branch_id="$branch_id" :key="'hr-assign-'.$branch_id" lazy/>
        <livewire:admin.hr.hr_acknowledge_notif :branch_id="$branch_id" :key="'hr-acknowledge-notif-'.$branch_id" lazy/>
        <livewire:admin.hr.hr_decision_notif :branch_id="$branch_id" :key="'hr-decision-notif-'.$branch_id" lazy />
        <livewire:admin.hr.hr_memo_notif :branch_id="$branch_id" :key="'hr-memo-notif-'.$branch_id" lazy/>
        <!-- result -->
        <livewire:admin.result.hr_re-assign />
        <livewire:admin.hr_result.result_ir_modal />
        <livewire:admin.hr_result.hr_decision_modal />
        <livewire:admin.hr_result.hr_memo_modal />

    </div>

</div>