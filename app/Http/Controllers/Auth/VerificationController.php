<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivityLog;
use App\Models\VerificationCode;
use App\Notifications\VerificationCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class VerificationController extends Controller
{
    public function show(Request $request)
    {
        return view('Auth.verify', [
            'email' => $request->string('email'),
        ]);
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'max:10'],
        ]);

        $verification = VerificationCode::query()
            ->where('email', $validated['email'])
            ->where('purpose', 'register')
            ->whereNull('used_at')
            ->where('expires_at', '>=', now())
            ->orderByDesc('id')
            ->first();

        if (!$verification || $verification->code !== $validated['code']) {
            return back()->withErrors(['code' => 'Invalid or expired code.'])->onlyInput('email');
        }

        $verification->used_at = now();
        $verification->save();

        $user = User::where('email', $validated['email'])->first();
        if ($user && !$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();

            UserActivityLog::create([
                'actor_user_id' => $user->id,
                'target_user_id' => $user->id,
                'action' => 'email_verified',
                'meta' => [
                    'email' => $user->email,
                ],
            ]);
        }

        return redirect()->route('admin.login')->with('status', 'Email verified. You can now log in.');
    }

    public function resend(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.']);
        }

        $verification = VerificationCode::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'code' => (string) random_int(100000, 999999),
            'purpose' => 'register',
            'expires_at' => now()->addMinutes(10),
        ]);

        Notification::route('mail', $user->email)->notify(new VerificationCodeNotification($verification));

        return back()->with('status', 'Verification code sent.');
    }
}








