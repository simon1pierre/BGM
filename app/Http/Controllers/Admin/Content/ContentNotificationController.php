<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Jobs\SendContentNotificationJob;
use App\Models\ContentNotification;
use Illuminate\Http\Request;

class ContentNotificationController extends Controller
{
    public function index()
    {
        $notifications = ContentNotification::query()
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('Admin.Content.Notifications.index', compact('notifications'));
    }

    public function resend(Request $request, ContentNotification $notification)
    {
        $payload = $notification->payload ?? [];
        $target = $notification->target_type ?? 'all';
        $emails = $notification->target_emails ?? [];

        if ($target === 'custom' && count($emails) === 0) {
            return back()->withErrors(['notify_emails' => 'This notification has no custom emails saved.']);
        }

        SendContentNotificationJob::dispatchSync($payload, $emails, $target !== 'custom');

        $notification->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return back()->with('status', 'Notification resent.');
    }
}








