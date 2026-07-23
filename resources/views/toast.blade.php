<div
    x-data="{
    show:false,
    message:'',
    type:'success'
    }"
    x-on:toast.window="
    show = true;
    message = $event.detail[0].message;
    type = $event.detail[0].type;

    setTimeout(() => show = false, 3000);
    "
    x-show="show"
    x-transition
    class="fixed top-5 right-5 z-[9999]"
    style="display:none;">
    <div
        class="px-5 py-3 rounded-lg shadow-lg text-white"
        :class="{
            'bg-green-600': type === 'success',
            'bg-red-600': type === 'error',
            'bg-yellow-500': type === 'warning',
            'bg-blue-600': type === 'info'
        }">
        <span x-text="message"></span>
    </div>
</div>