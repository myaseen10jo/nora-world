<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ForceHttpsScheme
{
    public function handle(Request $request, Closure $next): mixed
    {
        // Force HTTPS scheme when behind Railway or any reverse proxy
        $isHttps = $request->header('x-forwarded-proto') === 'https'
            || $request->header('x-forwarded-ssl') === 'on'
            || $request->server('HTTPS') === 'on'
            || str_starts_with(config('app.url', ''), 'https://')
            || env('FORCE_HTTPS', false);

        if ($isHttps) {
            URL::forceScheme('https');
            
            // Force secure cookies
            config(['session.secure' => true]);
            config(['session.domain' => null]);
        }

        return $next($request);
    }
}
