<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_notifications', function (Blueprint $table) {
            $table->id();
            $table->json('payload');
            $table->enum('target_type', ['all', 'custom'])->default('all');
            $table->json('target_emails')->nullable();
            $table->enum('status', ['sent', 'draft'])->default('sent');
            $table->timestamp('sent_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_notifications');
    }
};








