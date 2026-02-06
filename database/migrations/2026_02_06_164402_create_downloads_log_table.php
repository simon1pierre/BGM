<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('downloads_log', function (Blueprint $table) {
        $table->id();
        $table->string('item_type'); // book or audio
        $table->unsignedBigInteger('item_id');
        $table->string('ip_address')->nullable();
        $table->timestamp('downloaded_at')->useCurrent();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads_log');
    }
};
