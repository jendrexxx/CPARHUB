<div>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-800 dark:text-white">
                Dept Head Dashboard
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
                Dept Head Dashboard
            </flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>
    @include('toast')
    {{-- TOP CARDS --}}
    <div class="p-3 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
        {{-- CPAR Request --}}
        <flux:modal.trigger name="CPARModal">
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
                    <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">
                        Reported Concern
                    </div>
                </div>
            </div>
        </flux:modal.trigger>

        {{-- Acknowledgment Report --}}
        <flux:modal.trigger name="CPARSubmissionModal">
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
                        {{ $submission_cpar }}
                    </div>
                    <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">
                        Acknowledgment Concern
                    </div>
                </div>
            </div>
        </flux:modal.trigger>

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
                        Result Request
                    </div>
                </div>
            </div>
        </flux:modal.trigger>
    </div>

    <!-- CPAR -->
    <livewire:admin.cpar.cpar_notif />
    <livewire:admin.cpar.cpar_edit />
    <livewire:admin.cpar.cpar_re-assigned />
    <livewire:admin.cpar.cpar_submission_form />
    <livewire:admin.cpar.cpar_submission_modal />
</div>