<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audience_page_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 30);
            $table->string('visitor_id', 80)->nullable();
            $table->string('session_id', 120)->nullable();
            $table->string('device_hash', 64)->nullable();
            $table->string('route_name', 120)->nullable();
            $table->string('page_url', 500)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('cta_label', 180)->nullable();
            $table->string('cta_target', 500)->nullable();
            $table->unsignedInteger('scroll_depth')->nullable();
            $table->unsignedInteger('engaged_seconds')->nullable();
            $table->string('utm_source', 120)->nullable();
            $table->string('utm_medium', 120)->nullable();
            $table->string('utm_campaign', 120)->nullable();
            $table->string('utm_term', 120)->nullable();
            $table->string('utm_content', 120)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('device_type', 20)->nullable();
            $table->unsignedInteger('screen_width')->nullable();
            $table->unsignedInteger('screen_height')->nullable();
            $table->string('timezone', 60)->nullable();
            $table->string('language', 20)->nullable();
            $table->string('platform', 60)->nullable();
            $table->string('geo_country', 120)->nullable();
            $table->string('geo_country_code', 10)->nullable();
            $table->string('geo_region', 120)->nullable();
            $table->string('geo_city', 120)->nullable();
            $table->string('geo_continent_code', 10)->nullable();
            $table->decimal('geo_latitude', 10, 6)->nullable();
            $table->decimal('geo_longitude', 10, 6)->nullable();
            $table->string('geo_timezone', 60)->nullable();
            $table->string('geo_org', 255)->nullable();
            $table->string('geo_asn', 50)->nullable();
            $table->timestamps();

            $table->index(['event_type', 'created_at']);
            $table->index(['session_id', 'created_at']);
            $table->index(['visitor_id', 'created_at']);
            $table->index(['device_hash', 'created_at']);
            $table->index(['route_name', 'created_at']);
            $table->index(['geo_country_code', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audience_page_events');
    }
};


