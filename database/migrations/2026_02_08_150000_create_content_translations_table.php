<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_translations', function (Blueprint $table) {
            $table->id();
            $table->string('content_type', 120);
            $table->unsignedBigInteger('content_id');
            $table->string('locale', 5);
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['content_type', 'content_id']);
            $table->unique(['content_type', 'content_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_translations');
    }
};


