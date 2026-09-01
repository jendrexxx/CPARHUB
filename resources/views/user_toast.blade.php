    @if (session()->has('toast'))
    @php
    $toast = session('toast');
    @endphp
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition
        x-init="setTimeout(() => show = false, 5000)"
        class="fixed right-4 top-4 z-[9999] w-full max-w-sm">
        <div
            class="flex items-start gap-3 rounded-lg border border-green-200
                       bg-green-50 p-4 shadow-lg
                       dark:border-green-800 dark:bg-green-950">

            {{-- Icon --}}
            <div class="mt-0.5 shrink-0">
                <svg
                    class="h-5 w-5 text-green-600 dark:text-green-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>
            </div>

            {{-- Message --}}
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-green-800 dark:text-green-200">
                    Success
                </p>

                <p class="mt-1 text-sm text-green-700 dark:text-green-300">
                    {{ $toast['message'] ?? '' }}
                </p>
            </div>

            {{-- Close --}}
            <button
                type="button"
                @click="show = false"
                class="shrink-0 text-green-600 hover:text-green-800
                           dark:text-green-400 dark:hover:text-green-200">
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>
    </div>
    @endif
