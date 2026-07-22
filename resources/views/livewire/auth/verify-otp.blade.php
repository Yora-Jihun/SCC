{{-- resources/views/livewire/auth/verify-otp.blade.php --}}
<div
    x-data="{
        expiresAt: @entangle('expiresAt'),
        resendIn: @entangle('resendAvailableIn'),
        remaining: 0,
        resendRemaining: 0,
        tick() {
            this.remaining = this.expiresAt ? Math.max(0, this.expiresAt - Math.floor(Date.now() / 1000)) : 0;
            this.resendRemaining = Math.max(0, this.resendRemaining - 1);
        },
        format(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }
    }"
    x-init="
        resendRemaining = resendIn;
        tick();
        setInterval(() => tick(), 1000);
        $watch('resendIn', value => resendRemaining = value);
    "
    class="max-w-md mx-auto mt-10 space-y-4"
>
    <h1 class="text-2xl font-bold">Verify your email</h1>

    @if ($verified)
        {{-- Chapter 2 has no Login page yet — this is a temporary stand-in.
             Chapter 3 replaces this whole block with a redirect to route('login'). --}}
        <div class="bg-green-50 border border-green-300 text-green-700 rounded p-4 text-sm">
            {{ $message }}
        </div>
    @else
        <p class="text-gray-600">Enter the 6-digit code we sent to your email.</p>

        <input type="text" wire:model="code" maxlength="6" placeholder="123456"
               class="w-full border rounded p-2 text-center text-2xl tracking-widest">

        {{-- Persistent ambient state: always visible, never disappears for a message --}}
        <p class="text-sm font-mono"
           x-text="remaining > 0 ? `Code expires in ${format(remaining)}` : 'This code has expired.'"
           x-bind:class="remaining > 0 ? 'text-gray-500' : 'text-red-500 font-medium'">
        </p>

        {{-- Action feedback: only for things the countdown line can't already tell you --}}
        @if ($message)
            <p class="text-sm {{ $messageType === 'success' ? 'text-green-600' : 'text-red-600' }}">
                {{ $message }}
            </p>
        @endif

        <button wire:click="verify" class="w-full bg-blue-600 text-white py-2 rounded">
            Verify
        </button>

        <button
            wire:click="resend"
            x-bind:disabled="resendRemaining > 0"
            x-bind:class="resendRemaining > 0 ? 'opacity-50 cursor-not-allowed' : ''"
            class="w-full border border-gray-300 py-2 rounded text-sm"
        >
            <span x-show="resendRemaining === 0">Resend code</span>
            <span x-show="resendRemaining > 0">Resend available in <span x-text="resendRemaining"></span>s</span>
        </button>
    @endif
</div>