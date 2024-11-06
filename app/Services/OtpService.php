<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class OtpService
{
    /**
     * Amount of characters for the OTP
     *
     * @var int $otpLenght
     */
    private int $otpLenght = 6;

    /**
     * Lifetime of the OTP in minutes
     *
     * @var int $lifetime
     */
    private int $lifetime = 10;

    /**
     * Generate OTP string
     *
     * @param User $user
     * @return string
     */
    public function generateOtp(User $user): string
    {
        $otp = Str::random($this->otpLenght);

        Cache::put('otp_' . $user->id, $otp, now()->addMinutes($this->lifetime));

        $user->notify(new OtpNotification($otp));

        return $otp;
    }

    /**
     * Verify the OTP
     *
     * @param User $user
     * @param string $otp
     * @return bool
     */
    public function verifyOtp(User $user, string $otp): bool
    {
        return Cache::get('otp_' . $user->id) == $otp;
    }

    /**
     * Reset user's otp
     *
     * @param User $user
     */
    public function forgetOtp(User $user): void
    {
        Cache::forget('otp_' . $user->id);
    }
}
