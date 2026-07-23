{{-- resources/views/livewire/posts/create-post.blade.php --}}
<div class="max-w-xl mx-auto bg-white rounded-lg border p-4 space-y-3">
    <textarea wire:model="body" placeholder="What's on your mind?"
              class="w-full border rounded p-2" rows="3"></textarea>
    @error('body') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

    @if ($image)
        <img src="{{ $image->temporaryUrl() }}" class="max-h-48 rounded object-cover">
    @endif
    <input type="file" wire:model="image" accept="image/*">
    @error('image') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

    <select wire:model="visibility" class="border rounded p-2 text-sm">
        <option value="public">Public</option>
        <option value="private">Private</option>
    </select>

    <button wire:click="save" class="w-full bg-blue-600 text-white py-2 rounded">
        Post
    </button>
</div>