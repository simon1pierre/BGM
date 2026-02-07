<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_login_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('email')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
        $table->boolean('success')->default(false);
        $table->timestamp('logged_in_at')->useCurrent();
        $table->softDeletes();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('user_login_logs');
    }
};
