<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('content_categories')->nullOnDelete();
        });

        Schema::table('audios', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('content_categories')->nullOnDelete();
        });

        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('content_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::table('audios', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};


