<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyDesignTheme
{
    public function handle(Request $request, Closure $next): Response
    {
        $theme = $request->cookie('nora_theme') ?? 'pro';
        $animation = $request->cookie('nora_animation') ?? 'reveal';

        // Validate theme
        $validThemes = ['modern', 'ancient', 'pro', 'minimal', 'luxury'];
        if (!in_array($theme, $validThemes)) {
            $theme = 'pro';
        }

        // Validate animation
        $validAnimations = ['reveal', 'slide', 'fade', 'parallax', 'typewriter'];
        if (!in_array($animation, $validAnimations)) {
            $animation = 'reveal';
        }

        view()->share('designTheme', $theme);
        view()->share('designAnimation', $animation);

        return $next($request);
    }
}
