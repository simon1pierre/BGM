<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_likes', function (Blueprint $table) {
            $table->id();
            $table->morphs('content');
            $table->string('device_hash', 64);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('session_id', 120)->nullable();
            $table->timestamps();

            $table->unique(['content_type', 'content_id', 'device_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_likes');
    }
};








