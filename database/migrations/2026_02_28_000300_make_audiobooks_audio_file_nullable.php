<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('audiobooks')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');

            Schema::create('audiobooks_tmp', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('audio_file')->nullable();
                $table->string('thumbnail')->nullable();
                $table->string('duration')->nullable();
                $table->foreignId('category_id')->nullable()->constrained('content_categories')->nullOnDelete();
                $table->foreignId('book_id')->nullable()->constrained('books')->nullOnDelete();
                $table->string('narrator')->nullable();
                $table->string('series')->nullable();
                $table->timestamp('published_at')->nullable();
                $table->boolean('featured')->default(false);
                $table->boolean('recommended')->default(false);
                $table->boolean('is_prayer_audio')->default(false);
                $table->unsignedBigInteger('play_count')->default(0);
                $table->unsignedBigInteger('download_count')->default(0);
                $table->boolean('is_published')->default(true);
                $table->softDeletes();
                $table->timestamps();

                $table->index(['is_published', 'featured']);
                $table->index(['book_id', 'is_published']);
                $table->index(['is_published', 'is_prayer_audio', 'featured']);
            });

            DB::statement('
                INSERT INTO audiobooks_tmp
                (id, title, description, audio_file, thumbnail, duration, category_id, book_id, narrator, series, published_at, featured, recommended, is_prayer_audio, play_count, download_count, is_published, deleted_at, created_at, updated_at)
                SELECT id, title, description, audio_file, thumbnail, duration, category_id, book_id, narrator, series, published_at, featured, recommended, COALESCE(is_prayer_audio, 0), play_count, download_count, is_published, deleted_at, created_at, updated_at
                FROM audiobooks
            ');

            Schema::drop('audiobooks');
            Schema::rename('audiobooks_tmp', 'audiobooks');

            DB::statement('PRAGMA foreign_keys = ON');
            return;
        }

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE audiobooks MODIFY audio_file VARCHAR(255) NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE audiobooks ALTER COLUMN audio_file DROP NOT NULL');
            return;
        }

        Schema::table('audiobooks', function (Blueprint $table) {
            $table->string('audio_file')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('audiobooks')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');

            Schema::create('audiobooks_tmp', function (Blueprint $table) {
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
                $table->boolean('is_prayer_audio')->default(false);
                $table->unsignedBigInteger('play_count')->default(0);
                $table->unsignedBigInteger('download_count')->default(0);
                $table->boolean('is_published')->default(true);
                $table->softDeletes();
                $table->timestamps();

                $table->index(['is_published', 'featured']);
                $table->index(['book_id', 'is_published']);
                $table->index(['is_published', 'is_prayer_audio', 'featured']);
            });

            DB::statement('
                INSERT INTO audiobooks_tmp
                (id, title, description, audio_file, thumbnail, duration, category_id, book_id, narrator, series, published_at, featured, recommended, is_prayer_audio, play_count, download_count, is_published, deleted_at, created_at, updated_at)
                SELECT id, title, description, COALESCE(NULLIF(audio_file, \'\'), \'pending\'), thumbnail, duration, category_id, book_id, narrator, series, published_at, featured, recommended, COALESCE(is_prayer_audio, 0), play_count, download_count, is_published, deleted_at, created_at, updated_at
                FROM audiobooks
            ');

            Schema::drop('audiobooks');
            Schema::rename('audiobooks_tmp', 'audiobooks');

            DB::statement('PRAGMA foreign_keys = ON');
            return;
        }

        if ($driver === 'mysql') {
            DB::statement("UPDATE audiobooks SET audio_file = 'pending' WHERE audio_file IS NULL OR audio_file = ''");
            DB::statement('ALTER TABLE audiobooks MODIFY audio_file VARCHAR(255) NOT NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("UPDATE audiobooks SET audio_file = 'pending' WHERE audio_file IS NULL OR audio_file = ''");
            DB::statement('ALTER TABLE audiobooks ALTER COLUMN audio_file SET NOT NULL');
            return;
        }
    }
};

