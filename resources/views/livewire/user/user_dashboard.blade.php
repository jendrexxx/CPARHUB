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
    @include('validation')
    <div class="p-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 2xl:grid-cols-6 gap-4 sm:gap-6">
        <flux:modal.trigger name="CPARModal">
            <div class="flex items-center space-x-4 p-4 bg-white rounded-xl shadow-md border border-gray-200 hover:shadow-lg hover:scale-105 transition-transform duration-200">
                <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center text-white">
                    <flux:icon.calendar class="w-6 h-6" />
                </div>
                <div>
                    <div class="text-xl font-bold text-gray-900">{{ $cpar_request_count }}</div>
                    <div class="text-gray-700 text-sm">CPAR Request</div>
                </div>
            </div>
        </flux:modal.trigger>

        <flux:modal.trigger name="RESULTModal">
        <div class="flex items-center space-x-4 p-4 bg-white rounded-xl shadow-md border border-gray-200 hover:shadow-lg hover:scale-105 transition-transform duration-200">
            <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center text-white">
                <flux:icon.calendar class="w-6 h-6" />
            </div>
            <div>
                <div class="text-xl font-bold text-gray-900">{{ $result_request_count }}</div>
                <div class="text-gray-700 text-sm">Result Request</div>
            </div>
        </div>
        </flux:modal.trigger>
    </div>
    <!-- CPAR -->
    <livewire:user.modal.cpar_notif />
    <livewire:user.modal.cpar_edit />

    <!-- RESULT -->
    <livewire:user.modal.result_notif />
</div>