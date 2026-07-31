<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

        Schema::create('extracurriculars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['wajib', 'pilihan'])->default('pilihan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('student_extracurriculars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('extracurricular_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained()->cascadeOnDelete();
            $table->string('nilai', 30)->nullable(); // "Sangat Baik", "Baik", dll.
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'extracurricular_id', 'semester_id'], 'se_unique');
        });

        Schema::create('homeroom_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('note')->nullable();           // catatan wali kelas
            $table->text('character_desc')->nullable(); // deskripsi karakter
            $table->timestamps();
            $table->unique(['student_id', 'semester_id']);
        });

        Schema::create('report_card_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assessment_period_id')->constrained()->cascadeOnDelete();
            $table->timestamp('generated_at')->nullable();
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('pdf_path')->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'assessment_period_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_card_statuses');
        Schema::dropIfExists('homeroom_notes');
        Schema::dropIfExists('student_extracurriculars');
        Schema::dropIfExists('extracurriculars');
        Schema::dropIfExists('attendances');
    }
};
