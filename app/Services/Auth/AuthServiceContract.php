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
}