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
        Schema::table('homeroom_assignments', function (Blueprint $table) {
            $table->foreignId('companion_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homeroom_assignments', function (Blueprint $table) {
            $table->dropForeign(['companion_id']);
            $table->dropColumn('companion_id');
        });
    }
};
