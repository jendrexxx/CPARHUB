<div
    x-data="{
        show: false,
        message: '',
        type: 'success',
        timeout: null
    }"
    x-on:toast.window="
        show = true;
        message = $event.detail.message;
        type = $event.detail.type;

        clearTimeout(timeout);

        timeout = setTimeout(() => {
            show = false;
        }, 3000);
    "
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-5"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-5"
    class="fixed top-5 right-5 z-[99999]"
    style="display: none;"
>
    <div
        class="flex items-center gap-3 min-w-[300px] px-5 py-3 rounded-lg shadow-lg text-white"
        :class="{
            'bg-green-600': type === 'success',
            'bg-red-600': type === 'error',
            'bg-yellow-500': type === 'warning',
            'bg-blue-600': type === 'info'
        }"
    >
        {{-- Icon --}}
        <div class="shrink-0">
            <template x-if="type === 'success'">
                <span class="text-xl">✓</span>
            </template>

            <template x-if="type === 'error'">
                <span class="text-xl">✕</span>
            </template>

            <template x-if="type === 'warning'">
                <span class="text-xl">⚠</span>
            </template>

            <template x-if="type === 'info'">
                <span class="text-xl">ⓘ</span>
            </template>
        </div>

        {{-- Message --}}
        <span
            x-text="message"
            class="font-medium"
        ></span>

        {{-- Close --}}
        <button
            type="button"
            @click="show = false"
            class="ml-auto text-white/80 hover:text-white text-lg"
        >
            ×
        </button>
    </div>
</div>