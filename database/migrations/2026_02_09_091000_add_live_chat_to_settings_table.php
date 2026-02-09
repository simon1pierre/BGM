<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('live_chat_enabled')->default(false)->after('telegram_url');
            $table->string('tawk_property_id', 120)->nullable()->after('live_chat_enabled');
            $table->string('tawk_widget_id', 120)->nullable()->after('tawk_property_id');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['live_chat_enabled', 'tawk_property_id', 'tawk_widget_id']);
        });
    }
};
