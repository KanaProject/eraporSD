<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20); // e.g. "2024/2025"
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->string('name', 20); // e.g. "Ganjil", "Genap"
            $table->enum('type', ['ganjil', 'genap']);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('assessment_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_id')->constrained()->cascadeOnDelete();
            $table->enum('code', ['ASTS_GANJIL', 'SAS', 'ASTS_GENAP', 'SAT']);
            $table->string('name'); // "ASTS Ganjil", "SAS", "ASTS Genap", "SAT"
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_periods');
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('academic_years');
    }
};
