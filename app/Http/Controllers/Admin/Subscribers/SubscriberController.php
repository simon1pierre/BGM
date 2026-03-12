<?php

namespace App\Http\Controllers\Admin\Subscribers;

use App\Http\Controllers\Controller;
use App\\Models\\Subscriber;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscriber::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
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

        $subscribers = $query->orderByDesc('subscribed_at')->paginate(20)->withQueryString();

        return view('Admin.Subscribers.index', compact('subscribers'));
    }

    public function toggle(Subscriber $subscriber)
    {
        $subscriber->is_active = !$subscriber->is_active;
        $subscriber->save();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'subscriber_status_toggled',
            'meta' => [
                'email' => $subscriber->email,
                'is_active' => $subscriber->is_active,
            ],
        ]);

        return redirect()->back()->with('status', 'Subscriber status updated.');
    }

    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'subscriber_deleted',
            'meta' => [
                'email' => $subscriber->email,
            ],
        ]);

        return redirect()->back()->with('status', 'Subscriber deleted.');
    }

    public function restore(int $subscriberId)
    {
        $subscriber = Subscriber::withTrashed()->findOrFail($subscriberId);
        $subscriber->restore();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'subscriber_restored',
            'meta' => [
                'email' => $subscriber->email,
            ],
        ]);

        return redirect()->back()->with('status', 'Subscriber restored.');
    }

    public function forceDelete(int $subscriberId)
    {
        $subscriber = Subscriber::withTrashed()->findOrFail($subscriberId);
        $email = $subscriber->email;
        $subscriber->forceDelete();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'subscriber_force_deleted',
            'meta' => [
                'email' => $email,
            ],
        ]);

        return redirect()->back()->with('status', 'Subscriber permanently deleted.');
    }
}


