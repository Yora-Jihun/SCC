{{-- resources/views/livewire/profile/show-profile.blade.php --}}
<div class="max-w-xl mx-auto py-6 space-y-4">
    <div class="flex items-center gap-4">
        <img src="{{ $profileUser->avatar_url }}" class="w-16 h-16 rounded-full object-cover">
        <div class="flex-1">
            <h1 class="text-xl font-bold">{{ $profileUser->name }}</h1>
            @if ($profileUser->bio)
                <p class="text-gray-600 text-sm">{{ $profileUser->bio }}</p>
            @endif
        </div>
        <livewire:follow.follow-button :profile-user="$profileUser" :key="'follow-'.$profileUser->id" />
    </div>

    <div class="flex gap-4 text-sm border-b pb-2">
        <button wire:click="showTab('posts')" class="{{ $tab === 'posts' ? 'font-semibold' : 'text-gray-500' }}">
            Posts
        </button>
        <button wire:click="showTab('followers')" class="{{ $tab === 'followers' ? 'font-semibold' : 'text-gray-500' }}">
            Followers ({{ $profileUser->followers()->count() }})
        </button>
        <button wire:click="showTab('following')" class="{{ $tab === 'following' ? 'font-semibold' : 'text-gray-500' }}">
            Following ({{ $profileUser->following()->count() }})
        </button>
    </div>

    @if ($tab === 'followers')
        <livewire:follow.connections-list :user="$profileUser" type="followers" :key="'followers-'.$profileUser->id" />
    @elseif ($tab === 'following')
        <livewire:follow.connections-list :user="$profileUser" type="following" :key="'following-'.$profileUser->id" />
    @else
        <p class="text-gray-500 text-sm">This user's posts will show here in a future chapter's feed filter.</p>
    @endif
</div>