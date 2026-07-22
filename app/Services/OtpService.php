<?php
namespace App\Services;

use App\Enums\OtpPurpose;
use App\Enums\OtpVerificationResult;
use App\Models\Otp;
use App\Notifications\OtpCodeNotification;
use Illuminate\Support\Facades\Notification;

class OtpService
{
    public function generate(string $email, OtpPurpose $purpose): Otp
    {
        $code = (string) random_int(
            10 ** (config('otp.length') - 1),
            (10 ** config('otp.length')) - 1
        );

        $otp = Otp::create([
            'email' => $email,
            'code' => $code,
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(config('otp.expiry_minutes')),
        ]);

        Notification::route('mail', $email)
            ->notify(new OtpCodeNotification($code));

        return $otp;
    }

    public function verify(string $email, string $code, OtpPurpose $purpose): OtpVerificationResult
    {
        $otp = Otp::where('email', $email)
            ->where('purpose', $purpose)
            ->latest()
            ->first();

        if (! $otp) {
            return OtpVerificationResult::NotFound;
        }

        if ($otp->isExpired()) {
            return OtpVerificationResult::Expired;
        }

        if ($otp->attempts >= config('otp.max_attempts')) {
            return OtpVerificationResult::TooManyAttempts;
        }

        if ($otp->code !== $code) {
            $otp->increment('attempts');
            return OtpVerificationResult::InvalidCode;
        }

        $otp->delete();
        return OtpVerificationResult::Success;
    }

    /**
     * The most recent OTP for this email/purpose, if any.
     * The verification page uses this to know when the code expires.
     */
    public function latest(string $email, OtpPurpose $purpose): ?Otp
    {
        return Otp::where('email', $email)
            ->where('purpose', $purpose)
            ->latest()
            ->first();
    }

    public function canResend(string $email, OtpPurpose $purpose): bool
    {
        $latest = $this->latest($email, $purpose);

        if (! $latest) {
            return true;
        }

        return $latest->created_at
            ->addSeconds(config('otp.resend_throttle_seconds'))
            ->isPast();
    }

    /**
     * Seconds remaining before a resend is allowed. 0 means resend is allowed now.
     */
    public function secondsUntilResend(string $email, OtpPurpose $purpose): int
    {
        $latest = $this->latest($email, $purpose);

        if (! $latest) {
            return 0;
        }

        $availableAt = $latest->created_at->addSeconds(config('otp.resend_throttle_seconds'));

        return $availableAt->isPast() ? 0 : now()->diffInSeconds($availableAt);
    }
}