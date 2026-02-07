<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserLoginLog;

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
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'This account is inactive. Contact an administrator.'])
                ->onlyInput('email');
        }

        $user = Auth::user();
        if ($user instanceof User) {
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
        }

        return redirect()->route('admin.dashboard');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
