<?php
// app/Services/Auth/AuthService.php

namespace App\Services\Auth;

use App\DTO\RegisterUserData;
use App\Enums\OtpPurpose;
use App\Enums\OtpVerificationResult;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceContract
{
    public function __construct(protected OtpService $otpService) {}

    public function register(RegisterUserData $data): User
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);

        $this->otpService->generate($user->email, OtpPurpose::Registration);

        return $user;
    }

    public function verifyRegistration(string $email, string $code): OtpVerificationResult
    {
        $result = $this->otpService->verify($email, $code, OtpPurpose::Registration);

        if ($result === OtpVerificationResult::Success) {
            User::where('email', $email)->update(['email_verified_at' => now()]);
        }

        return $result;
    }

    public function resendRegistrationOtp(string $email): bool
    {
        if (! $this->otpService->canResend($email, OtpPurpose::Registration)) {
            return false;
        }

        $this->otpService->generate($email, OtpPurpose::Registration);

        return true;
    }

    public function attemptLogin(string $email, string $password, bool $remember = false): bool
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        if (! $user->email_verified_at) {
            throw ValidationException::withMessages([
                'email' => 'Please verify your email before logging in.',
            ]);
        }

        return Auth::attempt(['email' => $email, 'password' => $password], $remember);
    }

    public function sendPasswordResetOtp(string $email): bool
    {
        // Always report success to the caller, even if the email doesn't
        // exist — this prevents leaking which emails are registered.
        if (User::where('email', $email)->exists()) {
            $this->otpService->generate($email, OtpPurpose::PasswordReset);
        }

        return true;
    }

    public function resendPasswordResetOtp(string $email): bool
    {
        if (! $this->otpService->canResend($email, OtpPurpose::PasswordReset)) {
            return false;
        }

        $this->otpService->generate($email, OtpPurpose::PasswordReset);

        return true;
    }

    public function resetPassword(string $email, string $code, string $newPassword): OtpVerificationResult
    {
        $result = $this->otpService->verify($email, $code, OtpPurpose::PasswordReset);

        if ($result === OtpVerificationResult::Success) {
            User::where('email', $email)->update([
                'password' => Hash::make($newPassword),
            ]);
        }

        return $result;
    }
}