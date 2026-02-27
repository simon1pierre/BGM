<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            if (!Schema::hasColumn('videos', 'video_series_id')) {
                $table->foreignId('video_series_id')
                    ->nullable()
                    ->after('series')
                    ->constrained('video_series')
                    ->nullOnDelete();
            }
        });

        // Backfill existing plain-text series into managed video series records.
        if (Schema::hasTable('video_series')) {
            $legacySeries = DB::table('videos')
                ->whereNotNull('series')
                ->where('series', '!=', '')
                ->select('series')
                ->distinct()
                ->pluck('series');

            foreach ($legacySeries as $title) {
                $seriesId = DB::table('video_series')->where('title', $title)->value('id');
                if (!$seriesId) {
                    $seriesId = DB::table('video_series')->insertGetId([
                        'title' => $title,
                        'description' => null,
                        'sort_order' => 0,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('videos')
                    ->where('series', $title)
                    ->whereNull('video_series_id')
                    ->update(['video_series_id' => $seriesId]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            if (Schema::hasColumn('videos', 'video_series_id')) {
                $table->dropConstrainedForeignId('video_series_id');
            }
        });
    }
};
