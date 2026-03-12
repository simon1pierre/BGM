<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_campaigns', function (Blueprint $table) {
            $table->string('preheader')->nullable()->after('subject');
            $table->longText('body_html')->nullable()->after('message');
            $table->string('featured_image_url')->nullable()->after('body_html');
            $table->string('video_url')->nullable()->after('featured_image_url');
            $table->string('audio_url')->nullable()->after('video_url');
            $table->string('document_url')->nullable()->after('audio_url');
            $table->string('cta_text')->nullable()->after('document_url');
            $table->string('cta_url')->nullable()->after('cta_text');
        });
    }

    public function down(): void
    {
        Schema::table('email_campaigns', function (Blueprint $table) {
            $table->dropColumn([
                'preheader',
                'body_html',
                'featured_image_url',
                'video_url',
                'audio_url',
                'document_url',
                'cta_text',
                'cta_url',
            ]);
        });
    }
};


