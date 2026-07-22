<?php

namespace App\Services\Auth;

use App\DTO\RegisterUserData;
use App\Enums\OtpPurpose;
use App\Enums\OtpVerificationResult;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Support\Facades\Hash;

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
}