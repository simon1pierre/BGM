<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audiobook_parts', function (Blueprint $table) {
            $table->string('language', 5)->default('rw')->after('audio_file');
            $table->index(['audiobook_id', 'language', 'is_published', 'sort_order'], 'ab_parts_lang_pub_order_idx');
        });
    }

    public function down(): void
    {
        Schema::table('audiobook_parts', function (Blueprint $table) {
            $table->dropIndex('ab_parts_lang_pub_order_idx');
            $table->dropColumn('language');
        });
    }
};








