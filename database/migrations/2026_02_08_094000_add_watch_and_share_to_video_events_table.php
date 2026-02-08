<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('video_events', function (Blueprint $table) {
            $table->unsignedInteger('watch_seconds')->nullable()->after('device_hash');
            $table->string('share_channel', 30)->nullable()->after('watch_seconds');
            $table->index(['video_id', 'event_type', 'watch_seconds']);
        });
    }

    public function down(): void
    {
        Schema::table('video_events', function (Blueprint $table) {
            $table->dropIndex(['video_id', 'event_type', 'watch_seconds']);
            $table->dropColumn(['watch_seconds', 'share_channel']);
        });
    }
};
