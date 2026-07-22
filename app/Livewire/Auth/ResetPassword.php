<?php
// app/Livewire/Auth/ResetPassword.php

namespace App\Livewire\Auth;

use App\Enums\OtpPurpose;
use App\Enums\OtpVerificationResult;
use App\Services\Auth\AuthServiceContract;
use App\Services\OtpService;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ResetPassword extends Component
{
    public string $code = '';

    #[Rule('required|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public string $message = '';
    public string $messageType = '';

    public ?int $expiresAt = null;
    public int $resendAvailableIn = 0;

    public function mount(OtpService $otpService)
    {
        $email = session('reset_email');

        if (! $email) {
            return redirect()->route('forgot-password');
        }

        $this->refreshTimers($otpService, $email);
    }

    public function resetPassword(AuthServiceContract $authService, OtpService $otpService)
    {
        $email = session('reset_email');

        if (! $email) {
            return redirect()->route('forgot-password');
        }

        $this->validate();

        $result = $authService->resetPassword($email, $this->code, $this->password);

        if ($result === OtpVerificationResult::Success) {
            session()->forget('reset_email');

            return redirect()->route('login')->with('status', 'Password reset! You can now log in.');
        }

        $this->setMessage(match ($result) {
            OtpVerificationResult::InvalidCode => 'That code is incorrect. Please try again.',
            OtpVerificationResult::TooManyAttempts => 'Too many incorrect attempts. Request a new code below.',
            OtpVerificationResult::NotFound => 'We couldn\'t find a pending code. Request a new one below.',
            OtpVerificationResult::Expired => '', // countdown line already communicates this
        }, type: 'error');

        $this->refreshTimers($otpService, $email);
    }

    public function resend(AuthServiceContract $authService, OtpService $otpService)
    {
        $email = session('reset_email');

        if (! $email) {
            return redirect()->route('forgot-password');
        }

        $sent = $authService->resendPasswordResetOtp($email);

        $this->setMessage(
            $sent ? 'A new code has been sent to your email.' : 'Please wait before requesting another code.',
            type: $sent ? 'success' : 'error'
        );

        $this->code = '';
        $this->refreshTimers($otpService, $email);
    }

    protected function setMessage(string $text, string $type): void
    {
        $this->message = $text;
        $this->messageType = $type;
    }

    protected function refreshTimers(OtpService $otpService, string $email): void
    {
        $otp = $otpService->latest($email, OtpPurpose::PasswordReset);

        $this->expiresAt = $otp?->expires_at?->timestamp;
        $this->resendAvailableIn = $otpService->secondsUntilResend($email, OtpPurpose::PasswordReset);
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}