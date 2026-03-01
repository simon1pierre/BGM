<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_translations', function (Blueprint $table) {
            if (!Schema::hasColumn('content_translations', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('description');
            }

            if (!Schema::hasColumn('content_translations', 'body')) {
                $table->longText('body')->nullable()->after('excerpt');
            }
        });
    }

    public function down(): void
    {
        Schema::table('content_translations', function (Blueprint $table) {
            if (Schema::hasColumn('content_translations', 'body')) {
                $table->dropColumn('body');
            }

            if (Schema::hasColumn('content_translations', 'excerpt')) {
                $table->dropColumn('excerpt');
            }
        });
    }
};
