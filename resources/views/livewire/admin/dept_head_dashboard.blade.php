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
    <div class="grid grid-cols-1 gap-4 p-3 sm:grid-cols-2 sm:gap-6">

        {{-- Other Concern --}}
        <flux:modal.trigger name="CPARModal">
            <div
                class="group flex h-full w-full cursor-pointer items-center gap-4
                   rounded-2xl border border-gray-200 bg-white p-5 shadow-sm
                   transition-all duration-200 hover:-translate-y-1 hover:shadow-lg
                   dark:border-zinc-700 dark:bg-zinc-900">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                       rounded-xl bg-yellow-100 text-yellow-600
                       transition-colors duration-200
                       group-hover:bg-yellow-500 group-hover:text-white
                       dark:bg-yellow-900/30 dark:text-yellow-400
                       dark:group-hover:bg-yellow-500 dark:group-hover:text-white">
                    <flux:icon.clipboard-document-list class="h-6 w-6" />
                </div>

                <div class="min-w-0">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $concern_count }}
                    </div>

                    <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">
                        Other Concern
                    </div>
                </div>
            </div>
        </flux:modal.trigger>

        {{-- Acknowledgment Concern --}}
        <flux:modal.trigger name="CPARSubmissionModal">
            <div
                class="group flex h-full w-full cursor-pointer items-center gap-4
                   rounded-2xl border border-gray-200 bg-white p-5 shadow-sm
                   transition-all duration-200 hover:-translate-y-1 hover:shadow-lg
                   dark:border-zinc-700 dark:bg-zinc-900">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                       rounded-xl bg-blue-100 text-blue-600
                       transition-colors duration-200
                       group-hover:bg-blue-500 group-hover:text-white
                       dark:bg-blue-900/30 dark:text-blue-400
                       dark:group-hover:bg-blue-500 dark:group-hover:text-white">
                    <flux:icon.paper-airplane class="h-6 w-6" />
                </div>

                <div class="min-w-0">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $acknowledgment_count }}
                    </div>

                    <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">
                        Acknowledgment Concern
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
    <!-- RESULT -->
    <livewire:admin.result.result_assign />
    <livewire:admin.result.result_concern_form />
    <livewire:admin.result.result_concern_modal />
</div>