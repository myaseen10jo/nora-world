<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h2 class="text-xl font-semibold text-stone-900">Disable Two-Factor Authentication</h2>
        <p class="text-sm text-stone-500 mt-2">Enter your password to confirm</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('2fa.disable') }}">
        @csrf
        @method('DELETE')

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-stone-700 mb-1">Password</label>
            <input type="password" id="password" name="password" required
                   class="block w-full rounded-lg border-stone-300 shadow-sm focus:border-stone-500 focus:ring-stone-500"
                   placeholder="Enter your password">
        </div>

        <div class="flex gap-3">
            <a href="{{ route('profile.edit') }}" class="flex-1 px-4 py-2.5 border border-stone-300 text-stone-700 text-sm font-medium rounded-xl hover:bg-stone-50 transition-all duration-200 text-center">
                Cancel
            </a>
            <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-all duration-200">
                Disable 2FA
            </button>
        </div>
    </form>
</x-guest-layout>
