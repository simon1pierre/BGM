<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_events', function (Blueprint $table) {
            $table->id();
            $table->morphs('content');
            $table->string('event_type', 30);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('page_url', 500)->nullable();
            $table->string('session_id', 120)->nullable();
            $table->string('device_type', 20)->nullable();
            $table->unsignedInteger('screen_width')->nullable();
            $table->unsignedInteger('screen_height')->nullable();
            $table->string('timezone', 60)->nullable();
            $table->string('language', 20)->nullable();
            $table->string('platform', 60)->nullable();
            $table->string('device_hash', 64)->nullable();
            $table->unsignedInteger('watch_seconds')->nullable();
            $table->string('share_channel', 30)->nullable();
            $table->timestamps();

            $table->index(['content_type', 'content_id', 'event_type']);
            $table->index(['event_type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_events');
    }
};








