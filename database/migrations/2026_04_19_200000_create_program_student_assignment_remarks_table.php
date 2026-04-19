<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_student_assignment_remarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_student_id')->constrained()->onDelete('cascade');
            $table->foreignId('syllabus_assignment_id')->constrained()->onDelete('cascade');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['program_student_id', 'syllabus_assignment_id'], 'psar_student_assignment_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_student_assignment_remarks');
    }
};
