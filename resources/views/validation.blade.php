    @if(!empty($message))
    <div class="p-3 bg-green-100 text-green-700 rounded mb-4">
        {{ $message }}
    </div>
    @elseif(!empty($error))
    <div class="p-3 bg-red-100 text-red-700 rounded mb-4">
        {{ $error }}
    </div>
    @endif