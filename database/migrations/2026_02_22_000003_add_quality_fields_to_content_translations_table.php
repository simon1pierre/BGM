<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_translations', function (Blueprint $table) {
            if (!Schema::hasColumn('content_translations', 'source_locale')) {
                $table->string('source_locale', 5)->nullable()->after('locale');
            }

            if (!Schema::hasColumn('content_translations', 'translation_status')) {
                $table->string('translation_status', 32)->default('approved')->after('description');
                $table->index(['translation_status', 'locale']);
            }

            if (!Schema::hasColumn('content_translations', 'translated_by')) {
                $table->string('translated_by', 32)->default('manual')->after('translation_status');
            }

            if (!Schema::hasColumn('content_translations', 'quality_score')) {
                $table->decimal('quality_score', 5, 2)->nullable()->after('translated_by');
            }

            if (!Schema::hasColumn('content_translations', 'is_bible_locked')) {
                $table->boolean('is_bible_locked')->default(false)->after('quality_score');
            }

            if (!Schema::hasColumn('content_translations', 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->nullable()->after('is_bible_locked');
                $table->index('reviewed_by');
            }

            if (!Schema::hasColumn('content_translations', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('content_translations', function (Blueprint $table) {
            if (Schema::hasColumn('content_translations', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }
            if (Schema::hasColumn('content_translations', 'reviewed_by')) {
                $table->dropIndex(['reviewed_by']);
                $table->dropColumn('reviewed_by');
            }
            if (Schema::hasColumn('content_translations', 'is_bible_locked')) {
                $table->dropColumn('is_bible_locked');
            }
            if (Schema::hasColumn('content_translations', 'quality_score')) {
                $table->dropColumn('quality_score');
            }
            if (Schema::hasColumn('content_translations', 'translated_by')) {
                $table->dropColumn('translated_by');
            }
            if (Schema::hasColumn('content_translations', 'translation_status')) {
                $table->dropIndex(['translation_status', 'locale']);
                $table->dropColumn('translation_status');
            }
            if (Schema::hasColumn('content_translations', 'source_locale')) {
                $table->dropColumn('source_locale');
            }
        });
    }
};









