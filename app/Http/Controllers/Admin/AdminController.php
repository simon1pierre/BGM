<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\audio;
use App\Models\book;
use App\Models\subscriber;
use App\Models\video;
use App\Models\UserActivityLog;

class AdminController extends Controller
{
    public function index(){
        $videoCount = video::query()->count();
        $audioCount = audio::query()->count();
        $documentCount = book::query()->count();
        $subscriberCount = subscriber::query()->count();
        $totalDownloads = audio::query()->sum('download_count') + book::query()->sum('download_count');

        $latestVideos = video::query()->latest()->limit(5)->get();
        $latestAudios = audio::query()->latest()->limit(5)->get();
        $latestDocuments = book::query()->latest()->limit(5)->get();
        $recentActivity = UserActivityLog::query()
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('Admin.dashboard', compact(
            'videoCount',
            'audioCount',
            'documentCount',
            'subscriberCount',
            'totalDownloads',
            'latestVideos',
            'latestAudios',
            'latestDocuments',
            'recentActivity'
        ));
    }
}
