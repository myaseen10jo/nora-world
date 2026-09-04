<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class TwoFactorController extends Controller
{
    public function __construct(
        protected TwoFactorService $twoFactorService
    ) {}

    /**
     * Show 2FA setup page.
     */
    public function showSetup(Request $request)
    {
        $user = $request->user();

        if ($user->two_factor_enabled) {
            return redirect()->route('2fa.showDisable')
                ->with('status', 'Two-factor authentication is already enabled.');
        }

        $secret = $this->twoFactorService->generateSecret();
        $provisioningUri = $this->twoFactorService->getProvisioningUri($user, $secret);

        // Store encrypted secret temporarily until verified
        session(['2fa_pending_secret' => Crypt::encryptString($secret)]);

        // Generate QR code as SVG data URL
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . rawurlencode($provisioningUri);

        return view('auth.2fa-setup', [
            'secret' => $secret,
            'qrCodeUrl' => $qrCodeUrl,
            'provisioningUri' => $provisioningUri,
        ]);
    }

    /**
     * Verify and enable 2FA.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();
        $encryptedSecret = session('2fa_pending_secret');

        if (!$encryptedSecret) {
            return redirect()->route('2fa.showSetup')
                ->withErrors(['code' => 'Setup session expired. Please start over.']);
        }

        $secret = Crypt::decryptString($encryptedSecret);

        if (!$this->twoFactorService->verifyCode($secret, $request->code)) {
            return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
        }

        // Generate recovery codes
        $recoveryCodes = $this->twoFactorService->generateRecoveryCodes();
        $hashedCodes = $recoveryCodes->map(fn ($code) => $this->twoFactorService->hashRecoveryCode($code))->toArray();

        // Enable 2FA
        $user->update([
            'two_factor_enabled' => true,
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => $hashedCodes,
            'two_factor_verified_at' => now(),
        ]);

        session()->forget('2fa_pending_secret');

        return view('auth.2fa-recovery-codes', [
            'recoveryCodes' => $recoveryCodes,
        ]);
    }

    /**
     * Show 2FA verification form (during login).
     */
    public function showVerify()
    {
        if (!session('2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.2fa-verify');
    }

    /**
     * Verify 2FA code during login.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $userId = session('2fa_user_id');
        $user = \App\Models\User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        $code = strtoupper(trim($request->code));

        // Check if it's a recovery code
        if (strlen($code) === 9 && str_contains($code, '-')) {
            if ($this->twoFactorService->useRecoveryCode($user, $code)) {
                return $this->completeLogin($user, $request);
            }
            return back()->withErrors(['code' => 'Invalid recovery code.']);
        }

        // Check TOTP code
        if ($this->twoFactorService->verifyCode($user->two_factor_secret, $code)) {
            return $this->completeLogin($user, $request);
        }

        return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
    }

    /**
     * Complete the login after 2FA verification.
     */
    private function completeLogin($user, Request $request)
    {
        Auth::login($user, true);
        $request->session()->regenerate();
        session()->forget('2fa_user_id');

        if ($user->is_admin) {
            return redirect('/nora-backoffice-2024');
        }

        return redirect()->intended(route('home'));
    }

    /**
     * Show disable 2FA form.
     */
    public function showDisable(Request $request)
    {
        return view('auth.2fa-disable');
    }

    /**
     * Disable 2FA.
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        $user->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_verified_at' => null,
        ]);

        return redirect()->route('profile.edit')
            ->with('status', 'Two-factor authentication has been disabled.');
    }
}
