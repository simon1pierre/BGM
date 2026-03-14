<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('downloads_log', function (Blueprint $table) {
            if (!Schema::hasColumn('downloads_log', 'item_id')) {
                $table->unsignedBigInteger('item_id')->after('item_type');
                $table->index(['item_type', 'item_id']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('downloads_log', function (Blueprint $table) {
            if (Schema::hasColumn('downloads_log', 'item_id')) {
                $table->dropIndex(['item_type', 'item_id']);
                $table->dropColumn('item_id');
            }
        });
    }
};
