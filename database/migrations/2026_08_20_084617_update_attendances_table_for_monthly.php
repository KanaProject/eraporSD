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
        // Drop the old table and recreate
        Schema::dropIfExists('attendances');

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('month'); // 1-12
            $table->integer('sakit')->default(0);
            $table->integer('izin')->default(0);
            $table->integer('alpa')->default(0);
            $table->timestamps();
            
            $table->unique(['student_id', 'academic_year_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');

        // Restore original structure
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained()->cascadeOnDelete();
            $table->integer('sakit')->default(0);
            $table->integer('izin')->default(0);
            $table->integer('alpa')->default(0);
            $table->timestamps();
            $table->unique(['student_id', 'semester_id']);
        });
    }
};
