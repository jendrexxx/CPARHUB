<div>
    <div class="flex items-center justify-between">
        {{-- Left --}}
        <div>
            <h1 class="text-2xl font-bold text-zinc-800 dark:text-white">
                Lab Supervisor Dashboard
            </h1>

            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                Welcome back, {{ auth()->user()->name }}
            </p>
        </div>
    </div>
    @include('toast')
    <div class="p-3 grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        <flux:modal.trigger name="LABModal">
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
                        CPAR Review
                    </div>
                </div>
            </div>
        </flux:modal.trigger>
    </div>
    <livewire:admin.lab.lab_notif />
    <livewire:admin.lab.lab_request />
</div>