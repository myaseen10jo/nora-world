<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect the user to Google's OAuth consent screen.
     */
    public function redirect(): RedirectResponse
    {
        $query = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'offline',
            'prompt' => 'select_account',
        ]);

        return redirect("https://accounts.google.com/o/oauth2/v2/auth?{$query}");
    }

    /**
     * Handle the Google OAuth callback.
     */
    public function callback(): RedirectResponse
    {
        // Check for errors from Google
        if (request()->has('error')) {
            return redirect()->route('login')
                ->withErrors(['google' => 'Google login was cancelled or failed.']);
        }

        // Validate we have a code
        $code = request()->input('code');
        if (!$code) {
            return redirect()->route('login')
                ->withErrors(['google' => 'Invalid Google response.']);
        }

        // Exchange authorization code for tokens
        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.redirect'),
            'grant_type' => 'authorization_code',
        ]);

        if ($tokenResponse->failed()) {
            return redirect()->route('login')
                ->withErrors(['google' => 'Failed to authenticate with Google. Please try again.']);
        }

        $accessToken = $tokenResponse->json('access_token');

        // Fetch user info from Google
        $userResponse = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/oauth2/v2/userinfo');

        if ($userResponse->failed()) {
            return redirect()->route('login')
                ->withErrors(['google' => 'Failed to fetch your Google profile.']);
        }

        $googleUser = $userResponse->json();

        // Find or create the user
        $user = User::where('social_provider', 'google')
            ->where('social_id', $googleUser['id'])
            ->first();

        if (!$user) {
            // Check if a user with this email already exists
            $user = User::where('email', $googleUser['email'])->first();

            if ($user) {
                // Link the existing account to Google
                $user->update([
                    'social_provider' => 'google',
                    'social_id' => $googleUser['id'],
                    'avatar' => $googleUser['picture'] ?? null,
                    'email_verified_at' => now(),
                ]);
            } else {
                // Create a new user
                $user = User::create([
                    'name' => $googleUser['name'],
                    'email' => $googleUser['email'],
                    'social_provider' => 'google',
                    'social_id' => $googleUser['id'],
                    'avatar' => $googleUser['picture'] ?? null,
                    'email_verified_at' => now(),
                    'password' => null, // No password for social users
                ]);
            }
        } else {
            // Update avatar if it changed
            if (isset($googleUser['picture']) && $user->avatar !== $googleUser['picture']) {
                $user->update(['avatar' => $googleUser['picture']]);
            }
        }

        // Log the user in
        Auth::login($user, true);

        // Redirect based on admin status
        if ($user->is_admin) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended(route('home'));
    }
}
