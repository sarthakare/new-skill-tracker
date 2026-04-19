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
            $table->unsignedBigInteger('program_student_id');
            $table->unsignedBigInteger('syllabus_assignment_id');
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Short FK names: MySQL max identifier length is 64; Laravel’s default exceeds it for this table name.
            $table->foreign('program_student_id', 'psar_program_student_fk')
                ->references('id')
                ->on('program_students')
                ->onDelete('cascade');
            $table->foreign('syllabus_assignment_id', 'psar_syllabus_asg_fk')
                ->references('id')
                ->on('syllabus_assignments')
                ->onDelete('cascade');

            $table->unique(['program_student_id', 'syllabus_assignment_id'], 'psar_student_assignment_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_student_assignment_remarks');
    }
};
