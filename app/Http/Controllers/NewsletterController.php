<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
        ]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'is_active' => true,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]
        );

        return back()->with('success', 'Thank you for subscribing to our newsletter!');
    }

    public function unsubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $subscriber = NewsletterSubscriber::where('email', $request->email)->first();

        if ($subscriber) {
            $subscriber->update([
                'is_active' => false,
                'unsubscribed_at' => now(),
            ]);
        }

        return back()->with('success', 'You have been unsubscribed from our newsletter.');
    }
}
