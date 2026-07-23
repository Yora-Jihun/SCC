
<button
    wire:click="toggle"
    class="flex items-center gap-1 text-sm {{ $liked ? 'text-red-600 font-semibold' : 'text-gray-500' }}"
>
    <span>{{ $liked ? '♥' : '♡' }}</span>
    <span>{{ $count }}</span>
</button>