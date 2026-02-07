<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivityLog;
use App\Models\UserLoginLog;
use App\Models\UserStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->string('role'));
        }

        if ($request->filled('status')) {
            if ($request->string('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->string('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('deleted')) {
            if ($request->string('deleted') === 'only') {
                $query->onlyTrashed();
            } elseif ($request->string('deleted') === 'with') {
                $query->withTrashed();
            }
        }

        $users = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('Admin.Users.index', compact('users'));
    }

    public function show(User $user)
    {
        $loginLogs = UserLoginLog::where('user_id', $user->id)
            ->orderByDesc('logged_in_at')
            ->limit(10)
            ->get();

        $statusLogs = UserStatusLog::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $activityLogs = UserActivityLog::where('target_user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('Admin.Users.show', compact('user', 'loginLogs', 'statusLogs', 'activityLogs'));
    }

    public function edit(User $user)
    {
        return view('Admin.Users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255', 'unique:users,user_name,'.$user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'email_verified' => ['nullable', 'boolean'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        } else {
            unset($validated['avatar']);
        }

        $validated['name'] = trim($validated['first_name'].' '.$validated['last_name']);
        $validated['is_active'] = (bool) ($validated['is_active'] ?? false);
        $validated['email_verified_at'] = ($validated['email_verified'] ?? false) ? now() : null;
        unset($validated['email_verified']);

        $oldStatus = $user->is_active;
        $user->update($validated);

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'target_user_id' => $user->id,
            'action' => 'user_updated',
            'meta' => [
                'role' => $user->role,
                'is_active' => $user->is_active,
            ],
        ]);

        if ($oldStatus !== $user->is_active) {
            UserStatusLog::create([
                'user_id' => $user->id,
                'changed_by' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $user->is_active,
                'reason' => 'updated_via_admin',
            ]);
        }

        return redirect()->route('admin.users.edit', $user)->with('status', 'User updated.');
    }

    public function toggleStatus(Request $request, User $user)
    {
        $oldStatus = $user->is_active;
        $user->is_active = !$user->is_active;
        $user->save();

        UserStatusLog::create([
            'user_id' => $user->id,
            'changed_by' => Auth::id(),
            'old_status' => $oldStatus,
            'new_status' => $user->is_active,
            'reason' => $request->string('reason') ?: 'toggled_via_admin',
        ]);

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'target_user_id' => $user->id,
            'action' => 'user_status_toggled',
            'meta' => [
                'old_status' => $oldStatus,
                'new_status' => $user->is_active,
            ],
        ]);

        return redirect()->back()->with('status', 'User status updated.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'target_user_id' => $user->id,
            'action' => 'password_reset',
        ]);

        return redirect()->back()->with('status', 'Password reset.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'target_user_id' => $user->id,
            'action' => 'user_deleted',
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User deleted.');
    }

    public function forceLogout(User $user)
    {
        DB::table('sessions')->where('user_id', $user->id)->delete();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'target_user_id' => $user->id,
            'action' => 'user_forced_logout',
        ]);

        return redirect()->back()->with('status', 'User sessions cleared.');
    }

    public function restore(int $userId)
    {
        $user = User::withTrashed()->findOrFail($userId);
        $user->restore();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'target_user_id' => $user->id,
            'action' => 'user_restored',
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User restored.');
    }
}
