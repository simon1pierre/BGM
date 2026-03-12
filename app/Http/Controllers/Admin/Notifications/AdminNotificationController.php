<?php

namespace App\Http\Controllers\Admin\Notifications;

use App\Http\Controllers\Controller;
use App\Models\AdminNotificationRead;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    public function readAll(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->back();
        }

        $inAppActions = [
            'user_created',
            'user_updated',
            'user_status_toggled',
            'user_deleted',
            'user_restored',
            'password_reset',
            'subscriber_created',
        ];

        $activityIds = UserActivityLog::query()
            ->whereIn('action', $inAppActions)
            ->orderByDesc('id')
            ->pluck('id');

        $now = now();
        $rows = [];
        foreach ($activityIds as $activityId) {
            $rows[] = [
                'user_id' => $userId,
                'activity_log_id' => $activityId,
                'read_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($rows)) {
            AdminNotificationRead::upsert(
                $rows,
                ['user_id', 'activity_log_id'],
                ['read_at', 'updated_at']
            );
        }

        return redirect()->back()->with('status', 'Notifications marked as read.');
    }

    public function show(UserActivityLog $notification, Request $request)
    {
        $userId = Auth::id();
        if ($userId) {
            AdminNotificationRead::updateOrCreate(
                [
                    'user_id' => $userId,
                    'activity_log_id' => $notification->id,
                ],
                [
                    'read_at' => now(),
                ]
            );
        }

        return view('Admin.Notifications.show', [
            'notification' => $notification,
        ]);
    }
}








