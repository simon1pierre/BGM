<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_campaigns', function (Blueprint $table) {
            $table->enum('target_type', ['all', 'selected', 'custom'])->default('all')->after('status');
            $table->json('target_subscriber_ids')->nullable()->after('target_type');
            $table->json('target_emails')->nullable()->after('target_subscriber_ids');
        });
    }

    public function down(): void
    {
        Schema::table('email_campaigns', function (Blueprint $table) {
            $table->dropColumn(['target_type', 'target_subscriber_ids', 'target_emails']);
        });
    }
};


