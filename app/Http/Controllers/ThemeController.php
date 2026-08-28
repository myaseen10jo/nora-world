<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ThemeController extends Controller
{
    public function switch(Request $request): JsonResponse|RedirectResponse
    {
        $theme = $request->input('theme', 'pro');
        $animation = $request->input('animation', 'reveal');

        $validThemes = ['modern', 'ancient', 'pro', 'minimal', 'luxury'];
        $validAnimations = ['reveal', 'slide', 'fade', 'parallax', 'typewriter'];

        if (!in_array($theme, $validThemes)) {
            $theme = 'pro';
        }
        if (!in_array($animation, $validAnimations)) {
            $animation = 'reveal';
        }

        $response = $request->expectsJson()
            ? response()->json(['theme' => $theme, 'animation' => $animation])
            : redirect()->back();

        $response->withCookie(cookie('nora_theme', $theme, 60 * 24 * 365));
        $response->withCookie(cookie('nora_animation', $animation, 60 * 24 * 365));

        return $response;
    }
}
