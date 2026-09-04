<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-xl font-semibold text-stone-900">Set Up Two-Factor Authentication</h2>
        <p class="text-sm text-stone-500 mt-2">Scan this QR code with your authenticator app (Google Authenticator, Authy, etc.)</p>
    </div>

    {{-- QR Code --}}
    <div class="flex justify-center mb-6">
        <div class="bg-white p-4 rounded-xl border border-stone-200 shadow-sm">
            <img src="{{ $qrCodeUrl }}" alt="QR Code" class="w-48 h-48">
        </div>
    </div>

    {{-- Manual Entry --}}
    <div class="mb-6">
        <p class="text-xs text-stone-500 text-center mb-2">Or enter this code manually:</p>
        <div class="bg-stone-100 rounded-lg p-3 text-center">
            <code class="text-sm font-mono font-bold text-stone-800 tracking-wider select-all">{{ chunk_split($secret, 4, ' ') }}</code>
        </div>
    </div>

    {{-- Verification Form --}}
    <form method="POST" action="{{ route('2fa.enable') }}">
        @csrf

        <div class="mb-4">
            <label for="code" class="block text-sm font-medium text-stone-700 mb-1">Enter verification code</label>
            <input type="text" id="code" name="code" maxlength="6" pattern="[0-9]{6}"
                   class="block w-full rounded-lg border-stone-300 shadow-sm focus:border-stone-500 focus:ring-stone-500 text-center text-lg font-mono tracking-[0.5em]"
                   placeholder="000000" required autofocus autocomplete="one-time-code">
            @error('code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full px-4 py-2.5 bg-stone-900 text-white text-sm font-medium rounded-xl hover:bg-stone-800 transition-all duration-200">
            Enable Two-Factor Authentication
        </button>
    </form>

    <div class="mt-4 text-center">
        <a href="{{ route('profile.edit') }}" class="text-sm text-stone-500 hover:text-stone-800 transition-colors">
            ← Back to settings
        </a>
    </div>
</x-guest-layout>
