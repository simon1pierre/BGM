<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->boolean('is_read')->default(false)->after('page_url');
            $table->timestamp('read_at')->nullable()->after('is_read');
            $table->timestamp('replied_at')->nullable()->after('read_at');
            $table->foreignId('replied_by')->nullable()->after('replied_at')->constrained('users')->nullOnDelete();
            $table->string('reply_subject')->nullable()->after('replied_by');
            $table->text('reply_body')->nullable()->after('reply_subject');

            $table->index(['is_read', 'replied_at']);
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropIndex(['is_read', 'replied_at']);
            $table->dropConstrainedForeignId('replied_by');
            $table->dropColumn([
                'is_read',
                'read_at',
                'replied_at',
                'reply_subject',
                'reply_body',
            ]);
        });
    }
};








