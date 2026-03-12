<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audiobook_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audiobook_id')->constrained('audiobooks')->cascadeOnDelete();
            $table->string('title');
            $table->string('audio_file');
            $table->string('duration')->nullable();
            $table->unsignedInteger('sort_order')->default(1);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['audiobook_id', 'is_published', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audiobook_parts');
    }
};








