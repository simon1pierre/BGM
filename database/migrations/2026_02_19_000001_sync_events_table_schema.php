<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'event_type')) {
                $table->string('event_type', 50)->default('prayer_meeting')->after('description');
            }

            if (!Schema::hasColumn('events', 'location')) {
                $table->string('location')->nullable()->after('event_type');
            }

            if (!Schema::hasColumn('events', 'venue')) {
                $table->string('venue')->nullable()->after('location');
            }

            if (!Schema::hasColumn('events', 'timezone')) {
                $table->string('timezone', 64)->default('UTC')->after('ends_at');
            }

            if (!Schema::hasColumn('events', 'live_platform')) {
                $table->string('live_platform', 32)->nullable()->after('timezone');
            }

            if (!Schema::hasColumn('events', 'live_url')) {
                $table->string('live_url')->nullable()->after('live_platform');
            }

            if (!Schema::hasColumn('events', 'registration_url')) {
                $table->string('registration_url')->nullable()->after('live_url');
            }

            if (!Schema::hasColumn('events', 'image_path')) {
                $table->string('image_path')->nullable()->after('registration_url');
            }

            if (!Schema::hasColumn('events', 'is_published')) {
                $table->boolean('is_published')->default(true)->after('image_path');
            }

            if (!Schema::hasColumn('events', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_published');
            }
        });

        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'is_featured')) {
                $table->dropColumn('is_featured');
            }

            if (Schema::hasColumn('events', 'is_published')) {
                $table->dropColumn('is_published');
            }

            if (Schema::hasColumn('events', 'image_path')) {
                $table->dropColumn('image_path');
            }

            if (Schema::hasColumn('events', 'registration_url')) {
                $table->dropColumn('registration_url');
            }

            if (Schema::hasColumn('events', 'live_url')) {
                $table->dropColumn('live_url');
            }

            if (Schema::hasColumn('events', 'live_platform')) {
                $table->dropColumn('live_platform');
            }

            if (Schema::hasColumn('events', 'timezone')) {
                $table->dropColumn('timezone');
            }

            if (Schema::hasColumn('events', 'venue')) {
                $table->dropColumn('venue');
            }

            if (Schema::hasColumn('events', 'location')) {
                $table->dropColumn('location');
            }

            if (Schema::hasColumn('events', 'event_type')) {
                $table->dropColumn('event_type');
            }

            if (Schema::hasColumn('events', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
