<?php
// app/Livewire/Auth/VerifyOtp.php

namespace App\Livewire\Auth;

use App\Enums\OtpPurpose;
use App\Enums\OtpVerificationResult;
use App\Services\Auth\AuthServiceContract;
use App\Services\OtpService;
use Livewire\Component;

class VerifyOtp extends Component
{
    public string $code = '';

    // A single message slot instead of separate error/status strings —
    // only one message is ever visible at a time.
    public string $message = '';
    public string $messageType = ''; // 'error' | 'success'
    public bool $verified = false; // temporary: Chapter 2 has no Login page yet

    public ?int $expiresAt = null;
    public int $resendAvailableIn = 0;

    public function mount(OtpService $otpService)
    {
        $email = session('otp_email');

        if (! $email) {
            return redirect()->route('register');
        }

        $this->refreshTimers($otpService, $email);
    }

    public function verify(AuthServiceContract $authService, OtpService $otpService)
    {
        $email = session('otp_email');

        if (! $email) {
            return redirect()->route('register');
        }

        $result = $authService->verifyRegistration($email, $this->code);

        if ($result === OtpVerificationResult::Success) {
            session()->forget('otp_email');

            // TEMPORARY for Chapter 2: there's no Login page yet, so we
            // just show a success alert on this same page instead of
            // redirecting. Chapter 3 replaces this with a real redirect
            // to route('login') once that page exists.
            $this->verified = true;
            $this->setMessage('Email verified! You can now log in once Chapter 3 adds the login page.', type: 'success');

            return;
        }

        $this->setMessage(match ($result) {
            OtpVerificationResult::InvalidCode => 'That code is incorrect. Please try again.',
            OtpVerificationResult::TooManyAttempts => 'Too many incorrect attempts. Request a new code below.',
            OtpVerificationResult::NotFound => 'We couldn\'t find a pending code. Request a new one below.',
            // No message for Expired — the countdown line already says
            // "This code has expired," so a second message would be redundant.
            OtpVerificationResult::Expired => '',
        }, type: 'error');

        $this->refreshTimers($otpService, $email);
    }

    public function resend(AuthServiceContract $authService, OtpService $otpService)
    {
        $email = session('otp_email');

        if (! $email) {
            return redirect()->route('register');
        }

        $sent = $authService->resendRegistrationOtp($email);

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
        $otp = $otpService->latest($email, OtpPurpose::Registration);

        $this->expiresAt = $otp?->expires_at?->timestamp;
        $this->resendAvailableIn = $otpService->secondsUntilResend($email, OtpPurpose::Registration);
    }

    public function render()
    {
        return view('livewire.auth.verify-otp');
    }
}