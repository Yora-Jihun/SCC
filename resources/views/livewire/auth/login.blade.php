{{-- resources/views/livewire/auth/login.blade.php --}}
<div class="max-w-md mx-auto mt-10 space-y-4">
    <h1 class="text-2xl font-bold">Log in</h1>

    <input type="email" wire:model="email" placeholder="Email" class="w-full border rounded p-2">
    @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

    <input type="password" wire:model="password" placeholder="Password" class="w-full border rounded p-2">
    @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

    <label class="flex items-center gap-2 text-sm text-gray-600">
        <input type="checkbox" wire:model="remember">
        Remember me
    </label>

    @if ($error)
        <p class="text-red-600 text-sm">{{ $error }}</p>
    @endif

    @if (session('status'))
        <p class="text-green-600 text-sm">{{ session('status') }}</p>
    @endif

    <button wire:click="login" class="w-full bg-blue-600 text-white py-2 rounded">
        Log in
    </button>

    <a href="{{ route('forgot-password') }}" class="block text-sm text-blue-600 text-center">
        Forgot your password?
    </a>
</div>