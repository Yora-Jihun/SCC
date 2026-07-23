{{-- resources/views/livewire/follow/connections-list.blade.php --}}
<div class="space-y-3">
    @forelse ($people as $person)
        <div class="flex items-center gap-3 border-b pb-2">
            <img src="{{ $person->avatar_url }}" class="w-10 h-10 rounded-full object-cover">
            <a href="{{ route('profile.show', $person) }}" class="font-medium">{{ $person->name }}</a>
        </div>
    @empty
        <p class="text-gray-500 text-sm">
            {{ $type === 'followers' ? 'No followers yet.' : 'Not following anyone yet.' }}
        </p>
    @endforelse

    <div>{{ $people->links() }}</div>
</div>