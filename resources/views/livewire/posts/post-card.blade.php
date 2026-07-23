{{-- resources/views/livewire/posts/post-card.blade.php --}}
<div class="max-w-xl mx-auto bg-white rounded-lg border p-4 space-y-2" wire:key="post-{{ $post->id }}">

    @if ($post->isShare())
        <p class="text-xs text-gray-500">
            {{ auth()->user()->id === $post->user_id ? 'You' : $post->author->name }} shared a post
        </p>
    @endif

    @if (! $editing)
        <div class="flex justify-between items-start">
            <span class="font-semibold">{{ $post->author->name }}</span>
            <span class="text-xs text-gray-400">
                {{ $post->visibility === \App\Enums\PostVisibility::Private ? 'Private' : 'Public' }}
            </span>
        </div>

        @if ($post->body)
            <p class="text-gray-800">{{ $post->body }}</p>
        @endif

        @if ($post->image_path)
            <img src="{{ Storage::disk('public')->url($post->image_path) }}" class="rounded w-full">
        @endif

        @if ($post->isShare())
            <div class="border rounded p-3 bg-gray-50">
                @if ($post->originalPost)
                    <p class="text-sm font-semibold">{{ $post->originalPost->author->name }}</p>
                    <p class="text-sm text-gray-700">{{ $post->originalPost->body }}</p>
                @else
                    <p class="text-sm text-gray-400 italic">This post is no longer available.</p>
                @endif
            </div>
        @endif

        <div class="flex gap-3 text-sm text-gray-500 pt-2">
            @can('update', $post)
                <button wire:click="startEditing">Edit</button>
            @endcan
            @can('delete', $post)
                <button wire:click="delete" wire:confirm="Delete this post?">Delete</button>
            @endcan
            @can('share', $post)
                <button wire:click="share">Share</button>
            @endcan
        </div>
    @else
        <textarea wire:model="body" class="w-full border rounded p-2" rows="3"></textarea>
        @error('body') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

        <select wire:model="visibility" class="border rounded p-2 text-sm">
            <option value="public">Public</option>
            <option value="private">Private</option>
        </select>

        @if ($post->image_path && ! $removeImage)
            <div class="flex items-center gap-2">
                <img src="{{ Storage::disk('public')->url($post->image_path) }}" class="max-h-32 rounded">
                <button wire:click="$set('removeImage', true)" class="text-xs text-red-600">Remove image</button>
            </div>
        @endif

        <div class="flex gap-2">
            <button wire:click="update" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Save</button>
            <button wire:click="cancelEditing" class="text-sm text-gray-500">Cancel</button>
        </div>
    @endif
</div>