<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('twitter_url', 255)->nullable()->after('instagram_url');
            $table->string('tiktok_url', 255)->nullable()->after('twitter_url');
            $table->string('whatsapp_url', 255)->nullable()->after('tiktok_url');
            $table->string('telegram_url', 255)->nullable()->after('whatsapp_url');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['twitter_url', 'tiktok_url', 'whatsapp_url', 'telegram_url']);
        });
    }
};








