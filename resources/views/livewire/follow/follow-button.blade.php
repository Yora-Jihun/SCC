<div>
    @if (auth()->id() !== $profileUser->id)
        <button
            wire:click="toggle"
            class="px-4 py-1.5 rounded text-sm font-medium
                {{ $isFriends ? 'bg-green-100 text-green-700' : ($isFollowing ? 'bg-gray-100 text-gray-700' : 'bg-blue-600 text-white') }}"
        >
            @if ($isFriends)
                Friends
            @elseif ($isFollowing)
                Following
            @else
                Follow
            @endif
        </button>
    @endif
</div>