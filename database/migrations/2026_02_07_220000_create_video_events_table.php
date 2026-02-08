<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('video_id');
            $table->enum('event_type', ['play', 'youtube_click', 'impression']);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('page_url', 500)->nullable();
            $table->string('session_id', 120)->nullable();
            $table->timestamps();

            $table->index(['video_id', 'event_type']);
            $table->index(['event_type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_events');
    }
};
