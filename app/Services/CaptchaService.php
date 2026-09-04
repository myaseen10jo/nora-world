<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CaptchaService
{
    /**
     * Verify Cloudflare Turnstile CAPTCHA token.
     * If TURNSTILE_SECRET_KEY is not set, CAPTCHA is skipped (dev mode).
     */
    public function verify(?string $token, ?string $ip = null): bool
    {
        $secretKey = config('services.turnstile.secret_key');

        // If no secret key configured, skip verification (dev/demo mode)
        if (empty($secretKey)) {
            return true;
        }

        if (empty($token)) {
            return false;
        }

        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => $secretKey,
            'response' => $token,
            'remoteip' => $ip,
        ]);

        $result = $response->json();

        return $result['success'] ?? false;
    }
}
