<div>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-800 dark:text-white">
                User Dashboard
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                Welcome back, {{ auth()->user()->name }}
            </p>
        </div>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#">
                Home
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#">
                User Dashboard
            </flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>
    @include('toast')
    {{-- TOP CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 p-4">

        {{-- Reported Concern --}}
        <flux:modal.trigger name="CPARModal">
            <div
                class="group flex items-center gap-4 h-full p-5
            bg-white dark:bg-zinc-900
            rounded-2xl
            border border-gray-200 dark:border-zinc-700
            shadow-sm
            cursor-pointer
            transition-all duration-300
            hover:-translate-y-1 hover:shadow-xl">

                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                rounded-xl
                bg-yellow-100 dark:bg-yellow-900/30
                text-yellow-600 dark:text-yellow-400
                group-hover:bg-yellow-500 group-hover:text-white
                transition-all duration-300">

                    <flux:icon.clipboard-document-list class="w-6 h-6" />
                </div>

                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $cpar_request_count }}
                    </div>

                    <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">
                        Reported Concern
                    </div>
                </div>
            </div>
        </flux:modal.trigger>

        {{-- Assigned Concern --}}
        <flux:modal.trigger name="CPARAssignedModal">
            <div
                class="group flex items-center gap-4 h-full p-5
                bg-white dark:bg-zinc-900
                rounded-2xl
                border border-gray-200 dark:border-zinc-700
                shadow-sm
                cursor-pointer
                transition-all duration-300
                hover:-translate-y-1 hover:shadow-xl">

                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                rounded-xl
                bg-blue-100 dark:bg-blue-900/30
                text-blue-600 dark:text-blue-400
                group-hover:bg-blue-500 group-hover:text-white
                transition-all duration-300">

                    <flux:icon.user-group class="w-6 h-6" />
                </div>

                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $assigned_cpar }}
                    </div>

                    <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">
                        Assigned Concern
                    </div>
                </div>
            </div>
        </flux:modal.trigger>

        {{-- IR Request --}}
        <flux:modal.trigger name="IRRequestModal">
            <div
                class="group flex items-center gap-4 h-full p-5
            bg-white dark:bg-zinc-900
            rounded-2xl
            border border-gray-200 dark:border-zinc-700
            shadow-sm
            cursor-pointer
            transition-all duration-300
            hover:-translate-y-1 hover:shadow-xl">

                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                rounded-xl
                bg-orange-100 dark:bg-orange-900/30
                text-orange-600 dark:text-orange-400
                group-hover:bg-orange-500 group-hover:text-white
                transition-all duration-300">

                    <flux:icon.exclamation-triangle class="w-6 h-6" />
                </div>

                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $ir_cpar }}
                    </div>

                    <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">
                        IR Request
                    </div>
                </div>
            </div>
        </flux:modal.trigger>

        {{-- NTE --}}
        <flux:modal.trigger name="NoticeToExplainModal">
            <div
                class="group flex items-center gap-4 h-full p-5
                bg-white dark:bg-zinc-900
                rounded-2xl
                border border-gray-200 dark:border-zinc-700
                shadow-sm
                cursor-pointer
                transition-all duration-300
                hover:-translate-y-1 hover:shadow-xl">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                    rounded-xl
                    bg-red-100 dark:bg-red-900/30
                    text-red-600 dark:text-red-400
                    group-hover:bg-red-500 group-hover:text-white
                    transition-all duration-300">
                    <flux:icon.document-text class="w-6 h-6" />
                </div>

                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $nte_cpar }}
                    </div>
                    <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">
                        NTE Explanation
                    </div>
                </div>
            </div>
        </flux:modal.trigger>

    </div>

    {{-- BOTTOM CARD --}}
    <div class="px-3 pb-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
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

                        <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">
                            Result Related Concern
                        </div>
                    </div>
                </div>
            </flux:modal.trigger>
        </div>
    </div>


    <div class="mt-6">
        <flux:heading size="md">
            History Timeline
        </flux:heading>

        <div class="mt-4">
             @forelse($histories as $history)

        <div class="relative border-l-2 border-gray-300 dark:border-zinc-700 pl-6">

            {{-- Timeline dot --}}
            <div class="absolute -left-[9px] top-2 w-4 h-4 rounded-full 
                        bg-blue-500 border-2 border-white dark:border-zinc-900">
            </div>


            {{-- History Card --}}
            <div class="bg-white dark:bg-zinc-900 
                        border border-gray-200 dark:border-zinc-700
                        rounded-xl p-4 shadow-sm">

                <div class="flex justify-between items-start">

                    <div>
                        <flux:heading size="sm">
                            {{ $history->action }}
                        </flux:heading>

                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $history->created_at->format('M d, Y h:i A') }}
                        </p>
                    </div>


                    {{-- Status --}}
                    <flux:badge color="blue">
                        {{ $history->new_status }}
                    </flux:badge>

                </div>


                {{-- Status Change --}}
                <div class="mt-3 text-sm">

                    @if($history->old_status)

                    <div class="flex items-center gap-2">

                        <span class="text-gray-500">
                            {{ $history->old_status }}
                        </span>

                        <span>
                            →
                        </span>

                        <span class="font-semibold">
                            {{ $history->new_status }}
                        </span>

                    </div>

                    @endif

                </div>


                {{-- Details --}}
                <div class="mt-3 space-y-1 text-sm">

                    @if($history->reported_by)

                    <p>
                        <span class="font-medium">
                            Reported:
                        </span>

                        {{ $history->reported_by }}
                    </p>

                    @endif


                    @if($history->assigned_name)

                    <p>
                        <span class="font-medium">
                            Assigned:
                        </span>

                        {{ $history->assigned_name }}
                    </p>

                    @endif


                    @if($history->remarks)

                    <p class="mt-2 text-gray-600 dark:text-gray-300">
                        {{ $history->remarks }}
                    </p>

                    @endif

                </div>

            </div>

        </div>


        @empty

        <div class="text-center py-6 text-gray-500">
            No history found.
        </div>

        @endforelse
        </div>
    </div>

    <!-- CPAR -->
    <livewire:user.modal.cpar_notif />
    <livewire:user.modal.cpar_assigned />
    <livewire:user.modal.cpar_respond_form />
    <livewire:user.modal.cpar_edit />
    <livewire:user.modal.cpar_nte_table />
    <livewire:user.modal.cpar_nte_explanation />
    <livewire:user.modal.cpar_ir_table />
    <livewire:user.modal.cpar_ir_explanation />
    <!-- RESULT -->
    <livewire:user.modal.result_notif />
</div>