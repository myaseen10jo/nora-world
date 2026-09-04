<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-12 h-12 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h2 class="text-xl font-semibold text-stone-900">Two-Factor Authentication</h2>
        <p class="text-sm text-stone-500 mt-2">Enter the 6-digit code from your authenticator app</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('2fa.verify') }}">
        @csrf

        <div class="mb-4">
            <input type="text" id="code" name="code" maxlength="9"
                   class="block w-full rounded-lg border-stone-300 shadow-sm focus:border-stone-500 focus:ring-stone-500 text-center text-lg font-mono tracking-[0.5em] py-3"
                   placeholder="000000" required autofocus autocomplete="one-time-code"
                   inputmode="numeric">
        </div>

        <button type="submit" class="w-full px-4 py-2.5 bg-stone-900 text-white text-sm font-medium rounded-xl hover:bg-stone-800 transition-all duration-200">
            Verify & Log In
        </button>
    </form>

    <div class="mt-4 text-center">
        <p class="text-xs text-stone-400">
            Lost your device? Enter a recovery code (format: XXXX-XXXX)
        </p>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="text-sm text-stone-500 hover:text-stone-800 transition-colors">
            ← Back to login
        </a>
    </div>
</x-guest-layout>
