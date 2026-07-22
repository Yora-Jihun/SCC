{{-- resources/views/livewire/auth/forgot-password.blade.php --}}
<div class="max-w-md mx-auto mt-10 space-y-4">
    <h1 class="text-2xl font-bold">Forgot your password?</h1>
    <p class="text-gray-600">Enter your email and we'll send you a reset code.</p>

    <input type="email" wire:model="email" placeholder="Email" class="w-full border rounded p-2">
    @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

    <button wire:click="send" class="w-full bg-blue-600 text-white py-2 rounded">
        Send reset code
    </button>
</div>