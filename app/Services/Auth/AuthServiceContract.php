<?php

namespace App\Services\Auth;

use App\DTO\RegisterUserData;
use App\Enums\OtpVerificationResult;
use App\Models\User;

interface AuthServiceContract
{
    public function register(RegisterUserData $data): User;
    public function verifyRegistration(string $email, string $code): OtpVerificationResult;
    public function resendRegistrationOtp(string $email): bool;

    public function attemptLogin(string $email, string $password, bool $remember = false): bool;
    public function resendPasswordResetOtp(string $email): bool;
    public function resetPassword(string $email, string $code, string $newPassword): OtpVerificationResult;

}