<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h2 class="text-xl font-semibold text-stone-900">Two-Factor Authentication Enabled!</h2>
        <p class="text-sm text-stone-500 mt-2">Save these recovery codes in a safe place. Each code can only be used once.</p>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
        <div class="flex items-start gap-2">
            <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            <div>
                <p class="text-sm font-medium text-amber-800">Important</p>
                <p class="text-xs text-amber-700 mt-1">These codes will not be shown again. Copy them to a secure location now.</p>
            </div>
        </div>
    </div>

    <div class="bg-stone-50 rounded-lg p-4 mb-6">
        <div class="grid grid-cols-2 gap-2">
            @foreach($recoveryCodes as $code)
                <div class="bg-white rounded px-3 py-2 text-center border border-stone-200">
                    <code class="text-sm font-mono font-bold text-stone-800">{{ $code }}</code>
                </div>
            @endforeach
        </div>
    </div>

    <button onclick="copyRecoveryCodes()" class="w-full px-4 py-2.5 bg-stone-200 text-stone-700 text-sm font-medium rounded-xl hover:bg-stone-300 transition-all duration-200 mb-3">
        📋 Copy All Codes
    </button>

    <a href="{{ route('profile.edit') }}" class="block w-full px-4 py-2.5 bg-stone-900 text-white text-sm font-medium rounded-xl hover:bg-stone-800 transition-all duration-200 text-center">
        Done — Return to Settings
    </a>

    <script>
        function copyRecoveryCodes() {
            const codes = @json($recoveryCodes);
            navigator.clipboard.writeText(codes.join('\n')).then(() => {
                const btn = event.target;
                btn.textContent = '✅ Copied!';
                setTimeout(() => btn.textContent = '📋 Copy All Codes', 2000);
            });
        }
    </script>
</x-guest-layout>
