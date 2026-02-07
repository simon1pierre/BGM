<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('notifications_enabled')->default(true)->after('contact_email');
            $table->string('notifications_email')->nullable()->after('notifications_enabled');
            $table->string('mail_mailer')->nullable()->after('notifications_email');
            $table->string('mail_host')->nullable()->after('mail_mailer');
            $table->unsignedInteger('mail_port')->nullable()->after('mail_host');
            $table->string('mail_username')->nullable()->after('mail_port');
            $table->string('mail_password')->nullable()->after('mail_username');
            $table->string('mail_scheme')->nullable()->after('mail_password');
            $table->string('mail_from_address')->nullable()->after('mail_scheme');
            $table->string('mail_from_name')->nullable()->after('mail_from_address');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'notifications_enabled',
                'notifications_email',
                'mail_mailer',
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_scheme',
                'mail_from_address',
                'mail_from_name',
            ]);
        });
    }
};
