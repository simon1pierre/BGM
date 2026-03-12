<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivityLog;
use App\Models\UserLoginLog;
use App\Models\VerificationCode;
use App\Notifications\VerificationCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class TwoFactorController extends Controller
{
    public function show(Request $request)
    {
        return view('Admin.Auth.verify-login', [
            'email' => $request->session()->get('pending_2fa_email'),
        ]);
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:10'],
        ]);

        $userId = $request->session()->get('pending_2fa_user_id');
        $email = $request->session()->get('pending_2fa_email');

        if (!$userId || !$email) {
            return redirect()->route('admin.login')->withErrors(['email' => 'Session expired. Please log in again.']);
        }

        $verification = VerificationCode::query()
            ->where('user_id', $userId)
            ->where('email', $email)
            ->where('purpose', 'login')
            ->whereNull('used_at')
            ->where('expires_at', '>=', now())
            ->orderByDesc('id')
            ->first();

        if (!$verification || $verification->code !== $validated['code']) {
            return back()->withErrors(['code' => 'Invalid or expired code.']);
        }

        $verification->used_at = now();
        $verification->save();

        Auth::loginUsingId($userId);
        $request->session()->forget(['pending_2fa_user_id', 'pending_2fa_email']);

        $user = User::find($userId);
        if ($user) {
            $user->last_login_at = now();
            $user->save();

            UserLoginLog::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'success' => true,
                'logged_in_at' => now(),
            ]);

            UserActivityLog::create([
                'actor_user_id' => $user->id,
                'action' => 'login_success',
                'meta' => [
                    'email' => $user->email,
                    'ip' => $request->ip(),
                ],
            ]);
        }

        return redirect()->route('admin.dashboard');
    }

    public function resend(Request $request)
    {
        $userId = $request->session()->get('pending_2fa_user_id');
        $email = $request->session()->get('pending_2fa_email');

        if (!$userId || !$email) {
            return redirect()->route('admin.login')->withErrors(['email' => 'Session expired. Please log in again.']);
        }

        $verification = VerificationCode::create([
            'user_id' => $userId,
            'email' => $email,
            'code' => (string) random_int(100000, 999999),
            'purpose' => 'login',
            'expires_at' => now()->addMinutes(10),
        ]);

        Notification::route('mail', $email)->notify(new VerificationCodeNotification($verification));

        return back()->with('status', 'Verification code resent.');
    }
}


