<?php

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

new class extends Component
{
    public int $pendingJobs = 0;

    public int $failedJobs = 0;

    public string $message = '';

    public function mount(): void
    {
        abort_unless(auth()->check(), 403);

        $this->refreshQueueStatus();
    }

    public function refreshQueueStatus(): void
    {
        $this->pendingJobs = DB::table('jobs')->count();
        $this->failedJobs = DB::table('failed_jobs')->count();
    }

    public function processQueue(): void
    {
        abort_unless(auth()->check(), 403);

        // Add your admin role/permission check here before production use.

        try {
            Artisan::call('queue:work', [
                '--stop-when-empty' => true,
                '--tries' => 3,
                '--timeout' => 30,
                '--max-time' => 10,
            ]);

            $this->message = trim(Artisan::output())
                ?: 'Queue processing finished.';
        } catch (\Throwable $e) {
            report($e);

            $this->message = 'Queue processing failed. Check storage/logs/laravel.log.';
        }

        $this->refreshQueueStatus();
    }
};
?>

<div>

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">
                CPAR Mail Queue
            </h1>

            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                Monitor and process queued CPAR email notifications.
            </p>
        </div>

        @if ($message)
        <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-300">
            {{ $message }}
        </div>
        @endif

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    Pending Jobs
                </p>

                <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">
                    {{ $pendingJobs }}
                </p>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    Failed Jobs
                </p>

                <p class="mt-2 text-3xl font-bold text-red-700">
                    {{ $failedJobs }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <button
                type="button"
                wire:click="processQueue"
                wire:loading.attr="disabled"
                wire:target="processQueue"
                class="rounded-lg bg-red-800 px-4 py-2 text-sm font-semibold text-white hover:bg-red-900 disabled:opacity-50">
                <span wire:loading.remove wire:target="processQueue">
                    Run Mail Queue
                </span>

                <span wire:loading wire:target="processQueue">
                    Processing...
                </span>
            </button>

            <button
                type="button"
                wire:click="refreshQueueStatus"
                wire:loading.attr="disabled"
                class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-100 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">
                Refresh Status
            </button>
        </div>
    </div>

</div>