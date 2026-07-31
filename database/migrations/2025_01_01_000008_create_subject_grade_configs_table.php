<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // KKM + grade weights per subject per class
        Schema::create('subject_grade_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->integer('grade_level'); // 1-6
            $table->integer('kkm')->default(70);         // KKM per mapel per tingkat kelas
            $table->integer('bobot_uh')->default(50);    // bobot rata-rata UH (%)
            $table->integer('bobot_teori')->default(50); // bobot ujian teori (%)
            $table->timestamps();
            $table->unique(['subject_id', 'grade_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_grade_configs');
    }
};
