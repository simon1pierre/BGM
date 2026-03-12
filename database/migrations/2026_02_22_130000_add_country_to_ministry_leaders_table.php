<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ministry_leaders', function (Blueprint $table) {
            if (!Schema::hasColumn('ministry_leaders', 'country')) {
                $table->string('country', 120)->nullable()->after('position');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ministry_leaders', function (Blueprint $table) {
            if (Schema::hasColumn('ministry_leaders', 'country')) {
                $table->dropColumn('country');
            }
        });
    }
};



