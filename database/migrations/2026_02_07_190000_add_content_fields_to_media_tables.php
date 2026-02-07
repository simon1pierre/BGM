<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('speaker')->nullable()->after('description');
            $table->string('series')->nullable()->after('speaker');
            $table->timestamp('published_at')->nullable()->after('thumbnail');
            $table->boolean('featured')->default(false)->after('published_at');
            $table->unsignedInteger('view_count')->default(0)->after('featured');
        });

        Schema::table('audios', function (Blueprint $table) {
            $table->string('speaker')->nullable()->after('description');
            $table->string('series')->nullable()->after('speaker');
            $table->timestamp('published_at')->nullable()->after('duration');
            $table->boolean('featured')->default(false)->after('published_at');
            $table->unsignedInteger('play_count')->default(0)->after('featured');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->string('author')->nullable()->after('description');
            $table->string('category')->nullable()->after('author');
            $table->timestamp('published_at')->nullable()->after('cover_image');
            $table->boolean('featured')->default(false)->after('published_at');
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['speaker', 'series', 'published_at', 'featured', 'view_count']);
        });

        Schema::table('audios', function (Blueprint $table) {
            $table->dropColumn(['speaker', 'series', 'published_at', 'featured', 'play_count']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['author', 'category', 'published_at', 'featured']);
        });
    }
};
