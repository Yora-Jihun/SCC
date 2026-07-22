<div>
    <button wire:click="check" class="px-4 py-2 bg-blue-600 text-white rounded">
        Ping the server
    </button>

    @if ($result)
        <p class="mt-2 text-green-600">Result: {{ $result }}</p>
    @endif
</div>