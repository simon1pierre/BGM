<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('video_events', function (Blueprint $table) {
            $table->string('device_type', 20)->nullable()->after('session_id');
            $table->unsignedInteger('screen_width')->nullable()->after('device_type');
            $table->unsignedInteger('screen_height')->nullable()->after('screen_width');
            $table->string('timezone', 60)->nullable()->after('screen_height');
            $table->string('language', 20)->nullable()->after('timezone');
            $table->string('platform', 60)->nullable()->after('language');
        });
    }

    public function down(): void
    {
        Schema::table('video_events', function (Blueprint $table) {
            $table->dropColumn([
                'device_type',
                'screen_width',
                'screen_height',
                'timezone',
                'language',
                'platform',
            ]);
        });
    }
};
