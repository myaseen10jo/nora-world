<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TwoFactorService
{
    /**
     * Generate a new TOTP secret for a user.
     */
    public function generateSecret(): string
    {
        return strtoupper(Str::random(32));
    }

    /**
     * Get the TOTP provisioning URI for QR code generation.
     */
    public function getProvisioningUri(User $user, string $secret): string
    {
        $appName = config('app.name', 'NORA WORLD');
        $encodedApp = rawurlencode($appName);
        $encodedUser = rawurlencode($user->email);

        return "otpauth://totp/{$encodedApp}:{$encodedUser}?secret={$secret}&issuer={$encodedApp}&algorithm=SHA1&digits=6&period=30";
    }

    /**
     * Verify a TOTP code against the user's secret.
     */
    public function verifyCode(string $secret, string $code): bool
    {
        // Allow current and ±1 time step (30 seconds each) for clock drift
        $time = floor(time() / 30);

        for ($i = -1; $i <= 1; $i++) {
            $calculated = $this->generateCode($secret, $time + $i);
            if (hash_equals($calculated, $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate recovery codes for the user.
     */
    public function generateRecoveryCodes(): Collection
    {
        $codes = collect();
        for ($i = 0; $i < 10; $i++) {
            $codes->push(Str::upper(Str::random(4) . '-' . Str::random(4)));
        }
        return $codes;
    }

    /**
     * Hash a recovery code for storage.
     */
    public function hashRecoveryCode(string $code): string
    {
        return hash('sha256', strtoupper(str_replace('-', '', $code)));
    }

    /**
     * Check if a recovery code is valid and consume it.
     */
    public function useRecoveryCode(User $user, string $code): bool
    {
        $hashed = $this->hashRecoveryCode($code);
        $codes = $user->two_factor_recovery_codes ?? [];

        $index = array_search($hashed, $codes);
        if ($index !== false) {
            unset($codes[$index]);
            $user->update(['two_factor_recovery_codes' => array_values($codes)]);
            return true;
        }

        return false;
    }

    /**
     * Generate a TOTP code from a secret (RFC 6238).
     */
    private function generateCode(string $secret, int $time): string
    {
        $key = pack('H*', $this->base32Decode($secret));
        $timeBytes = pack('N*', 0) . pack('N*', $time);

        $hmac = hash_hmac('sha1', $timeBytes, $key, true);
        $offset = ord($hmac[19]) & 0x0F;
        $hash = (ord($hmac[$offset]) & 0x7F) << 24
            | (ord($hmac[$offset + 1]) & 0xFF) << 16
            | (ord($hmac[$offset + 2]) & 0xFF) << 8
            | (ord($hmac[$offset + 3]) & 0xFF);

        $code = $hash % 1000000;
        return str_pad((string) $code, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Decode base32 string to hex.
     */
    private function base32Decode(string $input): string
    {
        $map = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $input = strtoupper(trim($input, '='));
        $binaryString = '';

        for ($i = 0; $i < strlen($input); $i++) {
            $val = strpos($map, $input[$i]);
            if ($val === false) continue;
            $binaryString .= str_pad(decbin($val), 5, '0', STR_PAD_LEFT);
        }

        $hex = '';
        for ($i = 0; $i + 8 <= strlen($binaryString); $i += 8) {
            $hex .= sprintf('%02x', bindec(substr($binaryString, $i, 8)));
        }

        return $hex;
    }
}
