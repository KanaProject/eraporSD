<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assessment_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // guru yg input

            // Raw scores input by guru
            $table->decimal('uh1', 5, 2)->nullable();           // Ulangan Harian 1
            $table->decimal('uh2', 5, 2)->nullable();           // Ulangan Harian 2
            $table->decimal('ujian_teori', 5, 2)->nullable();   // Ujian Teori
            $table->decimal('ujian_praktek', 5, 2)->nullable(); // Ujian Praktek

            // Computed & stored for performance (computed on save)
            // ASTS: Sumatif 1 / SAS: Pengetahuan
            $table->decimal('nilai_pengetahuan', 5, 2)->nullable();
            // ASTS: Sumatif 2 / SAS: Keterampilan  (= ujian_praktek)
            $table->decimal('nilai_keterampilan', 5, 2)->nullable();

            $table->timestamps();
            $table->unique(['student_id', 'subject_id', 'assessment_period_id'], 'grades_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
