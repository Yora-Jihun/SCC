{{-- resources/views/livewire/profile/edit-profile.blade.php --}}
<div
    x-data="{ uploading: false, progress: 0 }"
    x-on:livewire-upload-start="uploading = true"
    x-on:livewire-upload-finish="uploading = false"
    x-on:livewire-upload-cancel="uploading = false"
    x-on:livewire-upload-error="uploading = false"
    x-on:livewire-upload-progress="progress = $event.detail.progress"
    class="max-w-md mx-auto mt-10 space-y-4"
>
    <h1 class="text-2xl font-bold">Edit your profile</h1>

    <div class="flex items-center gap-4">
        @if ($avatar)
            <img src="{{ $avatar->temporaryUrl() }}" class="w-16 h-16 rounded-full object-cover">
        @else
            <img src="{{ auth()->user()->avatar_url }}" class="w-16 h-16 rounded-full object-cover">
        @endif

        <input type="file" wire:model="avatar" accept="image/*">
    </div>
    @error('avatar') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

    <div x-show="uploading" class="w-full bg-gray-200 rounded h-2">
        <div class="bg-blue-600 h-2 rounded" x-bind:style="`width: ${progress}%`"></div>
    </div>

    <input type="text" wire:model="name" placeholder="Name" class="w-full border rounded p-2">
    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

    <textarea wire:model="bio" placeholder="Tell people about yourself"
              class="w-full border rounded p-2" rows="3"></textarea>
    @error('bio') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

    @if ($message)
        <p class="text-green-600 text-sm">{{ $message }}</p>
    @endif

    <button wire:click="save" class="w-full bg-blue-600 text-white py-2 rounded">
        Save changes
    </button>
</div>