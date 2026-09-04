@props(['error' => null])

@if(config('services.turnstile.site_key'))
<div class="mt-4">
    <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" data-theme="light" data-callback="turnstileCallback"></div>
    <input type="hidden" name="cf-turnstile-response" id="cf-turnstile-response" value="">
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<script>
    function turnstileCallback(token) {
        document.getElementById('cf-turnstile-response').value = token;
    }
</script>
@else
{{-- CAPTCHA not configured — hidden field for demo mode --}}
<input type="hidden" name="cf-turnstile-response" value="demo">
@endif
