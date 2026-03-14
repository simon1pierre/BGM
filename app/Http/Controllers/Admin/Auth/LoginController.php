<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserLoginLog;
use App\Models\UserActivityLog;
use App\Models\VerificationCode;
use App\Notifications\VerificationCodeNotification;
use Illuminate\Support\Facades\Notification;
use Throwable;

class LoginController extends Controller
{
    public function create()
    {
        return view('Admin.Auth.login');
    }
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
        $remember = $request->boolean('remember');
        if (!Auth::attempt($credentials, $remember)) {
            UserLoginLog::create([
                'user_id' => null,
                'email' => $credentials['email'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'success' => false,
                'logged_in_at' => now(),
            ]);
            UserActivityLog::create([
                'actor_user_id' => null,
                'action' => 'login_failed',
                'meta' => [
                    'email' => $credentials['email'],
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
            ]);
            return back()
                ->withErrors(['email' => 'Invalid email or password.'])
                ->onlyInput('email');
        }
        $request->session()->regenerate();
        if (Auth::user() && !Auth::user()->is_active) {
            UserLoginLog::create([
                'user_id' => Auth::id(),
                'email' => $credentials['email'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'success' => false,
                'logged_in_at' => now(),
            ]);
            UserActivityLog::create([
                'actor_user_id' => Auth::id(),
                'action' => 'security_issue',
                'meta' => [
                    'reason' => 'inactive_account_login_attempt',
                    'email' => $credentials['email'],
                    'ip' => $request->ip(),
                ],
            ]);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'This account is inactive. Contact an administrator.'])
                ->onlyInput('email');
        }

        $user = Auth::user();
        if ($user instanceof User) {
            if (!$user->email_verified_at) {
                $verification = VerificationCode::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'code' => (string) random_int(100000, 999999),
                    'purpose' => 'register',
                    'expires_at' => now()->addMinutes(10),
                ]);
                try {
                    Notification::route('mail', $user->email)->notify(new VerificationCodeNotification($verification));
                } catch (Throwable $e) {
                    report($e);
                    $verification->delete();

                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('admin.login')->with(
                        'error',
                        'Unable to send verification email right now. Please try again later or contact support.'
                    );
                }

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('verify.show', ['email' => $user->email])
                    ->with('status', 'Please verify your email before logging in.');
            }

            $verification = VerificationCode::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'code' => (string) random_int(100000, 999999),
                'purpose' => 'login',
                'expires_at' => now()->addMinutes(10),
            ]);
            try {
                Notification::route('mail', $user->email)->notify(new VerificationCodeNotification($verification));
            } catch (Throwable $e) {
                report($e);
                $verification->delete();

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('admin.login')->with(
                    'error',
                    'Unable to send verification email right now. Please try again later or contact support.'
                );
            }

            Auth::logout();
            $request->session()->put('pending_2fa_user_id', $user->id);
            $request->session()->put('pending_2fa_email', $user->email);

            return redirect()->route('admin.login.verify')
                ->with('status', 'A verification code has been sent to your email.');
        }

        return redirect()->route('admin.login');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}








