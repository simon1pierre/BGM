<?php

namespace App\Http\Middleware;

use App\Models\UserActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Schema::hasTable('user_activity_logs')) {
            UserActivityLog::create([
                'actor_user_id' => Auth::id(),
                'action' => 'request',
                'meta' => [
                    'method' => $request->method(),
                    'path' => $request->path(),
                    'route' => optional($request->route())->getName(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
            ]);
        }

        return $response;
    }
}
