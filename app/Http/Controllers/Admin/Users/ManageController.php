<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\UserActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ManageController extends Controller
{
    public function index(){
        return view('Admin.Users.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255', 'unique:users,user_name'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'user_name' => $validated['user_name'],
            'name' => trim($validated['first_name'].' '.$validated['last_name']),
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'admin',
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'avatar' => $avatarPath,
        ]);

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'target_user_id' => $user->id,
            'action' => 'user_created',
            'meta' => [
                'role' => $user->role,
                'is_active' => $user->is_active,
            ],
        ]);

        return redirect()->back()->with('status', 'Account created successfully.');
    }
}
