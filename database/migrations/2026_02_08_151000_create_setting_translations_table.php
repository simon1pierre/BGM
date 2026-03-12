<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setting_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('setting_id');
            $table->string('locale', 5);
            $table->string('site_name', 255)->nullable();
            $table->string('site_tagline', 255)->nullable();
            $table->text('site_description')->nullable();
            $table->string('footer_text', 255)->nullable();
            $table->string('hero_title', 255)->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_primary_label', 100)->nullable();
            $table->string('hero_secondary_label', 100)->nullable();
            $table->timestamps();

            $table->foreign('setting_id')->references('id')->on('settings')->onDelete('cascade');
            $table->unique(['setting_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setting_translations');
    }
};


