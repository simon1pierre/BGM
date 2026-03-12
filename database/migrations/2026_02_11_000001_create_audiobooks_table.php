<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audiobooks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('audio_file');
            $table->string('thumbnail')->nullable();
            $table->string('duration')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('content_categories')->nullOnDelete();
            $table->foreignId('book_id')->nullable()->constrained('books')->nullOnDelete();
            $table->string('narrator')->nullable();
            $table->string('series')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('recommended')->default(false);
            $table->unsignedBigInteger('play_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);
            $table->boolean('is_published')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['is_published', 'featured']);
            $table->index(['book_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audiobooks');
    }
};








