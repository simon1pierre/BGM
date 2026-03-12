<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('video_events', function (Blueprint $table) {
            $table->string('geo_country', 120)->nullable()->after('share_channel');
            $table->string('geo_country_code', 10)->nullable()->after('geo_country');
            $table->string('geo_region', 120)->nullable()->after('geo_country_code');
            $table->string('geo_city', 120)->nullable()->after('geo_region');
            $table->string('geo_continent_code', 10)->nullable()->after('geo_city');
            $table->decimal('geo_latitude', 10, 6)->nullable()->after('geo_continent_code');
            $table->decimal('geo_longitude', 10, 6)->nullable()->after('geo_latitude');
            $table->string('geo_timezone', 60)->nullable()->after('geo_longitude');
            $table->string('geo_org', 255)->nullable()->after('geo_timezone');
            $table->string('geo_asn', 50)->nullable()->after('geo_org');
        });

        Schema::table('content_events', function (Blueprint $table) {
            $table->string('geo_country', 120)->nullable()->after('share_channel');
            $table->string('geo_country_code', 10)->nullable()->after('geo_country');
            $table->string('geo_region', 120)->nullable()->after('geo_country_code');
            $table->string('geo_city', 120)->nullable()->after('geo_region');
            $table->string('geo_continent_code', 10)->nullable()->after('geo_city');
            $table->decimal('geo_latitude', 10, 6)->nullable()->after('geo_continent_code');
            $table->decimal('geo_longitude', 10, 6)->nullable()->after('geo_latitude');
            $table->string('geo_timezone', 60)->nullable()->after('geo_longitude');
            $table->string('geo_org', 255)->nullable()->after('geo_timezone');
            $table->string('geo_asn', 50)->nullable()->after('geo_org');
        });
    }

    public function down(): void
    {
        Schema::table('video_events', function (Blueprint $table) {
            $table->dropColumn([
                'geo_country',
                'geo_country_code',
                'geo_region',
                'geo_city',
                'geo_continent_code',
                'geo_latitude',
                'geo_longitude',
                'geo_timezone',
                'geo_org',
                'geo_asn',
            ]);
        });

        Schema::table('content_events', function (Blueprint $table) {
            $table->dropColumn([
                'geo_country',
                'geo_country_code',
                'geo_region',
                'geo_city',
                'geo_continent_code',
                'geo_latitude',
                'geo_longitude',
                'geo_timezone',
                'geo_org',
                'geo_asn',
            ]);
        });
    }
};


