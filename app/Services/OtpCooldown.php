<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OtpCooldown
{
    private static function key(string $email): string
    {
        return 'otp:cooldown:' . hash('sha256', strtolower(trim($email)));
    }

    public static function claim(string $email): void
    {
        // Atomic add prevents concurrent requests from both generating a code.
        if (!Cache::add(self::key($email), now()->timestamp + 60, 60)) {
            $seconds = max(1, self::remaining($email));
            Log::info('[OTP EMAIL] cooldown_blocked', [
                'email' => strtolower(trim($email)),
                'retry_after_seconds' => $seconds,
            ]);
            throw ValidationException::withMessages([
                'email' => "Please wait {$seconds} seconds before requesting another OTP. Use the most recent code sent to your email.",
            ]);
        }
    }

    public static function remaining(?string $email): int
    {
        return $email ? max(0, (int) Cache::get(self::key($email), 0) - now()->timestamp) : 0;
    }
}
