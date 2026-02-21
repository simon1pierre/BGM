<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_events', function (Blueprint $table) {
            if (!Schema::hasColumn('content_events', 'visitor_id')) {
                $table->string('visitor_id', 80)->nullable()->after('session_id');
                $table->index(['visitor_id', 'created_at']);
            }

            if (!Schema::hasColumn('content_events', 'reader_session_id')) {
                $table->string('reader_session_id', 120)->nullable()->after('visitor_id');
                $table->index(['reader_session_id', 'created_at']);
            }

            if (!Schema::hasColumn('content_events', 'page_number')) {
                $table->unsignedInteger('page_number')->nullable()->after('watch_seconds');
            }

            if (!Schema::hasColumn('content_events', 'total_pages')) {
                $table->unsignedInteger('total_pages')->nullable()->after('page_number');
            }

            if (!Schema::hasColumn('content_events', 'progress_percent')) {
                $table->decimal('progress_percent', 5, 2)->unsigned()->nullable()->after('total_pages');
            }
        });

        Schema::table('video_events', function (Blueprint $table) {
            if (!Schema::hasColumn('video_events', 'visitor_id')) {
                $table->string('visitor_id', 80)->nullable()->after('session_id');
                $table->index(['visitor_id', 'created_at']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('content_events', function (Blueprint $table) {
            if (Schema::hasColumn('content_events', 'progress_percent')) {
                $table->dropColumn('progress_percent');
            }
            if (Schema::hasColumn('content_events', 'total_pages')) {
                $table->dropColumn('total_pages');
            }
            if (Schema::hasColumn('content_events', 'page_number')) {
                $table->dropColumn('page_number');
            }
            if (Schema::hasColumn('content_events', 'reader_session_id')) {
                $table->dropIndex(['reader_session_id', 'created_at']);
                $table->dropColumn('reader_session_id');
            }
            if (Schema::hasColumn('content_events', 'visitor_id')) {
                $table->dropIndex(['visitor_id', 'created_at']);
                $table->dropColumn('visitor_id');
            }
        });

        Schema::table('video_events', function (Blueprint $table) {
            if (Schema::hasColumn('video_events', 'visitor_id')) {
                $table->dropIndex(['visitor_id', 'created_at']);
                $table->dropColumn('visitor_id');
            }
        });
    }
};
