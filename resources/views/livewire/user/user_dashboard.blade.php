<div class="w-full min-w-0">
    {{-- Toast Message --}}
    @include('user_toast')
    @include('toast')
    {{-- Dashboard Header --}}
    <div
        class="flex flex-col gap-3 px-3 pt-4
               sm:flex-row sm:items-center sm:justify-between
               sm:px-4
               lg:px-6">

        <div class="min-w-0">
            <h1
                class="truncate text-xl font-bold text-zinc-800
                       sm:text-2xl
                       dark:text-white">
                User Dashboard
            </h1>

            <p
                class="truncate text-sm text-zinc-500
                       dark:text-zinc-400">
                Welcome back, {{ auth()->user()->name }}
            </p>
        </div>

        <div class="w-full sm:w-auto">
            <flux:breadcrumbs class="text-sm">
                <flux:breadcrumbs.item href="#">
                    Home
                </flux:breadcrumbs.item>

                <flux:breadcrumbs.item href="#">
                    User Dashboard
                </flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
    </div>
    <div
        class="grid w-full grid-cols-1 gap-3
           p-3
           sm:grid-cols-2 sm:gap-4 sm:p-4
           lg:grid-cols-3 lg:gap-5 lg:p-6">

        <flux:modal.trigger name="CPARModal">
            <div
                class="group flex w-full min-w-0 cursor-pointer items-center gap-3
                       rounded-2xl border border-gray-200
                       bg-white p-4 shadow-sm
                       transition-all duration-300
                       hover:-translate-y-1 hover:shadow-xl
                       sm:gap-4 sm:p-5
                       dark:border-zinc-700
                       dark:bg-zinc-900">

                <div
                    class="flex size-11 shrink-0 items-center justify-center
                           rounded-xl
                           bg-yellow-100 text-yellow-600
                           transition-all duration-300
                           group-hover:bg-yellow-500
                           group-hover:text-white
                           sm:size-12
                           dark:bg-yellow-900/30
                           dark:text-yellow-400">

                    <flux:icon.clipboard-document-list
                        class="size-5 sm:size-6" />
                </div>

                <div class="min-w-0 flex-1">
                    <div
                        class="text-xl font-bold text-gray-900
                               sm:text-2xl
                               dark:text-white">
                        {{ $request_count }}
                    </div>

                    <div
                        class="truncate text-sm font-medium
                               text-gray-500
                               dark:text-zinc-400">
                        Reported Concern
                    </div>
                </div>
            </div>
        </flux:modal.trigger>

        <flux:modal.trigger name="CPARAssignedModal">
            <div
                class="group flex w-full min-w-0 cursor-pointer items-center gap-3
                       rounded-2xl border border-gray-200
                       bg-white p-4 shadow-sm
                       transition-all duration-300
                       hover:-translate-y-1 hover:shadow-xl
                       sm:gap-4 sm:p-5
                       dark:border-zinc-700
                       dark:bg-zinc-900">

                <div
                    class="flex size-11 shrink-0 items-center justify-center
                           rounded-xl
                           bg-blue-100 text-blue-600
                           transition-all duration-300
                           group-hover:bg-blue-500
                           group-hover:text-white
                           sm:size-12
                           dark:bg-blue-900/30
                           dark:text-blue-400">

                    <flux:icon.user-group
                        class="size-5 sm:size-6" />
                </div>

                <div class="min-w-0 flex-1">
                    <div
                        class="text-xl font-bold text-gray-900
                               sm:text-2xl
                               dark:text-white">
                        {{ $assigned_count }}
                    </div>

                    <div
                        class="truncate text-sm font-medium
                               text-gray-500
                               dark:text-zinc-400">
                        Assigned Concern
                    </div>
                </div>
            </div>
        </flux:modal.trigger>

        <flux:modal.trigger name="NoticeToExplainModal">
            <div
                class="group flex w-full min-w-0 cursor-pointer items-center gap-3
                       rounded-2xl border border-gray-200
                       bg-white p-4 shadow-sm
                       transition-all duration-300
                       hover:-translate-y-1 hover:shadow-xl
                       sm:gap-4 sm:p-5
                       dark:border-zinc-700
                       dark:bg-zinc-900">

                <div
                    class="flex size-11 shrink-0 items-center justify-center
                           rounded-xl
                           bg-red-100 text-red-600
                           transition-all duration-300
                           group-hover:bg-red-500
                           group-hover:text-white
                           sm:size-12
                           dark:bg-red-900/30
                           dark:text-red-400">

                    <flux:icon.document-text
                        class="size-5 sm:size-6" />
                </div>

                <div class="min-w-0 flex-1">
                    <div
                        class="text-xl font-bold text-gray-900
                               sm:text-2xl
                               dark:text-white">
                        {{ $nte_cpar }}
                    </div>

                    <div
                        class="truncate text-sm font-medium
                               text-gray-500
                               dark:text-zinc-400">
                        NTE Explanation
                    </div>
                </div>
            </div>
        </flux:modal.trigger>
    </div>

    <div class="w-full px-3 pb-4 sm:px-4 lg:px-6">
        <livewire:user.tabs />
    </div>

    <!-- cpar -->
    <livewire:user.modal.cpar_notif />
    <livewire:user.modal.cpar_assigned />
    <livewire:user.modal.cpar_respond_form />
    <livewire:user.modal.cpar_cancel />
    <livewire:user.modal.cpar_edit />
    <livewire:user.modal.cpar_nte_table />
    <livewire:user.modal.cpar_nte_explanation />
    <livewire:user.modal.cpar_ir_table />
    <livewire:user.modal.cpar_ir_explanation />
    <!-- result -->
    <livewire:user.modal.result_form />
    <livewire:user.modal.result_cancel />
    <!-- result -->
    <livewire:user.modal.result_notif />
    <livewire:user.modal.result_assigned />
    <livewire:user.modal.result_respond_form />
    <livewire:user.modal.result-nte />
</div>