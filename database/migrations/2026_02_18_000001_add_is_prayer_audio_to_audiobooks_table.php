<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audiobooks', function (Blueprint $table) {
            $table->boolean('is_prayer_audio')->default(false)->after('recommended');
            $table->index('is_prayer_audio');
            $table->index(['is_published', 'is_prayer_audio', 'featured']);
        });
    }

    public function down(): void
    {
        Schema::table('audiobooks', function (Blueprint $table) {
            $table->dropIndex(['is_published', 'is_prayer_audio', 'featured']);
            $table->dropIndex(['is_prayer_audio']);
            $table->dropColumn('is_prayer_audio');
        });
    }
};








