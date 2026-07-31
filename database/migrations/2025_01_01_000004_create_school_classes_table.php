<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->integer('grade_level'); // 1–6
            $table->string('section', 5);   // A, B, C
            $table->string('name', 10);     // e.g. "1A", "3B"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['grade_level', 'section']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
};
