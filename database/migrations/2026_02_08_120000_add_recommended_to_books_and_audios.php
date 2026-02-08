<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'recommended')) {
                $table->boolean('recommended')->default(false)->after('featured');
            }
        });

        Schema::table('audios', function (Blueprint $table) {
            if (!Schema::hasColumn('audios', 'recommended')) {
                $table->boolean('recommended')->default(false)->after('featured');
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'recommended')) {
                $table->dropColumn('recommended');
            }
        });

        Schema::table('audios', function (Blueprint $table) {
            if (Schema::hasColumn('audios', 'recommended')) {
                $table->dropColumn('recommended');
            }
        });
    }
};
