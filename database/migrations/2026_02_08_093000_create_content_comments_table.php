<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_comments', function (Blueprint $table) {
            $table->id();
            $table->morphs('content');
            $table->string('name', 120)->nullable();
            $table->string('email', 190)->nullable();
            $table->text('body');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('session_id', 120)->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();

            $table->index(['content_type', 'content_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_comments');
    }
};
