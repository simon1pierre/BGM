<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('video_events', function (Blueprint $table) {
            $table->string('device_hash', 64)->nullable()->after('platform');
            $table->index(['video_id', 'event_type', 'device_hash']);
        });
    }

    public function down(): void
    {
        Schema::table('video_events', function (Blueprint $table) {
            $table->dropIndex(['video_id', 'event_type', 'device_hash']);
            $table->dropColumn('device_hash');
        });
    }
};








