<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('home');
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $subscriber = Subscriber::updateOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'] ?? null,
                'is_active' => true,
                'subscribed_at' => now(),
            ]
        );

        UserActivityLog::create([
            'actor_user_id' => null,
            'action' => 'subscriber_created',
            'meta' => [
                'email' => $subscriber->email,
                'name' => $subscriber->name,
                'ip' => $request->ip(),
            ],
        ]);

        return redirect()->back()->with('status', 'Thank you for subscribing.');
    }
}
