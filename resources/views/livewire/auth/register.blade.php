{{-- resources/views/livewire/auth/register.blade.php --}}
<div class="max-w-md mx-auto mt-10 space-y-4">
    <h1 class="text-2xl font-bold">Create your account</h1>

    <input type="text" wire:model="name" placeholder="Name" class="w-full border rounded p-2">
    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

    <input type="email" wire:model="email" placeholder="Email" class="w-full border rounded p-2">
    @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

    <input type="password" wire:model="password" placeholder="Password" class="w-full border rounded p-2">
    @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

    <input type="password" wire:model="password_confirmation" placeholder="Confirm password" class="w-full border rounded p-2">

    <button wire:click="register" class="w-full bg-blue-600 text-white py-2 rounded">
        Register
    </button>
</div>