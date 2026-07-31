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
        Schema::table('assessment_periods', function (Blueprint $table) {
            $table->string('report_place')->nullable()->after('is_active');
            $table->date('report_date')->nullable()->after('report_place');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_periods', function (Blueprint $table) {
            $table->dropColumn(['report_place', 'report_date']);
        });
    }
};
